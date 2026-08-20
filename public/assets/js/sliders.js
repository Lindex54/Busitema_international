const heroFlow = document.querySelector("[data-hero-flow]");

if (heroFlow) {
  const slides = Array.from(heroFlow.querySelectorAll(".hero-flow__slide"));
  const dots = Array.from(heroFlow.querySelectorAll("[data-hero-slide]"));
  const intervalMs = 6200;
  let activeIndex = slides.findIndex((slide) => slide.classList.contains("is-active"));
  let timerId;

  if (activeIndex < 0) {
    activeIndex = 0;
  }

  slides.forEach((slide) => {
    const imageUrl = slide.dataset.image;

    if (!imageUrl) {
      return;
    }

    const image = slide.querySelector(".hero-flow__image");
    const slices = slide.querySelectorAll(".hero-flow__slice");

    if (image) {
      image.style.backgroundImage = `url("${imageUrl}")`;
    }

    slices.forEach((slice) => {
      slice.style.backgroundImage = `url("${imageUrl}")`;
    });
  });

  const setActiveSlide = (nextIndex) => {
    activeIndex = (nextIndex + slides.length) % slides.length;

    slides.forEach((slide, index) => {
      const isActive = index === activeIndex;

      slide.classList.toggle("is-active", isActive);
      slide.setAttribute("aria-hidden", String(!isActive));
    });

    dots.forEach((dot, index) => {
      const isActive = index === activeIndex;

      dot.classList.toggle("is-active", isActive);
      dot.setAttribute("aria-current", String(isActive));
    });
  };

  const stopAutoplay = () => {
    window.clearInterval(timerId);
  };

  const startAutoplay = () => {
    stopAutoplay();

    if (slides.length > 1 && !window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
      timerId = window.setInterval(() => {
        setActiveSlide(activeIndex + 1);
      }, intervalMs);
    }
  };

  dots.forEach((dot) => {
    dot.addEventListener("click", () => {
      setActiveSlide(Number(dot.dataset.heroSlide || 0));
      startAutoplay();
    });
  });

  heroFlow.addEventListener("mouseenter", stopAutoplay);
  heroFlow.addEventListener("mouseleave", startAutoplay);
  heroFlow.addEventListener("focusin", stopAutoplay);
  heroFlow.addEventListener("focusout", startAutoplay);

  setActiveSlide(activeIndex);
  startAutoplay();
}
