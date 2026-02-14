
const sidebar = document.getElementById('sidebar');
const sidebarToggle = document.getElementById('sidebarToggle');
const sidebarContainer = document.querySelector('.sidebar-container');
const sidebarOverlay = document.getElementById('sidebarOverlay');
const body = document.body;

sidebarToggle.addEventListener('click', () => {
    sidebar.classList.toggle('closed');
    body.classList.toggle('sidebar-closed'); // ← AJOUTÉ - C'EST LA LIGNE IMPORTANTE !

    // Sauvegarder l'état
    localStorage.setItem('sidebarClosed', sidebar.classList.contains('closed'));
});

// Fermer sur mobile avec overlay
sidebarOverlay.addEventListener('click', () => {
    sidebarContainer.classList.remove('mobile-open');
});

// Gérer les liens actifs
const links = document.querySelectorAll('.sidebar-link');
links.forEach(link => {
    link.addEventListener('click', function (e) {
        links.forEach(l => l.classList.remove('active'));
        this.classList.add('active');
    });
});

// Restaurer l'état au chargement
document.addEventListener('DOMContentLoaded', () => {
    const wasClosed = localStorage.getItem('sidebarClosed') === 'true';
    if (wasClosed) {
        sidebar.classList.add('closed');
        body.classList.add('sidebar-closed'); // ← AJOUTÉ - Restaurer aussi sur body
    }
});