// Get the message container element
const messageContainer = document.getElementById("message-container");

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
    .then((response) => response.text())
    .then((result) => {
      console.log(result);
      window.location.href = window.location.pathname + "?accepted=true"; // Redirect with the query parameter
    })
    .catch((error) => console.error("Error:", error));
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
    .then((response) => response.text())
    .then((result) => {
      console.log(result);
      window.location.href = window.location.pathname + "?rejected=true"; // Redirect with the query parameter
    })
    .catch((error) => console.error("Error:", error));
}

function showTemporaryMessage(message, duration) {
  const messageContainer = document.getElementById("message-container");
  messageContainer.innerHTML = message;
  messageContainer.style.display = "block";

  setTimeout(() => {
    messageContainer.style.display = "none";
  }, duration);
}

document.addEventListener("DOMContentLoaded", () => {
  const urlParams = new URLSearchParams(window.location.search);
  const accepted = urlParams.get("accepted");

  if (accepted === "true") {
    showTemporaryMessage("Appointment accepted successfully.", 3000);
    setTimeout(() => {
      // Remove the query parameter from the URL
      history.replaceState(null, "", window.location.pathname);
    }, 3000);
  }
});

document.addEventListener("DOMContentLoaded", () => {
  const urlParams = new URLSearchParams(window.location.search);
  const rejected = urlParams.get("rejected");

  if (rejected === "true") {
    showTemporaryMessage("Appointment rejected successfully.", 3000);
    setTimeout(() => {
      // Remove the query parameter from the URL
      history.replaceState(null, "", window.location.pathname);
    }, 3000);
  }
});
