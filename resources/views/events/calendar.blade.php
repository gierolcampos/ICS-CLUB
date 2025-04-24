@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css">
<style>
    .fc-event {
        cursor: pointer;
    }
    .fc-event-title {
        font-weight: 500;
    }
    .fc-today-button {
        text-transform: capitalize;
    }
    .fc-event-past {
        opacity: 0.7;
    }
    .tooltip {
        position: absolute;
        z-index: 1070;
        display: block;
        margin: 0;
        font-family: inherit;
        font-style: normal;
        font-weight: 400;
        line-height: 1.5;
        text-align: left;
        text-decoration: none;
        text-shadow: none;
        text-transform: none;
        letter-spacing: normal;
        word-break: normal;
        word-spacing: normal;
        white-space: normal;
        line-break: auto;
        font-size: 0.875rem;
        word-wrap: break-word;
        opacity: 0;
    }
    .tooltip.show {
        opacity: 0.9;
    }
    .tooltip .tooltip-inner {
        max-width: 200px;
        padding: 0.25rem 0.5rem;
        color: #fff;
        text-align: center;
        background-color: #000;
        border-radius: 0.25rem;
    }
</style>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800">Event Calendar</h1>
                        <p class="mt-1 text-sm text-gray-600">View all club events in calendar format</p>
                    </div>
                    <div class="mt-4 md:mt-0 flex space-x-2">
                        <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <i class="fas fa-list mr-1"></i> List View
                        </a>
                        @auth
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('events.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <i class="fas fa-plus mr-1"></i> New Event
                            </a>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Event Detail Modal -->
                <div id="eventModal" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-event-title"></h3>
                                        <div class="mt-4 text-sm text-gray-500 space-y-2">
                                            <div class="flex items-center">
                                                <i class="far fa-calendar-alt w-5 mr-2 text-indigo-500"></i>
                                                <span id="modal-event-date"></span>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="far fa-clock w-5 mr-2 text-indigo-500"></i>
                                                <span id="modal-event-time"></span>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-map-marker-alt w-5 mr-2 text-indigo-500"></i>
                                                <span id="modal-event-location"></span>
                                            </div>
                                            <div id="modal-event-participants-container" class="flex items-center">
                                                <i class="fas fa-users w-5 mr-2 text-indigo-500"></i>
                                                <span id="modal-event-participants"></span>
                                            </div>
                                            <div class="mt-4">
                                                <p class="text-sm font-medium text-gray-700 mb-1">Description</p>
                                                <p id="modal-event-description" class="text-sm text-gray-500"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <a href="#" id="modal-view-link" class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    View Details
                                </a>
                                <button type="button" id="modal-close-button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calendar Container -->
                <div id="calendar" class="bg-white rounded-lg shadow overflow-hidden"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var eventModal = document.getElementById('eventModal');
        var modalCloseButton = document.getElementById('modal-close-button');
        var modalTitle = document.getElementById('modal-event-title');
        var modalDate = document.getElementById('modal-event-date');
        var modalTime = document.getElementById('modal-event-time');
        var modalLocation = document.getElementById('modal-event-location');
        var modalParticipantsContainer = document.getElementById('modal-event-participants-container');
        var modalParticipants = document.getElementById('modal-event-participants');
        var modalDescription = document.getElementById('modal-event-description');
        var modalViewLink = document.getElementById('modal-view-link');
        
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            events: '{{ route("events.calendar.data") }}',
            eventClick: function(info) {
                var event = info.event;
                var eventData = event.extendedProps;
                
                // Format the date and time
                var startDate = new Date(event.start);
                var endDate = event.end ? new Date(event.end) : null;
                
                var dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                var timeOptions = { hour: '2-digit', minute: '2-digit' };
                
                modalTitle.textContent = event.title;
                modalDate.textContent = startDate.toLocaleDateString('en-US', dateOptions);
                
                if (endDate) {
                    modalTime.textContent = startDate.toLocaleTimeString('en-US', timeOptions) + ' - ' + 
                                          endDate.toLocaleTimeString('en-US', timeOptions);
                } else {
                    modalTime.textContent = startDate.toLocaleTimeString('en-US', timeOptions) + ' (' + eventData.duration + ' hours)';
                }
                
                modalLocation.textContent = eventData.location || 'No location specified';
                
                if (eventData.max_participants) {
                    modalParticipantsContainer.classList.remove('hidden');
                    modalParticipants.textContent = eventData.attendees_count + '/' + eventData.max_participants + ' participants';
                } else {
                    modalParticipantsContainer.classList.add('hidden');
                }
                
                modalDescription.textContent = eventData.description || 'No description available';
                modalViewLink.href = eventData.url;
                
                // Show the modal
                eventModal.classList.remove('hidden');
            },
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                meridiem: 'short'
            },
            eventDidMount: function(info) {
                // Add color based on event status
                if (info.event.extendedProps.status === 'Cancelled') {
                    info.el.style.opacity = '0.6';
                    info.el.style.textDecoration = 'line-through';
                }
                
                // Past events
                if (info.event.end && info.event.end < new Date()) {
                    info.el.classList.add('fc-event-past');
                }
            }
        });
        
        calendar.render();
        
        // Close modal when clicking the close button
        modalCloseButton.addEventListener('click', function() {
            eventModal.classList.add('hidden');
        });
        
        // Close modal when clicking outside of it
        window.addEventListener('click', function(event) {
            if (event.target === eventModal) {
                eventModal.classList.add('hidden');
            }
        });
    });
</script>
@endsection 