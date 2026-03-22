(function(){
  // Enhanced Custom Cursor
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

    // Enhanced cursor interaction with clickable elements
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
        return typeof element.matches === 'function' && element.matches('a, button, .btn, .carousel-btn, .sector-tab, input[type="submit"], input[type="button"], .read-more, .carousel-dot, input, textarea, select, [onclick], .clickable, [role="button"], [tabindex]:not([tabindex="-1"])');
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

  // Enhanced Navigation with Mobile Menu
  function initNavigation() {
    const navItems = [
      { href: '/index.html', label: 'الرئيسية' },
      { href: '/about.html', label: 'من أنا' },
      { href: '/articles.html', label: 'المقالات' },
      { href: '/timeline.html', label: 'الخط الزمني' },
      { href: '/dictionary.html', label: 'القاموس' },
    ];

    const current = location.pathname.replace(/\/$/, '') || '/index.html';
    const links = navItems.map(item => `<a class="${current === item.href ? 'active' : ''}" href="${item.href}">${item.label}</a>`).join('');

    const headerTarget = document.getElementById('site-header');
    if (headerTarget) {
      headerTarget.innerHTML = `
        <header class="site-shell-header">
          <div class="inner site-top">
            <a class="site-brand" href="/index.html"><i class="fas fa-sparkles"></i><span>أحمد أبو المجد</span></a>
            <nav class="site-links">${links}</nav>
            <div class="site-actions">
              <input id="siteSearch" class="site-search" placeholder="ابحث في الموقع..." aria-label="بحث" />
              <a class="btn-cta" href="/shop.html">تسوّق الآن</a>
              <a class="btn-account" href="/admin/login.php"><i class="fas fa-user"></i></a>
              <button class="mobile-menu-toggle" aria-label="القائمة">
                <i class="fas fa-bars"></i>
              </button>
            </div>
          </div>
        </header>

        <!-- Mobile Menu -->
        <div class="mobile-menu" id="mobileMenu">
          <div class="mobile-menu-content">
            <div class="mobile-menu-links">
              ${navItems.map(item => `<a class="${current === item.href ? 'active' : ''}" href="${item.href}">${item.label}</a>`).join('')}
            </div>
            <div class="mobile-menu-actions">
              <input class="site-search" placeholder="ابحث في الموقع..." aria-label="بحث" style="margin-bottom: 16px;" />
              <a class="btn btn-primary btn-lg" href="/shop.html" style="width: 100%; justify-content: center;">
                <i class="fas fa-shopping-cart"></i> تسوّق الآن
              </a>
              <a class="btn btn-secondary btn-lg" href="/admin/login.php" style="width: 100%; justify-content: center;">
                <i class="fas fa-user"></i> تسجيل الدخول
              </a>
            </div>
          </div>
        </div>
      `;

      // Mobile menu functionality
      const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
      const mobileMenu = document.getElementById('mobileMenu');

      if (mobileMenuToggle && mobileMenu) {
        mobileMenuToggle.addEventListener('click', () => {
          mobileMenu.classList.toggle('show');
        });

        mobileMenu.addEventListener('click', (e) => {
          if (e.target === mobileMenu) {
            mobileMenu.classList.remove('show');
          }
        });
      }

      // Header scroll effect
      const header = document.querySelector('.site-shell-header');
      let lastScrollY = window.scrollY;

      window.addEventListener('scroll', () => {
        if (window.scrollY > 100) {
          header.classList.add('scrolled');
        } else {
          header.classList.remove('scrolled');
        }
        lastScrollY = window.scrollY;
      });

      // Search functionality
      const searchInput = document.getElementById('siteSearch');
      if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', (e) => {
          clearTimeout(searchTimeout);
          searchTimeout = setTimeout(() => {
            performSearch(e.target.value);
          }, 300);
        });

        searchInput.addEventListener('keypress', (e) => {
          if (e.key === 'Enter') {
            e.preventDefault();
            performSearch(searchInput.value);
          }
        });
      }
    }

    // Enhanced footer
    const footerTarget = document.getElementById('site-footer');
    if (footerTarget) {
      footerTarget.innerHTML = `
        <footer class="site-shell-footer">
          <div class="inner">
            <div style="text-align: center; margin-bottom: 20px;">
              <p style="margin: 0; color: var(--site-gray-600);">© 2024 أحمد أبو المجد. جميع الحقوق محفوظة.</p>
            </div>
            <div style="display: flex; justify-content: center; gap: 30px; flex-wrap: wrap;">
              <a href="/index.html" style="color: var(--site-gray-600); text-decoration: none;">الرئيسية</a>
              <a href="/about.html" style="color: var(--site-gray-600); text-decoration: none;">من أنا</a>
              <a href="/articles.html" style="color: var(--site-gray-600); text-decoration: none;">المقالات</a>
              <a href="/timeline.html" style="color: var(--site-gray-600); text-decoration: none;">الخط الزمني</a>
              <a href="/dictionary.html" style="color: var(--site-gray-600); text-decoration: none;">القاموس</a>
              <a href="/admin/login.php" style="color: var(--site-gray-600); text-decoration: none;">تسجيل الدخول</a>
            </div>
          </div>
        </footer>
      `;
    }
  }

  // Search functionality
  function performSearch(query) {
    if (!query.trim()) return;

    // Show loading state
    showToast('جاري البحث...', 'info');

    // Simulate search (replace with actual search logic)
    setTimeout(() => {
      if (query.length > 2) {
        // Redirect to articles page with search
        window.location.href = `/articles.html?search=${encodeURIComponent(query)}`;
      } else {
        showToast('يرجى إدخال كلمة بحث أطول', 'warning');
      }
    }, 500);
  }

  // Toast notification system
  function showToast(message, type = 'info', duration = 4000) {
    // Create toast container if it doesn't exist
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
      toastContainer = document.createElement('div');
      toastContainer.className = 'toast-container';
      document.body.appendChild(toastContainer);
    }

    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;

    const icon = getToastIcon(type);
    toast.innerHTML = `
      <i class="${icon}"></i>
      <div style="flex: 1;">${message}</div>
      <button class="toast-close" aria-label="إغلاق">×</button>
    `;

    toastContainer.appendChild(toast);

    // Show toast
    setTimeout(() => toast.classList.add('show'), 10);

    // Auto hide
    const hideTimeout = setTimeout(() => hideToast(toast), duration);

    // Close button
    const closeBtn = toast.querySelector('.toast-close');
    closeBtn.addEventListener('click', () => {
      clearTimeout(hideTimeout);
      hideToast(toast);
    });
  }

  function hideToast(toast) {
    toast.classList.remove('show');
    setTimeout(() => {
      if (toast.parentNode) {
        toast.parentNode.removeChild(toast);
      }
    }, 300);
  }

  function getToastIcon(type) {
    const icons = {
      success: 'fas fa-check-circle',
      error: 'fas fa-exclamation-circle',
      warning: 'fas fa-exclamation-triangle',
      info: 'fas fa-info-circle'
    };
    return icons[type] || icons.info;
  }

  // Loading states
  function showLoading() {
    let overlay = document.querySelector('.loading-overlay');
    if (!overlay) {
      overlay = document.createElement('div');
      overlay.className = 'loading-overlay';
      overlay.innerHTML = '<div class="loading-spinner"></div>';
      document.body.appendChild(overlay);
    }
    overlay.classList.add('show');
  }

  function hideLoading() {
    const overlay = document.querySelector('.loading-overlay');
    if (overlay) {
      overlay.classList.remove('show');
      setTimeout(() => {
        if (overlay.parentNode) {
          overlay.parentNode.removeChild(overlay);
        }
      }, 300);
    }
  }

  // Enhanced form handling
  function initForms() {
    // Auto-submit search on enter
    document.addEventListener('keydown', (e) => {
      if (e.target.classList.contains('site-search') && e.key === 'Enter') {
        e.preventDefault();
        performSearch(e.target.value);
      }
    });

    // Enhanced form validation
    document.addEventListener('submit', (e) => {
      const form = e.target;
      if (form.classList.contains('needs-validation')) {
        if (!form.checkValidity()) {
          e.preventDefault();
          e.stopPropagation();

          // Show validation errors
          const invalidFields = form.querySelectorAll(':invalid');
          invalidFields.forEach(field => {
            showFieldError(field);
          });

          showToast('يرجى تصحيح الأخطاء في النموذج', 'error');
        } else {
          // Show loading state for form submission
          if (!form.classList.contains('no-loading')) {
            showLoading();
          }
        }
        form.classList.add('was-validated');
      }
    });

    // Real-time validation
    document.addEventListener('blur', (e) => {
      const field = e.target;
      if (field.classList.contains('form-input') || field.classList.contains('form-textarea')) {
        validateField(field);
      }
    });
  }

  function validateField(field) {
    const value = field.value.trim();
    let isValid = true;
    let errorMessage = '';

    // Required validation
    if (field.hasAttribute('required') && !value) {
      isValid = false;
      errorMessage = 'هذا الحقل مطلوب';
    }

    // Email validation
    if (field.type === 'email' && value) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(value)) {
        isValid = false;
        errorMessage = 'يرجى إدخال بريد إلكتروني صحيح';
      }
    }

    // URL validation
    if (field.type === 'url' && value) {
      try {
        new URL(value);
      } catch {
        isValid = false;
        errorMessage = 'يرجى إدخال رابط صحيح';
      }
    }

    // Min length validation
    if (field.hasAttribute('minlength')) {
      const minLength = parseInt(field.getAttribute('minlength'));
      if (value.length < minLength) {
        isValid = false;
        errorMessage = `يجب أن يكون الطول ${minLength} أحرف على الأقل`;
      }
    }

    showFieldError(field, isValid ? '' : errorMessage);
    return isValid;
  }

  function showFieldError(field, message = '') {
    const formGroup = field.closest('.form-group');
    if (!formGroup) return;

    let errorElement = formGroup.querySelector('.form-error');
    if (!errorElement) {
      errorElement = document.createElement('div');
      errorElement.className = 'form-error';
      field.parentNode.appendChild(errorElement);
    }

    if (message) {
      field.classList.add('error');
      errorElement.textContent = message;
      errorElement.classList.add('show');
    } else {
      field.classList.remove('error');
      errorElement.classList.remove('show');
    }
  }

  // Accessibility improvements
  function initAccessibility() {
    // Skip to main content link
    const skipLink = document.createElement('a');
    skipLink.href = '#main-content';
    skipLink.className = 'skip-link';
    skipLink.textContent = 'تخطي إلى المحتوى الرئيسي';
    skipLink.style.cssText = `
      position: absolute;
      top: -40px;
      left: 6px;
      background: var(--site-primary);
      color: white;
      padding: 8px;
      text-decoration: none;
      border-radius: 4px;
      z-index: 1000;
      transition: top 0.3s;
    `;
    document.body.insertBefore(skipLink, document.body.firstChild);

    skipLink.addEventListener('focus', () => {
      skipLink.style.top = '6px';
    });

    skipLink.addEventListener('blur', () => {
      skipLink.style.top = '-40px';
    });

    // Add main content landmark
    const mainContent = document.querySelector('main') || document.querySelector('.container') || document.body;
    if (mainContent && !mainContent.hasAttribute('id')) {
      mainContent.id = 'main-content';
    }

    // Improve focus management for modals
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        // Close mobile menu
        const mobileMenu = document.getElementById('mobileMenu');
        if (mobileMenu && mobileMenu.classList.contains('show')) {
          mobileMenu.classList.remove('show');
        }

        // Close modals
        const modals = document.querySelectorAll('.modal.show');
        modals.forEach(modal => {
          modal.style.display = 'none';
          modal.classList.remove('show');
        });
      }
    });
  }

  // Performance optimizations
  function initPerformance() {
    // Lazy loading for images
    const images = document.querySelectorAll('img[data-src]');
    if ('IntersectionObserver' in window) {
      const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const img = entry.target;
            img.src = img.dataset.src;
            img.classList.remove('loading-skeleton');
            observer.unobserve(img);
          }
        });
      });

      images.forEach(img => {
        img.classList.add('loading-skeleton');
        imageObserver.observe(img);
      });
    } else {
      // Fallback for browsers without IntersectionObserver
      images.forEach(img => {
        img.src = img.dataset.src;
      });
    }

    // Preload critical resources
    const criticalResources = [
      { href: '/static/css/site-layout.css', as: 'style' },
      { href: '/static/js/site-layout.js', as: 'script' }
    ];

    criticalResources.forEach(resource => {
      const link = document.createElement('link');
      link.rel = 'preload';
      link.href = resource.href;
      link.as = resource.as;
      document.head.appendChild(link);
    });
  }

  // Initialize all features
  function init() {
    initCustomCursor();
    initNavigation();
    initForms();
    initAccessibility();
    initPerformance();

    // Add loading class to body initially
    document.body.classList.add('loaded');

    // Global error handling
    window.addEventListener('error', (e) => {
      console.error('JavaScript error:', e.error);
      showToast('حدث خطأ غير متوقع. يرجى تحديث الصفحة.', 'error');
    });

    window.addEventListener('unhandledrejection', (e) => {
      console.error('Unhandled promise rejection:', e.reason);
      showToast('حدث خطأ في الطلب. يرجى المحاولة مرة أخرى.', 'error');
    });
  }

  // Initialize on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  // Export functions for global use
  window.SiteUX = {
    showToast,
    showLoading,
    hideLoading,
    showFieldError,
    validateField
  };
})();
