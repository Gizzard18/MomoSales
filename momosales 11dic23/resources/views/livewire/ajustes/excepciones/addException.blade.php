
<div class="modal-header" style="margin-top: -4rem;">
    <h4 class="modal-title">Agregar Excepciones</h4>
</div>
<div class="modal-body">
    <div class="row" x-data="{ open: false }" @click.away="open=false"> 
        <div class="col-sm-12 col-md-8">
            <input style="background-color: transparent;border-color:transparent;border-bottom:1px solid black;" wire:model="query" @focus="open=true" @keydown.escape.window="open=false" type="text" id="searchBox" class="form-control form-control-lg" autocomplete="off">
            <label>Nombre</label>
        </div>
        <ul x-show="open" class="list-group position-relative" style="width: 60%;z-index:99">
            @foreach ($listado as $index => $item)
            <li wire:click="selectedItem({{ $item->id }})" @click="open=false;" class="list-group-item list-group-item-action" style="font-weight:lighter; cursor:pointer; color:#6E6E6E;">{{ $item->name }}</li>
            @endforeach
        </ul>
        <div class="col-sm-12 col-md-2">
            <select style="background-color: transparent;border-color:transparent;border-bottom:1px solid black;" wire:model='tipoExc' class="form-control  form-control-lg">
                <option value="percent">%</option>
                <option value="qty">$</option>
            </select>
            @error('tipoExc') <span class="text-danger">{{ $message }}</span> @enderror
            <label>Tipo</label>
        </div>
        <div class="col-sm-12 col-md-2">
            <input style="background-color: transparent;border-color:transparent;border-bottom:1px solid black;" wire:model="qty" type="text"
                class="form-control form-control-lg">
            @error('qty') <span class="text-danger">{{ $message }}</span> @enderror
            <label>Cantidad</label>
        </div>
    </div>
</div>
<div class="modal-footer">
        <button class="btn btn-sm btn-dark float-left  mb-3" wire:click.prevent="cancel">Cancel</button>
    <button class="btn btn-sm btn-info float-right save  mb-3" wire:click.prevent="StoreException" style="background-color:#B59377; border-color:#B59377">Guardar</button>
</div>