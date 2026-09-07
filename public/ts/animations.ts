type AnimatedElement = HTMLElement & { dataset: DOMStringMap & { reveal?: string; delay?: string } };
const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const reveal = (element: AnimatedElement): void => element.classList.add('is-visible');
if (!reduced) {
 const observer = new IntersectionObserver((entries) => entries.forEach((entry) => {
  if (entry.isIntersecting) { reveal(entry.target as AnimatedElement); observer.unobserve(entry.target); }
 }), { threshold: 0.12, rootMargin: '0px 0px -45px' });
 document.querySelectorAll<AnimatedElement>('[data-reveal]').forEach((element, index) => {
  element.style.setProperty('--reveal-delay', `${Math.min(index % 4, 3) * 90}ms`); observer.observe(element);
 });
}
document.querySelectorAll<HTMLElement>('[data-counter]').forEach((counter) => {
 const target = Number(counter.dataset.counter ?? 0); const suffix = counter.dataset.suffix ?? '';
 let started = false; const run = (): void => { if (started) return; started = true; const start = performance.now();
  const frame = (now: number): void => { const progress = Math.min((now - start) / 1200, 1); counter.textContent = `${Math.round(target * (1 - Math.pow(1 - progress, 3)))}${suffix}`; if (progress < 1) requestAnimationFrame(frame); }; requestAnimationFrame(frame); };
 new IntersectionObserver((entries) => entries[0].isIntersecting && run(), { threshold: .5 }).observe(counter);
});
