<div class="input-group search-area d-xl-inline-flex d-none" wire:ignore>
    <input wire:keydown.enter.prevent="$emit('search', $event.target.value)" type="text" class="form-control" placeholder="Buscar..." id="searchInput" style="
        background-color: white; 
        padding: 15px 15px 15px 35px;
        height: 40px;
        border-radius: 50px;
        border-style: solid;
        border-width:1px;color:black">
        
    <div class="input-group-append">
        <i class="flaticon-381-search-2" style="
        color: #191919;
        position: absolute;
        width: 2px;
        height: 20px;
        left: 14rem;
        top: 50%;
        transform: translateY(-50%);"></i>
    </div>
    @push('my-scripts')
    <script>
        livewire.on('search', event=>{
            document.getElementById('searchInput').value=''
        })
    </script>
    @endpush
</div>
