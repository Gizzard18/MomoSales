<div id="modalViewService" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Contenido del modal-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Información del servicio</h4>
            </div>
            <div class="modal-body">
                @if($serviceSelected !=null)

                <div class="row">
                    <div class="col">
                        <!-- Mostrar la información del servicio -->
                        <h3 class="text-center">{{ $serviceSelected->name }}</h3>
                        <p class="text-center">{{ $serviceSelected->description ?? 'Sin descripción' }}</p>
                        <p><strong>Duración:</strong> {{ ucfirst($serviceSelected->duration) }} minutos</p>
                        <p><strong>Precio bruto:</strong> ${{ $serviceSelected->gross_price }}</p>
                        <p><strong>Precio descuento:</strong> ${{ $serviceSelected->disccount_price }}</p>
                        <p><strong>Categoría:</strong> {{implode(", ", $item->categorias->pluck('name')->toArray())}}</p>
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