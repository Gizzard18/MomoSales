<div class="card">
    <div class="card-header">
        <span class="h3" style="color:#B59377;margin:auto">Total</span>
    </div>
    <div class="card-body p-3" >
        <div>
            <span style="color: #B59377;">Productos:</span>
            <span class="float-right " style="color:#3B484C">
                {{ $itemsCart }}
            </span>
        </div>
        <div>
            <span style="color: #B59377;">Subtotal:</span>
            <span class="float-right " style="color:#3B484C">
                ${{ number_format(floatval($subtotalCart), 2, '.', ',') }}
            </span>
        </div>
        <div>
            <span style="color: #B59377;">Impuestos:</span>
            <span class="float-right " style="color:#3B484C">
                ${{ number_format($taxCart, 2, '.', ',') }}
            </span>
        </div>
        <hr>
        <div>
            <span style="color: #B59377;"><b>Total</b></span>
            <span class="float-right " style="color:#3B484C">
                <b>${{ number_format($totalCart, 2, '.', ',') }}</b>
            </span>
        </div>
        <hr>
        <div>
            <span style="color: #B59377;"><b>Puntos generados</b></span>
            <span class="float-right " style="color:#3B484C">
                <b>{{ number_format($generated_points, 2, '.', ',') }}</b>
            </span>
        </div>

        <div class="form-group mt-5">
            <button onclick="openPayment()" class="btn btn-sm btn-block" style="background-color: #B59377;color:white" {{ $totalCart> 0 ? '' :'disabled'}}>Registrar</button>
        </div>
        <div class="form-group mt-5">
            <button onclick="CancelSale()" class="btn btn-dark btn-sm btn-block " {{ $totalCart> 0 ? '' : 'disabled'
                }}>Cancelar</button>
        </div>
    </div>
</div>