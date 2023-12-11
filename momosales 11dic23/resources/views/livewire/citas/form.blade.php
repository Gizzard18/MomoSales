<div style="display: {{ $action!=1 ? 'block' : 'none' }}">
    
    <div class="row">

    <div class="col-sm-12 col-md-9"> 
        <livewire:cart-view-services/>
    </div> 
    <div class="col-sm-12 col-md-3"> 
        @include('livewire.citas.totales')
    </div>

    </div>

    <div>
        <livewire:payment-services :totalCart="$totalCart" :itemsCart="$itemsCart" :key="$totalCart" />
    </div>


    @include('livewire.citas.js')
</div>
