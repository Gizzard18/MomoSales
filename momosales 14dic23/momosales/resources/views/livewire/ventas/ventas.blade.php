<div>
    
    <div class="row">

    <div class="col-sm-12 col-md-9"> 
        <livewire:cart-view/>
    </div> 
    <div class="col-sm-12 col-md-3"> 
        @include('livewire.ventas.totales')
    </div>

    </div>

    <div>
        <livewire:payment :totalCart="$totalCart" :itemsCart="$itemsCart" :key="$totalCart" />
    </div>


    @include('livewire.ventas.js')
</div>
