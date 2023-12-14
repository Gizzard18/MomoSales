<?php

namespace App\Http\Livewire;

use App\Models\marca;
use Livewire\Component;
use Livewire\WithPagination;

class Marcas extends Component
{
    public $search, $editing, $records;
    public marca $marca;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'marca.name' => 'required|min:2|max:55|unique:marcas,name',
        'marca.contact_name' => 'nullable|min:2|max:55',
        'marca.phone_number' => 'nullable|min:2|max:15',
        'marca.email' => 'nullable|min:2|max:55|unique:marcas,email',
    ];

    public function mount()
    {
        $this->marca=new marca();
        $this->editing=false;
    }

    protected $listeners = [
        'refresh' => '$refresh',
        'search' => 'searching',
        'Delete'
    ];

    public function searching($searchText)
    {
        $this->search = trim($searchText);
    }

    public function render()
    {
        return view('livewire.marcas',[
            'marcas' => $this->loadBrands()
        ]);
    }

    function loadBrands(){
        if(!empty($this->search)){
            $this->resetPage();

            $query = marca::where(function ($query){
                $query->where('name','like',"%{$this->search}%")->orWhere('contact_name','like', "%{$this->search}%");
            })->orderBy('name','asc')->paginate(2);
        }else{
            $query = marca::orderBy('name', 'asc')->paginate(2);
        }

        $this->records = $query->total();
        return $query;
    }

    public function Add()
    {
        $this->resetValidation();
        $this->resetExcept('marca');
        $this->marca = new marca();
    }
    public function Delete(marca $marca)
    {
        $this -> destroy($marca);
    }

    public function Edit(marca $marca){
        $this->resetValidation();
        $this->marca = $marca;
        $this->editing = true;
    } 

    public function cancelEdit()
    {
        $this->resetValidation();
        $this->marca = new marca();
        $this->editing = false;
    }

    public function Store(){
        $this->rules['marca.name'] = $this->marca->id > 0 ? "required|min:2|max:55|unique:marcas,name,{$this->marca->id}" : 'required|min:2|max:55|unique:marcas,name';
        $this->rules['marca.email'] = $this->marca->id > 0 ? "nullable|min:2|max:55|unique:marcas,email,{$this->marca->id}" : 'nullable|min:2|max:55|unique:marcas,email';
        $this->validate($this->rules);
    
        $this->marca->save();
        $this->dispatchBrowserEvent('noty',['msg'=>'SOLICITUD PROCESADA CON ÉXITO']);
        $this->resetExcept('marca');
        
        $this->marca=new marca; 
    }
    public function destroy(marca $marca)
    {
        $marca->delete();

        $this->resetPage();

        $this->dispatchBrowserEvent('noty-error',['msg'=>'MARCA ELIMINADA']);
        
    }
}