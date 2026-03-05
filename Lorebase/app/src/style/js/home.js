const sidebar = document.getElementById('sidebar');
const sidebarToggle = document.getElementById('sidebarToggle');
const sidebarOverlay = document.getElementById('sidebarOverlay');

// Ouvrir/fermer la sidebar sur mobile
sidebarToggle.addEventListener('click', () => {
    sidebar.classList.toggle('active');
    sidebarOverlay.classList.toggle('active');
});

// Fermer en cliquant sur l'overlay
sidebarOverlay.addEventListener('click', () => {
    sidebar.classList.remove('active');
    sidebarOverlay.classList.remove('active');
});

const themeToggle = document.getElementById('themeToggle');
const themeIcon = themeToggle?.querySelector('.sidebar__theme-toggle-icon');
const htmlElement = document.documentElement;

// Récupérer le thème sauvegardé ou utiliser le thème système
const savedTheme = localStorage.getItem('theme');
const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
const currentTheme = savedTheme || systemTheme;

// Appliquer le thème au chargement
if (currentTheme === 'dark') {
    htmlElement.setAttribute('data-theme', 'dark');
    if (themeIcon) themeIcon.textContent = '🌙';
} else {
    htmlElement.setAttribute('data-theme', 'light');
    if (themeIcon) themeIcon.textContent = '☀️';
}

// Toggle theme on click
if (themeToggle) {
    themeToggle.addEventListener('click', function () {
        const currentTheme = htmlElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

        // Animation de rotation
        themeToggle.classList.add('rotating');
        setTimeout(() => themeToggle.classList.remove('rotating'), 600);

        // Changer le thème
        htmlElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);

        // Changer l'icône
        if (themeIcon) {
            themeIcon.textContent = newTheme === 'dark' ? '🌙' : '☀️';
        }
    });
}
