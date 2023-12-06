<div>
    <div >
        <!-- Add Project -->
        <div class="modal fade" id="addProjectSidebar">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create Project</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label class="text-black font-w500">Project Name</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="text-black font-w500">Deadline</label>
                                <input type="date" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="text-black font-w500">Client Name</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-primary">CREATE</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- row -->


        <div class="row">
            <div class="col-xl-3 col-xxl-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-intro-title">Calendar</h4>

                        <div class="">
                            <div id="external-events" class="my-3">
                                <p>Drag and drop your event or click in the calendar</p>
                                <div class="external-event btn-primary light" data-class="bg-primary"><i class="fa fa-move"></i><span>New Theme Release</span></div>
                                <div class="external-event btn-warning light" data-class="bg-warning"><i class="fa fa-move"></i>My Event
                                </div>
                                <div class="external-event btn-danger light" data-class="bg-danger"><i class="fa fa-move"></i>Meet manager</div>
                                <div class="external-event btn-info light" data-class="bg-info"><i class="fa fa-move"></i>Create New theme</div>
                                <div class="external-event btn-dark light" data-class="bg-dark"><i class="fa fa-move"></i>Project Launch</div>
                                <div class="external-event btn-secondary light" data-class="bg-secondary"><i class="fa fa-move"></i>Meeting</div>
                            </div>
                            <!-- checkbox -->
                            <div class="checkbox custom-control checkbox-event custom-checkbox pt-3 pb-5">
                                <input type="checkbox" class="custom-control-input" id="drop-remove">
                                <label class="custom-control-label" for="drop-remove">Remove After Drop</label>
                            </div>
                            <a href="javascript:void()" data-toggle="modal" data-target="#add-category" class="btn btn-primary btn-event w-100">
                                <span class="align-middle"><i class="ti-plus"></i></span> Create New
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-xxl-8">
                <div class="card">
                    <div class="card-body">
                        <div id="calendar" class="app-fullcalendar"></div>
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

                            <button type="button" class="btn btn-danger delete-event waves-effect waves-light" data-dismiss="modal">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Add Category -->
            <div class="modal fade none-border" id="add-category">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"><strong>Add a category</strong></h4>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Category Name</label>
                                        <input class="form-control form-white" placeholder="Enter name" type="text" name="category-name">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Choose Category Color</label>
                                        <select class="form-control form-white" data-placeholder="Choose a color..." name="category-color">
                                            <option value="success">Success</option>
                                            <option value="danger">Danger</option>
                                            <option value="info">Info</option>
                                            <option value="pink">Pink</option>
                                            <option value="primary">Primary</option>
                                            <option value="warning">Warning</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-danger waves-effect waves-light save-category" data-dismiss="modal">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
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
  
.fc .fc-button-primary:disabled {
    color: #6E6E6E;
    background-color:white;
}
</style>
	
	<script>
	  document.addEventListener('DOMContentLoaded', function() {

		/* initialize the external events
		-----------------------------------------------------------------*/

		var containerEl = document.getElementById('external-events');
		new FullCalendar.Draggable(containerEl, {
		  itemSelector: '.external-event',
		  eventData: function(eventEl) {
			return {
			  title: eventEl.innerText.trim()
			}
		  }
		 
		});

		//// the individual way to do it
		// var containerEl = document.getElementById('external-events-list');
		// var eventEls = Array.prototype.slice.call(
		//   containerEl.querySelectorAll('.fc-event')
		// );
		// eventEls.forEach(function(eventEl) {
		//   new FullCalendar.Draggable(eventEl, {
		//     eventData: {
		//       title: eventEl.innerText.trim(),
		//     }
		//   });
		// });

		/* initialize the calendar
		-----------------------------------------------------------------*/

		var calendarEl = document.getElementById('calendar');
		var calendar = new FullCalendar.Calendar(calendarEl, {
		  headerToolbar: {
			left: 'prev,next today',
			center: 'title',
			right: 'dayGridMonth,timeGridWeek,timeGridDay'
		  },
		  
		  selectable: true,
		  selectMirror: true,
		  select: function(arg) {
			$('#add-category').modal('show');
			if (title) {
			  calendar.addEvent({
				title: title,
				start: arg.start,
				end: arg.end,
				allDay: arg.allDay
			  })
			}
			calendar.unselect()
		  },
		  
		  editable: true,
		  droppable: true, // this allows things to be dropped onto the calendar
		  drop: function(arg) {
			// is the "remove after drop" checkbox checked?
			if (document.getElementById('drop-remove').checked) {
			  // if so, remove the element from the "Draggable Events" list
			  arg.draggedEl.parentNode.removeChild(arg.draggedEl);
			}
		  },
		  initialDate: '2021-02-13',
			  weekNumbers: true,
			  navLinks: true, // can click day/week names to navigate views
			  editable: true,
			  selectable: true,
			  nowIndicator: true,
		   events: [
				{
				  title: 'All Day Event',
				  start: '2021-02-01'
				},
				{
				  title: 'Long Event',
				  start: '2021-02-07',
				  end: '2021-02-10',
				  className: "bg-danger"
				},
				{
				  groupId: 999,
				  title: 'Repeating Event',
				  start: '2021-02-09T16:00:00'
				},
				{
				  groupId: 999,
				  title: 'Repeating Event',
				  start: '2021-02-16T16:00:00'
				},
				{
				  title: 'Conference',
				  start: '2021-02-11',
				  end: '2021-02-13',
				  className: "bg-danger"
				},
				{
				  title: 'Meeting',
				  start: '2021-02-12T10:30:00',
				  end: '2021-02-12T12:30:00',
				  className:"bg-info"
				},
				{
				  title: 'Lunch',
				  start: '2021-02-12T12:00:00'
				},
				{
				  title: 'Meeting',
				  start: '2021-04-12T14:30:00'
				},
				{
				  title: 'Happy Hour',
				  start: '2021-07-12T17:30:00'
				},
				{
				  title: 'Dinner',
				  start: '2021-02-12T20:00:00',
				  className: "bg-warning"
				},
				{
				  title: 'Birthday Party',
				  start: '2021-02-13T07:00:00',
				  className: "bg-secondary"
				},
				{
				  title: 'Click for Google',
				  url: 'http://google.com/',
				  start: '2021-02-28'
				}
			  ]
		});
		calendar.render();

	  });
	</script>