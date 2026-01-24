/* ================= THEME LOAD (PAGE LOAD) ================= */
try {
  const savedTheme = localStorage.getItem("theme");
  if (savedTheme) {
    document.documentElement.setAttribute("data-bs-theme", savedTheme);
  } else {
    document.documentElement.setAttribute("data-bs-theme", "light");
  }
} catch (e) {}

/* ================= DROPDOWN ================= */
try {
  var dropdownMenus = document.querySelectorAll(".dropdown-menu.stop");
  dropdownMenus.forEach(function (e) {
    e.addEventListener("click", function (e) {
      e.stopPropagation();
    });
  });
} catch (e) {}

/* ================= ICONS ================= */
try {
  lucide.createIcons();
} catch (e) {}

/* ================= LIGHT / DARK TOGGLE (WITH localStorage) ================= */
try {
  var themeColorToggle = document.getElementById("light-dark-mode");

  themeColorToggle &&
    themeColorToggle.addEventListener("click", function () {
      let currentTheme =
        document.documentElement.getAttribute("data-bs-theme") || "light";

      let newTheme = currentTheme === "light" ? "dark" : "light";

      document.documentElement.setAttribute("data-bs-theme", newTheme);

      // 🔥 localStorage এ save
      localStorage.setItem("theme", newTheme);
    });
} catch (e) {}

/* ================= SIDEBAR ================= */
try {
  var collapsedToggle = document.querySelector(".mobile-menu-btn");
  const h = document.querySelector(".startbar-overlay"),
    changeSidebarSize =
      (collapsedToggle?.addEventListener("click", function () {
        "collapsed" == document.body.getAttribute("data-sidebar-size")
          ? document.body.setAttribute("data-sidebar-size", "default")
          : document.body.setAttribute("data-sidebar-size", "collapsed");
      }),
      h &&
        h.addEventListener("click", () => {
          document.body.setAttribute("data-sidebar-size", "collapsed");
        }),
      () => {
        310 <= window.innerWidth && window.innerWidth <= 1440
          ? document.body.setAttribute("data-sidebar-size", "collapsed")
          : document.body.setAttribute("data-sidebar-size", "default");
      });
  window.addEventListener("resize", () => {
    changeSidebarSize();
  });
  changeSidebarSize();
} catch (e) {}

/* ================= TOOLTIP / POPOVER ================= */
try {
  const k = document.querySelectorAll('[data-bs-toggle="tooltip"]');
  [...k].map((e) => new bootstrap.Tooltip(e));

  var popoverTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="popover"]'),
  );
  popoverTriggerList.map(function (e) {
    return new bootstrap.Popover(e);
  });
} catch (e) {}

/* ================= SCROLL ================= */
function windowScroll() {
  var e = document.getElementById("topbar-custom");
  null != e &&
    (50 <= document.body.scrollTop || 50 <= document.documentElement.scrollTop
      ? e.classList.add("nav-sticky")
      : e.classList.remove("nav-sticky"));
}
window.addEventListener("scroll", (e) => {
  e.preventDefault();
  windowScroll();
});

/* ================= MENU ================= */
const initVerticalMenu = () => {
  var e = document.querySelectorAll(".navbar-nav li .collapse");

  document
    .querySelectorAll(".navbar-nav li [data-bs-toggle='collapse']")
    .forEach((e) => {
      e.addEventListener("click", function (e) {
        e.preventDefault();
      });
    });

  e.forEach((e) => {
    e.addEventListener("show.bs.collapse", function (t) {
      const o = t.target.closest(".collapse.show");
      document.querySelectorAll(".navbar-nav .collapse.show").forEach((e) => {
        e !== t.target && e !== o && new bootstrap.Collapse(e).hide();
      });
    });
  });

  document.querySelector(".navbar-nav") &&
    document.querySelectorAll(".navbar-nav a").forEach(function (t) {
      var e = window.location.href.split(/[?#]/)[0];
      if (t.href === e) {
        t.classList.add("active");
        t.parentNode.classList.add("active");
        let e = t.closest(".collapse");
        for (; e; )
          (e.classList.add("show"),
            e.parentElement.children[0].classList.add("active"),
            e.parentElement.children[0].setAttribute("aria-expanded", "true"),
            (e = e.parentElement.closest(".collapse")));
      }
    });
};
initVerticalMenu();
