<script>
    document.addEventListener('livewire:load', function () {        
    var mainWrapper = document.getElementById('main-wrapper')
// aplicamos la clase al contenedor principal para compactar el menu lateral y tener espacio de trabajo
mainWrapper.classList.add('menu-toggle') 


Livewire.hook('message.processed', (el, component) => {            
    initializeTomSelect()                 
})

            

})

window.addEventListener('view-product', event => {   
        $('#modalViewProduct').modal('show')
})



function initializeTomSelect() {

    var elTom = document.querySelector('#tomCategory');
    if (elTom.tomselect) return
    

var myurl ="{{ route('data.categories') }}"
        new TomSelect(elTom, {
        valueField: 'id',
        labelField: 'name',
        searchField: ['name'],  
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
         @this.set('categoriesList',value)
        },
        render: {
        option: function(item, escape) {
        return `<div class="py-2 d-flex">
            <div>
                <div class="mb-0"> 
                    <span style="color: #6E6E6E"> ${ escape(item.name) }</span>
                </div>
            </div>
        </div>`;
        },      
        },
        })
    }

</script>