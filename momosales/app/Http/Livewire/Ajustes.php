<?php

namespace App\Http\Livewire;

use App\Models\servicio;
use Livewire\Component;
use App\Models\producto;
use Livewire\WithPagination;
use App\Models\categoria_producto;
use App\Models\categoria_servicio;
use App\Models\recompensas_producto;
use App\Models\recompensas_servicio;

class Ajustes extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $categoriasP,$categoriasS,$categoriesList,$categoriesListP; 
    public recompensas_producto $recompensaP;
    public recompensas_servicio $recompensaS;
    
    protected $rules =    [
        'recompensaS.percent' => "required|numeric",
        'recompensaS.to_all' => "nullable",
        'recompensaP.percent' => "required",
        'recompensaP.to_all' => "nullable",
    ];


    public function mount()
    {
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
    protected $listeners = ['refresh' => '$refresh'];

    public function render()
    {
        $this->emit('livewire:load');
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

        $this->dispatchBrowserEvent('noty', ['msg' => 'PORCENTAJE GUARDADO - NO OLVIDES SINCRONIZAR PARA CALCULAR']);
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

        $this->dispatchBrowserEvent('noty', ['msg' => 'PORCENTAJE GUARDADO - NO OLVIDES SINCRONIZAR PARA CALCULAR']);
    }

}
