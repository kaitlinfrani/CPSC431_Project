// Helper function to convert a time string to the total number of minutes
function timeToMinutes(time) {
  const [hours, minutes] = time.split(":").map(Number);
  return hours * 60 + minutes;
}

// Helper function to convert total number of minutes to a time string
function minutesToTime(minutes) {
  const hours = Math.floor(minutes / 60);
  const minutesPart = minutes % 60;
  return `${hours.toString().padStart(2, "0")}:${minutesPart
    .toString()
    .padStart(2, "0")}`;
}

function isTimeSlotScheduled(date, startTime) {
  console.log("Checking appointment date:", date, "start time:", startTime);
  for (const appointment of acceptedAppointments) {
    console.log(
      "Existing appointment date:",
      appointment.appointment_date,
      "start time:",
      appointment.start_time,
      "status:",
      appointment.status
    );
    // Extract the "HH:mm" part of the appointment start time
    const appointmentStartTime = appointment.start_time.slice(0, 5);
    if (
      appointment.appointment_date === date &&
      appointmentStartTime === startTime &&
      appointment.status === "accepted"
    ) {
      console.log("Appointment found:", appointment);
      return true;
    }
  }
  console.log("No appointment found");
  return false;
}

// Helper function to convert 24-hour time format to 12-hour format with AM/PM
function format12HourTime(time) {
  return new Date("1970-01-01T" + time + "Z").toLocaleTimeString("en-US", {
    hour: "2-digit",
    minute: "2-digit",
    timeZone: "UTC",
  });
}

function generateTimeOptions() {
  const startSelect = document.getElementById("start_time");

  // Get the selected day
  const [year, month, day] = document
    .getElementById("appointment_date")
    .value.split("-");

  // Format the appointment date as yyyy-mm-dd
  const appointmentDate = `${year}-${month.padStart(2, "0")}-${day.padStart(
    2,
    "0"
  )}`;
  const selectedDay = new Date(year, month - 1, day).toLocaleDateString(
    "en-US",
    { weekday: "long" }
  );

  console.log("Selected day:", selectedDay);

  // Add a default option to the start time select element
  const defaultOption = document.createElement("option");
  defaultOption.value = "";
  defaultOption.text = "Please select a time";
  defaultOption.selected = true;
  defaultOption.disabled = true;
  startSelect.add(defaultOption);

  // Loop through the provider's availability
  for (const availability of window.providerAvailability) {
    console.log("Availability day:", availability.day_of_week);
    if (availability.day_of_week === selectedDay) {
      let startTimeMinutes = timeToMinutes(availability.start_time);
      const endTimeMinutes = timeToMinutes(availability.end_time);

      console.log("Start time minutes:", startTimeMinutes);
      console.log("End time minutes:", endTimeMinutes);

      // Generate options for start time
      while (startTimeMinutes + 60 <= endTimeMinutes) {
        const startTimeStr = minutesToTime(startTimeMinutes);
        const endTimeStr = minutesToTime(startTimeMinutes + 60);
        const startTime12Hour = format12HourTime(startTimeStr);

        const startTimeOption = document.createElement("option");
        startTimeOption.value = startTimeStr;
        startTimeOption.text = startTime12Hour;

        if (isTimeSlotScheduled(appointmentDate, startTimeStr)) {
          startTimeOption.text = startTime12Hour + " (unavailable)";
          startTimeOption.disabled = true;
        }

        startSelect.add(startTimeOption);
        startTimeMinutes += 60;
      }
    }
  }
}

// Call the generateTimeOptions function when the appointment date changes
document.getElementById("appointment_date").addEventListener("change", () => {
  // Clear the existing options
  document.getElementById("start_time").innerHTML = "";

  // Generate new options based on the selected date
  generateTimeOptions();
});

document.getElementById("cancel_button").addEventListener("click", () => {
  window.location.href = "../client-landing.php";
});
