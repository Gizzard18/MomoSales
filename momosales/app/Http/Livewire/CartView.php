<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Empleado;
use App\Models\producto;
use Illuminate\Support\Collection;

class CartView extends Component
{
    public $productos=[],$searchProduct='',$searchTerm,$empleados,$product,$productSelected;
    public Empleado $empleado;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = [
        'refresh' => '$refresh',
        'selected_product' => 'selectedProduct'
    ];
    public function searchAndDoAction()
    {
        $this->buscarProducto();
        $this->emit('refresh');
        $this->reset('searchProduct');
    }
    public function buscarProducto()
    {
        if($this->searchProduct!=null){
            $searchTerm = '%' . $this->searchProduct . '%';
            $this->productos = producto::where('name', 'like', $searchTerm)->get();    
        }else{
            $this->productos = '';
        }
        
    }

    function selectedProduct()
    {
        $this->productos = '';
    }
    
    public function render()
    {
        //validamos que exista la sesion
        if (session()->has('cart')) {
            //obtenemos el carrito
            $cart = session('cart');
            // ordenar los items del carrito mediante nombre de forma asc
            $cartInfo = $cart->sortBy(['name', ['name', 'asc']]);
        } else {
            $cartInfo = new Collection;
        }
        $this->empleados = Empleado::orderBy('first_name')->get();
        return view('livewire.cart-view',compact('cartInfo'));
    }

}
