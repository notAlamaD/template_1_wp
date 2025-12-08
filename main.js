(function () {
  const mobileBreakpoint = 960;
  const toggle = document.querySelector('.nav-toggle');
  const nav = document.querySelector('.nav-primary');

  if (!toggle || !nav) return;

  const submenuLinks = nav.querySelectorAll('.menu-item-has-children > a');

  toggle.addEventListener('click', function () {
    const isOpen = toggle.getAttribute('aria-expanded') === 'true';
    toggle.setAttribute('aria-expanded', String(!isOpen));
    nav.classList.toggle('nav-open', !isOpen);

    if (isOpen) {
      submenuLinks.forEach((link) => {
        link.parentElement.classList.remove('nav-sub-open');
        link.setAttribute('aria-expanded', 'false');
      });
    }
  });

  submenuLinks.forEach((link) => {
    link.setAttribute('aria-expanded', 'false');
    link.addEventListener('click', function (event) {
      if (window.innerWidth <= mobileBreakpoint) {
        event.preventDefault();
        const parent = link.parentElement;
        const isOpen = parent.classList.toggle('nav-sub-open');
        link.setAttribute('aria-expanded', String(isOpen));
      }
    });
  });

  window.addEventListener('resize', function () {
    if (window.innerWidth > mobileBreakpoint) {
      toggle.setAttribute('aria-expanded', 'false');
      nav.classList.remove('nav-open');
      submenuLinks.forEach((link) => {
        link.parentElement.classList.remove('nav-sub-open');
        link.setAttribute('aria-expanded', 'false');
      });
    }
  });
})();
