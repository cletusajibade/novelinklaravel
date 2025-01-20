<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar Booking System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex justify-center items-center h-screen bg-gray-100">
    <div class="w-11/12 max-w-2xl h-[600px] bg-white shadow-md rounded-lg flex flex-col">
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

    <div id="event-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-lg font-bold mb-2">Book Appointment</h3>
            <p id="selected-date" class="mb-4"></p>
            <input type="text" id="event-name" placeholder="Event Name" class="w-full p-2 border rounded mb-4">

            <div id="time-slots">

            </div>

            <div class="flex justify-end gap-2">
                <button id="save-event" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
                <button id="close-modal"
                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        const calendar = document.getElementById("calendar");
        const monthYear = document.getElementById("month-year");
        const prevMonth = document.getElementById("prev-month");
        const nextMonth = document.getElementById("next-month");
        const modal = document.getElementById("event-modal");
        const selectedDate = document.getElementById("selected-date");
        const eventName = document.getElementById("event-name");
        const saveEvent = document.getElementById("save-event");
        const closeModal = document.getElementById("close-modal");
        const todayButton = document.getElementById("today-button");
        const timeSlotsDiv = document.getElementById("time-slots");

        // Some colors used to style the cell
        const bgRed = "bg-red-200";
        const textGray = "text-gray-700";

        // Time slots from database
        const timeSlots = @json($time_slots);

        let currentDate = new Date();

        // Empty object to house events
        const events = {};

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
                cell.classList.add("bg-white", "text-center", "p-2", "cursor-pointer", "hover:bg-green-300");

                const fullDate = `${year}-${month + 1}-${day}`;

                if (events[fullDate]) {
                    cell.classList.add(bgRed, "cursor-not-allowed");
                }

                if (
                    day === today.getDate() &&
                    month === today.getMonth() &&
                    year === today.getFullYear()
                ) {
                    cell.classList.remove("bg-white", "hover:bg-blue-100");
                    cell.classList.add("bg-blue-600", "text-white", "font-bold");
                }

                cell.addEventListener("click", () => {
                    if (!cell.classList.contains(bgRed)) {
                        modal.classList.remove("hidden");
                        selectedDate.textContent = `Date: ${fullDate}`;
                        eventName.value = "";
                        saveEvent.dataset.date = fullDate;
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

        saveEvent.addEventListener("click", () => {
            const date = saveEvent.dataset.date;
            const name = eventName.value.trim();

            if (name) {
                events[date] = name;
                modal.classList.add("hidden");
                renderCalendar();
            } else {
                alert("Please enter an event name.");
            }
        });

        closeModal.addEventListener("click", () => {
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
            // TODO: Handle timezone effectively
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
</body>

</html>
