<div  style="display: {{ $action !=1 ? 'block' : 'none' }}">
    
    <div class="row">

    <div class="col-sm-12 col-md-7"> 
        <div id="cardForm">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header ">
                            <h4 class="card-title mb-1">{{ $action == 2 ? 'Crear Servicio' : 'Editar Servicio' }}</h4>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-sm-12 col-md-8">
                                    <input style="background-color: transparent;border-color:transparent;border-bottom:1px solid black;" wire:model.defer="service.name" type="text"
                                        class="form-control form-control-lg" placeholder="Nombre">
                                    @error('service.name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <select style="background-color: transparent;border-color:transparent;border-bottom:1px solid black;" wire:model.defer='service.duration' class="form-control  form-control-lg">
                                        <option value="30">30 minutos</option>
                                        <option value="60">1:00 hora</option>
                                        <option value="90">1:30 horas</option>
                                        <option value="120">2:00 horas</option>
                                        <option value="150">2:30 horas</option>
                                        <option value="180">3:00 horas</option>
                                        <option value="210">3:30 horas</option>
                                        <option value="240">4:00 horas</option>
                                    </select>
                                    @error('service.duration') <span class="text-danger">{{ $message }}</span> @enderror
                                    <label>Duración</label>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-sm-12 col-md-8">
                                    <input style="background-color: transparent;border-color:transparent;border-bottom:1px solid black;" wire:model.defer="service.description" type="text"
                                        class="form-control form-control-lg" placeholder="Descripción">
                                    @error('service.description') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <input style="background-color: transparent;border-color:transparent;border-bottom:1px solid black;" wire:model.defer="service.reward_points" type="number" class="form-control form-control-lg"
                                        placeholder="0.00">
                                    @error('service.reward_points') <span class="text-danger">{{ $message }}</span> @enderror
                                    <label>Puntos Generados</label>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-sm-12 col-md-4">
                                    <input style="background-color: transparent;border-color:transparent;border-bottom:1px solid black;" wire:model.defer="service.gross_price" type="number" class="form-control form-control-lg"
                                        placeholder="$0.00">
                                    @error('service.gross_price') <span class="text-danger">{{ $message }}</span> @enderror
                                    <label>Precio público</label>
                                </div>
                                <div class="col-sm-12 col-md-2">
                                    <input style="background-color: transparent;border-color:transparent;border-bottom:1px solid black;" wire:model.debounce.750ms="percent" type="number" class="form-control form-control-lg"
                                        placeholder="0.00%">
                                    <label>Porcentaje de descuento</label>
                                </div>
                                <div class="col-sm-12 col-md-2">
                                    <input style="background-color: transparent;border-color:transparent;border-bottom:1px solid black;" type="number" class="form-control form-control-lg"
                                    value="{{ $finalD ? number_format($finalD,2,'.',',') : number_format($this->service->disccount_price,2,'.',',') }}"" placeholder="Precio descuento" disabled>
                                    <button type="button" class="close" wire:click="calculate">×</button>
                                    <label>Precio descuento</label>
                                </div>
                                <div class="col-sm-12 col-md-4" wire:ignore style="background-color:white !important;">
                                    <input style="background-color: transparent  !important;border-color:transparent !important;border-bottom:1px solid black !important;"  wire:model="categoriesList" type="text" 
                                        placeholder="Buscar categoría" autocomplete="off" id="tomCategoryS" class="form-control form-control-lg" style="background-color:white !important;">
                                    <label style="background-color:white !important;">Categorías</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-sm btn-dark float-left" wire:click.prevent="cancelEdit">Cancel</button>
                            <button class="btn btn-sm btn-info float-right save" wire:click.prevent="Store" style="background-color:#B59377; border-color:#B59377">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>        
        </div>
    </div> 


    <div class="col-sm-12 col-md-5" x-data="{ open: false }"@click.away="open=false"> 
        <div class="card">
            <div class="card-header">
                <span class="h3" style="margin:auto">Agregar Fórmula</span>
            </div>
            <div >
                <div class="input-group search-area d-xl-inline-flex d-none w-100" style="border-bottom:1px solid black;">
                    <input style="background-color: transparent;border-color:transparent;" wire:model="query" @focus="open=true" @keydown.escape.window="open=false" type="text" id="searchBox" class="form-control form-control-lg" autocomplete="off" placeholder="Escriba el nombre del producto"> 
                    <div class="input-group-append">
                        <i style="background-color: white;" class="input-group-text"><i class="flaticon-381-search-2"></i></i>
                    </div>
                </div>
            </div>
            <div>
                <ul x-show="open" class="list-group position-relative"  style="width: 100%;z-index:99">
                @foreach ($productos as $index => $item)
                    <li wire:click="addProductFromCard({{ $item->id }})" @click="open=false;" class="list-group-item list-group-item-action" style="font-weight:lighter; cursor:pointer; color:#6E6E6E">{{ $item->name }}</li>
                @endforeach 
                </ul>
            </div>
            <div class="card-body p-1">
                <div class="table-responsive">

                    <table class="table table-striped table-responsive-sm">
                        <thead>
                            <tr class="text-center">
                                <th width="280">Material</th>
                                <th width="100">Unidad</th>
                                <th width="90">Cantidad</th>
                                <th width="90">Strock</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cartInfo as $item)
                            <tr class="text-center">

                                <td class="text-justify" >{{ $item['name'] }}
                                </td>
                                <td>{{ $item['unit_type'] }}</td>
                                <td>
                                    <input
                                        wire:keydown.enter.prevent="$emit('updateQty', '{{ $item['id'] }}', $event.target.value)"
                                        class="form-control form-control-sm text-center" type="numeric" style="background-color: transparent;border-color:transparent;border-bottom:1px solid black;" 
                                        value=" {{ $item['qty'] }}" >

                                </td>
                                <td>{{ $item['stock'] }}</td>
                                <td>
                                    <button wire:click.prevent="$emit('removeItem', '{{ $item['id'] }}' )"
                                        class="btn tp-btn btn-xxs btn-danger "><i class="fa fa-trash fa-lg"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">BUSCA O ESCANEA UN PRODUCTO</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    </div>
</div>



<style>

.ts-wrapper{
    background-color: transparent  !important;
    border-color:transparent !important;
    border-bottom:1px solid black !important;
}
input style="background-color: transparent;border-color:transparent;border-bottom:1px solid black;"[type=number]::-webkit-inner-spin-button, 
input style="background-color: transparent;border-color:transparent;border-bottom:1px solid black;"[type=number]::-webkit-outer-spin-button { 
-webkit-appearance: none; 
margin: 0; 
}
</style>