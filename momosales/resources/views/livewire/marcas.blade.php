<div>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>{{ $editing ? 'Editar Proveedor' : 'Crear Proveedor' }}</h4>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label>Nombre</label>
                        <input wire:model.defer="marca.name" id='inputFocus' type="text"
                            class="form-control form-control-lg" placeholder="Nombre">
                        @error('marca.name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input wire:model.defer="marca.phone_number"  type="phone"
                            class="form-control form-control-lg" placeholder="Teléfono">
                        @error('marca.phone_number') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Persona de contacto</label>
                        <input wire:model.defer="marca.contact_name"  type="text"
                            class="form-control form-control-lg" placeholder="Persona de contacto">
                        @error('marca.contact_name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input wire:model.defer="marca.email"  type="email"
                            class="form-control form-control-lg" placeholder="Email">
                        @error('marca.email') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>



                </div>
                <div class="card-footer">
                    <button class="btn btn-sm btn-dark light float-left hidden {{$editing ? 'd-block' : 'd-none' }}"
                        wire:click="cancelEdit">Cancelar</button>
                    <button class="btn btn-sm btn-info float-right save" style="background-color:#6E6E6E; border-color:#B59377" wire:click="Store">Guardar</button>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header ">
                    <div class="d-flex">
                        <div class="separator" style="background-color:#6E6E6E"></div>
                        <div class="mr-auto">
                            <h4 class="card-title mb-1">Proveedores</h4>
                            <p class="fs-14 mb-0"> Listado Registrado</p>
                        </div>
                    </div>
                    <div class="float-right">
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md table-hover  text-center">
                            <thead class="thead-primary">
                                <tr>
                                    <th style="background-color:#6E6E6E" >Nombre</th>
                                    <th style="background-color:#6E6E6E" >Teléfono</th>
                                    <th style="background-color:#6E6E6E" >Email</th>
                                    <th style="background-color:#6E6E6E" >Contacto</th>
                                    <th style="background-color:#6E6E6E" >Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($marcas as $item)
                                <tr>
                                    <td>
                                        <div>{{$item->name }}</div>
                                    </td>
                                    <td>
                                        <div>{{$item->phone_number }}</div>
                                    </td>
                                    <td>
                                        <div>{{$item->email }}</div>
                                    </td>
                                    <td>
                                        <div>{{$item->contact_name }}</div>
                                    </td>
                                    <td>
                                        <button class="btn tp-btn btn-xs btn-primary"
                                            wire:click="Edit({{ $item->id }})"><i class="las la-pen la-2x"></i>
                                        </button>

                                        <script>
                                            function confirmDelete(marcaId) {
                                                // Mostrar cuadro de diálogo de confirmación personalizado
                                                Swal.fire({
                                                    title: '¿Seguro que desea eliminar esta marca?',
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
                                                        Livewire.emit('Delete', marcaId); // Llamar al método de Livewire
                                                    }
                                                });
                                            }
                                        </script>
                                        <button title="EliminarMarca" class="btn tp-btn btn-xs btn-danger" onclick="confirmDelete({{ $item->id }})">
                                            <i class="las la-trash-alt la-2x"></i>
                                        </button>
                                        


                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3">No hay marcas</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            {{$marcas->links()}}
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