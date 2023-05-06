function openCancelAppointmentModal(modalId, appointmentId) {
  var modal = document.getElementById(modalId);
  modal.style.display = "block";
  modal.dataset.appointmentId = appointmentId; // Set the appointmentId as a data attribute

  var closeButton = modal.getElementsByClassName("close")[0];
  closeButton.onclick = function () {
    modal.style.display = "none";
  };

  var confirmCancelButton = modal.getElementsByClassName("confirm-cancel")[0];
  confirmCancelButton.onclick = function () {
    cancelAppointment(); // No need to pass appointmentId here
  };
}

function cancelAppointment() {
  const appointmentId = document.getElementById("cancelAppointmentModal")
    .dataset.appointmentId;

  fetch("../shared/cancel_appointment.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `appointment_id=${appointmentId}`,
  })
    .then((response) => response.text())
    .then((result) => {
      console.log(result);
      location.reload();
    })
    .catch((error) => console.error("Error:", error));

  showTemporaryMessage("Appointment cancelled successfully.", 3000); // Show message for 3 seconds (3000ms)
}

function showTemporaryMessage(message, duration) {
  const messageContainer = document.getElementById("message-container");
  messageContainer.innerHTML = message;
  messageContainer.style.display = "block";

  setTimeout(() => {
    messageContainer.style.display = "none";
  }, duration);
}

window.onclick = function (event) {
  var modals = document.getElementsByClassName("modal");
  for (var i = 0; i < modals.length; i++) {
    var modal = modals[i];
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }
};
