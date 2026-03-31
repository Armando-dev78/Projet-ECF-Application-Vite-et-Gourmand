// ================= AFFICHAGE MENUS =================
function afficherMenus(menus) {
  const container = document.getElementById("menus-container");
  container.innerHTML = "";

  if (menus.length === 0) {
    container.innerHTML = "<p>Aucun menu trouvé.</p>";
    return;
  }

  menus.forEach((menu) => {
    container.innerHTML += `
            <article class="menu-card">

                <img 
                    src="images/menus/menu_${menu.id}.jpg" 
                    alt="${menu.nom}" 
                    class="menu-image">

                <div class="menu-content">
                    <h2>${menu.nom}</h2>

                    <p>${menu.description}</p>

                    <p>
                        <strong>${menu.prix_par_personne} €</strong> / personne
                    </p>

                    <p>
                        Minimum ${menu.nb_personnes_min} personnes
                    </p>

                    <a href="menu.php?id=${menu.id}" class="btn-details">
                        Voir le détail
                    </a>
                </div>

            </article>
        `;
  });
}

// ================= FILTRE THÈME =================
const themeSelect = document.getElementById("theme");

if (themeSelect) {
  themeSelect.addEventListener("change", function () {
    fetch(`menus.php?action=filter-theme&theme=${this.value}`)
      .then((res) => res.json())
      .then((menus) => afficherMenus(menus));
  });
}

// ================= FILTRE MIN PERSONNES =================
const minPersons = document.getElementById("minPersons");

if (minPersons) {
  minPersons.addEventListener("input", function () {
    fetch(`menus.php?action=filter-min-persons&minPersons=${this.value}`)
      .then((res) => res.json())
      .then((menus) => afficherMenus(menus));
  });
}

// ================= FILTRE PRIX MAX =================
const maxPrice = document.getElementById("maxPrice");

if (maxPrice) {
  maxPrice.addEventListener("input", function () {
    if (this.value === "") {
      fetch(`menus.php?action=filter-theme&theme=`)
        .then((res) => res.json())
        .then((menus) => afficherMenus(menus));
      return;
    }

    fetch(`menus.php?action=filter-max-price&maxPrice=${this.value}`)
      .then((res) => res.json())
      .then((menus) => afficherMenus(menus));
  });
}

// ================= FILTRE RÉGIME =================
const regime = document.getElementById("regime");

if (regime) {
  regime.addEventListener("change", function () {
    fetch(`menus.php?action=filter-regime&regime=${this.value}`)
      .then((res) => res.json())
      .then((menus) => afficherMenus(menus));
  });
}

// ================= FILTRE FOURCHETTE =================
const minPrice = document.getElementById("minPrice");
const maxPriceRange = document.getElementById("maxPriceRange");

function filterByPriceRange() {
  if (!minPrice || !maxPriceRange) return;

  if (minPrice.value === "" || maxPriceRange.value === "") return;

  fetch(
    `menus.php?action=filter-price-range&minPrice=${minPrice.value}&maxPrice=${maxPriceRange.value}`,
  )
    .then((res) => res.json())
    .then((menus) => afficherMenus(menus));
}

if (minPrice) minPrice.addEventListener("input", filterByPriceRange);
if (maxPriceRange) maxPriceRange.addEventListener("input", filterByPriceRange);

// ================= CAROUSEL =================
document.addEventListener("DOMContentLoaded", () => {
  let currentSlide = 0;
  const slides = document.querySelectorAll("#carousel img");

  if (slides.length === 0) return;

  setInterval(() => {
    slides[currentSlide].classList.remove("active");
    currentSlide = (currentSlide + 1) % slides.length;
    slides[currentSlide].classList.add("active");
  }, 3000);
});
