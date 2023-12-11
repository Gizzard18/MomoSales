<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Empleado;
use App\Models\producto;
use Illuminate\Support\Collection;

class CartView extends Component
{
    public $productos=[],$searchTerm,$empleados,$product,$productSelected,$query;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = [
        'refresh' => '$refresh'
    ];
    function updatedQuery()
    {
        $this->productos= producto::where('name','like',"%{$this->query}%")->orderBy('name')->get()->take(5);
    }
    public function render()
    {
        //validamos que exista la sesion
        if (session()->has('cartP')) {
            //obtenemos el carrito
            $cart = session('cartP');
            // ordenar los items del carrito mediante nombre de forma asc
            $cartInfo = $cart->sortBy(['name', ['name', 'asc']]);
        } else {
            $cartInfo = new Collection;
        }
        $this->empleados = Empleado::orderBy('first_name')->get();
        return view('livewire.cart-view',compact('cartInfo'));
    }

}
