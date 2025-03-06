

document.addEventListener("DOMContentLoaded", function () {
    const currentPath = window.location.pathname; 
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

    
    detailButtons.forEach((button) => {
        button.addEventListener('click', function () {
            const targetId = button.getAttribute('data-target');
            const card = document.querySelector(targetId);

           
            card.classList.add('open');

            
            card.scrollIntoView({
                behavior: 'smooth',
                block: 'center' 
            });
        });
    });

    
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

    
    detailButtons.forEach((button) => {
        button.addEventListener('click', function () {
            const targetId = button.getAttribute('data-target');
            const card = document.querySelector(targetId);

            
            card.classList.add('open');

            
            card.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        });
    });

    
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


window.addEventListener('scroll', handleScroll);
document.addEventListener('DOMContentLoaded', handleScroll);


console.log("Script chargé et fonction handleScroll activée.");


window.addEventListener('scroll', handleScroll);


document.addEventListener('DOMContentLoaded', handleScroll);

function isMobileDevice() {
    return /iPhone|iPad|iPod|Android|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

function handleSocialClick(event) {
    event.preventDefault(); 
    const mobileUrl = event.currentTarget.getAttribute('data-mobile-url');
    const desktopUrl = event.currentTarget.getAttribute('data-desktop-url');

    if (isMobileDevice()) {
        window.location.href = mobileUrl;
    } else {
        window.open(desktopUrl, '_blank');
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const cards = document.querySelectorAll(".card"); 

    function handleScroll() {
        cards.forEach((card) => {
            const cardTop = card.getBoundingClientRect().top; 
            const windowHeight = window.innerHeight; 

            if (cardTop < windowHeight - 50) {
                
                card.classList.add("visible");
            }
        });
    }

    window.addEventListener("scroll", handleScroll); // Vérifiez à chaque scroll
    handleScroll(); 
});

document.addEventListener('DOMContentLoaded', function () {
    const scrollElements = document.querySelectorAll('.scroll-animation');

    const elementInView = (el, percentageScroll = 100) => {
        const elementTop = el.getBoundingClientRect().top;
        return (
            elementTop <=
            (window.innerHeight || document.documentElement.clientHeight) * (percentageScroll / 100)
        );
    };

    const displayScrollElement = (element) => {
        element.classList.add('scroll-visible');
    };

    const hideScrollElement = (element) => {
        element.classList.remove('scroll-visible');
    };

    const handleScrollAnimation = () => {
        scrollElements.forEach((el) => {
            if (elementInView(el, 90)) {
                displayScrollElement(el);
            } else {
                hideScrollElement(el);
            }
        });
    };

    
    window.addEventListener('scroll', () => {
        handleScrollAnimation();
    });

    
    handleScrollAnimation();
});




document.addEventListener('DOMContentLoaded', function () {
    const serviceRows = document.querySelectorAll('[data-field-name="serviceFacts"]');
    const totalHTCField = document.querySelector('[data-field-name="totalHTC"]');
    const tvaField = document.querySelector('[data-field-name="tva"]');
    const totalTTCField = document.querySelector('[data-field-name="totalTTC"]');
    const tauxTVA = 20;

    function calculateTotals() {
        let totalHTC = 0;

        serviceRows.forEach(row => {
            const quantity = parseFloat(row.querySelector('[data-field-name="quantity"]').value) || 0;
            const unitPrice = parseFloat(row.querySelector('[data-field-name="unitPrix"]').value) || 0;
            totalHTC += quantity * unitPrice;
        });

        const tva = totalHTC * (tauxTVA / 100);
        const totalTTC = totalHTC + tva;

        totalHTCField.value = totalHTC.toFixed(2);
        tvaField.value = tva.toFixed(2);
        totalTTCField.value = totalTTC.toFixed(2);
    }

    serviceRows.forEach(row => {
        row.addEventListener('input', calculateTotals);
    });

    calculateTotals(); 
});


