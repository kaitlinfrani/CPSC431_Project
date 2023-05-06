// Get the message container element
const messageContainer = document.getElementById("message-container");

// Function to show a message in the message container
function showMessage(message, isError) {
  // Set the background color of the message container based on whether it's an error or success message
  messageContainer.style.backgroundColor = isError ? "#f44336" : "#4CAF50";
  messageContainer.style.display = "block";
  messageContainer.innerHTML = message;

  // Hide the message container after 3 seconds
  setTimeout(() => {
    messageContainer.style.display = "none";
  }, 3000);
}

// Function to accept an appointment
function acceptAppointment(appointmentId) {
  // Send a POST request to the server to update the appointment status
  fetch("update_appointment.php", {
    method: "POST",
    body: JSON.stringify({ id: appointmentId, status: "accepted" }),
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((response) => response.json())
    .then((data) => {
      // Show a success message
      showMessage(data.message, false);
      // Reload the page to show the updated list of appointments
      location.reload();
    })
    .catch((error) => {
      // Show an error message
      showMessage("Error accepting appointment: " + error, true);
    });
}

// Function to reject an appointment
function rejectAppointment(appointmentId) {
  // Send a POST request to the server to update the appointment status
  fetch("update_appointment.php", {
    method: "POST",
    body: JSON.stringify({ id: appointmentId, status: "rejected" }),
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((response) => response.json())
    .then((data) => {
      // Show a success message
      showMessage(data.message, false);
      // Reload the page to show the updated list of appointments
      location.reload();
    })
    .catch((error) => {
      // Show an error message
      showMessage("Error rejecting appointment: " + error, true);
    });
}
