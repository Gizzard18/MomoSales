<script>
    document.addEventListener('livewire:load', function () {        
    var mainWrapper = document.getElementById('main-wrapper')
// aplicamos la clase al contenedor principal para compactar el menu lateral y tener espacio de trabajo
mainWrapper.classList.add('menu-toggle') 


Livewire.hook('message.processed', (el, component) => {            
    initializeTomSelect()                 
})

            

})

window.addEventListener('view-service', event => {   
        $('#modalViewService').modal('show')
})



function initializeTomSelect() {

    var elTom = document.querySelector('#tomCategoryS');
    if (elTom.tomselect) return
    

var myurl ="{{ route('data.categoriesS') }}"
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
        return `<div >
            <div >
                <div >                
                    <span style="color:#B59377"> ${ escape(item.name) }</span>
                </div>
            </div>
        </div>`;
        },      
        },
        })
    }

</script>

<style>
    .ts-control {
            padding: 0px !important;
            border-style: none;
            border-width: 0px !important;
            background-color: white; 
        }
</style>
