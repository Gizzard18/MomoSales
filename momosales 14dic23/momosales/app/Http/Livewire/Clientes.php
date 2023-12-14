<?php

namespace App\Http\Livewire;

use App\Models\categoria_cliente;
use App\Models\cliente;
use App\Models\entrega;
use App\Models\tarjetas_punto;
use App\Traits\CustomerTrait;
use Livewire\Component;
use Livewire\WithPagination;

class Clientes extends Component
{
    use WithPagination;
    use CustomerTrait;  

    public $search, $records,  $editing, $action = 1,$card, $customerSelected, $categorias;
    public cliente $cliente; // propiedad de tipo cliente
    public $entrega; // databinding / vinculacion de datos anidados con la notacion dot

    protected $rules =
    [
        'cliente.first_name' => "required|min:3|max:35",
        'cliente.last_name' => "nullable|min:3|max:35",
        'cliente.email' => "required|nullable|max:65|email",
        'cliente.phone' => "nullable|min:7|max:15|unique:clientes,phone",
        'cliente.description' => "nullable|min:7|max:100",
        'cliente.birth_date' => 'nullable',
        'cliente.want_offers' => 'nullable',
        'cliente.want_custom_messages' => 'nullable',
        'cliente.platform_id' => 'nullable',
        'cliente.categoria_cliente_id' => 'required'
    ];

    protected $paginationTheme = 'bootstrap';


    public function mount()
    {
        $this->cliente = new cliente(); // hacemos que la propiedad cliente sea una instancia del modelo
        $this->editing = false;

        $this->entrega = [
            'type' => 'shipping','country' => 'MX',
        ];
        $this->categorias = categoria_cliente::orderBy('name')->get();

        $this->cliente->categoria_cliente_id = $this->categorias->first()->id ?? null;
        $lastClient = Cliente::orderBy('id', 'desc')->first();
        if($lastClient){
            $this->cliente->email = 'momo' . ($lastClient->id + 1) . '@momosalon.com';
        }else{
            $this->cliente->email = 'momo@momosalon.com';
        }
    }


    protected $listeners = [
        'refresh' => '$refresh',
        'search' => 'searching',
        'Delete'
    ];

    public function render()
    {
        return view('livewire.clientes.clientes',[
            'clientes' => $this->loadCustomers()
        ]);
    }

    function activateCard(cliente $cliente)
    {
        if($cliente->tarjetaPuntos){
            $msj='El usuario ya cuenta con una tarjeta registrada';
        }else{
            $this->card = new tarjetas_punto();
            $this->card->cliente()->associate($cliente);
            $this->card->save();
            $msj='SOLICITUD PROCESADA CON ÉXITO';
        }
        $this->dispatchBrowserEvent('noty', ['msg' => $msj]);
    }
    public function loadCustomers()
    {
        if (!empty($this->search)) {

            $this->resetPage();

            $query = cliente::with('categoria')
                ->where(function ($query) {
                    $query->where('first_name', 'like', "%{$this->search}%")
                        ->orWhere('last_name', 'like', "%{$this->search}%");
                })
                ->orderBy('first_name', 'asc')
                ->paginate(5);
        } else {
            $query =  cliente::with('categoria','deliveries')->orderBy('last_name', 'asc')->paginate(5);
        }

        $this->records = $query->total();

        return $query;
    }

    public function searching($searchText)
    {
        $this->search = trim($searchText);
    }
    public function Delete(cliente $cliente)
    {
        $this -> destroy($cliente);
    }
    public function Add()
    {
        $this->resetValidation();
        $this->resetExcept('cliente');
        $this->cliente = new cliente();
        $this->cliente->categoria_cliente_id = $this->categorias->first()->id ?? null;
        $lastClient = Cliente::orderBy('id', 'desc')->first();
        if($lastClient){
            $this->cliente->email = 'momo' . ($lastClient->id + 1) . '@momosalon.com';
        }else{
            $this->cliente->email = 'momo@momosalon.com';
        }
        $this->action = 2;
    }
    public function Edit(cliente $cliente)
    {
        $this->resetValidation();
        $this->cliente = $cliente;
        $this->action = 3;
        $this->editing = true;
    }
    public function cancelEdit()
    {
        $this->resetValidation();
        $this->cliente = new cliente();
        $this->cliente->categoria_cliente_id = $this->categorias->first()->id ?? null;
        $lastClient = Cliente::orderBy('id', 'desc')->first();
        if($lastClient){
            $this->cliente->email = 'momo' . ($lastClient->id + 1) . '@momosalon.com';
        }else{
            $this->cliente->email = 'momo@momosalon.com';
        }
        $this->action = 1;
        $this->editing = false;
    }

    function Store()
    {

        sleep(1);
        
        $this->rules['cliente.phone'] = $this->cliente->id > 0 ? "nullable|min:7|max:15|unique:clientes,phone,{$this->cliente->id}" : 'nullable|min:7|max:15|unique:clientes,phone';
        $this->rules = [
            'cliente.first_name' => "required|min:3|max:35",
            'cliente.last_name' => "nullable|min:3|max:35",
            'cliente.email' => "nullable|max:65|email",
            'cliente.description' => "nullable|min:7|max:100",
            'cliente.birth_date' => 'nullable',
            'cliente.want_offers' => 'nullable',
            'cliente.want_custom_messages' => 'nullable',
            'cliente.platform_id' => 'nullable'
        ];

        $this->validate($this->rules);

        //save
        $this->cliente->save();


        // //sync
        $this->createOrUpdateCustomer($this->cliente, $this->action == 2 ? true : false);

        $this->dispatchBrowserEvent('noty', ['msg' => 'SOLICITUD PROCESADA CON ÉXITO']);
        $this->resetExcept(['cliente', 'categorias','email']);
        $this->cliente = new cliente();
        $this->cliente->categoria_cliente_id = $this->categorias->first()->id ?? null;
        $lastClient = Cliente::orderBy('id', 'desc')->first();
        if($lastClient){
            $this->cliente->email = 'momo' . ($lastClient->id + 1) . '@momosalon.com';
        }else{
            $this->cliente->email = 'momo@momosalon.com';
        }

        $this->entrega = ['type' => 'billing','country' => 'MX'];
        $this->cliente->categoria_cliente_id = $this->categorias->first()->id ?? null;
    }

    function deliveryForm(cliente $cliente)
    {
        $this->customerSelected = $cliente;
        $this->dispatchBrowserEvent('modal-delivery');
    }
    public function viewClient(cliente $cliente)
    {
        $this->customerSelected = $cliente;
        $this->dispatchBrowserEvent('modal-view-client');
    }

    function Destroy(cliente $cliente)
    {

        //eliminar de tienda

        // obtener los deliveries asociadas al cliente
        $deliveries = $cliente->deliveries;


        //eliminar las relaciones en la tabla pivote usando detach():
        $cliente->deliveries()->detach();


        // eliminar los deliveries asociados al cliente de la tabla deliveries
        foreach ($deliveries as $delivery) {
            $delivery->delete();
        }

        if ($cliente->platform_id != null) {
            $this->deleteCustomer($cliente->platform_id);
        }

        //eliminar cliente
        $cliente->delete();

        //reset pagination
        $this->resetPage();

        $this->dispatchBrowserEvent('noty-error', ['msg' => 'SOLICITUD PROCESADA CON ÉXITO']);
        $this->dispatchBrowserEvent('stop-loader');
    }

    function saveDelivery()
    {

        $validatedData = $this->validate([
            'entrega.first_name' => 'required|max:45',
            'entrega.last_name' => 'nullable|max:45',
            'entrega.company' => 'nullable|max:60',
            'entrega.primary_address' => 'nullable|max:255',
            'entrega.secondary_address' => 'nullable|max:255',
            'entrega.city' => 'nullable|max:50',
            'entrega.state' => 'nullable|max:50',
            'entrega.postcode' => 'nullable|max:10',
            'entrega.email' => 'nullable|email|max:55',
            'entrega.phone' => 'nullable|max:15',
            'entrega.country' => 'nullable|:max:40',
            'entrega.type' => 'required|in:billing,shipping'
        ]);

        // extraer el subarray 'entrega' del array validado
        $deliveryData = $validatedData['entrega'];

        // crear el modelo entrega con los datos validados
        $entrega = entrega::create($deliveryData);

        $this->customerSelected->deliveries()->attach($entrega->id);

        $this->dispatchBrowserEvent('noty', ['msg' => 'SOLICITUD REALIZADA CON ÉXITO']);

        $this->customerSelected->load('deliveries');

        $this->entrega = [
            'type' => 'billing','country' => 'MX'
        ];

        $this->Sync($this->customerSelected);
    }

    function editDelivery(entrega $entrega)
    {
        //hacer esto se deja de tener una propiedad nested data por una instancia de entrega
        //$this->entrega = $entrega;

        // clonar los datos del modelo a la nueva propiedad
        $editDeliveryData = $entrega->toArray();

        $this->entrega = $editDeliveryData;
    }
    function removeDelivery(entrega $entrega)
    {

        // Eliminar la relación en la tabla pivot
        $this->customerSelected->deliveries()->detach($entrega->id);

        // Eliminar la entrega
        $entrega->delete();

        $this->customerSelected->load('deliveries');
        $this->dispatchBrowserEvent('noty-error', ['msg' => 'OPERACION CON ÉXITO']);
    }


    function cancelEditDelivery()
    {
        $this->entrega = [
            'type' => 'billing','country' => 'MX'
        ];
    }


    function updateDelivery()
    {

        $validatedData = $this->validate([
            'entrega.first_name' => 'required|max:45',
            'entrega.last_name' => 'nullable|max:45',
            'entrega.company' => 'nullable|max:60',
            'entrega.primary_address' => 'nullable|max:255',
            'entrega.secondary_address' => 'nullable|max:255',
            'entrega.city' => 'nullable|max:50',
            'entrega.state' => 'nullable|max:50',
            'entrega.postcode' => 'nullable|max:10',
            'entrega.email' => 'nullable|email|max:55',
            'entrega.phone' => 'nullable|max:15',
            'entrega.country' => 'nullable|:max:40',
            'entrega.type' => 'required|in:billing,shipping'
        ]);

        // xtraer el subarray 'delivery' del array validado
        $deliveryData = $validatedData['entrega'];

        //buscamos la entrega y lo actualizamos
        entrega::find($this->entrega['id'])->update($deliveryData);

        $this->entrega = [
            'type' => 'billing','country' => 'MX'
        ];


        $this->customerSelected->load('deliveries');

        $this->Sync($this->customerSelected);

        $this->dispatchBrowserEvent('noty', ['msg' => 'SOLICITUD PROCESADA CON ÉXITO']);
    }

    function Sync(cliente $cliente)
    {
        $this->createOrUpdateCustomer($cliente);
    }

}
