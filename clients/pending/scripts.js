function openEditMessageModal(modalId, appointmentId, currentMessage) {
  const modal = document.getElementById(modalId);

  if (!modal) {
    console.error(`Could not find modal with ID: ${modalId}`);
    return;
  }

  modal.style.display = "block";
  const textarea = modal.querySelector("textarea");
  textarea.value = currentMessage;

  const saveButton = modal.querySelector(".save-changes");
  const cancelButton = modal.querySelector(".cancel-changes");

  saveButton.onclick = function () {
    const newMessage = textarea.value;
    console.log(`Saving new message: ${newMessage}`);
    updateMessage(appointmentId, newMessage);
    closeModal(modalId);
  };

  cancelButton.onclick = function () {
    closeModal(modalId);
  };
}

function updateMessage(appointmentId, newMessage) {
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "update_message.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
      // Handle the response from the server
      console.log(this.responseText);
      window.location.href = window.location.pathname + "?edited=true"; // Redirect to the same page with 'edited=true' parameter
    }
  };

  const data = `appointmentId=${appointmentId}&message=${encodeURIComponent(
    newMessage
  )}`;
  xhr.send(data);
}

function openCancelAppointmentModal(modalId, appointmentId) {
  const modal = document.getElementById(modalId);
  modal.style.display = "block";
  modal.dataset.appointmentId = appointmentId;

  const confirmButton = modal.querySelector(".confirm-cancel");
  const disregardButton = modal.querySelector(".disregard-cancel");

  confirmButton.onclick = function () {
    // Handle canceling the appointment
    cancelAppointment();
    closeModal(modalId);
  };

  disregardButton.onclick = function () {
    closeModal(modalId);
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

function closeModal(modalId) {
  console.log(`Closing modal: ${modalId}`);
  const modal = document.getElementById(modalId);

  if (!modal) {
    console.error(`Could not find modal with ID: ${modalId}`);
    return;
  }

  modal.style.display = "none";
}

window.onclick = function (event) {
  const modals = document.querySelectorAll(".modal");
  modals.forEach((modal) => {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });
};

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

document.addEventListener("DOMContentLoaded", () => {
  const urlParams = new URLSearchParams(window.location.search);
  const edited = urlParams.get("edited");

  if (edited === "true") {
    showTemporaryMessage("Successfully edited message.", 3000);
    setTimeout(() => {
      // Remove the query parameter from the URL
      history.replaceState(null, "", window.location.pathname);
    }, 3000);
  }
});
