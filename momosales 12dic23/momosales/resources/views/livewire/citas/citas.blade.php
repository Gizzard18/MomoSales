<div>
    <div style="background-color: white;display: {{ $action == 1 ? 'block' : 'none' }}">
        <!-- row -->


        <div class="row">
            <div class="col-xl-3 col-xxl-4" style="min-height: 200px;">
                <div class="card">
                    <div class="card-body">
                        <h4>Seleccione una fecha</h4>
                        <hr>
                        <input wire:model.defer="cliente.birth_date" type="date" class="form-control form-control-lg flatpickr" placeholder="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="card-body" style="margin-top: -4rem;">
                        <h4 class="card-intro-title">Empleados</h4>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <label><a style="cursor:pointer;text-decoration:underline" wire:click="mostrarCitas(0)">Todos</a></label>
                                <p></p>
                            </div>
                            @forelse ($empleados as $empleado)
                                <div class="col-md-6">
                                <label><a style="cursor:pointer;text-decoration:underline" wire:click="mostrarCitas({{ $empleado->id }})">{{ $empleado->first_name }}</a></label>
                                <p></p>
                                </div>
                            @empty
                            <tr>
                                <td>No hay empleados</td>
                            </tr>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-xxl-8">
                <div class="card">
                    <div class="card-body">
                        <div id="calendar" class="app-fullcalendar" style="font-size: smaller;"></div>
                    </div>
                </div>
            </div>
            <!-- BEGIN MODAL -->
            <div class="modal fade none-border" id="event-modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"><strong>Add New Event</strong></h4>
                        </div>
                        <div class="modal-body"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success save-event waves-effect waves-light">Create
                                event</button>

                            <button type="button" class="btn btn-danger delete-event waves-effect waves-light" >Delete</button>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>
    @if($action==2)
    @include('livewire.citas.form')
    @endif
</div>

@include('livewire.citas.js')
<style>
  .fc .fc-button-group>.fc-button.fc-button-active, .fc .fc-button-group>.fc-button:active, .fc .fc-button-group>.fc-button:focus, .fc .fc-button-group>.fc-button:hover {
    z-index: 1;
    background-color: #6E6E6E;
    border-color: transparent;
  }
  .fc-button.fc-button-primary.fc-today-button {
    z-index: 1;
    background-color: #6E6E6E;
    border-color: transparent;
  }
  .prueba {
    background: #f1f1f1;
    border-color: #5c5c5c;
    color: #4b4b4b;
    width: auto;
    height: 100px;
    }
  .fc .fc-button-primary:disabled {
    color: #6E6E6E;
    background-color:white;
}
</style>
