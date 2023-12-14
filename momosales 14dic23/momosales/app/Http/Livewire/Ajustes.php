<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Empleado;
use App\Models\producto;
use App\Models\servicio;
use Livewire\WithPagination;
use App\Models\categoria_producto;
use App\Models\categoria_servicio;
use App\Models\excepcion_producto;
use App\Models\excepcion_cat_producto;
use App\Models\excepcion_servicio;
use App\Models\excepcion_cat_servicio;
use App\Models\recompensas_producto;
use App\Models\recompensas_servicio;

class Ajustes extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $categoriasP,$categoriasS,$categoriesList,$categoriesListP,$empleados,$productos,$viewProducts=true,$viewServices,$action=1,$editing,$excepcion,$listado=[],$query,$tipoExc,$qty,$tipo,$creating=false,$comision,$selectedItem,$empleado; 
    public recompensas_producto $recompensaP;
    public recompensas_servicio $recompensaS;

    
    protected $rules =    [
        'recompensaS.percent' => "required|numeric",
        'recompensaS.to_all' => "nullable",
        'recompensaP.percent' => "required",
        'recompensaP.to_all' => "nullable",
        'query' => "nullable"
    ];


    public function cambiarModalP()
    {
        $this->action=2;
    }
    public function cambiarModalS()
    {
        $this->action=3;
    }
    public function mount()
    {
        $this->tipoExc = 'percent';
        $this->listado = [];
        $this->selectedItem = null;
        $this->empleados = Empleado::orderBy('first_name')->get();
        $this->recompensaS = new recompensas_servicio();
        if(!$this->recompensaS->first()){
            $this->recompensaS->percent = '0';
            $this->recompensaS->to_all = false;
            $this->categoriasS = categoria_servicio::orderBy('name')->get();
        }else{
            $this->recompensaS=$this->recompensaS->first();
            $this->categoriesList = implode(", ", $this->recompensaS->categorias->pluck('name')->toArray());
        }    

        $this->recompensaP = new recompensas_producto();
        if(!$this->recompensaP->first()){
            $this->recompensaP->percent = '0';
            $this->recompensaP->to_all = false;
            $this->categoriasP = categoria_producto::orderBy('name')->get();
        }else{
            $this->recompensaP=$this->recompensaP->first();
            $this->categoriesListP = implode(", ", $this->recompensaP->categorias->pluck('name')->toArray());
        }    
    }

    function ApplyChangeP()
    {
        $this->resetPage();
        if($this->recompensaP->to_all){
            $products = producto::all();
        }elseif($this->recompensaP->categorias){
            $products = collect();
            foreach ($this->recompensaP->categorias as $categoria) {
                $categoriaProducts = $categoria->productos()->get();
                $products = $products->merge($categoriaProducts);
            }
        }else{
            $this->dispatchBrowserEvent('noty-error',['msg'=>'NO SE HA SELECCIONADO UN MÉTODO DE AJUSTE']);
            return;
        }
        foreach($products as $product){
            $price=$product->gross_price;
            $calculatedReward=$price*(min(floatval($this->recompensaP->percent),100)/100);
            $product->reward_points=$calculatedReward;
            $product->save();
        }
        $this->dispatchBrowserEvent('stop-loader');
        $this->dispatchBrowserEvent('noty',['msg'=>'POCENTAJE APLICADO']);
    }
    function ApplyChangeS()
    {
        $this->resetPage();
        if($this->recompensaS->to_all){
            $services = servicio::all();
        }elseif($this->recompensaS->categorias){
            $services = collect();
            foreach ($this->recompensaS->categorias as $categoria) {
                $categoriaServices = $categoria->servicio()->get();
                $services = $services->merge($categoriaServices);
            }
        }
        else{
            $this->dispatchBrowserEvent('noty-error',['msg'=>'NO SE HA SELECCIONADO UN MÉTODO DE AJUSTE']);
            return;
        }
        foreach($services as $service){
            $price=$service->gross_price;
            $calculatedReward=$price*(min(floatval($this->recompensaS->percent),100)/100);
            $service->reward_points=$calculatedReward;
            $service->save();
        }
        $this->dispatchBrowserEvent('stop-loader');
        $this->dispatchBrowserEvent('noty',['msg'=>'POCENTAJE APLICADO']);
    }
    protected $listeners = ['refresh' => '$refresh', 'Add','Delete'];

    public function render()
    {
        return view('livewire.ajustes.ajustes');
    }
    function cancelRP()
    {
        if($this->recompensaP->id){
            $recompensaP = recompensas_producto::first();
            $recompensaP->categorias()->detach();   
            $recompensaP->delete();
            $this->resetPage();
    
            $this->dispatchBrowserEvent('noty-error',['msg'=>'POCENTAJE ELIMINADO - REINICIE LA PÁGINA PARA EFECTUAR CAMBIOS']);
            $this->dispatchBrowserEvent('stop-loader');
            return;
        }
        $this->dispatchBrowserEvent('noty-error',['msg'=>'NO HAY PORCENTAJE ALMACENADO - REINICIE LA PÁGINA PARA EFECTUAR CAMBIOS']);
    }

    function cancelRS()
    {
        if($this->recompensaS->id){
            $recompensa = recompensas_servicio::first();
            $recompensa->categorias()->detach();   
            $recompensa->delete();
            $this->resetPage();
    
            $this->dispatchBrowserEvent('noty-error',['msg'=>'POCENTAJE ELIMINADO - REINICIE LA PÁGINA PARA EFECTUAR CAMBIOS']);
            $this->dispatchBrowserEvent('stop-loader');
            return;
        }
        $this->dispatchBrowserEvent('noty-error',['msg'=>'NO HAY PORCENTAJE ALMACENADO - REINICIE LA PÁGINA PARA EFECTUAR CAMBIOS']);
    }


    function StoreService()
    {
        $this->validate($this->rules);
        if($this->recompensaS->percent>100 || $this->recompensaS->percent<0){
            $this->dispatchBrowserEvent('noty-error', ['msg' => 'EL PORCENTAJE DEBE ESTAR EN UN RANGO DE 0-100%']);
            return;
        }

        $this->recompensaS->save();

        $listCategories = null;
        if ($this->categoriesList != null)  $listCategories =  explode(",", $this->categoriesList);

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
        $listCategories !== null ? $this->recompensaS->categorias()->sync($listCategories) : $this->recompensaS->categorias()->detach();

        $this->dispatchBrowserEvent('noty', ['msg' => 'PORCENTAJE GUARDADO - NO OLVIDES APLICAR PARA CALCULAR']);
    }
    function StoreProduct()
    {
        $this->validate($this->rules);
        if($this->recompensaP->percent>100 || $this->recompensaP->percent<0){
            $this->dispatchBrowserEvent('noty-error', ['msg' => 'EL PORCENTAJE DEBE ESTAR EN UN RANGO DE 0-100%']);
            return;
        }
        $this->recompensaP->save();

        $listCategories = null;
        if ($this->categoriesListP != null)  $listCategories =  explode(",", $this->categoriesListP);

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
        $listCategories !== null ? $this->recompensaP->categorias()->sync($listCategories) : $this->recompensaP->categorias()->detach();

        $this->dispatchBrowserEvent('noty', ['msg' => 'PORCENTAJE GUARDADO - NO OLVIDES APLICAR PARA CALCULAR']);
    }
    
    function View(Empleado $empleado) {
        $this->empleado=$empleado;
        if ($this->empleado) {
            $this->comision = $this->empleado->comision;
            $this->action = 2;
            $this->dispatchBrowserEvent('refresh-tom');
        } else {
            $this->dispatchBrowserEvent('noty-error', ['msg' => 'ERROR - NO SE ENCUENTRA EL EMPLEADO']);
        }
    }

    function Add($tipo)
    {
        $this->creating=true;
        $this->tipo=$tipo;
        $this->dispatchBrowserEvent('modal-exceptions');   
    }
    function updatedQuery()
    {
        switch($this->tipo){
            case 1:$this->listado= producto::where('name','like',"%{$this->query}%")->orderBy('name')->get()->take(5);break;
            case 2:$this->listado= servicio::where('name','like',"%{$this->query}%")->orderBy('name')->get()->take(5);break;
            case 3:$this->listado= categoria_producto::where('name','like',"%{$this->query}%")->orderBy('name')->get()->take(5);break;
            case 4:$this->listado= categoria_servicio::where('name','like',"%{$this->query}%")->orderBy('name')->get()->take(5);break;
        }
    }
    function cancel()
    {
        $this->creating=false;
    }
    function selectedItem($itemId)
    {
        switch($this->tipo){
            case 1:$this->selectedItem= producto::find($itemId);break;
            case 2:$this->selectedItem= servicio::find($itemId);break;
            case 3:$this->selectedItem= categoria_producto::find($itemId);break;
            case 4:$this->selectedItem= categoria_servicio::find($itemId);break;
        }
        $this->query=$this->selectedItem->name;
    }
    function StoreException()
    {
        $rules = [
            'query' => "required",
            'qty' => "required|numeric",
            'tipoExc' => 'required|in:percent,qty'
        ];

        $this->validate($rules);

        switch($this->tipo){
            case 1
                :$this->excepcion= new excepcion_producto();
                $this->excepcion->producto_id=$this->selectedItem->id;
                break;
            case 2
                :$this->excepcion= new excepcion_servicio();
                $this->excepcion->servicio_id=$this->selectedItem->id;
                break;
            case 3
                :$this->excepcion= new excepcion_cat_producto();
                $this->excepcion->categoria_producto_id=$this->selectedItem->id;
                break;
            case 4
                :$this->excepcion= new excepcion_cat_servicio();
                $this->excepcion->categoria_servicio_id=$this->selectedItem->id;
                break;
        }

        $this->excepcion->qty=$this->qty;
        $this->excepcion->type_comission=$this->tipoExc;
        $this->excepcion->comision_id=$this->empleado->comision->id;
        

        $this->excepcion->save();
        $this->listado = [];
        $this->selectedItem = null;
        $this->tipo=false;
        $this->creating=false;
        $this->empleados = Empleado::orderBy('first_name')->get();
        $this->recompensaS = new recompensas_servicio();
        if(!$this->recompensaS->first()){
            $this->recompensaS->percent = '0';
            $this->recompensaS->to_all = false;
            $this->categoriasS = categoria_servicio::orderBy('name')->get();
        }else{
            $this->recompensaS=$this->recompensaS->first();
            $this->categoriesList = implode(", ", $this->recompensaS->categorias->pluck('name')->toArray());
        }    

        $this->recompensaP = new recompensas_producto();
        if(!$this->recompensaP->first()){
            $this->recompensaP->percent = '0';
            $this->recompensaP->to_all = false;
            $this->categoriasP = categoria_producto::orderBy('name')->get();
        }else{
            $this->recompensaP=$this->recompensaP->first();
            $this->categoriesListP = implode(", ", $this->recompensaP->categorias->pluck('name')->toArray());
        }    
        $this->dispatchBrowserEvent('noty',['msg'=>'EXCEPCIÓN CREADA CON ÉXITO']);
        $this->tipoExc = 'percent';
        $this->action=1;
    }
    function set($action)
    {
        $this->action = $action;
    }
    function Delete($excepcion_id,$tipo)
    {
        switch($tipo){
            case 1
                :$excepcion= excepcion_producto::find($excepcion_id);
                break;
            case 2
                :$excepcion= excepcion_servicio::find($excepcion_id);
                break;
            case 3
                :$excepcion= excepcion_cat_producto::find($excepcion_id);
                break;
            case 4
                :$excepcion= excepcion_cat_servicio::find($excepcion_id);
                break;
        }
        $excepcion->delete();
        $this->listado = [];
        $this->selectedItem = null;
        $this->tipo=false;
        $this->creating=false;
        $this->empleados = Empleado::orderBy('first_name')->get();
        $this->recompensaS = new recompensas_servicio();
        if(!$this->recompensaS->first()){
            $this->recompensaS->percent = '0';
            $this->recompensaS->to_all = false;
            $this->categoriasS = categoria_servicio::orderBy('name')->get();
        }else{
            $this->recompensaS=$this->recompensaS->first();
            $this->categoriesList = implode(", ", $this->recompensaS->categorias->pluck('name')->toArray());
        }    

        $this->recompensaP = new recompensas_producto();
        if(!$this->recompensaP->first()){
            $this->recompensaP->percent = '0';
            $this->recompensaP->to_all = false;
            $this->categoriasP = categoria_producto::orderBy('name')->get();
        }else{
            $this->recompensaP=$this->recompensaP->first();
            $this->categoriesListP = implode(", ", $this->recompensaP->categorias->pluck('name')->toArray());
        }    
        $this->dispatchBrowserEvent('noty-error',['msg'=>'EXCEPCIÓN ELIMINADA CON ÉXITO']);
            $this->tipoExc = 'percent';
            $this->action=1;
    }
}
