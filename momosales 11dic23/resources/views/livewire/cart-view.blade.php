<div>
    <div class="card"  x-data="{ open: false }"@click.away="open=false">
        <div class="card-header">
            <h3 class="h3">Buscar Productos</h3>
            <div class="input-group search-area d-xl-inline-flex d-none w-50" style="border-bottom:1px solid black;">
                <input style="background-color: transparent;border-color:transparent;" wire:model="query" @focus="open=true" @keydown.escape.window="open=false" type="text" id="searchBox" class="form-control form-control-lg" autocomplete="off" placeholder="Escriba el nombre del producto"> 
                <div class="input-group-append">
                    <i style="background-color: white;" class="input-group-text"><i class="flaticon-381-search-2"></i></i>
                </div>
            </div>
        </div>
        <div>
            <ul x-show="open" class="list-group position-relative float-right"  style="width: 50%;z-index:99">
            @foreach ($productos as $index => $item)
                <li wire:click="$emit('add-product', {{ $item->id }})" @click="open=false;" class="list-group-item list-group-item-action" style="font-weight:lighter; cursor:pointer; color:#6E6E6E">{{ $item->name }}</li>
            @endforeach 
            </ul>
        </div>
        <div class="card-body p-1">
            <div class="table-responsive">

                <table class="table table-striped table-responsive-sm">
                    <thead>
                        <tr class="text-center">
                            <th width="280">Producto</th>
                            <th width="260">Vendedor</th>
                            <th width="90">Cantidad</th>
                            <th width="90">Descuento</th>
                            <th width="200">IVA</th>
                            <th>Precio</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cartInfo as $item)
                        <tr class="text-center">

                            <td class="text-justify" >{{ $item['name'] }}
                            </td>
                            <td>
                                <select wire:change.prevent="$emit('updateEmpleado','{{ $item['id'] }}', $event.target.value)" class="form-control  form-control-lg" style="background-color: transparent;border-color:transparent;">
                                    <option style="text-align: center;" value="null">Seleccionar Empleado</option>
                                    @foreach($empleados as $empleado)
                                    <option style="text-align: center;" value="{{ $empleado->id }}">{{ $empleado->first_name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input
                                    wire:keydown.enter.prevent="$emit('updateQty', '{{ $item['id'] }}', $event.target.value)"
                                    class="form-control form-control-sm text-center" type="numeric" style="background-color: transparent;border-color:transparent;border-bottom:1px solid black;" 
                                    value=" {{ $item['qty'] }}" >

                            </td>
                            <td>
                                <input
                                    wire:keydown.enter.prevent="$emit('updatePercentage', '{{ $item['id'] }}', $event.target.value)"
                                    class="form-control form-control-sm text-center" type="numeric" style="background-color: transparent;border-color:transparent;border-bottom:1px solid black;" 
                                    value="{{ isset($item['disccount_percent']) ? $item['disccount_percent'] : '' }}">

                            </td>
                            <td><select wire:change="$emit('updateIva','{{ $item['id'] }}', $event.target.value)" class="form-control  form-control-lg" style="background-color: transparent;border-color:transparent;text-align:center;font-size:smaller">
                                <option value=0.16>16%</option>
                                <option value=0.08>8%</option>
                                <option value=0>Exento</option>
                            </select></td>
                            <td>${{ number_format($item['disccount_price'] ? $item['disccount_price'] : $item['sale_price'], 2, '.', ',') }}</td>
                            <td>${{ number_format($item['total'],2,'.',',') }}</td>
                            <td>
                                <button wire:click.prevent="$emit('removeItem', '{{ $item['id'] }}' )"
                                    class="btn tp-btn btn-xxs btn-danger "><i class="fa fa-trash fa-lg"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">AGREGA PRODUCTOS</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@section('content')
    <livewire:search />
@endsection

@push('my-scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            document.getElementById('inputFocus').focus(); // Enfoca el campo de búsqueda al cargar el componente
        });
        window.addEventListener('view-product', event => {   
            $('#modalViewProduct').modal('show')
        })
    </script>
@endpush
