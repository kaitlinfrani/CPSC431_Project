function openCancelAppointmentModal(modalId, appointmentId) {
  var modal = document.getElementById(modalId);
  modal.style.display = "block";
  modal.dataset.appointmentId = appointmentId; // Set the appointmentId as a data attribute

  var disregardButton = modal.querySelector(".disregard-cancel");
  disregardButton.onclick = function () {
    modal.style.display = "none"; // close the modal
  };

  var confirmCancelButton = modal.getElementsByClassName("confirm-cancel")[0];
  confirmCancelButton.onclick = function () {
    cancelAppointment(); // No need to pass appointmentId here
  };
}

function cancelAppointment() {
  const modal = document.getElementById("cancelAppointmentModal");

  fetch("../shared/cancel_appointment.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `appointment_id=${modal.dataset.appointmentId}`,
  })
    .then((response) => response.text())
    .then((result) => {
      console.log(result);
      window.location.href = window.location.pathname + "?cancelled=true"; // Redirect with the query parameter
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
  const cancelled = urlParams.get("cancelled");

  if (cancelled === "true") {
    showTemporaryMessage("Appointment cancelled successfully.", 3000);
    setTimeout(() => {
      // Remove the query parameter from the URL
      history.replaceState(null, "", window.location.pathname);
    }, 3000);
  }
});

window.onclick = function (event) {
  var modals = document.getElementsByClassName("modal");
  for (var i = 0; i < modals.length; i++) {
    var modal = modals[i];
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }
};

document.addEventListener("DOMContentLoaded", () => {
  const urlParams = new URLSearchParams(window.location.search);
  const cancelled = urlParams.get("cancelled");

  if (cancelled === "true") {
    showTemporaryMessage("Appointment cancelled successfully.", 3000);
    setTimeout(() => {
      // Remove the query parameter from the URL
      history.replaceState(null, "", window.location.pathname);
    }, 3000);
  }
});
