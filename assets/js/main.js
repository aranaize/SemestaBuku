// Toggle Light / Dark Mode
const themeToggleBtn = document.getElementById('theme-toggle');

const currentTheme = localStorage.getItem('theme') || 'light';
document.documentElement.setAttribute('data-theme', currentTheme);

if (themeToggleBtn) {
    themeToggleBtn.innerText = currentTheme === 'dark' ? '☀️ Light Mode' : '🌙 Dark Mode';

    themeToggleBtn.addEventListener('click', () => {
        let theme = document.documentElement.getAttribute('data-theme');
        let newTheme = theme === 'dark' ? 'light' : 'dark';

        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);

        themeToggleBtn.innerText = newTheme === 'dark' ? '☀️ Light Mode' : '🌙 Dark Mode';
    });
}

// Fitur Filter Search Sederhana
function filterBooks() {
    let input = document.getElementById('search-input').value.toLowerCase();
    let cards = document.querySelectorAll('.book-card');

    cards.forEach(card => {
        let title = card.querySelector('.book-title').innerText.toLowerCase();
        let author = card.querySelector('.book-author').innerText.toLowerCase();

        if (title.includes(input) || author.includes(input)) {
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }
    });
}