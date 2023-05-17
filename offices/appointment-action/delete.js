const deleteModal = document.getElementById("deleteModal");
const confirmDeleteBtn = document.getElementById("confirmDelete");
const cancelDeleteBtn = document.getElementById("cancelDelete");

function showDeleteModal() {
  deleteModal.style.display = "block";
}

function hideDeleteModal() {
  deleteModal.style.display = "none";
}

function deleteAppointment() {
  const appointmentId = confirmDeleteBtn.getAttribute("data-appointment-id");

  const formData = new FormData();
  formData.append("appointment_id", appointmentId);

  fetch("delete_appointment.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.text())
    .then((result) => {
      console.log(result);
      window.location.href = window.location.pathname + "?deleted=true";
    })
    .catch((error) => console.error("Error:", error));
  hideDeleteModal();
}

// Add event listeners to the delete and cancel buttons
confirmDeleteBtn.addEventListener("click", () => deleteAppointment());
cancelDeleteBtn.addEventListener("click", hideDeleteModal);

// Add event listeners to all delete buttons on the page
const deleteButtons = document.getElementsByClassName("delete-btn");
for (const button of deleteButtons) {
  button.addEventListener("click", () => {
    const appointmentId = button.getAttribute("data-appointment-id");
    confirmDeleteBtn.setAttribute("data-appointment-id", appointmentId);
    showDeleteModal();
  });
}

// Function to show a temporary message
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
  const deleted = urlParams.get("deleted");

  if (deleted === "true") {
    showTemporaryMessage("Appointment deleted successfully.", 3000);
    setTimeout(() => {
      // Remove the query parameter from the URL
      history.replaceState(null, "", window.location.pathname);
      location.reload();
    }, 3000);
  }
});
