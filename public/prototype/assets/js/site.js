/* =============================================
   THERAPISTLYSANDER.COM — Site JS
   ============================================= */

document.addEventListener('DOMContentLoaded', () => {

  // --- Scroll progress bar ---
  const progressBar = document.createElement('div');
  progressBar.className = 'scroll-progress';
  document.body.prepend(progressBar);
  window.addEventListener('scroll', () => {
    const scrollTop = document.documentElement.scrollTop;
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    const pct = docHeight ? (scrollTop / docHeight) * 100 : 0;
    progressBar.style.width = pct + '%';
  });

  // --- Sticky nav ---
  const nav = document.querySelector('.nav');
  if (nav) {
    window.addEventListener('scroll', () => {
      nav.classList.toggle('scrolled', window.scrollY > 20);
    });
  }

  // --- Mobile burger menu ---
  const burger = document.querySelector('.nav__burger');
  const mobileMenu = document.querySelector('.nav__mobile');
  if (burger && mobileMenu) {
    burger.addEventListener('click', () => {
      const open = burger.classList.toggle('open');
      mobileMenu.classList.toggle('open', open);
      document.body.style.overflow = open ? 'hidden' : '';
    });
    mobileMenu.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        burger.classList.remove('open');
        mobileMenu.classList.remove('open');
        document.body.style.overflow = '';
      });
    });
  }

  // --- Active nav link ---
  const currentPath = window.location.pathname;
  document.querySelectorAll('.nav__link, .nav__mobile a').forEach(link => {
    const href = link.getAttribute('href') || '';
    const isHome = (href === './index.html' || href === '../index.html' || href === '/') &&
                   (currentPath === '/' || currentPath.endsWith('/index.html') || currentPath.endsWith('/therapy/'));
    const pageMatch = href !== '#' && href !== '' && currentPath.includes(href.replace('./','').replace('../','').replace('index.html',''));
    if (isHome || (pageMatch && href !== './index.html' && href !== '../index.html')) {
      link.classList.add('active');
    }
  });

  // --- Fade-in on scroll ---
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

  document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));

  // --- Toast helper ---
  window.showToast = function(msg, type = 'info') {
    let toast = document.querySelector('.toast');
    if (!toast) {
      toast = document.createElement('div');
      toast.className = 'toast';
      document.body.appendChild(toast);
    }
    toast.className = `toast toast--${type}`;
    toast.textContent = msg;
    requestAnimationFrame(() => toast.classList.add('show'));
    clearTimeout(toast._timer);
    toast._timer = setTimeout(() => toast.classList.remove('show'), 4000);
  };

});
