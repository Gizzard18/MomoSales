<script>
    document.addEventListener('livewire:load', function () {

var mainWrapper = document.getElementById('main-wrapper')
mainWrapper.classList.add('menu-toggle')

//cerrar modal cash / payments
window.addEventListener('close-payment', event => {
    $('#modalPayment').modal('hide')
})


})


function CancelSale() {          
    Swal.fire({
    title: '¿CONFIRMAS CANCELAR LA VENTA?',
    text: "",
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Aceptar'
    }).then((result) => {
    if (result.value) {    
        showProcessing()
        @this.clear()       
    }
    })
}


function openPayment() {
    $('#modalPayment').modal('show')

    initializeTomSelect()

setTimeout(() => {
    document.getElementById('tomCustomer-ts-control').focus()
}, 1000);

}


function initializeTomSelect() {

    elTom = document.querySelector('#tomCustomer')
    if(elTom.tomselect) return


    var myurl ="{{ route('data.customers') }}"
    
        new TomSelect(elTom, {
        maxItems: 1,
        valueField: 'id',
        labelField: 'first_name',
        searchField: ['first_name','last_name'],  
        load: function(query, callback) {        
        var url = myurl + '?q=' + encodeURIComponent(query)
        fetch(url)
        .then(response => response.json())
        .then(json => {
        callback(json)        
        }).catch(()=>{
        callback()
        });        
        },
        onChange: function(value) {         
        Livewire.emit('customerId', value)
         setTimeout(() => {
            document.getElementById('inputCash').focus()
         }, 50);
        },
        render: {
        option: function(item, escape) {
        return `<div class="py-2 d-flex">
            <div>
                <div class="mb-0">
                    <span style="color:#B59377;"> ${ escape(item.first_name) }</span>
                </div>
            </div>
        </div>`;
        },      
        },
        }) 

}


</script>