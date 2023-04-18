function showProviderFields() {
  document.getElementById("provider-fields").style.display = "block";
  document.getElementById("client-fields").style.display = "none";
  document.getElementById("Name").required = true;
  document.getElementById("office-name").required = true;
  document.getElementById("occupation").required = false;
  document.getElementById("custom-occupation").required = false;
}

function showClientFields() {
  document.getElementById("provider-fields").style.display = "none";
  document.getElementById("client-fields").style.display = "block";
  document.getElementById("Name").required = true;
  document.getElementById("office-name").required = false;
  document.getElementById("occupation").required = true;
  document.getElementById("custom-occupation").required = false;
}

function initializeForm() {
  document.getElementById("provider-fields").style.display = "none";
  document.getElementById("client-fields").style.display = "none";
  document.getElementById("Name").required = false;
  document.getElementById("office-name").required = false;
  document.getElementById("occupation").required = false;
  document.getElementById("custom-occupation").required = false;
}

function goBack() {
  window.history.back();
}

function showCustomOccupationInput() {
  var select = document.getElementById("occupation");
  var customOccupation = document.getElementById("custom-occupation");

  if (select.value === "Other") {
    customOccupation.style.display = "block";
    customOccupation.required = true;
  } else {
    customOccupation.style.display = "none";
    customOccupation.required = false;
  }
}
