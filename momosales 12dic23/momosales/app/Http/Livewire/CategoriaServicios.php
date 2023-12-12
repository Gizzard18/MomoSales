<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\categoria_servicio;
use Livewire\WithPagination;

class CategoriaServicios extends Component
{
    use WithPagination;

    public categoria_servicio $categoria_servicio;
    protected $paginationTheme = 'bootstrap';

    public $upload, $editing, $search, $records, $delete;
    
    protected $rules = [
        'categoria_servicio.name' => "required|min:2|max:45|unique:categoria_servicios,name",
    ];
    public function mount()
    {
        $this->categoria_servicio = new categoria_servicio();
        $this->editing = false;
    }

    protected $listeners = ['refresh' => '$refresh',
    'search' => 'searching',
    'Delete'

    ];

    public function searching($searchText)
    {
        $this->search = $searchText;
    }

    public function render()
    {
        return view('livewire.categoriaServicios', [
            'categoria_servicios' => $this->loadCategories()
        ]);
    }
    public function loadCategories()
    {
        if (!empty($this->search)) {

            $this->resetPage();

            $query = categoria_servicio::where('name', 'like', "%{$this->search}%")
                ->orderBy('name', 'asc');
        } else {
            $query = categoria_servicio::orderBy('name', 'asc');
        }

        $this->records = $query->count();

        return $query->paginate(2);
    }

    public function Add() 
    {
        $this -> resetValidation();
        $this -> resetExcept('categoria_servicio');
        $this -> categoria_servicio = new categoria_servicio();    
    }

    public function Delete(categoria_servicio $categoria_servicio)
    {
        $this -> destroy($categoria_servicio);
    }

    public function Edit(categoria_servicio $categoria_servicio)
    {
        $this -> resetValidation();
        $this -> categoria_servicio = $categoria_servicio;
        $this -> upload = null;
        $this -> editing = true;
    }

    public function cancelEdit()
    {
        $this -> resetValidation();
        $this -> categoria_servicio = new categoria_servicio();
        $this -> editing = false;
    }

    public function Store() 
    {
        $this -> rules['categoria_servicio.name'] = $this->categoria_servicio->id > 0 ? "required|min:2|max:200|unique:categoria_servicios,name,{$this->categoria_servicio->id}" : 'required|min:2|max:50|unique:categoria_servicios,name';
        $this -> validate($this->rules);

        $this -> categoria_servicio -> save();

        $this->dispatchBrowserEvent('noty', ['msg' => 'SOLICITUD PROCESADA CON ÉXITO']);
        $this->resetExcept('categoria_servicio');
        $this->categoria_servicio = new categoria_servicio();

    }

    /**
     * Summary of Destroy
     * @param \App\Models\categoria_servicio $categoria_servicio
     * @return void
     */
    public function destroy(categoria_servicio $categoria_servicio)
    {
        $categoria_servicio->delete();

        $this->resetPage();

        $this->dispatchBrowserEvent('noty-error',['msg'=>'CATEGORÍA ELIMINADA']);
        
    }
}
