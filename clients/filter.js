const searchBar = document.getElementById("searchBar");
const appliedFilters = document.querySelector(".applied-filters");

searchBar.addEventListener("keydown", (event) => {
  if (event.key === "Enter" && searchBar.value.trim()) {
    const filterText = searchBar.value.trim();
    searchBar.value = "";
    addFilter(filterText);
    applyFilters();
  }
});

function addFilter(filterText) {
  const filter = document.createElement("div");
  filter.className = "filter";
  filter.textContent = filterText;

  const closeButton = document.createElement("button");
  closeButton.className = "close-filter";
  closeButton.innerHTML = "&times;";
  closeButton.addEventListener("click", () => {
    filter.remove();
    applyFilters();
  });

  filter.appendChild(closeButton);
  appliedFilters.appendChild(filter);
}

function applyFilters() {
  const filters = Array.from(document.querySelectorAll(".filter")).map(
    (filter) => filter.textContent.slice(0, -1).toLowerCase()
  );
  const providers = document.querySelectorAll(".single-provider");

  for (const provider of providers) {
    const providerText = provider.textContent.toLowerCase();
    const match = filters.every((filter) => providerText.includes(filter));

    if (match) {
      provider.style.display = "block";
    } else {
      provider.style.display = "none";
    }
  }
}

function clearAllFilters() {
  const filters = document.querySelectorAll(".filter");
  filters.forEach((filter) => filter.remove());
  applyFilters();
}
