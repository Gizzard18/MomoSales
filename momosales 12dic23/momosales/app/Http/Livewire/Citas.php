<?php

namespace App\Http\Livewire;

use App\Models\cita;
use App\Models\servicio;
use Illuminate\Support\Arr;
use Livewire\Component;
use App\Models\Empleado;
use Livewire\WithPagination;
use Illuminate\Support\Collection;

class Citas extends Component
{
    use WithPagination;
    public $search,$taxCart = 0, $itemsCart, $subtotalCart = 0, $totalCart = 0, $empleados, $disccount=0, $generated_points=0,$action=2;
    public Collection $cart;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        if (session()->has('cartS')) {
            $this->cart = session('cartS');
        } else {
            $this->cart = new Collection;
        }
        $this->empleados = Empleado::orderBy('first_name')->get();
    }

    protected $listeners = ['refresh' => '$refresh',
    'search' => 'searching',
    'add-service' => 'addServiceFromCard',
    'removeItem','updateIva',
    'updateEmpleado','updatePercentage',
    'clear-cart' => 'clear','generatedPoints','openForm'];

    function borrarCita($action)
    {
        $this->action=$action;
        $this->emit('actionChanged');
    }
    function openForm()
    {
        $this->action=2;
        $this->emit('actionChanged');
    }
    public function searching($searchText)
    {
        $this->search = trim($searchText);
    }

    public function render()
    {
        $this->taxCart = $this->totalIVA();
        $this->itemsCart = $this->totalItems();
        $this->subtotalCart = $this->subtotalCart();
        $this->totalCart = $this->totalCart();
        $this->generated_points = $this->generatedPoints();

        $events = array();
        $citas = cita::all();
        foreach($citas as $cita){
            $events[] = [
                'title' => $cita->title,
                'start' => $cita->start,
                'end' => $cita->end,
                'url' => $cita->url,
                'className' => $cita->className
            ];
        }

        return view('livewire.citas.citas', ['events' => $events]);
    }
    
    function removeItem($id)
    {
        $this->cart = $this->cart->reject(function ($service) use ($id) {
            return $service['id'] === $id;
        });

        $this->save();

        $this->emit('refresh');
        $this->dispatchBrowserEvent('noty-error', ['msg' => 'SERVICIO ELIMINADO']);
    }
    function addServiceFromCard(servicio $service)
    {
        $this->AddService($service);
    }
    function AddService($service, $qty = 1,$disccount_percent=0, $ind_iva=0.16,$empleado=NULL)
    {
        // iva méxico 16%
        $iva = $ind_iva;
        // determinar precio venta con iva
        $salePrice = ($service->disccount_price > 0 && $service->disccount_price < $service->gross_price ?  $service->disccount_price : $service->gross_price);
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
        $uid = uniqid() . $service->id;

        $coll = collect(
            [
                'id' => $uid,
                'sid' => $service->id,
                'name' => $service->name,
                'reward_points' => floatval($service->reward_points),
                'gross_price' => floatval($service->gross_price),
                'disccount_price' => floatval($service->disccount_price),
                'disccount_percent' => floatval($disccount_percent),
                'sale_price' => floatval($salePrice),
                'ind_iva' => floatval($ind_iva),
                'tax' => floatval($tax),
                'total' => floatval($total),
                'vendedor' => $empleado,
                'duration' => $service->duration
            ]
        );
        $itemCart = Arr::add($coll, null, null);
        $this->cart->push($itemCart);
        $this->save();
        $this->emit('refresh');
        $this->dispatchBrowserEvent('noty', ['msg' => 'SERVICIO AGREGADO']);
    }
    function save()
    {
        session()->put('cartS', $this->cart);
        session()->save();
        $this->emit('refresh');
    }

    function inCart($service_id)
    {
        $mycart = $this->cart;

        $cont = $mycart->where('sid', $service_id)->count();

        return  $cont > 0 ? true : false;
    }

    function updatePercentage($uid,$disccount_percent=0, $service_id = null)
    {
        $mycart = $this->cart;
        if ($service_id == null) {
            $oldItem = $mycart->where('id', $uid)->first();
        } else {
            $oldItem = $mycart->where('sid', $service_id)->first();
        }

        $newItem  = $oldItem;

        //se agrega 0 por default cuando se agrega por primera vez el servicio
        //si ya está agregado el servicio, toma lo que esté en el input
        $newItem['disccount_percent'] = $service_id == null ? intval($disccount_percent) : intval($newItem['disccount_percent'] + $disccount_percent);

        $this->disccount=$newItem['disccount_percent'];

        $values = $this->Calculator($newItem['sale_price'], $newItem['ind_iva']);

        $this->disccount=0;

        if(!$disccount_percent){
            $newItem['$disccount_percent']=0;
        }

        $newItem['tax'] =  $values['iva'];

        $newItem['disccount_price'] = $values['sale_price'];

        $newItem['subtotal'] = $values['neto'];

        $newItem['total'] = $values['total'];


        //eliminar el item de la coleccion / sesion
        $this->cart  = $this->cart->reject(function ($service) use ($uid, $service_id) {
            return  $service['id'] === $uid || $service['sid'] === $service_id;
        });
        $this->save();

        $this->cart->push(Arr::add($newItem, null, null));
        $this->save();

        $this->emit('refresh');
        $this->dispatchBrowserEvent('noty', ['msg' => 'SERVICIO ACTUALIZADO']);
    }
    function updateIva($uid, $selectedIva, $service_id = null)
    {
        $mycart = $this->cart;
        if ($service_id == null) {
            $oldItem = $mycart->where('id', $uid)->first();
        } else {
            $oldItem = $mycart->where('sid', $service_id)->first();
        }

        $newItem  = $oldItem;

        $newItem['ind_iva']= $selectedIva;
        if($newItem['disccount_price']){
            $values = $this->Calculator($newItem['disccount_price'], $newItem['ind_iva']);    
            $newItem['disccount_price'] = $values['sale_price'];
        }else{
            $values = $this->Calculator($newItem['sale_price'], $newItem['ind_iva']);
        }
        $newItem['tax'] =  $values['iva'];

        $newItem['total'] = $values['total'];


        //eliminar el item de la coleccion / sesion
        $this->cart  = $this->cart->reject(function ($service) use ($uid, $service_id) {
            return  $service['id'] === $uid || $service['sid'] === $service_id;
        });
        $this->save();

        $this->cart->push(Arr::add($newItem, null, null));
        $this->save();

        $this->emit('refresh');
        $this->dispatchBrowserEvent('noty', ['msg' => 'IVA ACTUALIZADO']);
    }
    function updateEmpleado($uid, $selectedEmpleado, $service_id = null)
    {
        $mycart = $this->cart;
        if ($service_id == null) {
            $oldItem = $mycart->where('id', $uid)->first();
        } else {
            $oldItem = $mycart->where('sid', $service_id)->first();
        }

        $newItem  = $oldItem;

        $newItem['vendedor']= $selectedEmpleado;
        //eliminar el item de la coleccion / sesion
        $this->cart  = $this->cart->reject(function ($service) use ($uid, $service_id) {
            return  $service['id'] === $uid || $service['sid'] === $service_id;
        });
        $this->save();

        $this->cart->push(Arr::add($newItem, null, null));
        $this->save();
        $this->emit('refresh');

    }

    function Calculator($price, $ind_iva)
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
        $subtotalNeto =   $precioUnitarioSinIva;
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
        $iva = $this->cart->sum(function ($service) {
            return $service['tax'];
        });

        return $iva;
    }

    function totalCart()
    {
        $amount = $this->cart->sum(function ($service) {
            return $service['total'];
        });
        return $amount;
    }

    function totalItems()
    {
        $items = count($this->cart);
        return $items;
    }


    function subtotalCart()
    {
        $subt = $this->cart->sum(function ($service) {
            if($service['disccount_price']){
                $subT=($service['disccount_price'])/($service['ind_iva']+1);
                return $subT;
            }else{
                $subT=($service['sale_price'])/($service['ind_iva']+1);
                return $subT;
            }
        });
        return $subt;
    }

    function generatedPoints()
    {
        $reward_points = $this->cart->sum(function ($service) {
            if(isset($service['reward_points'])){
                $rewP=$service['reward_points'];
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
