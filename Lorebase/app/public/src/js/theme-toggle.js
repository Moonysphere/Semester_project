class ThemeToggle {
    constructor() {
        this.theme = localStorage.getItem('theme') || this._getSystemTheme();
        this.button = null;
        this.init();
    }

    _getSystemTheme() {
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    init() {
        this.applyTheme(this.theme);

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this._setup());
        } else {
            this._setup();
        }
    }

    _setup() {
        this.createButton();
        this.setupEventListeners();
    }

    createButton() {
        const footer = document.getElementById('sidebarFooter');

        if (!footer) {
            console.warn('[ThemeToggle] #sidebarFooter introuvable dans le DOM');
            return;
        }

        if (document.getElementById('themeToggle')) {
            console.warn('[ThemeToggle] Bouton déjà présent, init annulée');
            return;
        }

        const item = document.createElement('div');
        item.classList.add('sidebar-item');
        item.setAttribute('data-tooltip', this.theme === 'dark' ? 'Mode clair' : 'Mode sombre');

        this.button = document.createElement('button');
        this.button.classList.add('sidebar-link', 'sidebar-theme-toggle');
        this.button.id = 'themeToggle';
        this.button.setAttribute('aria-label', 'Changer le thème');
        this.button.setAttribute('data-tooltip', this.theme === 'dark' ? 'Mode clair' : 'Mode sombre');

        const icon = document.createElement('span');
        icon.classList.add('sidebar-icon', 'sidebar-theme-icon');
        icon.textContent = this.theme === 'dark' ? '☀️' : '🌙';

        const label = document.createElement('span');
        label.classList.add('sidebar-text');
        label.textContent = this.theme === 'dark' ? 'Mode clair' : 'Mode sombre';

        this.button.appendChild(icon);
        this.button.appendChild(label);
        item.appendChild(this.button);

        footer.insertBefore(item, footer.firstChild);

        console.log('[ThemeToggle] Bouton injecté dans #sidebarFooter');
    }

    setupEventListeners() {
        if (this.button) {
            this.button.addEventListener('click', () => this.toggleTheme());
            console.log('[ThemeToggle] Listener attaché');
        }
    }

    toggleTheme() {
        this.button.classList.add('rotating');

        this.theme = this.theme === 'light' ? 'dark' : 'light';
        this.applyTheme(this.theme);
        localStorage.setItem('theme', this.theme);

        const icon = this.button.querySelector('.sidebar-theme-icon');
        if (icon) icon.textContent = this.theme === 'dark' ? '☀️' : '🌙';

        const label = this.button.querySelector('.sidebar-text');
        if (label) label.textContent = this.theme === 'dark' ? 'Mode clair' : 'Mode sombre';

        const item = this.button.closest('.sidebar-item');
        const tooltip = this.theme === 'dark' ? 'Mode clair' : 'Mode sombre';
        if (item) item.setAttribute('data-tooltip', tooltip);
        this.button.setAttribute('data-tooltip', tooltip);

        setTimeout(() => this.button.classList.remove('rotating'), 600);

        console.log(`[ThemeToggle] Thème → ${this.theme}`);
    }

    applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
    }
}

new ThemeToggle();

export default ThemeToggle;