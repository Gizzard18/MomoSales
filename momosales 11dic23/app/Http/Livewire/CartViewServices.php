<?php

namespace App\Http\Livewire;

use App\Models\servicio;
use Livewire\Component;
use App\Models\Empleado;
use Livewire\WithPagination;
use Illuminate\Support\Collection;

class CartViewServices extends Component
{
    public $servicios=[],$searchTerm,$empleados,$service,$serviceSelected,$query;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['refresh' => '$refresh'];

    function updatedQuery()
    {
        $this->servicios= servicio::where('name','like',"%{$this->query}%")->orderBy('name')->get()->take(5);
    }
    public function render()
    {
        //validamos que exista la sesion
        if (session()->has('cartS')) {
            //obtenemos el carrito
            $cart = session('cartS');
            // ordenar los items del carrito mediante nombre de forma asc
            $cartInfo = $cart->sortBy(['name', ['name', 'asc']]);
        } else {
            $cartInfo = new Collection;
        }
        $this->empleados = Empleado::orderBy('first_name')->get();
        return view('livewire.cart-view-services',compact('cartInfo'));
    }
}
