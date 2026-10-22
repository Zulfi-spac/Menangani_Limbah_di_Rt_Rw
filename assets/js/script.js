document.addEventListener('DOMContentLoaded', () => {
  const items = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.14 });
    items.forEach((item) => observer.observe(item));
  } else {
    items.forEach((item) => item.classList.add('is-visible'));
  }
  document.querySelectorAll('[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
      if (!window.confirm(form.dataset.confirm || 'Yakin ingin melanjutkan?')) event.preventDefault();
    });
  });
});