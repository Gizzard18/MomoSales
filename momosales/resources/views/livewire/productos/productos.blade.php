<div>
    <div class="row" id="cardTable" style="background-color: white;display: {{ $action == 1 ? 'block' : 'none' }}">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header ">
                    <div class="d-flex">
                        <div class="separator" style="background-color:#6E6E6E"></div>
                        <div class="mr-auto">
                            <h4 class="card-title mb-1">Productos</h4>
                            <p class="fs-14 mb-0"> Listado Registrado</p>
                        </div>
                    </div>
                    <div class="row">
                    <div class="float-right">
                        <button class="btn tp-btn-light btn-success btn-xs" wire:click="$set('action',2)">
                            <i class="las la-plus la-2x"></i>
                        </button>
                    </div>
                    <div class="float-right">
                        <a class="btn dropdown-item tp-btn-light btn-success btn-xs" href="#" wire:click.prevent="SyncAll"><i class="las la-sync la-2x"></i></a></div>

                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md table-hover  text-center" id="tblProducts">
                            <thead class="thead-primary">
                                <tr>
                                    <th width="80" style="background-color:#6E6E6E"></th>
                                    <th style="background-color:#6E6E6E">Nombre</th>
                                    <th style="background-color:#6E6E6E">Tipo</th>
                                    <th style="background-color:#6E6E6E">Status</th>
                                    <th style="background-color:#6E6E6E">Precio venta</th>
                                    <th style="background-color:#6E6E6E">Precio descuento</th>
                                    <th style="background-color:#6E6E6E">Precio compra</th>
                                    <th style="background-color:#6E6E6E">Stock</th>
                                    <th style="background-color:#6E6E6E">Stock mínimo</th>
                                    <th style="background-color:#6E6E6E">Categorías</th>
                                    <th style="background-color:#6E6E6E"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($productos as $item)
                                <tr>
                                    <td>
                                        <img alt="photo" class="img-fluid rounded gallery-item"
                                            src="{{ asset($item->photo) }}" data-src="{{ asset($item->photo) }}">
                                    </td>
                                    <td>
                                        <div class="{{ $item->platform_id !=null ? 'text-success' : '' }}" >{{$item->name
                                            }}</div>
                                        <small>SKU:{{$item->sku}}</small>
                                        <small>${{$item->woocommerce_sales}}</small>
                                    </td>
                                    <td>{{$item->type_product }}</td>
                                    <td>{{$item->visibility }}</td>
                                    <td>${{$item->gross_price }} </td>
                                    <td>${{$item->disccount_price }} </td>
                                    <td>${{$item->cost }}</td>
                                    <td style="color:{{ $item->stock_qty<$item->min_stock ? 'red' : '' }}">{{$item->stock_qty }}</td>
                                    <td>{{$item->min_stock }}</td>
                                    <td>
                                        <small> {{implode(", ", $item->categorias->pluck('name')->toArray())}}</small>
                                    </td>

                                    <td>
                                        <div class="dropdown position-static">
                                            <button class="btn btn-info dropdown-toggle" style="background-color:#6E6E6E; border-color:#B59377" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                Acciones
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right"
                                                aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="#"
                                                    wire:click.prevent="viewProduct({{ $item->id }})"><i
                                                        class="las la-eye la-2x"></i> Ver</a>
                                                <a class="dropdown-item" href="#"
                                                    wire:click.prevent="Edit({{ $item->id }})"><i
                                                        class="las la-pen la-2x"></i> Editar</a>
                                                        
                                                <script>
                                                    function confirmDelete(productId) {
                                                        // Mostrar cuadro de diálogo de confirmación personalizado
                                                        Swal.fire({
                                                            title: '¿Seguro que desea eliminar este producto?',
                                                            text: '',
                                                            icon: 'warning',
                                                            showCancelButton: true,
                                                            confirmButtonColor: '#9A7D65',
                                                            cancelButtonColor: '#962222',
                                                            confirmButtonText: 'Aceptar',
                                                            cancelButtonText: 'Cancelar',
                                                        }).then((result) => {
                                                            if (result.isConfirmed) {
                                                                showProcessing()
                                                                // Si el usuario hace clic en "Aceptar", ejecutar el método de Livewire
                                                                Livewire.emit('Delete', productId); // Llamar al método de Livewire
                                                            }
                                                        });
                                                    }
                                                </script>
                                                <a class="dropdown-item" href="#"
                                                    onclick="confirmDelete({{ $item->id }})"><i
                                                        class="las la-trash-alt la-2x"></i> Eliminar</a>
                                                <a class="dropdown-item save" href="#"
                                                    wire:click.prevent="Sync({{ $item->id }})"><i
                                                        class="las la-sync la-2x"></i> Sincronizar</a>
                                            </div>
                                        </div>
                                    </td>


                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3">No hay productos</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            {{$productos->links()}}
                        </div>
                        <div class="col-md-6"><span class="float-right">Records:{{$records}}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    
    {{-- card form --}}
    @include('livewire.productos.form')
    @include('livewire.productos.view')

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    @include('livewire.productos.js')
    @push('my-scripts')
    @endpush

</div>