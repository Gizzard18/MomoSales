<?php

namespace App\Http\Livewire;

use App\Models\venta;
use App\Models\cliente;
use Livewire\Component;
use App\Models\producto;
use App\Traits\SaleTrait;
use Illuminate\Support\Arr;
use Livewire\WithPagination;
use App\Models\Asignacion_venta;
use App\Models\detalles_puntos_venta;
use App\Models\metodo_pago_venta;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Payment extends Component
{
    use WithPagination;
    use SaleTrait;
    public $totalCart, $itemsCart, $cash, $customerId, $reference, $paymentMethod, $tips,$generated_points, $global_disccount, $disccount=0, $rest;
    public Collection $methods;

    protected $paginationTheme = 'bootstrap';

    public function __construct()
    {
        // Inicializa las propiedades en el constructor
        $this->methods = collect(); // O cualquier otra inicialización que necesites
    }

    public function mount($itemsCart)
    {
        $this->itemsCart = $itemsCart;
        if (session()->has('methods')) {
            $this->methods = session('methods');
            $this->rest = $this->totalCart;
            $this->calculateRest();
        } else {
            $this->methods = new Collection;
            $this->rest = $this->totalCart;
        }
    }

    public function calculateRest()
    {
        if($this->rest==$this->totalCart){
            foreach($this->methods as $method){
                $this->rest -= $method['qty'];
            }
        }
    }
    protected $listeners = [
        'refresh' => '$refresh',
        'customerId' => 'setCustomerId',
        'removeMethod'
    ];
    function generatedPoints()
    {
        $cart = session('cart');
        $reward_points = $cart->sum(function ($product) {
            if(isset($product['reward_points'])){
                $rewP=($product['qty']*$product['reward_points']);
                return $rewP;
            }else{
                return 0;
            }
        });
        return $reward_points;
    }
    function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
    }
    function setTips($tips)
    {
        $this->tips = $tips;
    }

    function setEfectivo()
    {
        $this->paymentMethod = 'Efectivo';
        $this->AddMethod();
    }

    function setCard()
    {
        $this->paymentMethod = 'Card';
        $this->AddMethod();
    }

    function setBanorte()
    {
        $this->paymentMethod = 'Banorte';
        $this->AddMethod();
    }
    function setReward()
    {
        $this->cash=0;
        $cust = cliente::find($this->customerId);
        if($cust){
            if($cust->tarjetaPuntos){
                if($cust->tarjetaPuntos->balance){
                    $this->cash = $cust->tarjetaPuntos->balance;
                    $cust->tarjetaPuntos->balance=0;
                    $cust->tarjetaPuntos->save();
                    $this->paymentMethod = 'Puntos Recompensa';
                    $this->AddMethod();
                }else{
                    $this->dispatchBrowserEvent('noty-error', ['msg' => 'SIN SALDO EN TARJETA']);
                }
            }else{
                $this->dispatchBrowserEvent('noty-error', ['msg' => 'EL CLIENTE NO TIENE UNA TARJETA DE PUNTOS ACTIVADA']);
            }
            return;
        }
        $this->dispatchBrowserEvent('noty-error', ['msg' => 'SELECCIONE UN CLIENTE']);
    }
    public function render()
    {
        
        //validamos que exista la sesion
        if (session()->has('methods')) {
            //obtenemos los métodos de pago
            $methods = session('methods');
            // ordenar los métodos mediante nombre de forma asc
            $methodsInfo = $methods->sortBy(['name', ['name', 'asc']]);
        } else {
            $methodsInfo = new Collection;
        }
        return view('livewire.payment',compact('methodsInfo'), [
            'change' => $this->change,
        ]);
    }
    function getChangeProperty()
    {

        if (!is_numeric($this->totalCart) || !is_numeric($this->cash )) {
            return 0;
        }
        $this->emit('totalUpdated', $this->rest);
        return $this->rest - $this->cash;

    }
    function applyDisccount()
    {
        try{
            $this->validate([
                'totalCart' => 'numeric',
                'global_disccount' => 'numeric',
            ]);
            // Establecer un valor predeterminado si el descuento está vacío
            $this->global_disccount = is_numeric($this->global_disccount) ? $this->global_disccount : 0;
    
            $this->global_disccount = min($this->global_disccount, 100); // Asegurar que el descuento no sea mayor al 100%

            $this->disccount=$this->totalCart-($this->totalCart*($this->global_disccount/100));

            $this->rest=$this->disccount;
    
            $this->emit('totalUpdated', $this->rest);
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "EXCEPCIÓN: \n {$th->getMessage()}"] );
        }
    }
    function AddMethod()
    {
        // validar si ya existe entre los métodos
        if ($this->inMethods()) {
            $this->updateQty();
            return; // => con esta línea se agrupan los productos por nombre dentro del carrito
        }
        $this->validate([
            'paymentMethod' => 'required',
            'cash' => 'numeric | required',
            'reference' => 'nullable',
        ]);

        $coll = collect(
            [
                'name' => $this->paymentMethod,
                'qty' => floatval($this->cash),
                'reference' => $this->reference,
                'current_disccount' => $this->disccount
            ]
        );
        $method = Arr::add($coll, null, null);
        $this->methods->push($method);

        $this->rest-= $this->cash;
        $this->save();
        $this->emit('totalUpdated', $this->rest);
        $this->reset(['cash','reference']);
        $this->dispatchBrowserEvent('noty', ['msg' => 'MÉTODO DE PAGO AGREGADO']);
    }
    
    function updateQty()
    {
        $mymethods = $this->methods; 
        $oldItem = $mymethods->where('name', $this->paymentMethod)->first();
        
        $newItem  = $oldItem;

        $newItem['qty'] += floatval($this->cash);
        
        $this->rest-= $this->cash;
        //eliminar el item de la coleccion / sesion
        $paymentMethod=$this->paymentMethod;
        $this->methods = $this->methods->reject(function ($method) use ($paymentMethod) {
            return $method['name'] === $paymentMethod;
        });

        $this->methods->push(Arr::add($newItem, null, null));
        $this->save();
        $this->reset(['cash','reference']);

        $this->dispatchBrowserEvent('noty', ['msg' => 'MÉTODO ACTUALIZADO']);
    }
    function removeMethod($paymentMethod)
    {
        $mymethods = $this->methods; 
        $oldItem = $mymethods->where('name', $paymentMethod)->first();

        if($this->paymentMethod==='Puntos Recompensa'){
            $cust=cliente::find($this->customerId);
            $cust->tarjetaPuntos->balance=$oldItem['qty'];
            $cust->tarjetaPuntos->save();
        }
        
        $this->rest  += floatval($oldItem['qty']);

        $this->methods = $this->methods->reject(function ($method) use ($paymentMethod) {
            return $method['name'] === $paymentMethod;
        });


        $this->save();
        $this->emit('totalUpdated', $this->rest);

        $this->dispatchBrowserEvent('noty-error', ['msg' => 'MÉTODO ELIMINADO']);
    }
    function inMethods()
    {
        $mymethods = $this->methods;

        $cont = $mymethods->where('name', $this->paymentMethod)->count();

        return  $cont > 0 ? true : false;
    }
    function Store()
    {

        if (!session()->has('cart')) {
            $this->dispatchBrowserEvent('noty-error', ['msg' => 'NO HAY PRODUCTOS AGREGADOS']);
            return;
        }

        if ($this->customerId == null) {
            $this->dispatchBrowserEvent('noty-error', ['msg' => 'SELECCIONA UN CLIENTE']);
            return;
        }
        //VALIDAR EL CASH/EFECTIVO > TOTALCART

        //recuperamos carrito
        $cart = session('cart');

            DB::transaction(function () use ($cart) {

                //guardar venta
                $sale =  venta::create([
                    'total' => $this->totalCart,
                    'disccount' => $this->disccount,
                    'tips' => $this->tips,
                    'items' => $this->itemsCart,
                    'customer_id' => $this->customerId,
                    'user_id' => Auth()->user()->id,
                ]);


                //asignaciones de venta
                $details = $cart->map(function ($item) use ($sale) {
                    return [
                        'selected_item' => $item['pid'],
                        'venta_id' => $sale->id,
                        'quantity' => $item['qty'],
                        'disccount_percent' => $item['disccount_percent'],
                        'disccount_price' => $item['disccount_price'],
                        'iva' => $item['ind_iva'],
                        'empleado_id' => $item['vendedor'],
                        'current_price' => $item['sale_price'],
                        'generated_points' => $item['reward_points']*$item['qty']
                    ];
                })->toArray();
                $this->generated_points=$this->generatedPoints();
                $sale->generated_points=$this->generated_points;
                $sale->save();
                //metodos de pago
                $payment_methods = $this->methods->map(function ($method) use ($sale) {
                    return [
                        'Payment_method' => $method['name'],
                        'venta_id' => $sale->id,
                        'reference' => $method['reference'],
                        'amount' => $method['qty']
                    ];
                })->toArray();

                //option 1
                //$sale->details()->createMany($details);

                // option 2
                Asignacion_venta::insert($details);
                metodo_pago_venta::insert($payment_methods);

                $point_details = new detalles_puntos_venta();
                $customer = cliente::find($this->customerId);
                if($customer->tarjetaPuntos){
                    $point_details->venta_id=$sale->id;
                    $point_details->cliente_id=$customer->id;
                    $point_details->save();
                }

                //sync stocks
                $dataProducts = ['update' => []];

                foreach ($cart as $item) {
                    $product = producto::find($item['pid']);
                    $product->stock_qty -= $item['qty'];
                    $product->save();

                    $customer = cliente::find($this->customerId);
                    if($customer->tarjetaPuntos){
                        $customer->tarjetaPuntos->balance += $this->generated_points;
                        $customer->tarjetaPuntos->save();
                    }


                    $newStock = $product->stock_qty;
                    $dataProducts['update'][] = [
                        'id' => $product->platform_id,
                        'stock_quantity' => $newStock
                    ];
                }

                $resultSync = $this->SyncBatchStock($dataProducts);


                $this->reset();
                $this->dispatchBrowserEvent('close-payment');
                $this->dispatchBrowserEvent('noty', ['msg' =>  $resultSync ? "VENTA REGISTRADA - STOCK SINCRONIZADO" : "FALLA DE SINCRONIZACIÓN"]);
                //
                $this->emit('clear-cart');
                $this->emit('refresh');
                $this->clear();
            });
    }
    function save()
    {
        session()->put('methods', $this->methods);
        session()->save();
    }
    function clear()
    {
        $this->methods = new Collection;
        $this->save();
        $this->emit('refresh');
    }


}
