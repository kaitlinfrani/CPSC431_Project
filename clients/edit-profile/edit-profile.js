window.onload = function () {
  var occupationSelect = document.getElementById("occupation");
  var otherOccupationDiv = document.getElementById("otherOccupation");

  function checkOccupation() {
    if (occupationSelect.value === "Other") {
      otherOccupationDiv.style.display = "block";
    } else {
      otherOccupationDiv.style.display = "none";
    }
  }

  // Check the occupation when the page loads
  checkOccupation();

  // And every time the occupation changes
  occupationSelect.addEventListener("change", checkOccupation);
};
