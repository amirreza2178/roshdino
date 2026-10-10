const root = document.documentElement;
root.classList.add("js");

const header = document.querySelector("[data-site-header]");
const toggle = document.querySelector("[data-menu-toggle]");
const menu = document.querySelector("[data-mobile-menu]");

const setMenu = (open) => {
    if (!toggle || !menu) return;

    toggle.classList.toggle("is-open", open);
    toggle.setAttribute("aria-expanded", String(open));
    toggle.setAttribute("aria-label", open ? "بستن منو" : "باز کردن منو");

    if (open) {
        menu.hidden = false;
        document.body.classList.add("menu-open");
        const firstLink = menu.querySelector("a");
        firstLink?.focus({ preventScroll: true });
    } else {
        menu.hidden = true;
        document.body.classList.remove("menu-open");
    }
};

toggle?.addEventListener("click", () => {
    setMenu(toggle.getAttribute("aria-expanded") !== "true");
});

menu?.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => setMenu(false));
});

document.addEventListener("keydown", (event) => {
    if (event.key === "Escape" && toggle?.getAttribute("aria-expanded") === "true") {
        setMenu(false);
        toggle?.focus({ preventScroll: true });
    }
});

document.addEventListener("click", (event) => {
    if (!menu || menu.hidden || !toggle) return;
    if (!menu.contains(event.target) && !toggle.contains(event.target)) {
        setMenu(false);
    }
});

const updateHeader = () => {
    header?.classList.toggle("is-scrolled", window.scrollY > 12);
};

updateHeader();
window.addEventListener("scroll", updateHeader, { passive: true });

const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
const revealItems = document.querySelectorAll(".reveal");

if (!reduceMotion && "IntersectionObserver" in window) {
    const observer = new IntersectionObserver(
        (entries, instance) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add("is-visible");
                instance.unobserve(entry.target);
            });
        },
        { threshold: 0.08, rootMargin: "0px 0px -40px" }
    );

    revealItems.forEach((item) => observer.observe(item));
} else {
    revealItems.forEach((item) => item.classList.add("is-visible"));
}
