<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Material;
use App\Models\producto;
use App\Models\servicio;
use Illuminate\Support\Arr;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use App\Models\categoria_servicio;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Servicios extends Component
{
    use WithPagination;

    public $records, $search, $action =1, $serviceSelected, $categoriesList,$percent=0,$finalD=0,$cartMaterials,$productos=[],$query,$editing=false;
    public Collection $cartP;
    protected $paginationTheme = 'bootstrap';

    public servicio $service;
    protected $rules =    [
        'service.name' => "required|min:3|max:80|unique:servicios,name",
        'service.description' => "nullable|max:200",
        'service.gross_price' => "required",
        'service.disccount_price' => "nullable",
        'service.reward_points' => "nullable",
        'service.duration' => "required|in:240,210,180,150,120,90,60,30",
    ];
    public function mount()
    {
        if (session()->has('cartMaterials')) {
            $this->cartP = session('cartMaterials');
        } else {
            $this->cartP = new Collection;
        }
        $this->service = new servicio();
        $this->service->duration = '60';
        $this->calculateFinalDS();
    }

    protected $listeners = [
        'refresh' => '$refresh',
        'addProduct' => 'addProductFromCard',
        'search' => 'searching',
        'searchSKU',
        'Delete', 'calculateFinalDS','calculate','removeItem','updateQty'
    ];
    
    public function searchSKU($searchText)
    {
        $this->search = trim($searchText);
    }

    function loadProducts()
    {
        $this->resetPage();
        if (!empty($this->search)) {

            $product = producto::where(function ($query) {
                    $query->where('sku', '=', $this->search)
                        ->orWhere('intern_sku', '=', $this->search);
                })->first();
        }else{
            $product='';
        }
        // Verifica si se encontró un producto
        if ($product) {
            // Llama a la función para agregar el producto al carrito
            $this->addProductFromCard($product);   
        }
    
        // Restablece el valor de búsqueda después de agregar el producto
        $this->search = '';
    }
    function addProductFromCard(producto $product)
    {
        $this->AddProduct($product);
    }
    public function render()
    {
        //validamos que exista la sesion
        if (session()->has('cartMaterials')) {
            //obtenemos el carrito
            $cart = session('cartMaterials');
            // ordenar los items del carrito mediante nombre de forma asc
            $cartInfo = $cart->sortBy(['name', ['name', 'asc']]);
        } else {
            $cartInfo = new Collection;
        }
        return view('livewire.servicios.servicios',[
            'servicios' => $this->loadServices(), 
            'productos' => $this->loadProducts(),
            'cartInfo'=>$cartInfo,
        ]);
    }
    public function updatedPercent()
    {
        $this->calculateFinalDS();
    }
    function loadServices()
    {

        if (!empty($this->search)) {
            $this->resetPage();
            $query=servicio::where('name', 'like', "%{$this->search}%")
            ->orderBy('first_name', 'asc')
            ->paginate(5);
        } else {

            $query =  servicio::with('categorias')->orderBy('name', 'asc')->paginate(5);        
        }
        $this->records = $query->total();
        return $query;
    }

    public function searching($searchText)
    {
        $this->search = trim($searchText);
    }

    public function Add(){
        $this->resetValidation();
        $this->resetExcept('service');
        $this->service = new servicio();
        $this->action =2;
    }
    public function Delete(servicio $servicio)
    {
        $this -> destroy($servicio);
    }

    public function Edit(servicio $service){
        $this->editing=true;
        $this->resetValidation();
        $this->service = $service;
        $this->categoriesList = implode(", ", $service->categorias->pluck('name')->toArray());
        $this->action = 3;

        
        $materials = Material::all();

        // Limpiar elementos existentes en cartP
        $this->cartP = new Collection;
        // Loop through each product associated with the servicio
        foreach ($materials as $material) {
            $product=producto::find($material->producto_id);
            if($product){
                $salePrice = ($product->disccount_price > 0 && $product->disccount_price < $product->gross_price ?  $product->disccount_price : $product->gross_price);
                $uid = uniqid() . $product->id;
                $coll = collect([
                    'id' => $uid,  // Make sure to define $uid appropriately
                    'pid' => $product->id,
                    'name' => $product->name,
                    'gross_price' => floatval($product->gross_price),
                    'sale_price' => floatval($salePrice),  // Make sure $salePrice is defined appropriately
                    'qty' => $material->qty,
                    'stock' => $product->stock_qty,
                    'type' => $product->type_product,
                    'unit_type' => $product->unit_type,
                ]);
    
                $itemCart = Arr::add($coll, null, null);
                $this->cartP->push($itemCart);
            }
        }

        $this->save();
        $this->dispatchBrowserEvent('refresh-tom');
    }

    public function cancelEdit()
    {
        $this->resetValidation();
        $this->service = new servicio();
        $this->action = 1;
    }

    function viewService(servicio $service)
    {
        $this->serviceSelected = $service;
        $this->dispatchBrowserEvent('view-service');
    }
    function calculate()
    {
        $this->finalD=$this->service->gross_price;
    }
    function calculateFinalDS()
    {
        if($this->percent){
            $disccountP=floatval($this->service->gross_price)-(floatval($this->service->gross_price)*floatval($this->percent/100));
            $this->finalD=floatval($disccountP);
            return $this->finalD;
        }
        return;
    }
    function Store()
    {
        sleep(1);

        if ($this->service->id > 0) {
            $this->validate([
                'service.name' => [
                    'required',
                    'min:3',
                    'max:80',
                    Rule::unique('servicios', 'name')->ignore($this->service->id, 'id')
                ],
            ]);
        } else {
            $this->validate($this->rules);
        }

        $this->service->disccount_price=$this->finalD;
        $this->service->save();

        $listCategories = null;
        if ($this->categoriesList != null)  $listCategories =  explode(",", $this->categoriesList);


        //relacionar categorias         
        if ($this->action > 1) {

            if ($listCategories != null) {

                $listCategories = array_map(function ($item) {
                    $catName = trim($item);
                    // verificar si el elemento no es numérico
                    if (!is_numeric($catName)) {
                        // buscar el ID de la categoría en la tabla correspondiente
                        $categoria = categoria_servicio::where('name', $catName)->first();
                        // reemplazar el elemento con el ID de la categoría si existe
                        if ($categoria) {
                            return $categoria->id;
                        }
                    }

                    // devolver el elemento sin cambios              
                    return $item;
                }, $listCategories);
            }
            $listCategories !== null ? $this->service->categorias()->sync($listCategories) : $this->service->categorias()->detach();
        }
        $cart = $this->cartP;

        
        if($this->cartP){

            DB::transaction(function () use ($cart) {

                $service=$this->service;
                // Obtén los materiales antiguos asociados a este servicio
                $oldMaterials = Material::where('servicio_id', $service->id)->get();
        
                // Elimina los materiales antiguos
                $oldMaterials->each(function ($oldMaterial) {
                    $oldMaterial->delete();
                });
                //asignaciones de venta
                $materials = $cart->map(function ($item) use ($service) {
                    return [
                        'qty' => $item['qty'],
                        'sale_price' => $item['sale_price'],
                        'gross_price' => $item['gross_price'],
                        'producto_id' => $item['pid'],
                        'servicio_id' => $service->id
                    ];
                })->toArray();
                Material::insert($materials);
                
                $this->dispatchBrowserEvent('noty', ['msg' => 'SOLICITUD PROCESADA CON ÉXITO']);
                //
                $this->emit('clear-cart');
            });
            $this->emit('refresh');
            $this->clear();
            $this->service = new servicio();
            $this->service->duration = '60';
        }
        $this->action=1;
    }
    public function destroy(servicio $servicio)
    {
        $servicio->categorias()->detach();

        $servicio->delete();

        $this->resetPage();

        $this->dispatchBrowserEvent('noty-error',['msg'=>'SERVICIO ELIMINADO']);
        $this->dispatchBrowserEvent('stop-loader');
    }
    
    function updatedQuery()
    {
        $this->productos= producto::where('name','like',"%{$this->query}%")->orderBy('name')->get()->take(5);
    }
    function AddProduct($product, $qty = 1)
    {
        if($product->type_product=='variable'){
            // validar si ya existe en el carrito
            if ($this->inCart($product->id)) {
                $this->updateQty(null, $qty, $product->id);
                return; // => con esta línea se agrupan los productos por nombre dentro del carrito
            }
            $salePrice = ($product->disccount_price > 0 && $product->disccount_price < $product->gross_price ?  $product->disccount_price : $product->gross_price);
            $uid = uniqid() . $product->id;

            $coll = collect(
                [
                    'id' => $uid,
                    'pid' => $product->id,
                    'name' => $product->name,
                    'gross_price' => floatval($product->gross_price),
                    'sale_price' => floatval($salePrice),
                    'qty' => intval($qty),
                    'stock' => $product->stock_qty,
                    'type' => $product->type_product,
                    'unit_type' => $product->unit_type,
                ]
            );
            $itemCart = Arr::add($coll, null, null);
            $this->cartP->push($itemCart);
            $this->save();
            $this->emit('refresh');
            $this->dispatchBrowserEvent('noty', ['msg' => 'MATERIAL AGREGADO']);
        }else{
            $this->dispatchBrowserEvent('noty-error', ['msg' => 'ESTE PRODUCTO NO ES PARA USO']);
        }
    }
    function removeItem($id)
    {
        $this->cartP = $this->cartP->reject(function ($product) use ($id) {
            return $product['id'] === $id;
        });

        $this->save();

        $this->emit('refresh');
        $this->dispatchBrowserEvent('noty-error', ['msg' => 'MATERIAL ELIMINADO']);
    }
    function save()
    {
        session()->put('cartMaterials', $this->cartP);
        session()->save();
        $this->emit('refresh');
    }

    function inCart($product_id)
    {
        $mycart = $this->cartP;

        $cont = $mycart->where('pid', $product_id)->count();

        return  $cont > 0 ? true : false;
    }

    function updateQty($uid, $cant = 1, $product_id = null)
    {
        if (!is_numeric($cant)) {
            $this->dispatchBrowserEvent('noty-error', ['msg' => $cant . ' NO ES UNA CANTIDAD VÁLIDA']);
            return;
        }

        $mycart = $this->cartP;
        if ($product_id == null) {
            $oldItem = $mycart->where('id', $uid)->first();
        } else {
            $oldItem = $mycart->where('pid', $product_id)->first();
        }

        $newItem  = $oldItem;

        $newItem['qty'] = $product_id == null ? intval($cant) : intval($newItem['qty'] + $cant);

        //eliminar el item de la coleccion / sesion
        $this->cartP  = $this->cartP->reject(function ($product) use ($uid, $product_id) {
            return  $product['id'] === $uid || $product['pid'] === $product_id;
        });
        $this->save();

        $this->cartP->push(Arr::add($newItem, null, null));
        $this->save();

        $this->emit('refresh');
        $this->dispatchBrowserEvent('noty', ['msg' => 'MATERIAL ACTUALIZADO ACTUALIZADO']);
    }
    function clear()
    {
        $this->cartP = new Collection;
        $this->save();
        $this->emit('refresh');
    }
}
