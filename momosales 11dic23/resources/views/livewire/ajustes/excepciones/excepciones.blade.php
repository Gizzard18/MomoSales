<div class="row" id="cardForm" style="background-color: white; top:0; display: {{ $action!=1 ? 'block' : 'none' }}">
    <div class="row">
        <div class="col-sm-12">
            <div class="card-header">
                <h4 class="text-center">Configurar Excepciones</h4>
            </div>
            <div class="card-body">
                <div class="float-right"><i class="las la-arrow-left"></i><a wire:click="set(1)" style="cursor: pointer;"> Regresar</a></div>
                <table class="table table-striped table-responsive-sm mt-n" style="margin-top: -4rem;">
                    <thead>
                        <tr class="row">
                            <button  class="btn btn-xs text-center"><th class="customized pl-2 pr-2" id="productosBtn" wire:click="cambiarModalP()":class="{ 'cust': productosBtnActivo }">Productos</th></button>
                            <button class="btn btn-xs text-center"><th id="serviciosBtn"  class="customized pl-2 pr-2" wire:click="cambiarModalS()" :class="{ 'cust': productosBtnActivo }">Servicios</th></button>
                            <th></th>
                        </tr>
                    </thead>
                    @if($action==2)
                    <div>
                        @include('livewire.ajustes.excepciones.productos')
                    </div>
                    @elseif($action==3)
                    <div>
                        @include('livewire.ajustes.excepciones.servicios')
                    </div>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
<style>
    .customized:hover{
        background-color: #6E6E6E;
        color: aliceblue !important ;
        cursor:pointer;
    }
    .cust{
        background-color: #6E6E6E;
        color: aliceblue !important ;
        cursor:pointer;
    }
    a:hover{
        color:#6E6E6E;
    }
</style>
