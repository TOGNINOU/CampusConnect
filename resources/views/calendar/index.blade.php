@extends('layouts.app')

@section('content')
<div class="bg-white shadow rounded p-6">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold">Calendrier des réservations</h1>
        <div>
            <label class="text-sm mr-2">Salle</label>
            <select id="room-select" class="border rounded px-2 py-1">
                <option value="">Toutes les salles</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div id="calendar" class="bg-white" style="min-height: 600px;"></div>
</div>

<!-- FullCalendar (CDN) -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var roomSelect = document.getElementById('room-select');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'timeGridDay,timeGridWeek,dayGridMonth'
        },
        events: function(info, successCallback, failureCallback) {
            var params = new URLSearchParams();
            if (roomSelect.value) params.append('room_id', roomSelect.value);
            fetch('/reservations/events?'+params.toString())
                .then(r => r.json())
                .then(data => successCallback(data))
                .catch(e => failureCallback(e));
        },
    });

    calendar.render();

    roomSelect.addEventListener('change', function() {
        calendar.refetchEvents();
    });
});
</script>

@endsection
