// Toggle sidebar
const sidebar = document.getElementById('sidebar');
const sidebarToggle = document.getElementById('sidebarToggle');
const sidebarContainer = document.querySelector('.sidebar-container');
const sidebarOverlay = document.getElementById('sidebarOverlay');
const body = document.body;

sidebarToggle.addEventListener('click', () => {
    sidebar.classList.toggle('closed');

    // Ajouter classe au body pour faciliter le CSS
    body.classList.toggle('sidebar-closed');

    // Sauvegarder l'état dans localStorage
    const isClosed = sidebar.classList.contains('closed');
    localStorage.setItem('sidebarClosed', isClosed);

    // Dispatch event pour ajuster le contenu principal
    window.dispatchEvent(new CustomEvent('sidebar-toggle', {
        detail: { isClosed }
    }));
});

// Restaurer l'état au chargement
document.addEventListener('DOMContentLoaded', () => {
    const wasClosed = localStorage.getItem('sidebarClosed') === 'true';
    if (wasClosed) {
        sidebar.classList.add('closed');
        body.classList.add('sidebar-closed');
    }
});

// Fermer sur mobile avec overlay
sidebarOverlay.addEventListener('click', () => {
    sidebarContainer.classList.remove('mobile-open');
});

// Active link
const links = document.querySelectorAll('.sidebar-link');
links.forEach(link => {
    link.addEventListener('click', function (e) {
        links.forEach(l => l.classList.remove('active'));
        this.classList.add('active');
    });
});

// Optionnel : Écouter l'événement pour d'autres ajustements
window.addEventListener('sidebar-toggle', (e) => {
    console.log('Sidebar toggled:', e.detail.isClosed ? 'closed' : 'open');

    // Tu peux ajouter d'autres ajustements ici si besoin
    // Par exemple, redimensionner des graphiques, etc.
});