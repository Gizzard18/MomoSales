<div>
    <tbody>
        <thead>
            <tr>
                <th>Servicio</th>
                <th>Precio</th>
                <th>Comisión</th>
            </tr>
        </thead>
        @if($comision)
        @forelse($comision->excepcion_servicio as $excepcion)
                <tr >
                    <th style="font-weight: lighter;">{{ $excepcion->servicio->name }}</th>
                    <th style="font-weight: lighter;">{{ $excepcion->servicio->gross_price }}</th>
                    <th style="font-weight: lighter;">@if($excepcion->type_comission==='percent') {{ number_format($excepcion->qty,2,'.') }}% @else ${{ number_format($excepcion->qty,2,'.',',') }} @endif</th>
                    <th>
                    <button title="Eliminar" class="btn tp-btn btn-xs btn-danger" onclick="confirmDelete({{ $excepcion->id }},2)"><i class="las la-trash-alt la-2x"></i></button>
                    </th>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No hay excepciones para servicios almacenadas</td>
                </tr>
        @endforelse
        <tr class="row m-auto">
            <button class="btn btn-xs text-center"><th class="customized pl-2 pr-2" wire:click="Add(2)">Añadir una excepción para servicios</th></button>
        </tr>
        @if($creating&&$tipo==2)
            @include('livewire.ajustes.excepciones.addException')
        @endif
        <thead>
            <tr>
                <th>Categoría</th>
                <th></th>
                <th>Comisión</th>
            </tr>
        </thead>
        @forelse($comision->excepcion_cat_servicio as $excepcion)
                <tr>
                    <th style="font-weight: lighter;">{{ $excepcion->cat_servicio?->name }}</th>
                    <th></th>
                    <th style="font-weight: lighter;">@if($excepcion->type_comission==='percent') {{ number_format($excepcion->qty,2,'.') }}% @else ${{ number_format($excepcion->qty,2,'.',',') }} @endif</th>
                    <th>
                    <button title="Eliminar" class="btn tp-btn btn-xs btn-danger" onclick="confirmDelete({{ $excepcion->id }},4)"><i class="las la-trash-alt la-2x"></i></button>
                    </th>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No hay excepciones para categorías almacenadas</td>
                </tr>
        @endforelse
        <tr class="row m-auto">
            <button class="btn btn-xs text-center"><th class="customized pl-2 pr-2" wire:click="Add(4)">Añadir una excepción para una categoría de servicios</th></button>
        </tr>
        @if($creating&&$tipo==4)
            @include('livewire.ajustes.excepciones.addException')
        @endif
        @endif
    </tbody>
</div>
