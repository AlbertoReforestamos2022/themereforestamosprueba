/**
 * Search Filters JavaScript
 * 
 * @package Reforestamos
 */

(function() {
    'use strict';
    
    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        initSearchFilters();
        initSearchHighlighting();
    });
    
    /**
     * Initialize search filters
     */
    function initSearchFilters() {
        const filterForm = document.querySelector('.search-filters-form');
        
        if (!filterForm) {
            return;
        }
        
        // Handle filter changes
        const filterSelects = filterForm.querySelectorAll('select');
        filterSelects.forEach(select => {
            select.addEventListener('change', function() {
                updateSearchResults();
            });
        });
        
        // Handle filter button click
        const filterButton = filterForm.querySelector('.filter-button');
        if (filterButton) {
            filterButton.addEventListener('click', function(e) {
                e.preventDefault();
                updateSearchResults();
            });
        }
    }
    
    /**
     * Update search results based on filters
     */
    function updateSearchResults() {
        const filterForm = document.querySelector('.search-filters-form');
        if (!filterForm) {
            return;
        }
        
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);
        
        // Add current search query
        const searchQuery = new URLSearchParams(window.location.search).get('s');
        if (searchQuery) {
            params.set('s', searchQuery);
        }
        
        // Update URL and reload
        window.location.search = params.toString();
    }
    
    /**
     * Initialize search term highlighting
     */
    function initSearchHighlighting() {
        if (!document.body.classList.contains('search')) {
            return;
        }
        
        const searchQuery = new URLSearchParams(window.location.search).get('s');
        if (!searchQuery) {
            return;
        }
        
        const searchTerms = searchQuery.split(' ').filter(term => term.length > 2);
        
        if (searchTerms.length === 0) {
            return;
        }
        
        // Highlight terms in search results
        const resultItems = document.querySelectorAll('.wp-block-post');
        resultItems.forEach(item => {
            highlightTermsInElement(item, searchTerms);
        });
    }
    
    /**
     * Highlight search terms in an element
     */
    function highlightTermsInElement(element, terms) {
        const walker = document.createTreeWalker(
            element,
            NodeFilter.SHOW_TEXT,
            null,
            false
        );
        
        const nodesToReplace = [];
        let node;
        
        while (node = walker.nextNode()) {
            // Skip if parent is already highlighted or is a script/style tag
            const parent = node.parentNode;
            if (parent.classList && parent.classList.contains('search-highlight')) {
                continue;
            }
            if (parent.tagName === 'SCRIPT' || parent.tagName === 'STYLE') {
                continue;
            }
            
            nodesToReplace.push(node);
        }
        
        nodesToReplace.forEach(node => {
            let text = node.textContent;
            let hasMatch = false;
            
            terms.forEach(term => {
                const regex = new RegExp(`(${escapeRegex(term)})`, 'gi');
                if (regex.test(text)) {
                    hasMatch = true;
                    text = text.replace(regex, '<mark class="search-highlight">$1</mark>');
                }
            });
            
            if (hasMatch) {
                const span = document.createElement('span');
                span.innerHTML = text;
                node.parentNode.replaceChild(span, node);
            }
        });
    }
    
    /**
     * Escape special regex characters
     */
    function escapeRegex(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }
    
    /**
     * Show/hide filter panel on mobile
     */
    function initMobileFilters() {
        const filterToggle = document.querySelector('.filter-toggle');
        const filterPanel = document.querySelector('.search-filters');
        
        if (!filterToggle || !filterPanel) {
            return;
        }
        
        filterToggle.addEventListener('click', function() {
            filterPanel.classList.toggle('active');
            this.setAttribute('aria-expanded', 
                filterPanel.classList.contains('active') ? 'true' : 'false'
            );
        });
    }
    
    // Initialize mobile filters
    if (window.innerWidth <= 768) {
        initMobileFilters();
    }
    
    /**
     * Track search analytics
     */
    function trackSearchAnalytics() {
        if (!document.body.classList.contains('search')) {
            return;
        }
        
        const searchQuery = new URLSearchParams(window.location.search).get('s');
        const resultsCount = document.querySelectorAll('.wp-block-post').length;
        
        if (searchQuery && typeof gtag !== 'undefined') {
            gtag('event', 'search', {
                'search_term': searchQuery,
                'results_count': resultsCount
            });
        }
    }
    
    // Track analytics
    trackSearchAnalytics();
    
})();
