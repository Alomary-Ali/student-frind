document.addEventListener('DOMContentLoaded', function () {
    var sidebar = document.getElementById('sidebar');
    if (!sidebar) return;

    var navLinks = sidebar.querySelectorAll('[data-nav-link]');
    if (navLinks.length === 0) return;

    var observed = document.querySelectorAll('[data-observe-section]');
    if (observed.length === 0) return;

    var navMap = {};
    navLinks.forEach(function (link) {
        var key = link.getAttribute('data-nav-link');
        navMap[key] = link;
    });

    function deactivateAll() {
        navLinks.forEach(function (l) {
            l.classList.remove('nav-link-active');
            l.removeAttribute('aria-current');
        });
    }

    function activate(key) {
        var link = navMap[key];
        if (link) {
            link.classList.add('nav-link-active');
            link.setAttribute('aria-current', 'page');
        }
    }

    var activeSection = null;

    var observer = new IntersectionObserver(function (entries) {
        var maxRatio = 0;
        var maxKey = null;

        entries.forEach(function (entry) {
            if (entry.isIntersecting && entry.intersectionRatio > maxRatio) {
                maxRatio = entry.intersectionRatio;
                maxKey = entry.target.getAttribute('data-observe-section');
            }
        });

        if (maxKey && maxKey !== activeSection) {
            deactivateAll();
            activate(maxKey);
            activeSection = maxKey;
        }
    }, {
        root: document.querySelector('#main-content') || null,
        threshold: [0, 0.25, 0.5, 0.75, 1],
    });

    observed.forEach(function (el) {
        observer.observe(el);
    });
});
