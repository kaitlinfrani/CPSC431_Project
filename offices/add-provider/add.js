function addFields() {
  // Create a new div
  var newDiv = document.createElement("div");
  newDiv.className = "availability-inputs";

  // Add day of week dropdown
  var dayOfWeekLabel = document.createElement("label");
  dayOfWeekLabel.textContent = "Day of Week:";
  var dayOfWeekSelect = document.createElement("select");
  dayOfWeekSelect.name = "day_of_week[]";
  dayOfWeekSelect.required = true;
  // Add options here
  dayOfWeekSelect.innerHTML = `<option value="">Select a day</option>
    <option value="Monday">Monday</option>
    <option value="Tuesday">Tuesday</option>
    <option value="Wednesday">Wednesday</option>
    <option value="Thursday">Thursday</option>
    <option value="Friday">Friday</option>
    <option value="Saturday">Saturday</option>
    <option value="Sunday">Sunday</option>`;
  newDiv.appendChild(dayOfWeekLabel);
  newDiv.appendChild(dayOfWeekSelect);

  // Add start time input
  var startTimeLabel = document.createElement("label");
  startTimeLabel.textContent = "Start Time:";
  var startTimeInput = document.createElement("input");
  startTimeInput.type = "time";
  startTimeInput.name = "start_time[]";
  startTimeInput.required = true;
  newDiv.appendChild(startTimeLabel);
  newDiv.appendChild(startTimeInput);

  // Add end time input
  var endTimeLabel = document.createElement("label");
  endTimeLabel.textContent = "End Time:";
  var endTimeInput = document.createElement("input");
  endTimeInput.type = "time";
  endTimeInput.name = "end_time[]";
  endTimeInput.required = true;
  newDiv.appendChild(endTimeLabel);
  newDiv.appendChild(endTimeInput);

  // Add remove button
  var removeButton = document.createElement("button");
  removeButton.textContent = "Remove";
  removeButton.type = "button";
  removeButton.className = "remove-button"; // new line
  removeButton.onclick = function () {
    this.parentNode.parentNode.removeChild(this.parentNode);
  };
  newDiv.appendChild(removeButton);

  // Append the new div to the parent div
  document.getElementById("availabilityFields").appendChild(newDiv);
}
