<?php

namespace App\Http\Livewire;

use App\Models\Comision;
use Livewire\Component;
use App\Models\Empleado;
use App\Models\User;
use Livewire\WithPagination;

class Empleados extends Component
{
    public $search, $editing, $records, $empleadoSeleccionado, $usuarios;
    public Empleado $empleado;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $rules =
    [
        'empleado.first_name' => "required|min:3|max:80",
        'empleado.last_name' => "nullable|min:3|max:80",
        'empleado.email' => "nullable|max:80|email|unique:empleados,email",
        'empleado.phone_number' => "nullable|min:7|max:15|unique:empleados,phone_number",
        'empleado.birth_date' => 'nullable',
        'empleado.is_active' => 'nullable',
        'empleado.user_id' => 'nullable|unique:empleados,user_id'
    ];

    public function mount()
    {
        $this->empleado = new Empleado();
        $this->editing = false;
        $this->usuarios = User::orderBy('name')->get();
        $this->empleado->is_active = true;
    }
    protected $listeners = [
        'refresh' => '$refresh',
        'search' => 'searching',
        'Delete','Activate','Deactivate'
    ];
    function Activate(Empleado $empleado)
    {
        $empleado->is_active=true;
        $empleado->save();
    }
    function Deactivate(Empleado $empleado)
    {
        $empleado->is_active=false;
        $empleado->save();
    }

    public function render()
    {
        return view('livewire.empleados.empleados',[
            'empleados' => $this->loadEmployee()
        ]);
    }
    public function loadEmployee()
    {
        if (!empty($this->search)) {

            $this->resetPage();

            $query = Empleado::where(function ($query) {
                    $query->where('first_name', 'like', "%{$this->search}%")
                        ->orWhere('last_name', 'like', "%{$this->search}%");
                })
                ->orderBy('first_name', 'asc')
                ->paginate(5);
        } else {
            $query =  Empleado::orderBy('first_name', 'asc')->paginate(5);
        }

        $this->records = $query->total();

        return $query;
    }

    public function searching($searchText)
    {
        $this->search = trim($searchText);
    }
    public function Delete(Empleado $empleado)
    {
        $this -> destroy($empleado);
    }
    public function Add()
    {
        $this->resetValidation();
        $this->resetExcept('empleado');
        $this->empleado = new Empleado();
    }
    public function Edit(Empleado $empleado)
    {
        $this->resetValidation();
        $this->empleado = $empleado;
        $this->editing = true;
    }
    public function cancelEdit()
    {
        $this->resetValidation();
        $this->resetExcept(['empleado', 'usuarios']);
        $this->empleado = new Empleado();
        $this->empleado->is_active = true;
        $this->editing = false;
    }

    function Store()
    {

        sleep(1);
        

        try{
        $this->rules['empleado.phone_number'] = $this->empleado->id > 0 ? "nullable|min:7|max:15|unique:empleados,phone_number,{$this->empleado->id}" : 'nullable|min:7|max:15|unique:empleados,phone_number';
        $this->rules['empleado.user_id'] = $this->empleado->id > 0 ? "nullable|unique:empleados,user_id,{$this->empleado->id}" : 'nullable|unique:empleados,user_id';
        $this->rules['empleado.email'] = $this->empleado->id > 0 ? "nullable|min:2|max:80|unique:empleados,email,{$this->empleado->id}" : 'nullable|min:2|max:80|unique:empleados,email';
        $this->rules = [
            'empleado.first_name' => "required|min:3|max:80",
            'empleado.last_name' => "nullable|min:3|max:80",
            'empleado.birth_date' => 'nullable',
            'empleado.is_active' => 'nullable',
        ];
            $this->validate($this->rules);
        

        //save
        $this->empleado->save();

        if(!$this->editing){
            $comision=new Comision();
            $comision->empleado_id=$this->empleado->id;
            $comision->save();
        }

        $this->dispatchBrowserEvent('noty', ['msg' => 'SOLICITUD PROCESADA CON ÉXITO']);
        $this->resetExcept(['empleado', 'usuarios']);
        $this->empleado = new Empleado();
        $this->empleado->is_active = true;
        }catch(\Throwable $th){
            $this->dispatchBrowserEvent('noty-error', ['msg' =>  "EXCEPCIÓN: \n {$th->getMessage()}"] );
        }
    }


    function Destroy(Empleado $empleado)
    {
        //eliminar empleado
        $empleado->delete();

        //reset pagination
        $this->resetPage();

        $this->dispatchBrowserEvent('noty-error', ['msg' => 'SOLICITUD PROCESADA CON ÉXITO']);
    }

}
