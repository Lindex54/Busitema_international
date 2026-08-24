(() => {
  const counters = document.querySelectorAll("[data-count-up]");

  if (!counters.length) {
    return;
  }

  const formatter = new Intl.NumberFormat("en-US");
  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  const showFinalValue = (counter) => {
    const target = Number(counter.dataset.countTo || 0);
    const suffix = counter.dataset.countSuffix || "";
    counter.textContent = `${formatter.format(target)}${suffix}`;
  };

  const animateCounter = (counter) => {
    if (counter.dataset.counted === "true") {
      return;
    }

    counter.dataset.counted = "true";

    if (reduceMotion) {
      showFinalValue(counter);
      return;
    }

    const target = Number(counter.dataset.countTo || 0);
    const suffix = counter.dataset.countSuffix || "";
    const duration = target > 10000 ? 1800 : 1300;
    const startTime = performance.now();

    const update = (currentTime) => {
      const progress = Math.min((currentTime - startTime) / duration, 1);
      const easedProgress = 1 - Math.pow(1 - progress, 3);
      const currentValue = Math.round(target * easedProgress);

      counter.textContent = `${formatter.format(currentValue)}${suffix}`;

      if (progress < 1) {
        requestAnimationFrame(update);
      } else {
        showFinalValue(counter);
      }
    };

    counter.textContent = `0${suffix}`;
    requestAnimationFrame(update);
  };

  if (!("IntersectionObserver" in window)) {
    counters.forEach(animateCounter);
    return;
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) {
        return;
      }

      animateCounter(entry.target);
      observer.unobserve(entry.target);
    });
  }, { threshold: 0.45 });

  counters.forEach((counter) => observer.observe(counter));
})();

(() => {
  const showcase = document.querySelector("[data-uganda-showcase]");

  if (!showcase) {
    return;
  }

  const stories = [...showcase.querySelectorAll("[data-uganda-story]")];
  const controls = [...showcase.querySelectorAll("[data-uganda-target]")];
  const menu = showcase.querySelector(".uganda-showcase__menu");
  const description = showcase.querySelector("[data-uganda-description]");
  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  let activeIndex = -1;
  let typingTimer;

  const typeDescription = (text) => {
    window.clearInterval(typingTimer);

    if (reduceMotion) {
      description.textContent = text;
      return;
    }

    let characterIndex = 0;
    description.textContent = "";

    typingTimer = window.setInterval(() => {
      characterIndex += 1;
      description.textContent = text.slice(0, characterIndex);

      if (characterIndex >= text.length) {
        window.clearInterval(typingTimer);
      }
    }, 16);
  };

  const activate = (index) => {
    if (!stories[index] || index === activeIndex && stories[index].classList.contains("is-active")) {
      return;
    }

    activeIndex = index;

    stories.forEach((story, storyIndex) => {
      story.classList.toggle("is-active", storyIndex === index);
    });

    controls.forEach((control, controlIndex) => {
      const isActive = controlIndex === index;
      control.classList.toggle("is-active", isActive);
      control.setAttribute("aria-current", String(isActive));

      if (isActive && menu) {
        const centeredPosition = control.offsetLeft - (menu.clientWidth - control.offsetWidth) / 2;
        menu.scrollTo({
          left: Math.max(0, centeredPosition),
          behavior: reduceMotion ? "auto" : "smooth",
        });
      }
    });

    typeDescription(stories[index].dataset.ugandaDescription);
  };

  controls.forEach((control) => {
    control.addEventListener("click", () => {
      const index = Number(control.dataset.ugandaTarget);
      stories[index]?.scrollIntoView({ behavior: "smooth", block: "center" });
    });
  });

  if (!("IntersectionObserver" in window)) {
    return;
  }

  const observer = new IntersectionObserver((entries) => {
    const visibleEntry = entries
      .filter((entry) => entry.isIntersecting)
      .sort((a, b) => b.intersectionRatio - a.intersectionRatio)[0];

    if (visibleEntry) {
      activate(Number(visibleEntry.target.dataset.ugandaStory));
    }
  }, {
    rootMargin: "-36% 0px -36% 0px",
    threshold: [0.15, 0.35, 0.6],
  });

  stories.forEach((story) => observer.observe(story));
})();
