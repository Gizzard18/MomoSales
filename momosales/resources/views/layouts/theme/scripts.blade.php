<script src="{{ asset('vendor/global/global.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('vendor/chart.js/Chart.bundle.min.js') }}"></script>

<script src="{{ asset('vendor/peity/jquery.peity.min.js') }}"></script>

<!-- <script src="{{ asset('vendor/apexchart/apexchart.js') }}"></script>

<script src="{{ asset('js/dashboard/dashboard-2.js') }}"></script> -->

<script src="{{ asset('js/custom.min.js') }}"></script>

<script src="{{ asset('js/deznav-init.js') }}"></script>

<script src="{{ asset('vendor/toastr/js/toastr.min.js') }}"></script>

<script src="{{ asset('js/sweetalert2.js') }}"></script>

<script src="{{ asset('js/tom-select.complete.min.js') }}"></script>

<script src="{{ asset('js/notify.min.js') }}"></script>

<script src="{{ asset('js/slick-loader.min.js') }}"></script>

<script src="{{ asset('plugins/flatpickr.js') }}"></script>


<script src="{{ asset('vendor/global/global.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('vendor/moment/moment.min.js') }}"></script>
<script src="{{ asset('vendor/fullcalendar/js/main.min.js') }}"></script>


<!--  <script src="./js/plugins-init/fullcalendar-init.js"></script> -->


<script>
    //full loaded
    window.addEventListener('load', () => {

        document.addEventListener('ok', (event) => {
            Swal.fire({
                title: "<span style='color:orange'>"+ "info" + "</span>",
                html: event.detail.msg,
                timer: 3000,
                showConfirmButton: !1,
                confirmButtonColor: '#EAE0D8',
            }).then((result) => {
                // do something
            })
        })


        document.addEventListener('noty-error', (event) => {
            toastr.error(event.detail.msg, "Info",{
                positionClass: "toast-bottom-center",
                closeButton: true,
                progressBar: true, 
            })
        })

        document.addEventListener('stop-loader', (event) => {
            hideProcessing();
        })

        document.addEventListener('loader', (event) => {
            showProcessing();
        })

        document.addEventListener('noty', (event) => {
            toastr.info(event.detail.msg, "Info",{
                positionClass: "toast-bottom-center",
                closeButton: !0,
                progressBar: !0, 
                confirmButtonColor: '#EAE0D8',
            })
        })

    })


document.querySelector('.save').addEventListener('click', function() {   
        showProcessing()
});

document.addEventListener('click', function (event) {    
    if (event.target.classList.contains('save') ) {
        showProcessing()
    }
})


function showProcessing() {
    SlickLoader.setText('MOMÓ SALES','PROCESANDO SOLICITUD')
    SlickLoader.enable()
}


function hideProcessing() {    
    SlickLoader.disable()
}



// function confirm() {
//     Swal.fire({
//     title: '¿CONFIRMAS ELIMINAR EL REGISTRO?',
//     text: "",
//     icon: 'warning',
//     showCancelButton: true,
//     confirmButtonColor: '#3085d6',
//     cancelButtonColor: '#d33',
//     confirmButtonText: 'Aceptar'
//     }).then((result) => {
//     if (result.value) {
//         showProcessing()
//         return result.value
//         console.log(result);
//     }
//     })
// }


</script>
