//Navbar

document.addEventListener("DOMContentLoaded", function () {
    const currentPath = window.location.pathname; // Récupère le chemin actuel
    const links = document.querySelectorAll(".nav-link");

    links.forEach(link => {
        if (link.getAttribute("href") === currentPath) {
            link.classList.add("active");
        }
    });
});


document.addEventListener('DOMContentLoaded', function () {
    const detailButtons = document.querySelectorAll('.detail-basique');
    const closeButtons = document.querySelectorAll('.btn-close-card');

    // Afficher la carte et faire défiler vers elle
    detailButtons.forEach((button) => {
        button.addEventListener('click', function () {
            const targetId = button.getAttribute('data-target');
            const card = document.querySelector(targetId);

            // Afficher la carte
            card.classList.add('open');

            // Défilement fluide vers la carte
            card.scrollIntoView({
                behavior: 'smooth',
                block: 'center' // Centre la carte verticalement
            });
        });
    });

    // Masquer la carte au clic sur "Fermer"
    closeButtons.forEach((button) => {
        button.addEventListener('click', function () {
            const card = button.closest('.hidden-card');
            card.classList.remove('open');
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const detailButtons = document.querySelectorAll('.detail-premium');
    const closeButtons = document.querySelectorAll('.btn-close-card');

    // Afficher la carte et faire défiler vers elle
    detailButtons.forEach((button) => {
        button.addEventListener('click', function () {
            const targetId = button.getAttribute('data-target');
            const card = document.querySelector(targetId);

            // Afficher la carte
            card.classList.add('open');

            // Défilement fluide vers la carte
            card.scrollIntoView({
                behavior: 'smooth',
                block: 'center' // Centre la carte verticalement
            });
        });
    });

    // Masquer la carte au clic sur "Fermer"
    closeButtons.forEach((button) => {
        button.addEventListener('click', function () {
            const card = button.closest('.hidden-card');
            card.classList.remove('open');
        });
    });
});

