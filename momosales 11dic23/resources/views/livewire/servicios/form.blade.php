<div id="cardForm" style="background-color: white; display: {{ $action !=1 ? 'block' : 'none' }}">
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
    
</div>