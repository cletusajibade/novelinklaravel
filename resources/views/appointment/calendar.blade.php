@if (session('client_id'))
    @php
        $client_id = session('client_id');
    @endphp
@endif

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Calendar Booking System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex justify-center items-center h-screen bg-gray-100">

    <div class="w-[90%] md:w-[70%] md:max-w-5xl flex flex-col gap-4">
        @if (session('success'))
            <x-bladewind::alert type="success" shade="dark">
                {{ session('success') }}
            </x-bladewind::alert>
        @endif
        @if (session('warning'))
            <x-bladewind::alert type="warning" shade="dark">
                {{ session('warning') }}
            </x-bladewind::alert>
        @endif
        @if (session('error'))
            <x-bladewind::alert type="warning" shade="dark">
                {{ session('error') }}
            </x-bladewind::alert>
        @endif
        @if ($errors->any())
            <div
                style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 0.75rem 1rem; border-radius: 0.25rem; margin-bottom: 1rem;">
                <ul style="list-style-type: none; padding: 0; margin: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="bg-white shadow-lg rounded px-2 py-4">
            <h1 class="text-3xl">Book appointment by selecting available time slot</h1>
        </div>
        <div class="flex flex-col bg-white shadow-lg rounded">
            <div class="flex justify-between items-center p-4 bg-white text-blue-600">
                <button id="prev-month" class="text-lg">&#9664;</button>
                <h2 id="month-year" class="text-xl font-bold"></h2>
                <button id="next-month" class="text-lg">&#9654;</button>
            </div>

            <div class="grid grid-cols-7 gap-px bg-gray-200 text-center font-semibold text-gray-700">
                <div class="bg-white my-[1px]">Sun</div>
                <div class="bg-white my-[1px]">Mon</div>
                <div class="bg-white my-[1px]">Tue</div>
                <div class="bg-white my-[1px]">Wed</div>
                <div class="bg-white my-[1px]">Thu</div>
                <div class="bg-white my-[1px]">Fri</div>
                <div class="bg-white my-[1px]">Sat</div>
            </div>

            <div class="grid grid-cols-7 flex-grow gap-px bg-gray-200" id="calendar"></div>

            <button id="today-button"
                class="my-[10px] mx-auto py-2 px-5 bg-blue-600 text-white rounded hover:bg-blue-700 text-[13px]">
                Today
            </button>
        </div>
    </div>

    <div id="event-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-lg font-bold mb-2">Book Appointment</h3>
            <p id="selected-date" class="mb-2"></p>
            <p class="mb-3">Select from the available meeting times</p>
            {{-- <form id="time-slots-form" action="{{ route('appointment.store') }}" method="post"> --}}
            <form id="time-slots-form">
                @csrf
                {{-- the time slot elements are dynamically generated and appended to this div --}}
                <div id="time-slots"></div>


                <div class="flex justify-between gap-2">
                    <button id="book-time" class="w-2/4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Book
                        Time</button>
                    <button id="close-modal"
                        class="w-2/4 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const calendar = document.getElementById("calendar");
        const monthYear = document.getElementById("month-year");
        const prevMonth = document.getElementById("prev-month");
        const nextMonth = document.getElementById("next-month");
        const modal = document.getElementById("event-modal");
        const selectedDate = document.getElementById("selected-date");
        const bookTimeButton = document.getElementById("book-time");
        const closeModal = document.getElementById("close-modal");
        const todayButton = document.getElementById("today-button");
        const timeSlotsDiv = document.getElementById("time-slots");
        const radioButtons = document.querySelectorAll('input[name="timeslots"]');

        // Some colors used to style the cell
        const bgRed = "bg-red-200";
        const textGray = "text-gray-700";

        // Time slots from database
        const timeSlots = @json($time_slots);

        let currentDate = new Date();

        // Empty object to house days with appointments
        const daysWithApointments = {};

        function renderCalendar() {
            calendar.innerHTML = "";
            const month = currentDate.getMonth();
            const year = currentDate.getFullYear();
            monthYear.textContent = `${currentDate.toLocaleString('default', { month: 'long' })} ${year}`;

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const today = new Date();

            for (let i = 0; i < firstDay; i++) {
                const emptyCell = document.createElement("div");
                emptyCell.classList.add("bg-gray-100");
                calendar.appendChild(emptyCell);
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const cell = document.createElement("div");
                cell.textContent = day;
                cell.classList.add("bg-white", "text-center", "p-4", "md:p-7", "cursor-pointer", "hover:bg-green-300");

                const fullDate = `${year}-${month + 1}-${day}`;

                if (
                    day === today.getDate() &&
                    month === today.getMonth() &&
                    year === today.getFullYear()
                ) {
                    cell.classList.remove("bg-white", "hover:bg-blue-100");
                    cell.classList.add("bg-blue-600", "text-white", "font-bold");
                }

                cell.addEventListener("click", () => {
                    if (!cell.classList.contains("cursor-not-allowed")) {
                        // if (!cell.classList.contains(bgRed)) {
                        modal.classList.remove("hidden");
                        selectedDate.textContent = `Date: ${fullDate}`;
                        // eventName.value = "";
                        bookTimeButton.dataset.date = fullDate;
                        setTimeSlots(year, month, day);
                    }
                });


                formatCellColors(cell, year, month, day, today);

                calendar.appendChild(cell);
            }
        }

        prevMonth.addEventListener("click", () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        });

        nextMonth.addEventListener("click", () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        });

        todayButton.addEventListener("click", () => {
            currentDate = new Date();
            renderCalendar();
        });

        bookTimeButton.addEventListener("click", (e) => {
            e.preventDefault();

            const form = document.getElementById('time-slots-form');
            const formData = new FormData(form);
            const selectedTimeSlot = formData.get('timeslots') || null;
            const meetingDuration = formData.get('duration') || 1;
            console.log(selectedTimeSlot, meetingDuration);


            if (selectedTimeSlot) {
                alert(`Is the meeting time ${selectedTimeSlot} okay for you?`);
                modal.classList.add("hidden");

                // Send client_id, date, time slot, and duration back to the server to process
                const data = {
                    clientId: @json($client_id),
                    date: bookTimeButton.dataset.date,
                    time: selectedTimeSlot,
                    duration: meetingDuration
                };

                fetch(@json(route('appointment.store')), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'), // Laravel CSRF Token
                        },
                        body: JSON.stringify(data),
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(result => {
                        console.log('Success:', result);

                        // reload the page to trigger the flashed success session message
                        location.reload(true);

                        // You can append a query string to the URL to ensure a fresh reload:
                        // - window.location.href = window.location.href + '?cache=' + new Date().getTime();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });


                renderCalendar();
            } else {
                alert("Please select a meeting time slot");
            }
        });

        closeModal.addEventListener("click", (e) => {
            e.preventDefault();
            modal.classList.add("hidden");
        });

        function formatCellColors(cell, year, month, day, today) {
            const calendarDay = new Date(year, month, day);
            const slotsForDate = timeSlots.filter(slot => slot.start_date ===
                formatDate(calendarDay).toLocaleString());

            if (slotsForDate.length == 0) {
                cell.classList.remove("cursor-pointer");
                cell.classList.add(bgRed, "cursor-not-allowed", textGray, "hover:bg-red-300");
            } else {
                cell.classList.add("font-bold", "text-green-600", "underline", "underline-offset-7");
            }

            if (slotsForDate.length == 0 && (day === today.getDate() &&
                    month === today.getMonth() &&
                    year === today.getFullYear())) {
                cell.classList.remove(bgRed, textGray, "hover:bg-blue-100");
                cell.classList.add("bg-blue-500", "text-white", "font-bold");
            }

            if (slotsForDate.length > 0 && (day === today.getDate() &&
                    month === today.getMonth() &&
                    year === today.getFullYear())) {
                cell.classList.remove("text-green-600");
                cell.classList.add("bg-blue-600", "text-white", "font-bold", "hover:text-green-600");
            }
        }

        const formatDate = (date) => {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
            const day = String(date.getDate()).padStart(2, '0');

            return `${year}-${month}-${day}`;
        };

        function setTimeSlots(year, month, day) {
            // TODO: Handle potential timezone issues effectively
            // clear time slots from previous iteration.
            timeSlotsDiv.innerHTML = '';

            const calendarDay = new Date(year, month, day);

            const slotsForDate = timeSlots.filter(slot => slot.start_date ===
                formatDate(calendarDay).toLocaleString());

            if (slotsForDate.length > 0) {
                slotsForDate.forEach(slot => {
                    if (slot.start_date === formatDate(calendarDay).toLocaleString()) {
                        const newLabel = document.createElement('label');
                        newLabel.classList.add("flex", "items-center", "mb-4");
                        newLabel.innerHTML =
                            `<div>
                                <input type="hidden" id="duration" name="duration" value="${slot.duration}">
                                <input type="radio" id="${slot.start_time}" name="timeslots" value="${slot.start_time}" class="mr-2">
                                <label for="${slot.start_time}">${slot.start_time} - ${slot.end_time}</label>
                            </div>`
                        timeSlotsDiv.appendChild(newLabel)
                    }
                });
            }
        }

        renderCalendar();
    </script>
    @include('includes.hide-bw-alert')
</body>

</html>
