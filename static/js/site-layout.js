(function(){
  // Custom Cursor
  function initCustomCursor() {
    // Create cursor element if it doesn't exist
    if (!document.getElementById('customCursor')) {
      const cursorDiv = document.createElement('div');
      cursorDiv.id = 'customCursor';
      cursorDiv.className = 'custom-cursor';
      document.body.prepend(cursorDiv);
    }

    const customCursor = document.getElementById('customCursor');
    let mouseX = 0;
    let mouseY = 0;

    document.addEventListener('mousemove', (e) => {
      mouseX = e.clientX;
      mouseY = e.clientY;
      customCursor.style.left = mouseX + 'px';
      customCursor.style.top = mouseY + 'px';
    });

    // Cursor interaction with clickable elements
    document.addEventListener('mouseenter', (e) => {
      const target = e.target;
      if (isClickable(target)) {
        customCursor.classList.add('active');
      }
    }, true);

    document.addEventListener('mouseleave', (e) => {
      const target = e.target;
      if (isClickable(target)) {
        customCursor.classList.remove('active');
      }
    }, true);

    function isClickable(element) {
      if (!element || element.nodeType !== 1) return false;
      try {
        return typeof element.matches === 'function' && element.matches('a, button, .btn, .carousel-btn, .sector-tab, input[type="submit"], input[type="button"], .read-more, .carousel-dot, input, textarea, select, [onclick], .clickable');
      } catch (e) {
        return false;
      }
    }

    // Hide cursor on mouse leave
    document.addEventListener('mouseenter', () => {
      customCursor.style.opacity = '1';
    });

    document.addEventListener('mouseleave', () => {
      customCursor.style.opacity = '0';
    });
  }

  // Initialize on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCustomCursor);
  } else {
    initCustomCursor();
  }

  const navItems = [
    { href: '/index.html', label: 'الرئيسية' },
    { href: '/about.html', label: 'من أنا' },
    { href: '/articles.html', label: 'المقالات' },
    // { href: '/courses.html', label: 'الدورات' }, // commented per request
    { href: '/timeline.html', label: 'الخط الزمني' },
    { href: '/dictionary.html', label: 'القاموس' },
    // { href: '/influencers.html', label: 'المؤثرون' }, // commented per request
    // removed: shop, products, tags, search entries to simplify navbar
  ];
  const current = location.pathname.replace(/\/$/, '') || '/index.html';
  const links = navItems.map(item => `<a class="${current === item.href ? 'active' : ''}" href="${item.href}">${item.label}</a>`).join('');

  const headerTarget = document.getElementById('site-header');
  if (headerTarget) {
    /*
      New creative header was added earlier. User requested to deactivate it using comments.
      The block below is intentionally commented out to restore the previous simple header behavior.

    headerTarget.innerHTML = `
      <header class="site-shell-header">
        <div class="inner site-top">
          <a class="site-brand" href="/index.html"><i class="fas fa-sparkles"></i><span>أحمد أبو المجد</span></a>
          <nav class="site-links">${links}</nav>
          <div class="site-actions">
            <input id="siteSearch" class="site-search" placeholder="ابحث في الموقع..." aria-label="بحث" />
            <a class="btn-cta" href="/shop.html">تسوّق الآن</a>
            <a class="btn-account" href="/admin/login.php"><i class="fas fa-user"></i></a>
          </div>
        </div>
      </header>
      `;

    // Small hero on homepage only
    if (current === '/index.html' || current === '/' ) {
      const heroHtml = `
        <section class="shared-hero">
          <div class="inner">
            <h1>مرحباً بكم في موقعنا — تصميم إبداعي وتجربة متميزة</h1>
            <p>نقدّم محتوى، دورات، ومجتمعاً نابضاً. انطلق الآن.</p>
            <div class="hero-ctas"><a class="btn-cta" href="/courses.html">ابدأ التعلم</a><a class="btn-cta ghost" href="/influencers.html">اعرف المؤثرين</a></div>
          </div>
        </section>
      `;
      headerTarget.insertAdjacentHTML('afterend', heroHtml);
    }

    // Wire up search box
    const searchEl = document.getElementById('siteSearch');
    if (searchEl) {
      searchEl.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
          const q = searchEl.value.trim();
          if (q) location.href = '/articles.html?search=' + encodeURIComponent(q);
        }
      });
    }

    */

    // Fallback simple header to keep site usable
    headerTarget.innerHTML = '<header class="site-shell-header"><div class="inner site-top"><a class="site-brand" href="/index.html"><span>أحمد ابو المجد</span></a><nav class="site-links">' + links + '</nav><div class="site-actions"><a class="btn-account" href="/admin/login.php"><i class="fas fa-user"></i></a></div></div></header>';
  }

  const footerTarget = document.getElementById('site-footer');
  if (footerTarget) {
    footerTarget.innerHTML = `<footer class="site-shell-footer"><div class="inner"><p>واجهة موحدة بتصميم إبداعي ✨</p><div><a href="/index.html">الرئيسية</a> • <a href="/articles.html">المحتوى</a> • <a href="/courses.html">الأكاديمية</a></div></div></footer>`;
  }
  document.body.classList.add('with-shared-layout');
})();
