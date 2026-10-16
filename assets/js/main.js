document.addEventListener('DOMContentLoaded', () => {
  const searchInput = document.getElementById('searchBook');
  if (searchInput) {
    searchInput.addEventListener('input', (e) => {
      const term = e.target.value.toLowerCase();
      const cards = document.querySelectorAll('.card-book');
      cards.forEach(card => {
        const title = card.querySelector('h3').innerText.toLowerCase();
        card.style.display = title.includes(term) ? 'flex' : 'none';
      });
    });
  }
});