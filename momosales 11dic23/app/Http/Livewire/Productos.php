<?php

namespace App\Http\Livewire;

use App\Models\Image;
use App\Models\marca;
use Livewire\Component;
use App\Models\producto;
use App\Traits\ProductTrait;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use App\Models\categoria_producto;

class Productos extends Component
{
    use WithFileUploads;
    use WithPagination;
    use ProductTrait;

    public $records, $search, $action =1, $gallery, $marcas, $productSelected, $categoriesList,$percent=0,$finalD=0;

    public producto $product;

    protected $paginationTheme = 'bootstrap';


    protected $rules =    [
        'product.name' => "required|min:3|max:60|unique:productos,name",
        'product.sku' => "nullable|max:25|unique:productos,sku",
        'product.intern_sku' => "nullable|max:25|unique:productos,intern_sku",
        'product.description' => "nullable|max:500",
        'product.type_product' => "required|in:simple,variable",
        'product.unit_type' => "required|in:Unidad,Mililitro,Ampolleta,Artículo,Onza,Gramo,Envase",
        'product.status' => "required|in:publish,pending,draft",
        'product.visibility' => "required|in:visible,hide",
        'product.gross_price' => "required",
        'product.disccount_price' => "nullable",
        'product.reward_points' => "nullable",
        'product.cost' => "nullable",
        'product.stock_status' => "nullable|in:instock,outofstock,onbackorder",
        'product.manage_stock' => "nullable",
        'product.stock_qty' => "required",
        'product.min_stock' => "required",
        'product.brand_id' => "nullable",
    ];

    public function mount()
    {
        $this->product = new producto();
        $this->product->type_product = 'simple';
        $this->product->status = 'publish';
        $this->product->visibility = 'visible';
        $this->product->stock_status = 'instock';
        $this->product->manage_stock = 1;
        $this->product->unit_type = 'Unidad';
        $this->calculateFinalD();

        $this->marcas = marca::orderBy('name')->get();

        $this->product->brand_id = $this->marcas->first()->id ?? null;
    }

    protected $listeners = [
        'refresh' => '$refresh',
        'search' => 'searching',
        'Delete', 'calculateFinalD','SyncAll','calculateP','viewProduct'
    ];

    public function render()
    {
        return view('livewire.productos.productos',[
            'productos' => $this->loadProducts(),
            'finalD' => $this->product->disccount_price,
        ]);
    }
    public function updatedPercent()
    {
        $this->calculateFinalD();
    }
    function loadProducts()
    {

        if (!empty($this->search)) {
            $this->resetPage();

            $query = producto::with('marca')
                ->where(function ($query) {
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%")
                        ->orWhere('sku', "{$this->search}")
                        ->orWhere('intern_sku', "{$this->search}");
                })
                ->orderBy('name', 'asc')
                ->paginate(5);
        } else {

            $query =  producto::with(['categorias','marca'])->orderBy('stock_qty', 'asc')->paginate(5);
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
        $this->resetExcept('product');
        $this->product = new producto();
        $this->action =2;
    }
    public function Delete(producto $producto)
    {
        $this -> destroy($producto);
    }

    public function Edit(producto $product){
        $this->resetValidation();
        $this->product = $product;
        $this->categoriesList = implode(", ", $product->categorias->pluck('name')->toArray());
        $this->action = 3;
        $this->dispatchBrowserEvent('refresh-tom');
    }

    public function cancelEdit()
    {
        $this->resetValidation();
        $this->resetExcept(['product', 'marcas']);
        $this->product = new producto();
        $this->product->type = 'simple';
        $this->product->status = 'publish';
        $this->product->visibility = 'visible';
        $this->product->stock_status = 'instock';
        $this->product->manage_stock = 1;
        $this->product->brand_id = $this->marcas->first()->id ?? null;
        $this->action = 1;
    }

    function viewProduct(producto $product)
    {
        $this->productSelected = $product;
        $this->dispatchBrowserEvent('view-product', ['id' => $this->productSelected]);
    }
    function calculateFinalD()
    {
        if($this->percent){
            $disccountP=floatval($this->product->gross_price)-(floatval($this->product->gross_price)*floatval($this->percent/100));
            $this->finalD=floatval($disccountP);
            return $this->finalD;
        }
        return;
    }

    function calculateP()
    {
        $this->finalD=$this->product->gross_price;
    }
    function Store()
    {
        sleep(1);

        if ($this->product->id > 0) {
            $this->validate([
                'product.name' => [
                    'required',
                    'min:3',
                    'max:60',
                    Rule::unique('productos', 'name')->ignore($this->product->id, 'id')
                ],
                'product.sku' => [
                    'nullable',
                    'max:25',
                    Rule::unique('productos', 'sku')->ignore($this->product->id, 'id')
                ],
            ]);
        } else {
            $this->validate($this->rules);
        }

        $this->product->disccount_price=$this->finalD;
        $this->product->save();

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
                        $categoria = categoria_producto::where('name', $catName)->first();
                        // reemplazar el elemento con el ID de la categoría si existe
                        if ($categoria) {
                            return $categoria->id;
                        }
                    }

                    // devolver el elemento sin cambios              
                    return $item;
                }, $listCategories);
            }
            $listCategories !== null ? $this->product->categorias()->sync($listCategories) : $this->product->categorias()->detach();
        }


        //images
        if (!empty($this->gallery)) {
            // eliminar imagenes del disco
            if ($this->product->id > 0) {
                $this->product->images()->each(function ($img) {
                    unlink('storage/productos/' . $img->file);
                });

                // eliminar las relaciones
                $this->product->images()->delete();
            }

            // guardar imagenes nuevas
            foreach ($this->gallery as $photo) {
                $fileName = uniqid() . '_.' . $photo->extension();
                $photo->storeAs('public/productos', $fileName);

                // creamos relacion
                $img = Image::create([
                    'model_id' => $this->product->id,
                    'model_type' => 'App\Models\producto',
                    'file' => $fileName
                ]);

                // guardar relacion
                $this->product->images()->save($img);
            }
        }

        // //sync con woocommerce
        // // if ($this->action == 2) $this->createProduct($this->product);
        // // if ($this->action == 3) $this->updateProduct($this->product);
        // $this->action == 2 ? $this->createProduct($this->product) : ($this->action == 3 ? $this->updateProduct($this->product) : null);




        $this->dispatchBrowserEvent('noty', ['msg' => 'SOLICITUD PROCESADA CON ÉXITO']);
        $this->resetExcept(['product', 'marcas']);
        
        $this->product = new producto();
        $this->product->type = 'simple';
        $this->product->status = 'publish';
        $this->product->visibility = 'visible';
        $this->product->stock_status = 'instock';
        $this->product->manage_stock = 1;
        $this->product->brand_id = $this->marcas->first()->id ?? null;


    }
    public function destroy(producto $producto)
    {
        //eliminar el archivo físicamente            
        $producto->images()->each(function ($img){
            unlink('storage/productos/' . $img->file);
        });
        //Eliminar archivo de la base de datos
        $producto->images()->delete();

        if ($producto->platform_id != null) {
            $this->deleteProduct($producto->platform_id, true);
        }

        $producto->categorias()->detach();

        $producto->delete();

        $this->resetPage();

        $this->dispatchBrowserEvent('noty-error',['msg'=>'PRODUCTO ELIMINADO']);
        $this->dispatchBrowserEvent('stop-loader');
        
    }

    function Sync(producto $product){
        $this->findOrCreateProductByName($product);
    }

    function SyncAll(){
        $productos = producto::all();
        $this->resetPage();
        foreach($productos as $product){
            $this->sync($product);
        }
        $this->dispatchBrowserEvent('noty',['msg'=>'SINCRONIZACIÓN COMPLETA']);
    }
}
