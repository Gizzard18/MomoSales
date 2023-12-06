<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\servicio;
use App\Models\categoria_servicio;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class Servicios extends Component
{
    use WithPagination;

    public $records, $search, $action =1, $serviceSelected, $categoriesList,$percent=0,$finalD=0;
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
        $this->service = new servicio();
        $this->service->duration = '60';
        $this->calculateFinalDS();
    }

    protected $listeners = [
        'refresh' => '$refresh',
        'search' => 'searching',
        'Delete', 'calculateFinalDS','calculate'
        ];
    
    public function render()
    {
        return view('livewire.servicios.servicios',[
            'servicios' => $this->loadServices()
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
        $this->resetValidation();
        $this->service = $service;
        $this->categoriesList = implode(", ", $service->categorias->pluck('name')->toArray());
        $this->action = 3;
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

        $this->dispatchBrowserEvent('noty', ['msg' => 'SOLICITUD PROCESADA CON ÉXITO']);
        $this->resetExcept(['service']);
        
        $this->service = new servicio();
        $this->service->duration = '60';
    }
    public function destroy(servicio $servicio)
    {
        $servicio->categorias()->detach();

        $servicio->delete();

        $this->resetPage();

        $this->dispatchBrowserEvent('noty-error',['msg'=>'servicio ELIMINADO']);
        $this->dispatchBrowserEvent('stop-loader');
    }
}
