/* File: resources/js/landing.js */

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute("href"));
        if (target) {
            target.scrollIntoView({
                behavior: "smooth",
                block: "start",
            });
        }
    });
});

// Intersection Observer for reveal animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -100px 0px",
};

const observer = new IntersectionObserver(function (entries) {
    entries.forEach((entry) => {
        if (entry.isIntersecting) {
            entry.target.classList.add("active");
            if (entry.target.classList.contains("animate-on-scroll")) {
                entry.target.classList.add("animate-fade-in-up");
            }
        }
    });
}, observerOptions);

// Observe all sections and elements with reveal class
document.querySelectorAll("section, .reveal").forEach((el) => {
    observer.observe(el);
});

// PIN input formatting
const pinInput = document.querySelector('input[name="pin"]');
if (pinInput) {
    pinInput.addEventListener("input", function (e) {
        // Convert to uppercase and remove non-alphanumeric
        let value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, "");

        // Limit to 6 characters
        if (value.length > 6) {
            value = value.slice(0, 6);
        }

        e.target.value = value;
    });

    // Auto-submit when 6 characters entered
    pinInput.addEventListener("keyup", function (e) {
        if (e.target.value.length === 6) {
            // Optional: Auto-submit the form
            // e.target.closest('form').submit();
        }
    });
}

// Progress bar animations on scroll
const progressBars = document.querySelectorAll(".progress-bar");
const progressObserver = new IntersectionObserver(
    function (entries) {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                const bar = entry.target.querySelector(".bg-gradient-to-r");
                if (bar) {
                    const width = bar.style.width;
                    bar.style.width = "0";
                    setTimeout(() => {
                        bar.style.transition = "width 1s ease-out";
                        bar.style.width = width;
                    }, 100);
                }
            }
        });
    },
    { threshold: 0.5 }
);

progressBars.forEach((bar) => {
    progressObserver.observe(bar);
});

// Add hover effects to root cards
document.querySelectorAll(".root-card").forEach((card) => {
    card.addEventListener("mouseenter", function () {
        this.querySelector(".root-icon")?.classList.add("animate-pulse");
    });

    card.addEventListener("mouseleave", function () {
        this.querySelector(".root-icon")?.classList.remove("animate-pulse");
    });
});

// Counter animation for statistics
function animateCounter(element, target, duration = 2000) {
    const start = 0;
    const increment = target / (duration / 16);
    let current = start;

    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }

        element.textContent = Math.round(current).toLocaleString("ar-SA");
    }, 16);
}

// Animate counters when they come into view
const counterObserver = new IntersectionObserver(
    function (entries) {
        entries.forEach((entry) => {
            if (entry.isIntersecting && !entry.target.dataset.animated) {
                entry.target.dataset.animated = "true";
                const target = parseInt(
                    entry.target.dataset.target ||
                        entry.target.textContent.replace(/[^0-9]/g, "")
                );
                animateCounter(entry.target, target);
            }
        });
    },
    { threshold: 0.5 }
);

document.querySelectorAll(".counter").forEach((counter) => {
    counterObserver.observe(counter);
});

// Parallax effect for hero section
window.addEventListener("scroll", () => {
    const scrolled = window.pageYOffset;
    const parallax = document.querySelector(".parallax-bg");
    if (parallax) {
        parallax.style.transform = `translateY(${scrolled * 0.5}px)`;
    }
});

// Form validation feedback
const forms = document.querySelectorAll("form");
forms.forEach((form) => {
    form.addEventListener("submit", function (e) {
        const submitButton = this.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.innerHTML =
                '<span class="loading-dots"><span></span><span></span><span></span></span>';
            submitButton.disabled = true;
        }
    });
});
