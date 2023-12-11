<div>
    <tbody>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Comisión</th>
            </tr>
        </thead>
        @if($comision)
        @forelse($comision->excepcion_producto as $excepcion)
                <tr >
                    <th style="font-weight: lighter;">{{ $excepcion->producto->name }}</th>
                    <th style="font-weight: lighter;">{{ $excepcion->producto->gross_price }}</th>
                    <th style="font-weight: lighter;">@if($excepcion->type_comission==='percent') {{ number_format($excepcion->qty,2,'.') }}% @else ${{ number_format($excepcion->qty,2,'.',',') }} @endif</th>
                    <th>
                    <button title="Eliminar" class="btn tp-btn btn-xs btn-danger" onclick="confirmDelete({{ $excepcion->id }},1)"><i class="las la-trash-alt la-2x"></i></button>
                    </th>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" >No hay excepciones para productos almacenadas</td>
                </tr>
        @endforelse
        <tr class="row m-auto">
            <button class="btn btn-xs text-center"><th class="customized pl-2 pr-2" wire:click="Add(1)">Añadir una excepción para productos</th></button>
        </tr>
        @if($creating&&$tipo==1)
            @include('livewire.ajustes.excepciones.addException')
        @endif
        <thead>
            <tr>
                <th>Categoría</th>
                <th></th>
                <th>Comisión</th>
            </tr>
        </thead>
        @forelse($comision->excepcion_cat_producto as $excepcion)
                <tr>
                    <th style="font-weight: lighter;">{{ $excepcion->cat_producto?->name }}</th>
                    <th></th>
                    <th style="font-weight: lighter;">@if($excepcion->type_comission==='percent') {{ number_format($excepcion->qty,2,'.') }}% @else ${{ number_format($excepcion->qty,2,'.',',') }} @endif</th>
                    <th>
                    <button title="Eliminar" class="btn tp-btn btn-xs btn-danger" onclick="confirmDelete({{ $excepcion->id }},3)"><i class="las la-trash-alt la-2x"></i></button>
                    </th>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No hay excepciones para categorías almacenadas</td>
                </tr>
        @endforelse
        <tr class="row m-auto">
            <button class="btn btn-xs text-center"><th class="customized pl-2 pr-2"  wire:click="Add(3)">Añadir una excepción para una categoría de productos</th></button>
        </tr>
        @endif
        @if($creating&&$tipo==3)
            @include('livewire.ajustes.excepciones.addException')
        @endif
    </tbody>
</div>
