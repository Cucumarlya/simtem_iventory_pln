// Landing Page Script for PLN Material Management System
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 PLN Material Management System - Landing Page Loaded');
    
    // Initialize all components
    initAnimations();
    initSmoothScrolling();
    initIntersectionObserver();
    initCurrentYear();
    initMobileOptimizations();
    initMobileHeader();
    
    console.log('✅ Landing page initialized successfully');
});

// Utility functions
const utils = {
    // Debounce function for performance
    debounce: function(func, wait, immediate) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            const later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    },
    
    // Throttle function for performance
    throttle: function(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    },
    
    // Check if element is in viewport
    isInViewport: function(element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }
};

// Check if device is touch device
function isTouchDevice() {
    return 'ontouchstart' in window || 
           navigator.maxTouchPoints > 0 || 
           navigator.msMaxTouchPoints > 0;
}

// Handle fixed header on mobile
function initMobileHeader() {
    const heroHeader = document.querySelector('.hero-header');
    const heroSection = document.querySelector('.hero');
    
    if (!heroHeader || !heroSection) return;
    
    function updateHeaderLayout() {
        if (window.innerWidth <= 576) {
            heroHeader.style.position = 'fixed';
            heroHeader.style.background = 'rgba(10, 45, 77, 0.98)';
            heroHeader.style.backdropFilter = 'blur(15px)';
            heroSection.style.paddingTop = '80px';
        } else {
            heroHeader.style.position = 'absolute';
            heroHeader.style.background = 'transparent';
            heroHeader.style.backdropFilter = 'none';
            heroSection.style.paddingTop = '0';
        }
    }
    
    // Initial call
    updateHeaderLayout();
    
    // Update on resize
    window.addEventListener('resize', utils.debounce(updateHeaderLayout, 250));
}

// Smooth scrolling for anchor links
function initSmoothScrolling() {
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                const offsetTop = targetElement.getBoundingClientRect().top + window.pageYOffset - 80;
                
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
                
                // Update URL without scrolling
                if (history.pushState) {
                    history.pushState(null, null, targetId);
                }
            }
        });
    });
}

// Intersection Observer for animations
function initIntersectionObserver() {
    // Check if Intersection Observer is supported
    if (!('IntersectionObserver' in window)) {
        // Fallback: add animate class immediately
        const animatedElements = document.querySelectorAll('.feature-card, .tujuan-card, .sistem-description');
        animatedElements.forEach(element => {
            element.classList.add('animate');
        });
        return;
    }
    
    const animatedElements = document.querySelectorAll('.feature-card, .tujuan-card, .sistem-description');
    
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    animatedElements.forEach(element => {
        observer.observe(element);
    });
}

// Additional animations and effects
function initAnimations() {
    // Add loading animation to dashboard preview
    const dashboardPreview = document.querySelector('.dashboard-preview');
    if (dashboardPreview) {
        // Simulate data loading
        setTimeout(() => {
            dashboardPreview.style.animation = 'float 6s ease-in-out infinite';
        }, 1000);
    }
    
    // Add smooth hover effects to buttons (only for non-touch devices)
    if (!isTouchDevice()) {
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    }
    
    // Add scroll progress indicator
    initScrollProgress();
}

// Scroll progress indicator
function initScrollProgress() {
    // Only add progress bar if not on mobile or if explicitly wanted
    if (window.innerWidth > 768) {
        const progressBar = document.createElement('div');
        progressBar.className = 'scroll-progress';
        progressBar.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 3px;
            background: var(--pln-yellow);
            z-index: 1001;
            transition: width 0.3s ease;
        `;
        document.body.appendChild(progressBar);
        
        window.addEventListener('scroll', utils.throttle(function() {
            const winHeight = window.innerHeight;
            const docHeight = document.documentElement.scrollHeight;
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const scrollPercent = (scrollTop / (docHeight - winHeight)) * 100;
            
            progressBar.style.width = Math.min(scrollPercent, 100) + '%';
        }, 100));
    }
}

// Mobile optimizations
function initMobileOptimizations() {
    // Prevent zoom on double tap for iOS (with better detection)
    if (isTouchDevice()) {
        let lastTouchEnd = 0;
        document.addEventListener('touchend', function (event) {
            const now = (new Date()).getTime();
            if (now - lastTouchEnd <= 300) {
                event.preventDefault();
            }
            lastTouchEnd = now;
        }, { passive: false });
        
        // Improve scrolling performance on mobile
        document.documentElement.style.setProperty('--transition-normal', '0.2s ease');
        document.documentElement.style.setProperty('--transition-slow', '0.3s ease');
        
        // Add touch feedback for buttons
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(button => {
            button.addEventListener('touchstart', function() {
                this.style.transform = 'scale(0.98)';
            });
            
            button.addEventListener('touchend', function() {
                this.style.transform = 'scale(1)';
            });
        });
    }
    
    // Handle orientation changes
    window.addEventListener('orientationchange', function() {
        setTimeout(() => {
            window.scrollTo(0, 0);
        }, 100);
    });
}

// Set current year in footer
function initCurrentYear() {
    const yearElement = document.getElementById('current-year');
    if (yearElement) {
        yearElement.textContent = new Date().getFullYear();
    }
}

// Export for global access if needed (with error handling)
if (typeof window !== 'undefined') {
    window.PLNLandingPage = {
        initSmoothScrolling: initSmoothScrolling,
        initAnimations: initAnimations,
        initIntersectionObserver: initIntersectionObserver,
        initMobileOptimizations: initMobileOptimizations,
        initMobileHeader: initMobileHeader,
        utils: utils,
        isTouchDevice: isTouchDevice
    };
}

// Error handling
window.addEventListener('error', function(e) {
    console.error('Global error:', e.error);
    
    // Fallback: ensure animations still work even if JS has errors
    const animatedElements = document.querySelectorAll('.feature-card, .tujuan-card, .sistem-description');
    setTimeout(() => {
        animatedElements.forEach(element => {
            element.classList.add('animate');
        });
    }, 1000);
});

// Handle resize events with debounce
window.addEventListener('resize', utils.debounce(function() {
    // Re-initialize animations on resize if needed
    if (window.innerWidth < 768) {
        document.documentElement.style.setProperty('--space-xl', '2rem');
    } else {
        document.documentElement.style.setProperty('--space-xl', '3rem');
    }
}, 250));

// Load event for additional optimizations
window.addEventListener('load', function() {
    // Preload critical images
    const criticalImages = document.querySelectorAll('.system-image, .brand-logo');
    criticalImages.forEach(img => {
        if (img.complete) {
            img.style.opacity = '1';
        } else {
            img.addEventListener('load', function() {
                this.style.opacity = '1';
            });
            img.addEventListener('error', function() {
                console.warn('Failed to load image:', this.src);
            });
        }
    });
    
    // Remove any loading states
    document.body.classList.add('loaded');
});

// Fallback for very old browsers
if (!Array.prototype.forEach) {
    Array.prototype.forEach = function(callback) {
        for (var i = 0; i < this.length; i++) {
            callback(this[i], i, this);
        }
    };
}

// CSS untuk state loaded (opsional)
const fallbackStyles = `
<body>.loaded .feature-card,
.loaded .tujuan-card,
.loaded .sistem-description {
    opacity: 1;
    transform: translateY(0);
}
</body>`;

// Inject fallback styles if needed
if (!document.querySelector('#fallback-styles')) {
    const styleElement = document.createElement('style');
    styleElement.id = 'fallback-styles';
    styleElement.textContent = fallbackStyles;
    document.head.appendChild(styleElement);
}