@if (session('isRescheduling'))
@php $isRescheduling = session('isRescheduling'); @endphp
@endif
@if (session('appointmentExists'))
@php $appointmentExists = session('appointmentExists');@endphp
@endif

    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Calendar Booking System</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    <!-- Load Bundled Scripts -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-figtree flex justify-center items-center h-screen bg-gray-100">

<div class="w-[90%] md:w-[70%] md:max-w-5xl flex flex-col gap-4">
    <div id="successMessage" style="display: none;"></div>
    <div id="warningMessage" style="display: none;"></div>
    <div id="errorMessage" style="display: none;"></div>

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
        @if (isset($isRescheduling))
            <h1 class="text-xl">Reschedule your appointment by selecting from the available time slots</h1>
            <div class="border rounded-md mt-3 p-3">
                <div class="underline">Current Appointment Schedule</div>
                <div id="currentDate"></div>
                <div id="currentTime"></div>
            </div>
        @elseif (isset($appointmentExists))
            <h1 class="text-xl">You already have a scheduled appointment. You can reschedule your with this link
            </h1>
            <div class="border rounded-md mt-3 p-3">
                <div class="underline">Current Appointment Schedule</div>
                <div id="currentDate"></div>
                <div id="currentTime"></div>
            </div>
        @else
            <h1 class="text-2xl">Book appointment by selecting from the available time slots</h1>
        @endif
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

        <div class="flex items-center justify-center border border-gray-200 ">
            <button id="today-button"
                    class="my-[10px] mx-auto py-2 px-5 bg-blue-600 text-white rounded hover:bg-blue-700 text-[13px]">
                Today
            </button>
        </div>
    </div>
    <p>Note: Available dates are marked with <span class="text-green-600 font-bold">green</span> or <span
            class="text-white font-bold bg-black">white</span> text and an <span class="underline">underline</span>
    </p>
</div>

<div id="event-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-lg font-bold mb-2">Book Appointment</h3>
        <p id="selected-date" class="mb-2"></p>
        <p class="mb-3">Select from the available meeting times</p>
        <form id="time-slots-form">
            @csrf
            {{-- the time slot elements are dynamically generated and appended to this div --}}
            <div id="time-slots"></div>
            <div class="flex justify-between gap-2">
                <button id="book-time" class="w-2/4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Book
                    Time
                </button>
                <button id="close-modal"
                        class="w-2/4 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Cancel
                </button>
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
    const currentAppointmentDate = document.getElementById("currentDate");
    const currentAppointmentTime = document.getElementById("currentTime");
    const successMessageDiv = document.getElementById('successMessage');
    const warningMessageDiv = document.getElementById('warningMessage');
    const errorMessageDiv = document.getElementById('errorMessage');

    // Set the CSRF token for all Axios requests
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute(
        'content');

    // Get the client's timezone
    const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    // Get the client's locale
    const locale = navigator.language || navigator.userLanguage;

    // Time slots from database
    const timeSlotData = @json($time_slots);

    // Variable $current_appointment only exists during rescheduling, it does not exists during fresh appointment creation.
    const currentAppointment = @json($current_appointment ?? null);
    if (currentAppointment !== null) {
        const convertedCurrentTime = mstToClientTz(currentAppointment.start_time, timezone, locale);
        currentAppointmentDate.innerHTML = `Date: ${currentAppointment.start_date}`;
        currentAppointmentTime.innerHTML = `Time: ${convertedCurrentTime}`;
    }

    // Get the current route
    const route = window.location.href;

    let currentDate = new Date();

    // Empty object to house days with appointments
    const daysWithApointments = {};

    function renderCalendar() {
        calendar.innerHTML = "";
        const month = currentDate.getMonth();
        const year = currentDate.getFullYear();
        monthYear.textContent = `${currentDate.toLocaleString('default', {month: 'long'})} ${year}`;

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
            cell.classList.add("bg-white", "text-center", "p-4", "md:p-4", "cursor-pointer", "hover:bg-green-200");

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
                let formattedDate;
                if (!cell.classList.contains("cursor-not-allowed")) {
                    // if (!cell.classList.contains("bg-red-200")) {
                    // Build a Date object directly so we keep the selected day in the client’s timezone.
                    const cellDate = new Date(year, month, day);
                    formattedDate = formatDateToClientLocale(cellDate, timezone, locale)
                    modal.classList.remove("hidden");
                    selectedDate.textContent = `Date: ${formattedDate}`;
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
        // const selectedTimeSlot = formData.get('timeslots') || null;
        const slotId = formData.get('timeslotId') || 0;
        const meetingDuration = formData.get('duration') || 1;

        // Grab the checked radio's sibling label so we display the time the user actually picked.
        const selectedRadio = document.querySelector('input[name="timeslotId"]:checked');
        const timeText = selectedRadio?.nextElementSibling?.textContent || '';

        if (slotId > 0) {
            if (confirm(`Are you sure you want to book ${timeText} for your appointment?`)) {

                modal.classList.add("hidden");

                const data = {
                    slotId: slotId,
                    date: bookTimeButton.dataset.date,
                    timezone: timezone,
                    locale: locale
                };

                // Send POST request
                axios.post(route, data)
                    .then(response => {
                        successMessageDiv.innerHTML =
                            `<x-bladewind::alert type="success" shade="dark">
                                ${response.data.message}
                            </x-bladewind::alert>`;
                        successMessageDiv.style.display = 'block';

                        if (response.data.redirect) {
                            window.location.href = response.data.redirect_url;
                        }
                    })
                    .catch(error => {
                        if (error.response) {
                            // Server responded with a status code
                            console.error('Error:', error.response.data.error);

                            if (error.response.data.redirect) {
                                if (error.response.data.delay_redirect) {
                                    errorMessageDiv.innerHTML =
                                        `<x-bladewind::alert type="error" shade="dark">
                                            ${error.response.data.error}
                                        </x-bladewind::alert>`;
                                    errorMessageDiv.style.display = 'block';

                                    window.setTimeout(function () {
                                        window.location.href = error.response.data.redirect_url;
                                    }, 5000);
                                } else {
                                    window.location.href = error.response.data.redirect_url;
                                }
                            } else {
                                errorMessageDiv.innerHTML =
                                    `<x-bladewind::alert type="error" shade="dark">
                                        ${error.response.data.error}
                                    </x-bladewind::alert>`;
                                errorMessageDiv.style.display = 'block';
                            }

                        } else if (error.request) {
                            // Request was made but no response received
                            console.error('No response received:', error.request);
                            errorMessageDiv.innerHTML =
                                `<x-bladewind::alert type="error" shade="dark">Error processing your appointment request. Contact us for further help.</x-bladewind::alert>`;
                            errorMessageDiv.style.display = 'block';
                        } else {
                            // Something else caused the error
                            console.error('Error:', error.message);
                            errorMessageDiv.innerHTML =
                                `<x-bladewind::alert type="error" shade="dark">${error.message}</x-bladewind::alert>`;
                            errorMessageDiv.style.display = 'block';
                        }
                    });

                renderCalendar();
            }
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
        const slotsForDate = timeSlotData.filter(slot => slot.start_date ===
            formatDate(calendarDay).toLocaleString());

        if (slotsForDate.length == 0) {
            cell.classList.remove("cursor-pointer", "bg-white");
            cell.classList.add("bg-red-200", "cursor-not-allowed", "text-gray-700", "hover:bg-red-300");
        } else {
            cell.classList.add("font-bold", "text-green-600", "underline", "underline-offset-7");
        }

        if (slotsForDate.length == 0 && (day === today.getDate() &&
            month === today.getMonth() &&
            year === today.getFullYear())) {
            cell.classList.remove("bg-red-200", "text-gray-700", "hover:bg-blue-100");
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
        // clear time slots from previous iteration.
        timeSlotsDiv.innerHTML = '';

        const calendarDay = new Date(year, month, day);
        const timeSlots = timeSlotData.filter(slot => slot.start_date === formatDate(calendarDay).toLocaleString());
        if (timeSlots.length > 0) {
            timeSlots.forEach(slot => {
                if (slot.start_date === formatDate(calendarDay).toLocaleString()) {
                    const convertedTime = mstToClientTz(slot.start_time, timezone, locale);
                    const newLabel = document.createElement('label');
                    newLabel.classList.add("flex", "items-center", "mb-4");
                    newLabel.innerHTML =
                        `<div>
                                <input type="radio" id="${slot.start_time}" name="timeslotId" value="${slot.id}" class="mr-2">
                                <label for="${slot.start_time}" id="time-label-${slot.id}">${convertedTime}</label>
                            </div>`
                    timeSlotsDiv.appendChild(newLabel)
                }
            });
        }
    }

    function mstToClientTz(mstTime, clientTimezone, locale) {
        // Original time in MST (Mountain Standard Time) - no seconds part (H:i format)
        mstTime = removeSeconds(mstTime);

        // Get the current date of the client
        const currentDate = new Date();
        const currentDateString = currentDate.toISOString().split('T')[0]; // Format: YYYY-MM-DD

        // Combine the current date with the MST time string
        const mstDateTimeString = `${currentDateString}T${mstTime}:00-07:00`; // Assuming MST (UTC -7)

        // Convert the MST time to a JavaScript Date object
        const mstDate = new Date(mstDateTimeString);

        // Convert MST time to the client's time zone using toLocaleString
        const options = {
            hour: '2-digit',
            minute: '2-digit',
            timeZone: clientTimezone
        };
        const convertedTime = mstDate.toLocaleString(locale, options);

        // Get the client's timezone abbreviation (automatically adjusts for DST (daylight saving))
        const clientTimeAbbr = new Date().toLocaleString(locale, {
            timeZone: clientTimezone,
            timeZoneName: 'short'
        }).split(' ').pop();

        // Return the converted time along with the timezone abbreviation
        return convertedTime + " " + clientTimeAbbr;
    }

    function removeSeconds(timeWithSeconds) {
        // Split the time string into hours, minutes, and seconds
        const [hours, minutes] = timeWithSeconds.split(':');
        return `${hours}:${minutes}`;
    }

    function formatDateToClientLocale(date, clientTimezone, locale) {
        // We already have a Date object built with the client's timezone, so formatting it directly
        // prevents UTC conversion from shifting the day backwards (e.g., showing the previous day).
        const options = {
            year: 'numeric',
            month: 'long', // Full month name
            day: 'numeric',
            timeZone: clientTimezone // Ensure it respects the client's timezone
        };

        return date.toLocaleDateString(locale, options);
    }

    renderCalendar();
</script>
{{-- @include('includes.hide-bw-alert') --}}
</body>

</html>
