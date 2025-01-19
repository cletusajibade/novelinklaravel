<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calendar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                {{-- <x-alert type='success' :message="session('success')" /> --}}
                <x-bladewind::alert type="success" shade="dark">
                    {{ session('success') }}
                </x-bladewind::alert>
            @endif
             @if (session('warning'))
                {{-- <x-alert type='success' :message="session('success')" /> --}}
                <x-bladewind::alert type="warning" shade="dark">
                    {{ session('warning') }}
                </x-bladewind::alert>
            @endif
            <div class="flex flex-col md:flex-row md:space-x-4 space-y-4 md:space-y-0">

                {{-- Calendar component --}}
                <div class="flex flex-col md:w-[70%] bg-blue-200 px-4 pt-4 rounded-lg shadow ">
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
                        class="my-[10px] mx-auto py-1 px-5 bg-blue-600 text-white rounded hover:bg-blue-700 active:bg-blue-600 text-sm font-bold">
                        Today
                    </button>
                </div>

                {{-- Set Availability --}}
                <div class="flex flex-col md:w-[30%] bg-white px-8 py-4 rounded-lg shadow-md">
                    <h2 id="availability" class="text-xl font-semibold text-gray-800 mb-6">Set Availability
                    </h2>
                    <form action="{{ route('time-slots.store') }}" method="post">
                        @csrf
                        <div class="flex items-center mb-4">
                            <span id="set-availability">Appointment Duration:</span>
                            <label class="flex items-center mt-1">
                                <input type="number" min="0.5" max="5" step="0.5" name="duration"
                                    value="{{ old('duration', 1) }}" class="w-12 h-4 mr-2 ml-2 rounded-sm pr-0 pl-1" />
                                hour
                            </label>
                            @error('duration')
                                <div class="text-red-500 error-message text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Start Date and Time -->
                        <div class="mb-4">
                            <label for="start-date" class="block text-sm font-medium text-gray-700 mb-1">Start
                                Date</label>
                            <input type="date" id="start-date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('start_date')
                                <div class="text-red-500 error-message text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="start-time" class="block text-sm font-medium text-gray-700 mb-1">Start
                                Time</label>
                            <input type="time" id="start-time" name="start_time"
                                value="{{ old('start_time', '09:00') }}" min="09:00" max="18:00"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('start_time')
                                <div class="text-red-500 error-message text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- End Date and Time -->
                        <div class="mb-4">
                            <label for="end-date" class="block text-sm font-medium text-gray-700 mb-1">End
                                Date</label>
                            <input type="date" id="end-date" name="end_date" value="{{ old('end_date', date('Y-m-d')) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('end_date')
                                <div class="text-red-500 error-message text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="end-time" class="block text-sm font-medium text-gray-700 mb-1">End
                                Time</label>
                            <input type="time" id="end-time" name="end_time" value="{{ old('end_time', '18:00') }}"
                                min="09:00" max="18:00"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('end_time')
                                <div class="text-red-500 error-message text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="flex items-center mb-2 mt-1">
                                <input type="checkbox" name="exclude_weekends" value="1" class="mr-2"
                                    {{ old('exclude_weekends') ? 'checked' : '' }}>
                                Exclude Weekends (Sat - Sun)
                            </label>
                            @error('exclude_weekends')
                                <div class="text-red-500 error-message text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Save
                            Schedule</button>
                    </form>
                </div>

                {{-- Calendar Modal, hidden by default --}}
                <div id="event-modal" style="margin-left: 0px;"
                    class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-lg font-bold mb-2">Set Availability</h3>
                        <form action="{{ route('calendar.store') }}" method="post">
                            @csrf
                            <p id="selected-date" class="mb-4"></p>
                            <input type="text" id="event-name" name="event_name" placeholder="Event Name"
                                class="w-full p-2 border rounded mb-4">
                            <label class="flex items-center mb-4">
                                <input type="checkbox" name="timeslot[]" value="12:30" class="mr-2">
                                9:00 - 10:00 AM MST
                            </label>
                            <label class="flex items-center mb-4">
                                <input type="checkbox" name="timeslot[]" value="12:30" class="mr-2">
                                10:00 - 11:00 AM MST
                            </label>
                            <label class="flex items-center mb-4">
                                <input type="checkbox" name="timeslot[]" value="12:30" class="mr-2">
                                11:00 - 12:00 PM MST
                            </label>
                            <label class="flex items-center mb-4">
                                <input type="checkbox" name="timeslot[]" value="12:30" class="mr-2">
                                12:00 - 1:00 PM MST
                            </label>
                            <div class="flex justify-evenly gap-2">
                                <button id="save-event" type="submit"
                                    class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
                                <button id="close-modal"
                                    class="w-full px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>

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
        const startDate = document.getElementById("start-date");
        const startTime = document.getElementById('start-time');
        const endTime = document.getElementById('end-time');

        let currentDate = new Date();
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
                emptyCell.classList.add("bg-[#F8F8F8]");
                calendar.appendChild(emptyCell);
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const cell = document.createElement("div");
                cell.textContent = day;
                cell.classList.add("bg-white", "text-center", "p-4", "md:p-7", "cursor-pointer", "hover:bg-blue-100");

                const fullDate = `${year}-${month + 1}-${day}`;

                if (events[fullDate]) {
                    cell.classList.add("bg-red-200", "cursor-not-allowed");
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
                    if (!cell.classList.contains("bg-red-200")) {
                        modal.classList.remove("hidden");
                        selectedDate.textContent = `Date: ${fullDate}`;
                        eventName.value = "";
                        saveEvent.dataset.date = fullDate;
                    }
                });

                calendar.appendChild(cell);
            }
        }

        function setDefaultStartDate(startDate) {
            // Get the current date
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are zero-based
            const day = String(today.getDate()).padStart(2, '0');

            // Format the date as YYYY-MM-DD
            const currentDate = `${year}-${month}-${day}`;
            startDate.value = currentDate;
        }

        function setStartTime(startTime) {
            // const now = new Date();
            // const hours = String(now.getHours()).padStart(2, '0');
            // const minutes = String(now.getMinutes()).padStart(2, '0');
            // startTime.value = `${hours}:${minutes}`;
            startTime.value = '09:00';
        }

        function setEndTime(endTime) {
            // const now = new Date();
            // const hours = String(now.getHours()).padStart(2, '0');
            // const minutes = String(now.getMinutes()).padStart(2, '0');
            endTime.value = '18:00';
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

        renderCalendar();
        // setDefaultStartDate(startDate)
        // setStartTime(startTime);
        // setEndTime(endTime);
    </script>
</x-app-layout>
