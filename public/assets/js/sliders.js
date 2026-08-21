const heroFlow = document.querySelector("[data-hero-flow]");

if (heroFlow) {
  const slides = Array.from(heroFlow.querySelectorAll(".hero-flow__slide"));
  const dots = Array.from(heroFlow.querySelectorAll("[data-hero-slide]"));
  const intervalMs = 7200;
  const transitionMs = 1050;
  let activeIndex = slides.findIndex((slide) => slide.classList.contains("is-active"));
  let timerId;
  let transitionTimerId;

  if (activeIndex < 0) {
    activeIndex = 0;
  }

  slides.forEach((slide) => {
    const imageUrl = slide.dataset.image;

    if (!imageUrl) {
      return;
    }

    const image = slide.querySelector(".hero-flow__image");
    if (image) {
      image.style.backgroundImage = `url("${imageUrl}")`;
    }
  });

  const setActiveSlide = (nextIndex) => {
    const nextActiveIndex = (nextIndex + slides.length) % slides.length;

    if (nextActiveIndex === activeIndex) {
      return;
    }

    const currentSlide = slides[activeIndex];
    const nextSlide = slides[nextActiveIndex];

    window.clearTimeout(transitionTimerId);

    slides.forEach((slide) => {
      if (slide !== currentSlide && slide !== nextSlide) {
        slide.classList.remove("is-active", "is-leaving");
        slide.setAttribute("aria-hidden", "true");
      }
    });

    currentSlide.classList.remove("is-active");
    currentSlide.classList.add("is-leaving");
    currentSlide.setAttribute("aria-hidden", "true");

    nextSlide.classList.remove("is-leaving");
    nextSlide.classList.add("is-active");
    nextSlide.setAttribute("aria-hidden", "false");

    activeIndex = nextActiveIndex;

    transitionTimerId = window.setTimeout(() => {
      currentSlide.classList.remove("is-leaving");
      currentSlide.setAttribute("aria-hidden", "true");
    }, transitionMs);

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

  setActiveSlide(activeIndex);
  startAutoplay();
}
