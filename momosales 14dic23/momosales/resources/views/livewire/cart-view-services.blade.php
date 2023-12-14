<div>
    <div class="card"  x-data="{ open: false }"@click.away="open=false">
        <div class="card-header">
            <div class="d-flex">
                <div class="separator" style="background-color:#6E6E6E"></div>
                <div class="mr-auto">
                    <h4 >Nueva Cita ({{ $this->minutes }} min.)</h4>
                    <p class="fs-14 mb-0">{{ $this->start_date }} - {{ $cartInfo['end'] ?? $this->end_date }}</p>
                </div>
            </div>
            <div class="input-group search-area d-xl-inline-flex d-none w-50" style="border-bottom:1px solid black;">
                <input style="background-color: transparent;border-color:transparent;" wire:model="query" @focus="open=true" @keydown.escape.window="open=false" type="text" id="searchBox" class="form-control form-control-lg" autocomplete="off" placeholder="Escriba el nombre del servicio"> 
                <div class="input-group-append">
                    <i style="background-color: white;" class="input-group-text"><i class="flaticon-381-search-2"></i></i>
                </div>
            </div>
        </div>
        <div>
            <ul x-show="open" class="list-group position-relative float-right"  style="width: 50%;z-index:99">
            @foreach ($servicios as $index => $item)
                <li wire:click.prevent="$emit('add-service', {{ $item->id }});" @click="open=false;" class="list-group-item list-group-item-action" style="font-weight:lighter; cursor:pointer; color:#6E6E6E">{{ $item->name }}</li>
            @endforeach 
            </ul>
        </div>
        <div class="card-body p-1">
            <div class="table-responsive">

                <table class="table table-striped table-responsive-sm">
                    <thead>
                        <tr class="text-center">
                            <th width="96"><i class="las la-download"></i>Uso</th>
                            <th width="280">Servicio</th>
                            <th>Tiempo</th>
                            <th width="260">Vendedor</th>
                            <th width="90">Descuento</th>
                            <th width="200">IVA</th>
                            <th>Precio</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cartInfo as $item)
                        <tr class="text-center">
                            <td>
                                <i class="las la-download" style="cursor: pointer;" wire:click="mostrarMateriales({{ $item['sid'] }})"></i>
                            </td>

                            <td>{{ $item['name'] }}
                            </td>
                            <td class="text-center" >{{ $item['duration'] }} min
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
                            <td>
                                <button wire:click.prevent="$emit('removeItem', '{{ $item['id'] }}' )"
                                    class="btn tp-btn btn-xxs btn-danger "><i class="fa fa-trash fa-lg"></i></button>
                            </td>
                        </tr>
                        @if($show==$item['sid'])
                        <tr>
                            <thead>
                                <tr class="text-center">
                                    <th><a  style="cursor: pointer;text-decoration:underline" wire:click="close()">Cerrar</a></th>
                                    <th width="280">Material</th>
                                    <th width="90">Cantidad</th>
                                    <th width="100">Unidad</th>
                                    <th width="90">Stock</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($materials as $material)
                                <tr class="text-center">
                                    <td></td>
                                    <td >{{ $material->producto->name }}
                                    </td>
                                    <td>
                                        <input
                                            wire:keydown.enter.prevent="$emit('updateQty', '{{ $material['id'] }}', $event.target.value)"
                                            class="form-control form-control-sm text-center" type="numeric" style="background-color: transparent;border-color:transparent;border-bottom:1px solid black;" 
                                            value=" {{ $material->qty }}" >

                                    </td>
                                    <td>{{ $material->producto->unit_type }}</td>
                                    <td>{{ $material->producto->stock_qty }}</td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <button wire:click.prevent="$emit('removematerial', '{{ $material['id'] }}' )"
                                            class="btn tp-btn btn-xxs btn-danger "><i class="fa fa-trash fa-lg"></i></button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">BUSCA O ESCANEA UN PRODUCTO</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </tr>
                        @endif
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">AGREGA SERVICIOS</td>
                        </tr>
                    </tbody>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
