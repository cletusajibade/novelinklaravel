 <div class="calendar-container">
     <div class="calendar-header">
         <button id="prev-month">&#9664;</button>
         <h2 id="month-year"></h2>
         <button id="next-month">&#9654;</button>
     </div>
     <div class="calendar-days-grid">
         <div class="calendar-days-cell">Sun</div>
         <div class="calendar-days-cell">Mon</div>
         <div class="calendar-days-cell">Tue</div>
         <div class="calendar-days-cell">Wed</div>
         <div class="calendar-days-cell">Thu</div>
         <div class="calendar-days-cell">Fri</div>
         <div class="calendar-days-cell">Sat</div>
     </div>
     <div class="calendar-grid" id="calendar"></div>
     <button class="today-button" id="today-button">Today</button>
 </div>

 <div id="event-modal">
     <div class="modal-content">
         <h3>Book Appointment</h3>
         <p id="selected-date"></p>
         <input type="text" id="event-name" placeholder="Event Name" />
         <label>
             <input type="checkbox" name="timeslot[]" value="12:30" />
             12:30 - 1:00 PM
         </label>
         <button id="save-event">Save</button>
         <button class="close" id="close-modal">Cancel</button>
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
             emptyCell.classList.add("calendar-cell");
             calendar.appendChild(emptyCell);
         }

         for (let day = 1; day <= daysInMonth; day++) {
             const cell = document.createElement("div");
             cell.textContent = day;
             cell.classList.add("calendar-cell");

             const fullDate = `${year}-${month + 1}-${day}`;

             if (events[fullDate]) {
                 cell.classList.add("booked");
             }

             if (
                 day === today.getDate() &&
                 month === today.getMonth() &&
                 year === today.getFullYear()
             ) {
                 cell.classList.add("today");
             }

             cell.addEventListener("click", () => {
                 if (!cell.classList.contains("booked")) {
                     modal.style.display = "flex";
                     selectedDate.textContent = `Date: ${fullDate}`;
                     eventName.value = "";
                     saveEvent.dataset.date = fullDate;
                 }
             });

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
             modal.style.display = "none";
             renderCalendar();
         } else {
             alert("Please enter an event name.");
         }
     });

     closeModal.addEventListener("click", () => {
         modal.style.display = "none";
     });

     renderCalendar();
 </script>
