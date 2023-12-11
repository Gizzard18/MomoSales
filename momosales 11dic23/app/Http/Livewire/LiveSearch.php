<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\producto;
use App\Models\categoria_producto;
use App\Models\servicio;
use App\Models\categoria_servicio;
use Livewire\WithPagination;

class LiveSearch extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $query,$listado,$itemSelected;
    public function mount()
    {
        $this->listado = [];
        $this->itemSelected = null;
    }

    protected $listeners = ['refresh' => '$refresh'];

    public function render()
    {
        return view('livewire.live-search');
    }

    function updatedQuery()
    {
        switch($this->tipo){
            case 1:$this->listado= producto::where('name','like',"%{$this->query}%")->oderBy('name')->get()->take(5);
            case 2:$this->listado= servicio::where('name','like',"%{$this->query}%")->oderBy('name')->get()->take(5);
            case 3:$this->listado= categoria_producto::where('name','like',"%{$this->query}%")->oderBy('name')->get()->take(5);
            case 4:$this->listado= categoria_servicio::where('name','like',"%{$this->query}%")->oderBy('name')->get()->take(5);
        }
    }
}
