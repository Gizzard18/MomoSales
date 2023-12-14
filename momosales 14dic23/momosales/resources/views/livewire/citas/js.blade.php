<script>

	document.addEventListener('livewire:load', function () {
		Livewire.on('actionChanged', function () {
			var events = @json($events);
			var calendarEl = document.getElementById('calendar');
			var calendar = new FullCalendar.Calendar(calendarEl, {
				headerToolbar: {
					left: 'prev,next today',
					center: 'title',
					right: 'dayGridMonth,timeGridWeek,timeGridDay'
				},
				allDaySlot:false,
				slotDuration:'00:15:00',
				slotLabelInterval:"01:00",
				height:970,
				firstDay:1,
				initialView: 'timeGridDay',
   		 		timeZone: 'America/Mexico_City',
				locale: 'es-us',
				slotMinTime: '08:00:00', // Hora de inicio que quieres mostrar
				slotMaxTime: '18:00:00', // Hora de fin que quieres mostrar
				
				eventTimeFormat: {
					hour: '2-digit',
					minute:'2-digit',
					meredium: 'short'
				},
				
					
				selectable: true,
				selectMirror: true,
				select: function(arg) {
    				Livewire.emit('select', arg);

					$('#saveBtn').click(function(){
						var title = $('#title').val();
						var start_date = moment(start).format('YYYY-MM-DD HH-MM-SS');
						var end_date = moment(end).format('YYYY-MM-DD HH-MM-SS');

						$.ajax({
							url:"{{ route('citas.citas.store') }}",
							type:"POST",
							dataType:'json',
							data:{ title, start_date, end_date }
						});
					});
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
				initialDate: '2024-01-01',
					weekNumbers: true,
					navLinks: true, // can click day/week names to navigate views
					editable: true,
					selectable: true,
					nowIndicator: true,
					events: events
			});
			calendar.render();
		});
	});

    
	document.addEventListener('DOMContentLoaded', function() {
		/* initialize the external events
		-----------------------------------------------------------------*/

    var events = @json($events);
		var calendarEl = document.getElementById('calendar');
		var calendar = new FullCalendar.Calendar(calendarEl, {
			headerToolbar: {
				left: 'prev,next today',
				center: 'title',
				right: 'dayGridMonth,timeGridWeek,timeGridDay'
			},
			allDaySlot:false,
			slotDuration:'00:15:00',
			slotLabelInterval:"01:00",
			height:970,
			firstDay:1,
			initialView: 'timeGridDay',
			locale: 'es-us',
			slotMinTime: '08:00:00', // Hora de inicio que quieres mostrar
			slotMaxTime: '18:00:00', // Hora de fin que quieres mostrar
			
			eventTimeFormat: {
				hour: '2-digit',
				minute:'2-digit',
				meredium: 'short'
			},
			
				
			selectable: true,
   		 	timeZone: 'America/Mexico_City',
			selectMirror: true,
			select: function(arg) {
    			Livewire.emit('select', arg);

				$('#saveBtn').click(function(){
					var title = $('#title').val();
					var start_date = moment(start).format('YYYY-MM-DD HH-MM-SS');
					var end_date = moment(end).format('YYYY-MM-DD HH-MM-SS');

					$.ajax({
						url:"{{ route('citas.citas.store') }}",
						type:"POST",
						dataType:'json',
						data:{ title, start_date, end_date }
					});
				});
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
			initialDate: '2024-01-01',
				weekNumbers: true,
				navLinks: true, // can click day/week names to navigate views
				editable: true,
				selectable: true,
				nowIndicator: true,
				events: events
		});
		calendar.render();
		flatpickr(document.getElementsByClassName('flatpickr'),{
            enableTime: false,
            dateFormat: 'Y-m-d',
            locale: {
                firstDateofWeek:1,
                weekdays: {
                    shorthand: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
                    longhand: [
                    "Domingo",
                    "Lunes",
                    "Martes",
                    "Miércoles",
                    "Jueves",
                    "Viernes",
                    "Sábado",
                    ],
                },    
                months: {
                    shorthand: [
                    "Ene",
                    "Feb",
                    "Mar",
                    "Abr",
                    "May",
                    "Jun",
                    "Jul",
                    "Ago",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dic",
                    ],
                    longhand: [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre",
                    ],
                }
            }
        })

	  });
	</script>