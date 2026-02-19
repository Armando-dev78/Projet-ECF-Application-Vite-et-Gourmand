document.addEventListener("DOMContentLoaded", function () {
    const slides = document.querySelectorAll("#carousel img");
    let index = 0;

    if (slides.length === 0) {
        console.log("Aucune image trouvée");
        return;
    }

    setInterval(() => {
        slides[index].classList.remove("active");
        index = (index + 1) % slides.length;
        slides[index].classList.add("active");
    }, 4000);
});