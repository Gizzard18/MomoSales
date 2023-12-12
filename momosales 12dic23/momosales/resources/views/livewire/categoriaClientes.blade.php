<div>
    <div class="row">
        <div class="col-md-4" >
            <div class="card"style="background-color: white;">

                <div class="card-header"style="">
                    <h4 style="margin: auto;">{{ $editing ? 'Editar categoría' : 'Crear categoría' }}</h4>
                </div>

                <div class="card-body">

                    <div class="form-group">
                        <input wire:model.defer="categoria_cliente.name" id='inputFocus' type="text"
                            class="form-control form-control-lg" placeholder="Categoría" style="background-color: white; color:black;">
                        @error('categoria_cliente.name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                </div>
                <div class="card-footer">
                    <button class="btn btn-sm btn-dark light float-left hidden {{$editing ? 'd-block' : 'd-none' }}"
                        wire:click="cancelEdit">
                        Cancelar
                    </button>
                    <button class="btn btn-sm btn-info float-right save" wire:click="Store" style="background-color: #6E6E6E;border-color:white">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card"style="background-color: white;">
                <div class="card-header ">
                    <div class="d-flex">
                        <div class="separator" style="background-color:#6E6E6E"></div>
                        <div class="mr-auto">
                            <h4 >Categorias de Clientes</h4>
                            <p class="fs-14 mb-0"> Listado Registrado</p>
                        </div>
                    </div>
                        
                </div>
                <div class="card-body" >
                    <div class="table-responsive" >
                        <table class="table table-responsive-md text-center" >
                            <thead class="thead-primary" style="max-width: 400px;">
                                <tr>
                                    <th width="50%"style="background-color:#6E6E6E">Nombre</th>
                                    <th width="70%" style="background-color:#6E6E6E">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($categoria_clientes as $item)
                                <tr style="max-width: 400px;">
                                    
                                    <td>
                                        <div>{{$item->name }}</div>
                                    </td>
                                    <td>
                                        <button class="btn tp-btn btn-xs btn-primary"
                                            wire:click="Edit({{ $item->id }})"><i class="las la-pen la-2x"></i>

                                        <script>
                                            function confirmDelete(categoryId) {
                                                // Mostrar cuadro de diálogo de confirmación personalizado
                                                Swal.fire({
                                                    title: '¿Seguro que desea eliminar esta categoría?',
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
                                                        Livewire.emit('Delete', categoryId); // Llamar al método de Livewire
                                                    }
                                                });
                                            }
                                        </script>
                                        </button>

                                        @if(!$item->cliente()->exists())
                                        <button title="Eliminar" class="btn tp-btn btn-xs btn-danger" onclick="confirmDelete({{ $item->id }})">
                                            <i class="las la-trash-alt la-2x"></i>
                                        </button>
                                        
                                        @endif

                                        
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3">No hay categorías</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            {{$categoria_clientes->links()}}
                        </div>
                        <div class="col-md-6"><span class="float-right dei" >Elementos:{{$records}}</span></div>
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