const navToggle = document.querySelector(".nav-toggle");
const primaryNav = document.querySelector(".primary-nav");
const menuTriggers = document.querySelectorAll(".primary-nav__trigger");
const siteHeader = document.querySelector(".site-header");

if (siteHeader) {
  let scrollUpdateQueued = false;

  const updateHeaderScrollState = () => {
    siteHeader.classList.toggle("is-scrolled", window.scrollY > 12);
    scrollUpdateQueued = false;
  };

  window.addEventListener("scroll", () => {
    if (scrollUpdateQueued) {
      return;
    }

    scrollUpdateQueued = true;
    window.requestAnimationFrame(updateHeaderScrollState);
  }, { passive: true });

  updateHeaderScrollState();
}

const closeMenus = () => {
  menuTriggers.forEach((trigger) => {
    trigger.setAttribute("aria-expanded", "false");
    trigger.closest(".has-menu")?.classList.remove("is-open");
  });
};

if (navToggle && primaryNav) {
  navToggle.addEventListener("click", () => {
    const isOpen = navToggle.getAttribute("aria-expanded") === "true";

    navToggle.setAttribute("aria-expanded", String(!isOpen));
    navToggle.setAttribute("aria-label", isOpen ? "Open navigation menu" : "Close navigation menu");
    primaryNav.classList.toggle("is-open", !isOpen);
  });

  primaryNav.addEventListener("click", (event) => {
    if (!(event.target instanceof HTMLAnchorElement)) {
      return;
    }

    navToggle.setAttribute("aria-expanded", "false");
    navToggle.setAttribute("aria-label", "Open navigation menu");
    primaryNav.classList.remove("is-open");
    closeMenus();
  });
}

menuTriggers.forEach((trigger) => {
  trigger.addEventListener("click", () => {
    const isOpen = trigger.getAttribute("aria-expanded") === "true";

    closeMenus();
    trigger.setAttribute("aria-expanded", String(!isOpen));
    trigger.closest(".has-menu")?.classList.toggle("is-open", !isOpen);
  });
});

document.addEventListener("click", (event) => {
  if (event.target instanceof Element && event.target.closest(".primary-nav__item.has-menu")) {
    return;
  }

  closeMenus();
});

document.addEventListener("keydown", (event) => {
  if (event.key !== "Escape") {
    return;
  }

  closeMenus();
  navToggle?.setAttribute("aria-expanded", "false");
  navToggle?.setAttribute("aria-label", "Open navigation menu");
  primaryNav?.classList.remove("is-open");
});
