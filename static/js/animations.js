// Simple UI animation helpers
(function(){
  // Fade-in on scroll for elements with .fade-in
  function onScrollFade() {
    const els = document.querySelectorAll('.fade-in');
    const h = window.innerHeight;
    els.forEach(el => {
      const rect = el.getBoundingClientRect();
      if (rect.top < h - 80) el.classList.add('visible');
    });
  }
  document.addEventListener('DOMContentLoaded', () => {
    onScrollFade();
    window.addEventListener('scroll', onScrollFade);
  });

  // Simple button press animation (ripple-like)
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('.btn-cta, .save-btn, .cta');
    if (!btn) return;
    btn.classList.add('pressed');
    setTimeout(() => btn.classList.remove('pressed'), 250);
  });
})();
