function openEditMessageModal(modalId, appointmentId, currentMessage) {
  var modal = document.getElementById(modalId);
  modal.style.display = "block";
  modal.dataset.appointmentId = appointmentId;

  var closeButton = modal.getElementsByClassName("close")[0];
  closeButton.onclick = function () {
    modal.style.display = "none";
  };

  var saveChangesButton = modal.getElementsByClassName("save-changes")[0];
  saveChangesButton.onclick = function () {
    saveChanges(appointmentId); // Pass the appointmentId to saveChanges
  };

  var textarea = modal.getElementsByTagName("textarea")[0];
  textarea.value = currentMessage;
}

function saveChanges(appointmentId) {
  const newMessage = document.querySelector("#editMessageModal textarea").value;

  fetch("update_message.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `appointment_id=${appointmentId}&new_message=${encodeURIComponent(
      newMessage
    )}`,
  })
    .then((response) => response.text())
    .then((result) => {
      console.log(result);
      if (result === "Message updated successfully") {
        // Reload the page and add the "edited=true" query parameter
        window.location.href = window.location.pathname + "?edited=true";
      } else {
        showTemporaryMessage("Error editing message.", 3000);
      }
    })
    .catch((error) => console.error("Error:", error));
}

function openCancelAppointmentModal(modalId, appointmentId) {
  var modal = document.getElementById(modalId);
  modal.style.display = "block";
  modal.dataset.appointmentId = appointmentId;

  var closeButton = modal.getElementsByClassName("close")[0];
  closeButton.onclick = function () {
    modal.style.display = "none";
  };

  var confirmCancelButton = modal.querySelector(".confirm-cancel");
  confirmCancelButton.onclick = cancelAppointment;
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
