<div>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>{{ $editing ? 'Editar Empleado' : 'Crear Empleado' }}</h4>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label>Nombre</label>
                        <input wire:model.defer="empleado.first_name" id='inputFocus' type="text"
                            class="form-control form-control-lg" placeholder="Nombre">
                        @error('empleado.first_name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Apellido</label>
                        <input wire:model.defer="empleado.last_name"  type="text"
                            class="form-control form-control-lg" placeholder="Apellido">
                        @error('empleado.distributor') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <label>Usuario</label>
                            <select wire:model.defer="empleado.user_id" class="form-control  form-control-lg">
                                @foreach($usuarios as $usuario)
                                    @if(!$usuario->empleado||$usuario->empleado==$this->empleado)
                                    <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                    @endif
                                @endforeach
                                <option value="null">Seleccionar usuario</option>
                            </select>
                            @error('empleado.user_id') <span class="text-danger">{{ $message }}</span> @enderror
                    <div class="form-group mt-2">
                        <a style="cursor: pointer;color:#B59377" id="showMoreButton" class="mt-3">Ver más</a>
                        <div id="moreContainer"style="display: none;">
                        
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input wire:model.defer="empleado.phone_number" type="text" class="form-control form-control-lg"
                                    placeholder="Teléfono">
                                @error('empleado.phone_number') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input wire:model.defer="empleado.email" type="text" class="form-control form-control-lg"
                                    placeholder="Email">
                                @error('empleado.email') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <label>Cumpleaños</label>
                            <input wire:model.defer="empleado.birth_date" type="date" class="form-control form-control-lg flatpickr" placeholder="<?php echo date('Y-m-d'); ?>">
                            @error('empleado.birth_date') <span class="text-danger">{{ $message }}</span> @enderror
                            <div>
                            
                        </div>
                            <p></p>
                            <label>Empleado Activo</label>
                            <input type="checkbox" wire:model.defer="empleado.is_active" value="1">
                            <p></p>
                            @error('empleado.is_active') <span class="text-danger">{{ $message }}</span> @enderror
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
                    <button class="btn btn-sm btn-dark float-left hidden {{$editing ? 'd-block' : 'd-none' }}"
                        wire:click="cancelEdit">Cancel</button>
                    <button class="btn btn-sm btn-info float-right save" style="width:8rem;background-color:#B59377; border-color:#B59377" wire:click="Store"><i class="las la-save la-2x"></i> <div>Guardar</div></button>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header ">
                    <div class="d-flex">
                        <div class="separator" style="background-color:#6E6E6E"></div>
                        <div class="mr-auto">
                            <h4 class="card-title mb-1">Empleados</h4>
                            <p class="fs-14 mb-0"> Listado Registrado</p>
                        </div>
                    </div>
                    <div class="float-right">
                        <!-- <button class="btn tp-btn-light btn-success btn-xs" wire:click="SyncDown">
                            <i class="las la-download la-2x"></i>
                        </button> -->
                        <button class="btn tp-btn-light btn-success btn-xs" wire:click="Add">
                            <i class="las la-plus la-2x"></i>
                        </button>
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
                                    <th style="background-color:#6E6E6E">Usuario</th>
                                    <th style="background-color:#6E6E6E"></th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse ($empleados as $item)
                                <tr>
                                    <td class="text-left">
                                        <span class="{{ $item->is_active !=null ? 'text-success' : '' }}">
                                            {{ $item->first_name }} {{ $item->last_name }} 
                                        </span>
                                    </td>
                                    <td> {{ $item->email }} </td>
                                    <td> {{ $item->phone_number }} </td>
                                    <td> {{ $item->user?->name }} </td>

                                    <td class="text-right">

                                        <div class="dropdown position-static">
                                            <button class="btn btn-info dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false" style="background-color:#6E6E6E;border-color:#6E6E6E">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right"
                                                aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="#"
                                                    wire:click.prevent="Edit({{ $item->id }})"><i
                                                        class="las la-pen la-2x"></i> Editar
                                                </a>
                                                @if(!$item->is_active)
                                                <a class="dropdown-item" href="#"
                                                    wire:click.prevent="Activate({{ $item->id }})"><i
                                                        class="las la-check la-2x"></i> Activar
                                                </a>
                                                @else
                                                <a class="dropdown-item" href="#"
                                                    wire:click.prevent="Deactivate({{ $item->id }})"><i
                                                        class="las la-ban la-2x"></i> Desactivar
                                                </a>
                                                @endif
                                                <script>
                                                    function confirmDelete(empleadoId) {
                                                        // Mostrar cuadro de diálogo de confirmación personalizado
                                                        Swal.fire({
                                                            title: '¿Seguro que desea eliminar al empleado?',
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
                                                                Livewire.emit('Delete', empleadoId); // Llamar al método de Livewire
                                                            }
                                                        });
                                                    }
                                                </script>
                                                <a class="dropdown-item" href="#"
                                                    onclick="confirmDelete({{ $item->id }})"><i
                                                        class="las la-trash-alt la-2x"></i> Eliminar
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">No hay empleados</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            {{$empleados->links()}}
                        </div>
                        <div class="col-md-6"><span class="float-right">Records:{{$records}}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('stop-loader', (event) => {
                SlickLoader.disable()
    }) 
    </script>

</div>


@include('livewire.empleados.view')
@include('livewire.empleados.js')
@push('my-scripts')
@endpush

