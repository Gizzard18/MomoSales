<?php

namespace App\Http\Livewire;

use App\Models\Asignacion_venta;
use App\Models\Empleado;
use Livewire\Component;
use App\Models\producto;
use Illuminate\Support\Arr;
use Livewire\WithPagination;
use Illuminate\Support\Collection;

class Ventas extends Component
{
    use WithPagination;
    public $search,$taxCart = 0, $itemsCart, $subtotalCart = 0, $totalCart = 0, $empleados, $disccount=0, $generated_points=0;
    public Collection $cart;
    public Asignacion_venta $asignacion_venta;
    public Empleado $empleado;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        if (session()->has('cart')) {
            $this->cart = session('cart');
        } else {
            $this->cart = new Collection;
        }
        
        $this->empleados = Empleado::orderBy('first_name')->get();
    }

    protected $listeners = [
        'refresh' => '$refresh',
        'search' => 'searching',
        'add-product' => 'addProductFromCard',
        'removeItem', 'updateQty','updateIva',
        'updateEmpleado','updatePercentage',
        'clear-cart' => 'clear','generatedPoints'
    ];
    
    public function searching($searchText)
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
    public function render()
    {
        $this->taxCart = $this->totalIVA();
        $this->itemsCart = $this->totalItems();
        $this->subtotalCart = $this->subtotalCart();
        $this->totalCart = $this->totalCart();
        $this->generated_points = $this->generatedPoints();
        return view('livewire.ventas.ventas', [
            'productos' => $this->loadProducts()
        ]);
    }
    function removeItem($id)
    {
        $this->cart = $this->cart->reject(function ($product) use ($id) {
            return $product['id'] === $id;
        });

        $this->save();

        $this->emit('refresh');
        $this->dispatchBrowserEvent('noty-error', ['msg' => 'PRODUCTO ELIMINADO']);
    }
    function addProductFromCard(producto $product)
    {
        $this->AddProduct($product);
    }
    function AddProduct($product, $qty = 1,$disccount_percent=0, $ind_iva=0.16,$empleado=NULL)
    {
        // validar si ya existe en el carrito
        if ($this->inCart($product->id)) {
            $this->updateQty(null, $qty, $product->id);
            return; // => con esta línea se agrupan los productos por nombre dentro del carrito
        }

        //agregar al carrito

        // iva méxico 16%
        $iva = $ind_iva;
        // determinar precio venta con iva
        $salePrice = ($product->disccount_price > 0 && $product->disccount_price < $product->gross_price ?  $product->disccount_price : $product->gross_price);
        //precio unitario sin iva
        $precioUnitarioSinIva = $salePrice / (1 + $iva);
        // subtotal neto
        $subtotalNeto = $precioUnitarioSinIva * intval($qty);
        //monto del iva
        $montoIva = $subtotalNeto * $iva;
        //total con iva
        $totalConIva  = $subtotalNeto + $montoIva;

        $tax  = $montoIva;
        $total = $totalConIva;
        $uid = uniqid() . $product->id;

        $coll = collect(
            [
                'id' => $uid,
                'pid' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'reward_points' => floatval($product->reward_points),
                'intern_sku' => $product->intern_sku,
                'gross_price' => floatval($product->gross_price),
                'disccount_price' => floatval($product->disccount_price),
                'disccount_percent' => floatval($disccount_percent),
                'sale_price' => floatval($salePrice),
                'qty' => intval($qty),
                'ind_iva' => floatval($ind_iva),
                'tax' => floatval($tax),
                'total' => floatval($total),
                'stock' => $product->stock_qty,
                'type' => $product->type_product,
                'image' => $product->photo,
                'vendedor' => $empleado,
                'platform_id' => $product->platform_id
            ]
        );
        $itemCart = Arr::add($coll, null, null);
        $this->cart->push($itemCart);
        $this->save();
        $this->emit('refresh');
        $this->emit('selected_product');
        $this->dispatchBrowserEvent('noty', ['msg' => 'PRODUCTO AGREGADO']);
    }
    function save()
    {
        session()->put('cart', $this->cart);
        session()->save();
        $this->emit('refresh');
    }

    function inCart($product_id)
    {
        $mycart = $this->cart;

        $cont = $mycart->where('pid', $product_id)->count();

        return  $cont > 0 ? true : false;
    }

    function updateQty($uid, $cant = 1, $product_id = null)
    {
        if (!is_numeric($cant)) {
            $this->dispatchBrowserEvent('noty-error', ['msg' => $cant . ' NO ES UNA CANTIDAD VÁLIDA']);
            return;
        }

        $mycart = $this->cart;
        if ($product_id == null) {
            $oldItem = $mycart->where('id', $uid)->first();
        } else {
            $oldItem = $mycart->where('pid', $product_id)->first();
        }


        $newItem  = $oldItem;

        $newItem['qty'] = $product_id == null ? intval($cant) : intval($newItem['qty'] + $cant);

        if($newItem['disccount_price']){
            $values = $this->Calculator($newItem['disccount_price'], $newItem['qty'], $newItem['ind_iva']);    
        }else{
            $values = $this->Calculator($newItem['sale_price'], $newItem['qty'], $newItem['ind_iva']);
        }
        $newItem['tax'] =  $values['iva'];

        $newItem['total'] = $values['total'];


        //eliminar el item de la coleccion / sesion
        $this->cart  = $this->cart->reject(function ($product) use ($uid, $product_id) {
            return  $product['id'] === $uid || $product['pid'] === $product_id;
        });
        $this->save();

        $this->cart->push(Arr::add($newItem, null, null));
        $this->save();

        $this->emit('refresh');
        $this->dispatchBrowserEvent('noty', ['msg' => 'PRODUCTO ACTUALIZADO']);
    }
    function updatePercentage($uid,$disccount_percent=0, $product_id = null)
    {
        $mycart = $this->cart;
        if ($product_id == null) {
            $oldItem = $mycart->where('id', $uid)->first();
        } else {
            $oldItem = $mycart->where('pid', $product_id)->first();
        }

        $newItem  = $oldItem;

        //se agrega 0 por default cuando se agrega por primera vez el producto
        //si ya está agregado el producto, toma lo que esté en el input
        $newItem['disccount_percent'] = $product_id == null ? intval($disccount_percent) : intval($newItem['disccount_percent'] + $disccount_percent);

        $this->disccount=$newItem['disccount_percent'];

        $values = $this->Calculator($newItem['sale_price'], $newItem['qty'], $newItem['ind_iva']);

        $this->disccount=0;

        if(!$disccount_percent){
            $newItem['$disccount_percent']=0;
        }

        $newItem['tax'] =  $values['iva'];

        $newItem['disccount_price'] = $values['sale_price'];

        $newItem['subtotal'] = $values['neto'];

        $newItem['total'] = $values['total'];


        //eliminar el item de la coleccion / sesion
        $this->cart  = $this->cart->reject(function ($product) use ($uid, $product_id) {
            return  $product['id'] === $uid || $product['pid'] === $product_id;
        });
        $this->save();

        $this->cart->push(Arr::add($newItem, null, null));
        $this->save();

        $this->emit('refresh');
        $this->dispatchBrowserEvent('noty', ['msg' => 'PRODUCTO ACTUALIZADO']);
    }
    function updateIva($uid, $selectedIva, $product_id = null)
    {
        $mycart = $this->cart;
        if ($product_id == null) {
            $oldItem = $mycart->where('id', $uid)->first();
        } else {
            $oldItem = $mycart->where('pid', $product_id)->first();
        }

        $newItem  = $oldItem;

        $newItem['ind_iva']= $selectedIva;
        if($newItem['disccount_price']){
            $values = $this->Calculator($newItem['disccount_price'], $newItem['qty'], $newItem['ind_iva']);    
            $newItem['disccount_price'] = $values['sale_price'];
        }else{
            $values = $this->Calculator($newItem['sale_price'], $newItem['qty'], $newItem['ind_iva']);
        }
        $newItem['tax'] =  $values['iva'];

        $newItem['total'] = $values['total'];


        //eliminar el item de la coleccion / sesion
        $this->cart  = $this->cart->reject(function ($product) use ($uid, $product_id) {
            return  $product['id'] === $uid || $product['pid'] === $product_id;
        });
        $this->save();

        $this->cart->push(Arr::add($newItem, null, null));
        $this->save();

        $this->emit('refresh');
        $this->dispatchBrowserEvent('noty', ['msg' => 'IVA ACTUALIZADO']);
    }
    function updateEmpleado($uid, $selectedEmpleado, $product_id = null)
    {
        $mycart = $this->cart;
        if ($product_id == null) {
            $oldItem = $mycart->where('id', $uid)->first();
        } else {
            $oldItem = $mycart->where('pid', $product_id)->first();
        }

        $newItem  = $oldItem;

        $newItem['vendedor']= $selectedEmpleado;
        //eliminar el item de la coleccion / sesion
        $this->cart  = $this->cart->reject(function ($product) use ($uid, $product_id) {
            return  $product['id'] === $uid || $product['pid'] === $product_id;
        });
        $this->save();

        $this->cart->push(Arr::add($newItem, null, null));
        $this->save();
        $this->emit('refresh');

    }

    function Calculator($price, $qty, $ind_iva)
    {
        if($this->disccount){
            //determinamos el precio de venta(con iva)
            $salePrice = $price-(($price*$this->disccount)/100);
        }else{
            //determinamos el precio de venta(con iva)
            $salePrice = $price;
        }
        // precio unitario sin iva
        $precioUnitarioSinIva =  $salePrice / (1 + $ind_iva);
        // subtotal neto
        $subtotalNeto =   $precioUnitarioSinIva * intval($qty);
        //monto iva
        $montoIva = $subtotalNeto  * $ind_iva;
        //total con iva
        $totalConIva =  $subtotalNeto + $montoIva;
        // dd($subtotalNeto);

        return [
            'sale_price' => $salePrice,
            'neto' => $subtotalNeto,
            'iva' => $montoIva,
            'total' => $totalConIva
        ];
    }


    function totalIVA()
    {
        $iva = $this->cart->sum(function ($product) {
            return $product['tax'];
        });

        return $iva;
    }

    function totalCart()
    {
        $amount = $this->cart->sum(function ($product) {
            return $product['total'];
        });
        return $amount;
    }

    function totalItems()
    {
        $items = $this->cart->sum(function ($product) {
            return $product['qty'];
        });
        return $items;
    }


    function subtotalCart()
    {
        $subt = $this->cart->sum(function ($product) {
            if($product['disccount_price']){
                $subT=($product['qty']*$product['disccount_price'])/($product['ind_iva']+1);
                return $subT;
            }else{
                $subT=($product['qty']*$product['sale_price'])/($product['ind_iva']+1);
                return $subT;
            }
        });
        return $subt;
    }

    function generatedPoints()
    {
        $reward_points = $this->cart->sum(function ($product) {
            if(isset($product['reward_points'])){
                $rewP=($product['qty']*$product['reward_points']);
                return $rewP;
            }else{
                return 0;
            }
        });
        return $reward_points;
    }

    function clear()
    {
        $this->cart = new Collection;
        $this->save();
        $this->emit('refresh');
    }

}
