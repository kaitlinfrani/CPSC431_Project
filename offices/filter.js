// Get the input field and select dropdown
var searchBar = document.getElementById("searchBar");
var filterDropdown = document.getElementById("filterDropdown");

// Listen for changes in the input field and select dropdown
searchBar.addEventListener("input", filterResults);
filterDropdown.addEventListener("change", filterResults);

function filterResults() {
  // Get the value of the input field and select dropdown
  var searchString = searchBar.value.toLowerCase();
  var filterOption = filterDropdown.value;

  // Get all providers
  var providers = document.getElementsByClassName("provider");

  // Loop through each provider
  for (var i = 0; i < providers.length; i++) {
    var provider = providers[i];
    var occupation = provider.getElementsByClassName("occupation")[0];

    // Check if occupation matches the filter option
    if ((filterOption == "" || occupation.textContent.toLowerCase().indexOf(filterOption.toLowerCase()) > -1)
      // Check if search string matches name, zipcode, or food preference
      && (searchString == "" || provider.textContent.toLowerCase().indexOf(searchString) > -1)) {
      provider.style.display = "";
    } else {
      provider.style.display = "none";
    }
  }
}
