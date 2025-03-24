function initAdminBurgerMenu() {
  const burgerOpenBtn = document.getElementById("admin-burger-btn");
  const burgerCloseBtn = document.getElementById("admin-close-btn");
  const sidebar = document.querySelector(".admin-sidebar");
  const overlay = document.getElementById("burger-overlay");

  if (!burgerOpenBtn || !burgerCloseBtn || !sidebar || !overlay) {
    return;
  }

  const resetMenu = () => {
    sidebar.classList.remove("open");
    overlay.classList.remove("show");
    document.body.classList.remove("no-scroll");
  };

  resetMenu();

  burgerOpenBtn.addEventListener("click", (e) => {
    e.preventDefault();
    sidebar.classList.add("open");
    overlay.classList.add("show");
    document.body.classList.add("no-scroll");
  });

  burgerCloseBtn.addEventListener("click", (e) => {
    e.preventDefault();
    resetMenu();
  });

  overlay.addEventListener("click", () => {
    resetMenu();
  });

  window.addEventListener("resize", () => {
    if (window.innerWidth > 900 && sidebar.classList.contains("open")) {
      resetMenu();
    }
  });

  sidebar.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => {
      resetMenu();
    });
  });
}

document.addEventListener("turbo:load", () => {
  initAdminBurgerMenu();
});
