<div id="modalViewProduct" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Contenido del modal-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Información del producto</h4>
            </div>
            <div class="modal-body">
                @if($productSelected !=null)

                <div class="col">
                    <div >
                        <!-- Mostrar la primera imagen del producto o una imagen por defecto si no hay imágenes -->
                        <img src="{{ asset($productSelected->photo) }}" class="img-fluid rounded"
                            alt="{{ $productSelected->name }}">
                    </div>
                    <div >
                        <!-- Mostrar la información del producto -->
                        <h3 class="text-center">{{ $productSelected->name }}</h3>
                        <p class="text-center">{{ $productSelected->description ?? 'Sin descripción' }}</p>
                        <p><strong>Existencias:</strong> {{ $productSelected->stock_qty }}</p>
                        <p><strong>SKU:</strong> {{ $productSelected->sku }}</p>
                        <p><strong>SKU interno:</strong> {{ $productSelected->intern_sku }}</p>
                        <p><strong>Categoría(s):</strong> {{implode(", ", $item->categorias->pluck('name')->toArray())}}</p>
                        <p><strong>Precio bruto:</strong> ${{ $productSelected->gross_price }}</p>
                        <p><strong>Precio descuento:</strong> ${{ $productSelected->disccount_price }}</p>
                        <p><strong>Precio compra:</strong> ${{ $productSelected->cost }}</p>
                        <p><strong>Tipo:</strong> {{ ucfirst($productSelected->type_product) }}</p>
                        <p><strong>Marca:</strong> {{ $productSelected->marca ? $productSelected->marca->name : 'Sin marca' }}</p>
                        <p><strong>Estatus:</strong> {{ ucfirst($productSelected->status) }}</p>
                        <p><strong>Visibilidad:</strong> {{ ucfirst($productSelected->visibility) }}</p>
                        <p><strong>Stock mínimo:</strong> {{ $productSelected->min_stock }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="tab-slide-content new-arrival-product mb-4 mb-xl-0">
                            <!-- Nav tabs -->
                            <ul class="nav slide-item-list mt-3" role="tablist">
                                @if ($productSelected->images)
                                @foreach ($productSelected->images as $image)
                                <li role="presentation" class="show">
                                    <a href="#first" role="tab" data-toggle="tab">
                                        <img class="img-fluid rounded"
                                            src="{{ asset('storage/productos/' . $image->file)}}" alt="" width="50">
                                    </a>
                                </li>
                                @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>