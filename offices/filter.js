document.getElementById("searchBar").addEventListener("input", applyFilters);
document.getElementById("filterDropdown").addEventListener("change", applyFilters);

function applyFilters() {
  const filter = document.getElementById("searchBar").value.toLowerCase();
  const filterType = document.getElementById("filterDropdown").value;
  const providers = document.querySelectorAll(".single-provider");

  for (const provider of providers) {
    const occupation = provider.querySelector(".provider-occupation").textContent.toLowerCase();
    const zipcode = provider.querySelector(".provider-zipcode").textContent.toLowerCase();
    const foodPreference = provider.querySelector(".provider-food_preference").textContent.toLowerCase();

    let searchText;
    if (filterType === "location") {
      searchText = zipcode;
    } else if (filterType === "occupation") {
      searchText = occupation;
    } else if (filterType === "food_preference") {
      searchText = foodPreference;
    } else {
      searchText = provider.querySelector("li").textContent.toLowerCase();
    }

    if (searchText.indexOf(filter) > -1) {
      provider.style.display = "block";
    } else {
      provider.style.display = "none";
    }
  }
}