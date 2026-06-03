import './bootstrap';

// Theme Toggle
const themeToggle = document.getElementById('themeToggle');
if (themeToggle) {
    themeToggle.addEventListener('click', () => {
        const html = document.documentElement;
        const isDark = html.classList.contains('dark');
        html.classList.toggle('dark');
        localStorage.setItem('theme', isDark ? 'light' : 'dark');
    });
    
    // Load saved theme
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.documentElement.classList.add('dark');
    }
}

// Sidebar Toggle
const showSidebar = document.getElementById('showSidebar');
const closeSidebar = document.getElementById('closeSidebar');
const sidebar = document.querySelector('.sidebar');

if (showSidebar && closeSidebar && sidebar) {
    showSidebar.addEventListener('click', () => {
        sidebar.classList.remove('sidebar-hidden');
    });
    
    closeSidebar.addEventListener('click', () => {
        sidebar.classList.add('sidebar-hidden');
    });
}

// Dropdown Toggles
const notificationToggle = document.getElementById('notificationToggle');
const notificationDropdown = document.getElementById('notificationDropdown');
const userMenuToggle = document.getElementById('userMenuToggle');
const userDropdown = document.getElementById('userDropdown');

function toggleDropdown(dropdown, event) {
    if (event) event.stopPropagation();
    if (dropdown) dropdown.classList.toggle('hidden');
}

if (notificationToggle && notificationDropdown) {
    notificationToggle.addEventListener('click', (e) => toggleDropdown(notificationDropdown, e));
}

if (userMenuToggle && userDropdown) {
    userMenuToggle.addEventListener('click', (e) => toggleDropdown(userDropdown, e));
}

// Close dropdowns when clicking outside
document.addEventListener('click', () => {
    if (notificationDropdown) notificationDropdown.classList.add('hidden');
    if (userDropdown) userDropdown.classList.add('hidden');
});

// Submenu Toggle
document.querySelectorAll('.menu-toggle').forEach(button => {
    button.addEventListener('click', () => {
        const targetId = button.dataset.target;
        const submenu = document.getElementById(targetId);
        if (submenu) {
            submenu.classList.toggle('hidden');
            const icon = button.querySelector('svg:last-child');
            if (icon) {
                icon.style.transform = submenu.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
            }
        }
    });
});