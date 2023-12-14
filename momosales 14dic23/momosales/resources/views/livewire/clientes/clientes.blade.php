<div>
    <div class="row">
        <div class="col-md-4">
            <div class="card" style="background-color: white;">
                <div class="card-header">
                    <h4>{{ $editing ? 'Editar Cliente' : 'Crear Cliente' }}</h4>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label>Nombre(s)</label>
                        <input wire:model.defer="cliente.first_name" id='inputFocus' type="text"
                            class="form-control form-control-lg" placeholder="Nombre(s)" autocomplete="nope">
                        @error('cliente.first_name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Apellido</label>
                        <input wire:model.defer="cliente.last_name" type="text" class="form-control form-control-lg"
                            placeholder="Apellido">
                        @error('cliente.last_name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input wire:model.defer="cliente.email" type="text" class="form-control form-control-lg"
                            placeholder="Email">
                        @error('cliente.email') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input wire:model.defer="cliente.phone" type="text" class="form-control form-control-lg"
                            placeholder="Teléfono">
                        @error('cliente.phone') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <a style="cursor: pointer;color:#B59377" id="showMoreButton" class="">Ver más</a>
                        <div id="moreContainer"style="display: none;">
                            <label>Cumpleaños</label>
                            <input wire:model.defer="cliente.birth_date" type="date" class="form-control form-control-lg flatpickr" placeholder="<?php echo date('Y-m-d'); ?>">
                            @error('cliente.birth_date') <span class="text-danger">{{ $message }}</span> @enderror
                            <label>Notas</label>
                            <textarea wire:model.defer="cliente.description" type="text" class="form-control form-control-lg" style="height: 200px;" placeholder=""></textarea>
                            @error('cliente.description') <span class="text-danger">{{ $message }}</span> @enderror
                            <div class="form-group">
                                <label>Categoría</label>
                                    <select wire:model.defer='cliente.categoria_cliente_id' class="form-control  form-control-lg">
                                        @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}">{{ $categoria->name }}</option>
                                        @endforeach
                                    </select>
                                @error('cliente.categoria_cliente_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <p></p>
                            <label>Mensajes Personalizados</label>
                            <input type="checkbox" wire:model.defer="cliente.want_custom_messages" value="1">
                            @error('cliente.want_custom_messages') <span class="text-danger">{{ $message }}</span> @enderror
                            <p></p>
                            <label>Ofertas</label>
                            <input type="checkbox" wire:model.defer="cliente.want_offers" value="1">
                            <p></p>
                            @error('cliente.want_offers') <span class="text-danger">{{ $message }}</span> @enderror
                            <p></p>
                            <a style="cursor: pointer;color:#B59377" class="" id="hideButton">Ver menos</a>
                        </div>
                    </div>
                    
                    <script>
                        // Obtén referencias a los elementos HTML
                        const showMoreButton = document.getElementById('showMoreButton');
                        const moreContainer = document.getElementById('moreContainer');
                        const hideButton = document.getElementById('hideButton');

                        // Maneja el clic en el botón "Ver más"
                        showMoreButton.addEventListener('click', () => {
                            // Muestra el contenedor del campo de fecha
                            moreContainer.style.display = 'block';
                            // Oculta el botón "Ver más"
                            showMoreButton.style.display = 'none';
                        });
                        hideButton.addEventListener('click', () => {
                            // Muestra el contenedor del campo de fecha
                            moreContainer.style.display = 'none';
                            // Oculta el botón "Ver más"
                            showMoreButton.style.display = 'block';
                        });
                    </script>




                </div>
                <div class="card-footer">
                    <button class="btn btn-sm btn-dark light float-left hidden {{$editing ? 'd-block' : 'd-none' }}"
                        wire:click="cancelEdit">Cancelar</button>
                    <button class="btn btn-sm btn-info float-right save" wire:click="Store" style="background-color: #B59377;border-color:white">Guardar</button>
                </div>
            </div>
        </div>
        <div class="col-md-8" style="background-color: white;">
            <div class="card">
                <div class="card-header ">
                    <div class="d-flex">
                        <div class="separator" style="background-color:#6E6E6E"></div>
                        <div class="mr-auto">
                            <h4 class="card-title mb-1">Clientes</h4>
                            <p class="fs-14 mb-0"> Listado Registrado</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md table-hover  text-center">
                            <thead class="thead-primary">
                                <tr >
                                    <th style="background-color:#6E6E6E">Nombre</th>
                                    <th style="background-color:#6E6E6E">Email</th>
                                    <th style="background-color:#6E6E6E">Teléfono</th>
                                    <th style="background-color:#6E6E6E"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($clientes as $item)
                                <tr>
                                    <td class="text-left">
                                        <span class="{{ $item->platform_id !=null ? 'text-success' : '' }}">
                                            {{ $item->first_name }} {{ $item->last_name }}
                                        </span>
                                    </td>
                                    <td> {{ $item->email }} </td>
                                    <td> {{ $item->phone }} </td>

                                    <td class="text-right">

                                        <div class="dropdown position-static">
                                            <button class="btn btn-info dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false" style="background-color:#6E6E6E; border-color:#6E6E6E">
                                                Acciones
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right"
                                                aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="#"
                                                    wire:click.prevent="deliveryForm({{ $item->id }})"><i
                                                        class="las la-truck la-2x"></i> Datos de Entrega
                                                </a>
                                                <a class="dropdown-item" href="#" wire:click.prevent="activateCard({{ $item->id }})"><i class="las la-piggy-bank la-2x"></i> Activar Recompensas</a>
                                                <a class="dropdown-item" href="#"
                                                    wire:click.prevent="viewClient({{ $item->id }})"><i
                                                        class="las la-eye la-2x"></i> Ver
                                                </a>
                                                <a class="dropdown-item" href="#"
                                                    wire:click.prevent="Edit({{ $item->id }})"><i
                                                        class="las la-pen la-2x"></i> Editar
                                                </a>
                                                <script>
                                                    function confirmDelete(clienteId) {
                                                        // Mostrar cuadro de diálogo de confirmación personalizado
                                                        Swal.fire({
                                                            title: '¿Seguro que desea eliminar al cliente?',
                                                            text: '',
                                                            icon: 'warning',
                                                            showCancelButton: true,
                                                            confirmButtonColor: '#3085d6',
                                                            cancelButtonColor: '#d33',
                                                            confirmButtonText: 'Aceptar',
                                                            cancelButtonText: 'Cancelar'
                                                        }).then((result) => {
                                                            if (result.isConfirmed) {
                                                                showProcessing()
                                                                // Si el usuario hace clic en "Aceptar", ejecutar el método de Livewire
                                                                Livewire.emit('Delete', clienteId); // Llamar al método de Livewire
                                                            }
                                                        });
                                                    }
                                                </script>
                                                <a class="dropdown-item" href="#"
                                                    onclick="confirmDelete({{ $item->id }})"><i
                                                        class="las la-trash-alt la-2x"></i> Eliminar
                                                </a>
                                                @if($item->platform_id == null)
                                                <a class="dropdown-item save" href="#"
                                                    wire:click.prevent="Sync({{ $item->id }})"><i
                                                        class="las la-sync la-2x"></i> Sincronizar
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">No hay clientes</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            {{$clientes->links()}}
                        </div>
                        <div class="col-md-6"><span class="float-right">Records:{{$records}}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('livewire.clientes.delivery')
    @include('livewire.clientes.view')

    <script>
        document.addEventListener('livewire:load', function () {
        var mainWrapper = document.getElementById('main-wrapper')        
            mainWrapper.classList.add('menu-toggle')  
    })

    window.addEventListener('modal-delivery', event => {   
      $('#modalDelivery').modal('show')
      setTimeout(() => {
        document.getElementById('inputDeliveryName').focus()
      }, 1000);
   })
    window.addEventListener('modal-view-client', event => {   
      $('#modalViewClient').modal('show')
   })

   document.addEventListener('DOMContentLoaded', function(){
        flatpickr(document.getElementsByClassName('flatpickr'),{
            enableTime: false,
            dateFormat: 'Y-m-d',
            locale: {
                firstDateofWeek:1,
                weekdays: {
                    shorthand: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
                    longhand: [
                    "Domingo",
                    "Lunes",
                    "Martes",
                    "Miércoles",
                    "Jueves",
                    "Viernes",
                    "Sábado",
                    ],
                },    
                months: {
                    shorthand: [
                    "Ene",
                    "Feb",
                    "Mar",
                    "Abr",
                    "May",
                    "Jun",
                    "Jul",
                    "Ago",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dic",
                    ],
                    longhand: [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre",
                    ],
                }
            }
        })
   })

    </script>
</div>