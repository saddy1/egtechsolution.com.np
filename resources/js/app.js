import './bootstrap';
// Minimal carousel initializer (Flowbite-like data API, no dependencies)
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-carousel="slide"]').forEach((root) => {
    const items = Array.from(root.querySelectorAll('[data-carousel-item]'));
    const dots  = Array.from(root.querySelectorAll('[data-carousel-slide-to]'));
    const prev  = root.querySelector('[data-carousel-prev]');
    const next  = root.querySelector('[data-carousel-next]');
    const autoplay = root.getAttribute('data-autoplay') !== 'false';
    const intervalMs = parseInt(root.getAttribute('data-interval') || '1000', 10);

    if (!items.length) return;
    let index = Math.max(
      0,
      dots.findIndex((d) => d.getAttribute('aria-current') === 'true')
    );
    if (index === -1) index = 0;

    function show(i) {
      index = (i + items.length) % items.length;
      items.forEach((el, k) => {
        // Tailwind classes assume "hidden" for inactive
        if (k === index) {
          el.classList.remove('hidden');
        } else {
          el.classList.add('hidden');
        }
      });
      dots.forEach((d, k) => {
        const active = k === index;
        d.setAttribute('aria-current', active ? 'true' : 'false');
        d.classList.toggle('bg-white/50', active);
        d.classList.toggle('bg-white/30', !active);
      });
    }

    function nextSlide() { show(index + 1); }
    function prevSlide() { show(index - 1); }

    // Controls
    next?.addEventListener('click', nextSlide);
    prev?.addEventListener('click', prevSlide);

    // Dots
    dots.forEach((btn, k) => btn.addEventListener('click', () => show(k)));

    // Autoplay with pause on hover / tab hidden
    let timer = null;
    function start() { if (autoplay) timer = setInterval(nextSlide, intervalMs); }
    function stop() { if (timer) clearInterval(timer); timer = null; }

    root.addEventListener('mouseenter', stop);
    root.addEventListener('mouseleave', start);
    document.addEventListener('visibilitychange', () => {
      if (document.hidden) stop(); else start();
    });

    // Touch swipe (basic)
    let touchStartX = 0;
    root.addEventListener('touchstart', (e) => { touchStartX = e.touches[0].clientX; stop(); }, { passive: true });
    root.addEventListener('touchend', (e) => {
      const delta = e.changedTouches[0].clientX - touchStartX;
      if (Math.abs(delta) > 40) (delta < 0 ? nextSlide : prevSlide)();
      start();
    }, { passive: true });

    // Init
    show(index);
    start();
  });
});


