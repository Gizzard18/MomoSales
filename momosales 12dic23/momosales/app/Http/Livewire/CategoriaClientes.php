<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\categoria_cliente;

class CategoriaClientes extends Component
{
    use WithPagination;

    public categoria_cliente $categoria_cliente;
    protected $paginationTheme = 'bootstrap';

    public $upload, $editing, $search, $records, $delete;
    
    protected $rules = [
        'categoria_cliente.name' => "required|min:2|max:200|unique:categoria_clientes,name",
    ];
    public function mount()
    {
        $this->categoria_cliente = new categoria_cliente();
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
        return view('livewire.categoriaClientes', [
            'categoria_clientes' => $this->loadCategories()
        ]);
    }
    public function loadCategories()
    {
        if (!empty($this->search)) {

            $this->resetPage();

            $query = categoria_cliente::where('name', 'like', "%{$this->search}%")
                ->orderBy('name', 'asc');
        } else {
            $query = categoria_cliente::orderBy('name', 'asc');
        }

        $this->records = $query->count();

        return $query->paginate(2);
    }

    public function Add() 
    {
        $this -> resetValidation();
        $this -> resetExcept('categoria_cliente');
        $this -> categoria_cliente = new categoria_cliente();    
    }

    public function Delete(categoria_cliente $categoria_cliente)
    {
        $this -> destroy($categoria_cliente);
    }

    public function Edit(categoria_cliente $categoria_cliente)
    {
        $this -> resetValidation();
        $this -> categoria_cliente = $categoria_cliente;
        $this -> upload = null;
        $this -> editing = true;
    }

    public function cancelEdit()
    {
        $this -> resetValidation();
        $this -> categoria_cliente = new categoria_cliente();
        $this -> editing = false;
    }

    public function Store() 
    {
        $this -> rules['categoria_cliente.name'] = $this->categoria_cliente->id > 0 ? "required|min:2|max:200|unique:categoria_clientes,name,{$this->categoria_cliente->id}" : 'required|min:2|max:50|unique:categoria_clientes,name';
        $this -> validate($this->rules);

        $this -> categoria_cliente -> save();

        $this->dispatchBrowserEvent('noty', ['msg' => 'SOLICITUD PROCESADA CON ÉXITO']);
        $this->resetExcept('categoria_cliente');
        $this->categoria_cliente = new categoria_cliente();

    }

    /**
     * Summary of Destroy
     * @param \App\Models\categoria_cliente $categoria_cliente
     * @return void
     */
    public function destroy(categoria_cliente $categoria_cliente)
    {
        $categoria_cliente->delete();

        $this->resetPage();

        $this->dispatchBrowserEvent('noty-error',['msg'=>'CATEGORÍA ELIMINADA']);
        
    }
}
