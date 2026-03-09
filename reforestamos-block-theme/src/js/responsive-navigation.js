/**
 * Responsive Navigation JavaScript
 * Handles mobile menu, off-canvas navigation, and keyboard accessibility
 * 
 * @package Reforestamos
 */

(function() {
  'use strict';

  // Wait for DOM to be ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initResponsiveNavigation);
  } else {
    initResponsiveNavigation();
  }

  function initResponsiveNavigation() {
    // Initialize mobile menu
    initMobileMenu();
    
    // Initialize keyboard navigation
    initKeyboardNavigation();
    
    // Initialize focus trap for mobile menu
    initFocusTrap();
    
    // Initialize submenu toggles
    initSubmenuToggles();
  }

  /**
   * Initialize mobile menu toggle
   */
  function initMobileMenu() {
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const offCanvasMenu = document.querySelector('.off-canvas-menu');
    const offCanvasOverlay = document.querySelector('.off-canvas-overlay');
    const closeButton = document.querySelector('.off-canvas-close');

    if (!menuToggle || !offCanvasMenu) return;

    // Toggle menu
    menuToggle.addEventListener('click', function() {
      const isOpen = this.getAttribute('aria-expanded') === 'true';
      toggleMenu(!isOpen);
    });

    // Close menu when clicking overlay
    if (offCanvasOverlay) {
      offCanvasOverlay.addEventListener('click', function() {
        toggleMenu(false);
      });
    }

    // Close menu when clicking close button
    if (closeButton) {
      closeButton.addEventListener('click', function() {
        toggleMenu(false);
      });
    }

    // Close menu on escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && offCanvasMenu.classList.contains('is-open')) {
        toggleMenu(false);
        menuToggle.focus();
      }
    });

    function toggleMenu(open) {
      menuToggle.setAttribute('aria-expanded', open);
      offCanvasMenu.classList.toggle('is-open', open);
      
      if (offCanvasOverlay) {
        offCanvasOverlay.classList.toggle('is-visible', open);
      }

      document.body.classList.toggle('menu-open', open);

      // Set focus to first menu item when opening
      if (open) {
        const firstMenuItem = offCanvasMenu.querySelector('a');
        if (firstMenuItem) {
          setTimeout(() => firstMenuItem.focus(), 100);
        }
      }

      // Announce to screen readers
      announceToScreenReader(open ? 'Menu opened' : 'Menu closed');
    }
  }

  /**
   * Initialize keyboard navigation
   */
  function initKeyboardNavigation() {
    const menuItems = document.querySelectorAll('.desktop-nav-menu .menu-item-has-children');

    menuItems.forEach(function(item) {
      const link = item.querySelector('> a');
      const submenu = item.querySelector('.sub-menu');

      if (!link || !submenu) return;

      // Open submenu on Enter or Space
      link.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          const isExpanded = this.getAttribute('aria-expanded') === 'true';
          this.setAttribute('aria-expanded', !isExpanded);
          submenu.style.display = !isExpanded ? 'block' : 'none';
        }
      });

      // Close submenu on Escape
      submenu.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
          link.setAttribute('aria-expanded', 'false');
          submenu.style.display = 'none';
          link.focus();
        }
      });

      // Arrow key navigation within submenu
      const submenuLinks = submenu.querySelectorAll('a');
      submenuLinks.forEach(function(submenuLink, index) {
        submenuLink.addEventListener('keydown', function(e) {
          if (e.key === 'ArrowDown') {
            e.preventDefault();
            const nextLink = submenuLinks[index + 1];
            if (nextLink) nextLink.focus();
          } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (index === 0) {
              link.focus();
            } else {
              const prevLink = submenuLinks[index - 1];
              if (prevLink) prevLink.focus();
            }
          }
        });
      });
    });

    // Add keyboard navigation indicator
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Tab') {
        document.body.classList.add('keyboard-navigation-active');
      }
    });

    document.addEventListener('mousedown', function() {
      document.body.classList.remove('keyboard-navigation-active');
    });
  }

  /**
   * Initialize focus trap for mobile menu
   */
  function initFocusTrap() {
    const offCanvasMenu = document.querySelector('.off-canvas-menu');
    if (!offCanvasMenu) return;

    offCanvasMenu.addEventListener('keydown', function(e) {
      if (e.key !== 'Tab') return;

      const focusableElements = offCanvasMenu.querySelectorAll(
        'a[href], button:not([disabled]), textarea:not([disabled]), input:not([disabled]), select:not([disabled])'
      );

      const firstElement = focusableElements[0];
      const lastElement = focusableElements[focusableElements.length - 1];

      if (e.shiftKey && document.activeElement === firstElement) {
        e.preventDefault();
        lastElement.focus();
      } else if (!e.shiftKey && document.activeElement === lastElement) {
        e.preventDefault();
        firstElement.focus();
      }
    });
  }

  /**
   * Initialize submenu toggles for mobile
   */
  function initSubmenuToggles() {
    const mobileMenuItems = document.querySelectorAll('.mobile-nav-menu .menu-item-has-children > a');

    mobileMenuItems.forEach(function(link) {
      const submenu = link.nextElementSibling;
      if (!submenu || !submenu.classList.contains('sub-menu')) return;

      // Make link toggle submenu instead of navigating
      link.addEventListener('click', function(e) {
        e.preventDefault();
        const isExpanded = this.getAttribute('aria-expanded') === 'true';
        this.setAttribute('aria-expanded', !isExpanded);
        submenu.classList.toggle('is-open', !isExpanded);
      });

      // Initialize aria-expanded
      link.setAttribute('aria-expanded', 'false');
    });
  }

  /**
   * Announce message to screen readers
   */
  function announceToScreenReader(message) {
    const announcement = document.createElement('div');
    announcement.setAttribute('role', 'status');
    announcement.setAttribute('aria-live', 'polite');
    announcement.className = 'sr-only';
    announcement.textContent = message;
    document.body.appendChild(announcement);

    setTimeout(function() {
      document.body.removeChild(announcement);
    }, 1000);
  }

  /**
   * Handle sticky header on scroll
   */
  function initStickyHeader() {
    const header = document.querySelector('.reforestamos-header-navbar.sticky-header');
    if (!header) return;

    let lastScroll = 0;
    const scrollThreshold = 100;

    window.addEventListener('scroll', function() {
      const currentScroll = window.pageYOffset;

      if (currentScroll > scrollThreshold) {
        header.classList.add('scrolled');
        header.classList.remove('at-top');
      } else {
        header.classList.remove('scrolled');
        header.classList.add('at-top');
      }

      lastScroll = currentScroll;
    });

    // Initialize state
    if (window.pageYOffset <= scrollThreshold) {
      header.classList.add('at-top');
    }
  }

  // Initialize sticky header
  initStickyHeader();

  /**
   * Handle window resize
   */
  let resizeTimer;
  window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function() {
      // Close mobile menu on resize to desktop
      if (window.innerWidth >= 992) {
        const offCanvasMenu = document.querySelector('.off-canvas-menu');
        const offCanvasOverlay = document.querySelector('.off-canvas-overlay');
        const menuToggle = document.querySelector('.mobile-menu-toggle');

        if (offCanvasMenu && offCanvasMenu.classList.contains('is-open')) {
          offCanvasMenu.classList.remove('is-open');
          if (offCanvasOverlay) {
            offCanvasOverlay.classList.remove('is-visible');
          }
          if (menuToggle) {
            menuToggle.setAttribute('aria-expanded', 'false');
          }
          document.body.classList.remove('menu-open');
        }
      }
    }, 250);
  });

})();
