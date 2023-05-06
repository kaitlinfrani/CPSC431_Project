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
      if (result === "success") {
        // Reload the page to update the list of rejected appointments
        location.reload();
      } else {
        alert("An error occurred while deleting the appointment.");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });

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
