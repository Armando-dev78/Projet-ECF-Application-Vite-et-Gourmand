/**
 * Filtre par thème
 * Actualisation dynamique des menus sans rechargement
 */
document.getElementById("theme").addEventListener("change", function () {
const theme = this.value;

fetch(`index.php?action=filter-theme&theme=${theme}`)
    .then((response) => response.json())
    .then((menus) => {
    const container = document.getElementById("menus-container");
    container.innerHTML = "";

    if (menus.length === 0) {
        container.innerHTML = "<p>Aucun menu trouvé.</p>";
        return;
    }

    menus.forEach((menu) => {
        container.innerHTML += `
                    <article>
                        <h2>${menu.nom}</h2>
                        <p>${menu.description}</p>
                        <p><strong>${menu.prix_par_personne} €</strong> / personne</p>
                        <p>Minimum ${menu.nb_personnes_min} personnes</p>
                        <hr>
                    </article>
                `;
    });
    });
});

/**
 * Filtre par nombre minimum de personnes
 */
document.getElementById("minPersons").addEventListener("input", function () {
const minPersons = this.value;

fetch(`index.php?action=filter-min-persons&minPersons=${minPersons}`)
    .then((response) => response.json())
    .then((menus) => {
    const container = document.getElementById("menus-container");
    container.innerHTML = "";

    if (menus.length === 0) {
        container.innerHTML = "<p>Aucun menu trouvé.</p>";
        return;
    }

    menus.forEach((menu) => {
        container.innerHTML += `
                    <article>
                        <h2>${menu.nom}</h2>
                        <p>${menu.description}</p>
                        <p><strong>${menu.prix_par_personne} €</strong> / personne</p>
                        <p>Minimum ${menu.nb_personnes_min} personnes</p>
                        <hr>
                    </article>
                `;
    });
    });
});

/**
 * Filtre par prix maximum
 */
document.getElementById("maxPrice").addEventListener("input", function () {
const maxPrice = this.value;

if (maxPrice === "") {
    fetch(`index.php`)
    .then((response) => response.text())
    .then((html) => {
        document.open();
        document.write(html);
        document.close();
    });
    return;
}

fetch(`index.php?action=filter-max-price&maxPrice=${maxPrice}`)
    .then((response) => response.json())
    .then((menus) => {
    const container = document.getElementById("menus-container");
    container.innerHTML = "";

    if (menus.length === 0) {
        container.innerHTML = "<p>Aucun menu trouvé.</p>";
        return;
    }

    menus.forEach((menu) => {
        container.innerHTML += `
                    <article>
                        <h2>${menu.nom}</h2>
                        <p>${menu.description}</p>
                        <p><strong>${menu.prix_par_personne} €</strong> / personne</p>
                        <p>Minimum ${menu.nb_personnes_min} personnes</p>
                        <hr>
                    </article>
                `;
    });
    });
});

/**
 * Filtre par régime alimentaire
 * (classique, vegetarien, vegan)
 */
document.getElementById("regime").addEventListener("change", function () {
const regime = this.value;

fetch(`index.php?action=filter-regime&regime=${regime}`)
    .then((response) => response.json())
    .then((menus) => {
    const container = document.getElementById("menus-container");
    container.innerHTML = "";

    if (menus.length === 0) {
        container.innerHTML = "<p>Aucun menu trouvé.</p>";
        return;
    }

    menus.forEach((menu) => {
        container.innerHTML += `
                    <article>
                        <h2>${menu.nom}</h2>
                        <p>${menu.description}</p>
                        <p><strong>${menu.prix_par_personne} €</strong> / personne</p>
                        <p>Minimum ${menu.nb_personnes_min} personnes</p>
                        <hr>
                    </article>
                `;
    });
    });
});

/**
 * Filtre par fourchette de prix
 * Actualisation dynamique des menus (AJAX)
 */
function filterByPriceRange() {
    const min = document.getElementById("minPrice").value;
    const max = document.getElementById("maxPriceRange").value;

    if (min === "" || max === "") {
        return;
    }

    fetch(`index.php?action=filter-price-range&minPrice=${min}&maxPrice=${max}`)
        .then(response => response.json())
        .then(menus => {
            const container = document.getElementById("menus-container");
            container.innerHTML = "";

            if (menus.length === 0) {
                container.innerHTML = "<p>Aucun menu trouvé.</p>";
                return;
            }

            menus.forEach(menu => {
                container.innerHTML += `
                    <article>
                        <h2>${menu.nom}</h2>
                        <p>${menu.description}</p>
                        <p><strong>${menu.prix_par_personne} €</strong> / personne</p>
                        <p>Minimum ${menu.nb_personnes_min} personnes</p>
                        <hr>
                    </article>
                `;
            });
        });
}

document.getElementById("minPrice").addEventListener("input", filterByPriceRange);
document.getElementById("maxPriceRange").addEventListener("input", filterByPriceRange);

/**
 * Carrousel automatique page d’accueil
 * Exécuté après chargement complet du DOM
 */
document.addEventListener("DOMContentLoaded", () => {
    let currentSlide = 0;
    const slides = document.querySelectorAll("#carousel img");

    if (slides.length === 0) return;

    setInterval(() => {
        slides[currentSlide].classList.remove("active");
        currentSlide = (currentSlide + 1) % slides.length;
        slides[currentSlide].classList.add("active");
    }, 3000); // 3 secondes (plus fluide que 4)
});