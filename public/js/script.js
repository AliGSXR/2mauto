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

function isInViewport(element) {
    const rect = element.getBoundingClientRect();
    return rect.top < window.innerHeight && rect.bottom >= 0;
}

// Fonction principale pour gérer l'apparition des éléments
function handleScroll() {
    console.log("Éléments cachés détectés :", document.querySelectorAll('.hidden'));
    const hiddenElements = document.querySelectorAll('.hidden');
    hiddenElements.forEach((el) => {
        if (isInViewport(el)) {
            el.classList.add('visible');
            el.classList.remove('hidden');
        }
    });
}

// Ajouter les événements de défilement et de chargement
window.addEventListener('scroll', handleScroll);
document.addEventListener('DOMContentLoaded', handleScroll);

// Vérification au cas où
console.log("Script chargé et fonction handleScroll activée.");

// Écouteur d'événement pour le défilement
window.addEventListener('scroll', handleScroll);

// Exécute une première vérification au chargement de la page
document.addEventListener('DOMContentLoaded', handleScroll);

function isMobileDevice() {
    return /iPhone|iPad|iPod|Android|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

function handleSocialClick(event) {
    event.preventDefault(); // Empêche le comportement par défaut
    const mobileUrl = event.currentTarget.getAttribute('data-mobile-url');
    const desktopUrl = event.currentTarget.getAttribute('data-desktop-url');

    if (isMobileDevice()) {
        window.location.href = mobileUrl;
    } else {
        window.open(desktopUrl, '_blank');
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const cards = document.querySelectorAll(".card"); // Sélectionnez toutes les cartes

    function handleScroll() {
        cards.forEach((card) => {
            const cardTop = card.getBoundingClientRect().top; // Position de la carte
            const windowHeight = window.innerHeight; // Hauteur de la fenêtre

            if (cardTop < windowHeight - 50) {
                // Si la carte est visible dans la fenêtre
                card.classList.add("visible");
            }
        });
    }

    window.addEventListener("scroll", handleScroll); // Vérifiez à chaque scroll
    handleScroll(); // Vérifiez une fois au chargement
});