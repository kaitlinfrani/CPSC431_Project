function openEditMessageModal(modalId, appointmentId, currentMessage) {
  var modal = document.getElementById(modalId);
  modal.style.display = "block";
  modal.dataset.appointmentId = appointmentId;

  var closeButton = modal.getElementsByClassName("close")[0];
  closeButton.onclick = function () {
    modal.style.display = "none";
  };

  var saveChangesButton = modal.getElementsByClassName("save-changes")[0];
  saveChangesButton.onclick = saveChanges;

  var textarea = modal.getElementsByTagName("textarea")[0];
  textarea.value = currentMessage;
}

function openCancelAppointmentModal(modalId, appointmentId) {
  var modal = document.getElementById(modalId);
  modal.style.display = "block";
  modal.dataset.appointmentId = appointmentId;

  var closeButton = modal.getElementsByClassName("close")[0];
  closeButton.onclick = function () {
    modal.style.display = "none";
  };

  var confirmCancelButton = modal.getElementsByClassName("confirm-cancel")[0];
  confirmCancelButton.onclick = cancelAppointment;
}

function saveChanges() {
  const appointmentId =
    document.getElementById("editMessageModal").dataset.appointmentId;
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
      location.reload();
    })
    .catch((error) => console.error("Error:", error));
}

function cancelAppointment() {
  const appointmentId = document.getElementById("cancelAppointmentModal")
    .dataset.appointmentId;

  fetch("cancel_appointment.php", {
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

function goBackToProfile() {
  window.location.href = "client_landing.php";
}
