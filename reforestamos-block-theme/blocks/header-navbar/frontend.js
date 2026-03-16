/**
 * Header Navbar Frontend JavaScript
 * Handles sticky header and transparent-on-top behavior
 *
 * @package
 */

document.addEventListener('DOMContentLoaded', function () {
	const headers = document.querySelectorAll('.reforestamos-header-navbar');

	headers.forEach((header) => {
		const isSticky = header.dataset.sticky === 'true';
		const isTransparentOnTop = header.dataset.transparentOnTop === 'true';

		if (isTransparentOnTop) {
			handleTransparentHeader(header);
		}

		// Handle language switcher
		handleLanguageSwitcher(header);

		// Handle mobile menu accessibility
		handleMobileMenu(header);
	});
});

/**
 * Handle transparent header on scroll
 * @param header
 */
function handleTransparentHeader(header) {
	const updateHeaderState = () => {
		const scrollPosition = window.scrollY;

		if (scrollPosition < 50) {
			header.classList.add('at-top');
			header.classList.remove('scrolled');
		} else {
			header.classList.remove('at-top');
			header.classList.add('scrolled');
		}
	};

	// Initial check
	updateHeaderState();

	// Update on scroll
	let ticking = false;
	window.addEventListener('scroll', () => {
		if (!ticking) {
			window.requestAnimationFrame(() => {
				updateHeaderState();
				ticking = false;
			});
			ticking = true;
		}
	});
}

/**
 * Handle language switcher functionality
 * @param header
 */
function handleLanguageSwitcher(header) {
	const languageButtons = header.querySelectorAll('.language-btn');

	languageButtons.forEach((button) => {
		button.addEventListener('click', function (e) {
			e.preventDefault();
			const lang = this.dataset.lang;

			// Remove active class from all buttons
			languageButtons.forEach((btn) => btn.classList.remove('active'));

			// Add active class to clicked button
			this.classList.add('active');

			// Store language preference
			localStorage.setItem('reforestamos_language', lang);

			// Trigger custom event for language change
			const event = new CustomEvent('reforestamosLanguageChange', {
				detail: { language: lang },
			});
			document.dispatchEvent(event);

			// TODO: Implement actual language switching logic
			// This could integrate with WPML, Polylang, or custom translation system
			console.log('Language changed to:', lang);
		});
	});

	// Set initial active state based on stored preference or default
	const storedLang = localStorage.getItem('reforestamos_language') || 'es';
	const activeButton = header.querySelector(
		`.language-btn[data-lang="${storedLang}"]`
	);
	if (activeButton) {
		activeButton.classList.add('active');
	}
}

/**
 * Handle mobile menu accessibility
 * @param header
 */
function handleMobileMenu(header) {
	const toggler = header.querySelector('.navbar-toggler');
	const collapse = header.querySelector('.navbar-collapse');

	if (!toggler || !collapse) return;

	// Handle keyboard navigation
	toggler.addEventListener('keydown', function (e) {
		if (e.key === 'Enter' || e.key === ' ') {
			e.preventDefault();
			toggler.click();
		}
	});

	// Close menu when clicking outside
	document.addEventListener('click', function (e) {
		if (!header.contains(e.target) && collapse.classList.contains('show')) {
			const bsCollapse = bootstrap.Collapse.getInstance(collapse);
			if (bsCollapse) {
				bsCollapse.hide();
			}
		}
	});

	// Close menu on escape key
	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape' && collapse.classList.contains('show')) {
			const bsCollapse = bootstrap.Collapse.getInstance(collapse);
			if (bsCollapse) {
				bsCollapse.hide();
			}
			toggler.focus();
		}
	});

	// Handle submenu toggle on mobile
	const menuItemsWithChildren = header.querySelectorAll(
		'.menu-item-has-children'
	);
	menuItemsWithChildren.forEach((item) => {
		const link = item.querySelector('.nav-link');
		const submenu = item.querySelector('.sub-menu');

		if (link && submenu) {
			// Add aria attributes
			link.setAttribute('aria-haspopup', 'true');
			link.setAttribute('aria-expanded', 'false');

			// Toggle submenu on click (mobile)
			link.addEventListener('click', function (e) {
				if (window.innerWidth <= 991) {
					e.preventDefault();
					const isExpanded =
						link.getAttribute('aria-expanded') === 'true';
					link.setAttribute('aria-expanded', !isExpanded);
					submenu.style.display = isExpanded ? 'none' : 'block';
				}
			});
		}
	});
}

/**
 * Render WordPress menu in the navbar
 * This function is called by PHP to inject the menu HTML
 * @param menuId
 * @param menuHtml
 */
window.reforestamosRenderMenu = function (menuId, menuHtml) {
	const container = document.querySelector(
		`.navbar-menu-container[data-menu-id="${menuId}"]`
	);
	if (container) {
		container.innerHTML = menuHtml;
	}
};
