function initBurgerMenu() {
  const burgerBtn = document.getElementById("burger-btn");
  const mainNav = document.getElementById("main-nav");
  const burgerIcon = document.getElementById("burger-icon");
  const closeIcon = document.getElementById("close-icon");

  if (!burgerBtn || !mainNav || !burgerIcon || !closeIcon) {
    return;
  }

  const resetMenu = () => {
    mainNav.classList.remove("open");
    document.body.classList.remove("no-scroll");
    burgerIcon.classList.remove("hidden");
    closeIcon.classList.add("hidden");
  };

  resetMenu();

  burgerBtn.addEventListener("click", (e) => {
    e.preventDefault();
    mainNav.classList.toggle("open");
    document.body.classList.toggle("no-scroll");
    burgerIcon.classList.toggle("hidden");
    closeIcon.classList.toggle("hidden");
  });

  window.addEventListener("resize", () => {
    if (window.innerWidth > 900 && mainNav.classList.contains("open")) {
      resetMenu();
    }
  });

  mainNav.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => {
      resetMenu();
    });
  });
}

document.addEventListener("turbo:load", () => {
  initBurgerMenu();
});
