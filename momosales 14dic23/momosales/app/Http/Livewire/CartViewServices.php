<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Empleado;
use App\Models\Material;
use App\Models\servicio;
use Livewire\WithPagination;
use Illuminate\Support\Collection;

class CartViewServices extends Component
{
    public $servicios=[],$searchTerm,$empleados,$service,$serviceSelected,$query,$show=false,$materials=[],$start_date,$end_date,$minutesQty=0,$minutes;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['refresh' => '$refresh','rangeSelected','sumDuration','resetQuery'];

    function sumDuration($minutes)
    {
        $this->minutesQty=$minutes;
    }
    function resetQuery()
    {
        $this->reset('query');
    }
    function updatedQuery()
    {
        $this->servicios= servicio::where('name','like',"%{$this->query}%")->orderBy('name')->get()->take(5);
    }
    function rangeSelected($start,$end)
    {
        $start = Carbon::parse($start)->locale('es')->format('l d-m-Y H:i');
        $end = Carbon::parse($end);
        
        // Sumar minutos y obtener la nueva fecha
        $newEnd = $end->copy()->addMinutes(intval($this->minutesQty));
    
        // Asignar las fechas formateadas a las propiedades públicas
        $this->start_date = $start;
        $this->end_date = $newEnd->format('H:i'); // Solo mostrar la hora
        $this->minutes = $this->calcularDiferenciaEnMinutos();
        $this->resetPage();
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
    function mostrarMateriales(servicio $servicio)
    {
        $this->show = $servicio->id;
        $this->materials = Material::where('servicio_id', $servicio->id)->get();
        $this->emit('refresh');
    }
    function calcularDiferenciaEnMinutos()
    {
        $start_date= Carbon::parse($this->start_date)->format('H:i');
        // Asegurarse de que las fechas sean instancias de Carbon
        $start = Carbon::parse($start_date);
        $end = Carbon::parse($this->end_date);

        // Calcular la diferencia en minutos
        $diferenciaEnMinutos = $start->diffInMinutes($end);

        // Puedes usar $diferenciaEnMinutos como necesites
        return $diferenciaEnMinutos;
    }
    function close()
    {
        $this->show=false;
    }
}