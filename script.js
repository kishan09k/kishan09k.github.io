/* ================================================================
   KISHAN GOSWAMI — CYBERPUNK PORTFOLIO — SCRIPT
   ================================================================ */

(() => {
"use strict";

/* ── LOADER ── */
const loader        = document.getElementById("loader");
const loaderBar     = document.getElementById("loaderBar");
const loaderPct     = document.getElementById("loaderPct");
const warnLines     = [document.getElementById("warn1"), document.getElementById("warn2"), document.getElementById("warn3")];
const warnings = [
    "> Scanning neural pathways...",
    "> Bypassing firewall...",
    "> Loading skill modules...",
    "> Establishing secure connection...",
    "> Decrypting project archives...",
    "> Initializing danger protocols...",
    "> Calibrating threat level...",
    "> System ready."
];
let loadProgress = 0;
let warnIdx = 0;
const loaderInterval = setInterval(() => {
    loadProgress += Math.random() * 8 + 2;
    if (loadProgress >= 100) loadProgress = 100;
    loaderBar.style.width = loadProgress + "%";
    loaderPct.textContent = Math.floor(loadProgress);
    if (Math.random() > 0.5 && warnIdx < warnings.length) {
        warnLines[2].textContent = warnLines[1].textContent;
        warnLines[1].textContent = warnLines[0].textContent;
        warnLines[0].textContent = warnings[warnIdx++];
    }
    if (loadProgress >= 100) {
        clearInterval(loaderInterval);
        setTimeout(() => { loader.classList.add("hidden"); initAll(); }, 600);
    }
}, 120);

/* ── INIT EVERYTHING ── */
function initAll() {
    initCursor();
    initNav();
    initThreeHero();
    initParticles();
    initTyping();
    initScrollAnimations();
    initCountUp();
    initMiniBars();
    initSkillPlanets();
    initProjectCards();
    initBubbleGame();
    initDragDrop();
    initContactForm();
    initEasterEgg();
    initScrollProgress();
    initParallax();
    initMascotEyes();
}

/* ── CUSTOM CURSOR ── */
function initCursor() {
    if (window.matchMedia("(max-width: 900px)").matches) return;
    const dot = document.getElementById("cursorDot");
    const ring = document.getElementById("cursorRing");
    const canvas = document.getElementById("cursorTrail");
    const ctx = canvas.getContext("2d");
    let mx = -100, my = -100, rx = -100, ry = -100;
    const trail = [];

    function resize() { canvas.width = window.innerWidth; canvas.height = window.innerHeight; }
    resize();
    window.addEventListener("resize", resize);

    document.addEventListener("mousemove", e => {
        mx = e.clientX; my = e.clientY;
        trail.push({ x: mx, y: my, life: 1 });
        if (trail.length > 30) trail.shift();
    });

    const hovers = document.querySelectorAll("a, button, .planet, .project-card, .drag-item, .fact-card, .social-icon, .nav-toggle");
    hovers.forEach(el => {
        el.addEventListener("mouseenter", () => ring.classList.add("hover"));
        el.addEventListener("mouseleave", () => ring.classList.remove("hover"));
    });

    function animateCursor() {
        rx += (mx - rx) * 0.15;
        ry += (my - ry) * 0.15;
        dot.style.left = mx + "px";
        dot.style.top = my + "px";
        ring.style.left = rx + "px";
        ring.style.top = ry + "px";

        ctx.clearRect(0, 0, canvas.width, canvas.height);
        for (let i = trail.length - 1; i >= 0; i--) {
            trail[i].life -= 0.035;
            if (trail[i].life <= 0) { trail.splice(i, 1); continue; }
            ctx.beginPath();
            ctx.arc(trail[i].x, trail[i].y, trail[i].life * 3, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(255, 0, 68, ${trail[i].life * 0.4})`;
            ctx.fill();
        }
        requestAnimationFrame(animateCursor);
    }
    animateCursor();
}

/* ── NAVBAR ── */
function initNav() {
    const navbar    = document.getElementById("navbar");
    const toggle    = document.getElementById("navToggle");
    const links     = document.getElementById("navLinks");
    const allLinks  = document.querySelectorAll(".nav-link");

    window.addEventListener("scroll", () => {
        navbar.classList.toggle("scrolled", window.scrollY > 50);
    });

    toggle.addEventListener("click", () => {
        toggle.classList.toggle("open");
        links.classList.toggle("open");
    });

    allLinks.forEach(l => l.addEventListener("click", () => {
        toggle.classList.remove("open");
        links.classList.remove("open");
    }));

    // Active link on scroll
    const sections = document.querySelectorAll(".section");
    const observerCb = entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                allLinks.forEach(l => l.classList.remove("active"));
                const active = document.querySelector(`.nav-link[data-section="${entry.target.id}"]`);
                if (active) active.classList.add("active");
            }
        });
    };
    const obs = new IntersectionObserver(observerCb, { threshold: 0.3 });
    sections.forEach(s => obs.observe(s));
}

/* ── THREE.JS HERO ── */
function initThreeHero() {
    const canvas = document.getElementById("heroCanvas");
    if (!canvas || typeof THREE === "undefined") return;
    const scene    = new THREE.Scene();
    const camera   = new THREE.PerspectiveCamera(60, window.innerWidth / window.innerHeight, 0.1, 1000);
    camera.position.z = 5;
    const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

    const material = new THREE.MeshBasicMaterial({ color: 0xff0044, wireframe: true, transparent: true, opacity: 0.15 });
    const shapes = [];
    const geos = [
        new THREE.IcosahedronGeometry(1.2, 1),
        new THREE.OctahedronGeometry(0.8, 0),
        new THREE.TorusGeometry(0.6, 0.2, 8, 16),
        new THREE.TetrahedronGeometry(0.7, 0),
        new THREE.TorusKnotGeometry(0.5, 0.15, 50, 8)
    ];
    geos.forEach((g, i) => {
        const m = new THREE.Mesh(g, material.clone());
        m.position.set(
            (Math.random() - 0.5) * 8,
            (Math.random() - 0.5) * 6,
            (Math.random() - 0.5) * 4 - 1
        );
        m.material.opacity = 0.08 + Math.random() * 0.1;
        scene.add(m);
        shapes.push({ mesh: m, rx: Math.random() * 0.005 + 0.002, ry: Math.random() * 0.005 + 0.002 });
    });

    let mouseX = 0, mouseY = 0;
    document.addEventListener("mousemove", e => {
        mouseX = (e.clientX / window.innerWidth - 0.5) * 2;
        mouseY = (e.clientY / window.innerHeight - 0.5) * 2;
    });

    window.addEventListener("resize", () => {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
    });

    function animate() {
        requestAnimationFrame(animate);
        shapes.forEach(s => {
            s.mesh.rotation.x += s.rx;
            s.mesh.rotation.y += s.ry;
            s.mesh.position.x += mouseX * 0.003;
            s.mesh.position.y += -mouseY * 0.003;
        });
        camera.position.x += (mouseX * 0.3 - camera.position.x) * 0.02;
        camera.position.y += (-mouseY * 0.3 - camera.position.y) * 0.02;
        renderer.render(scene, camera);
    }
    animate();
}

/* ── PARTICLES ── */
function initParticles() {
    if (typeof particlesJS === "undefined") return;
    particlesJS("particles-js", {
        particles: {
            number: { value: 50, density: { enable: true, value_area: 800 } },
            color: { value: "#ff0044" },
            shape: { type: "circle" },
            opacity: { value: 0.15, random: true },
            size: { value: 2, random: true },
            line_linked: { enable: true, distance: 120, color: "#ff0044", opacity: 0.06, width: 1 },
            move: { enable: true, speed: 0.8, direction: "none", random: true, out_mode: "out" }
        },
        interactivity: {
            detect_on: "canvas",
            events: { onhover: { enable: true, mode: "grab" }, onclick: { enable: true, mode: "push" } },
            modes: { grab: { distance: 150, line_linked: { opacity: 0.2 } }, push: { particles_nb: 3 } }
        },
        retina_detect: true
    });
}

/* ── TYPING EFFECT ── */
function initTyping() {
    const el = document.getElementById("typingText");
    if (!el) return;
    const phrases = [
        "Building the future, one line at a time.",
        "Chemical reactions & code injections.",
        "Full-stack developer // problem destroyer.",
        "System compromised... by creativity.",
        "Deploying innovation at scale."
    ];
    let pi = 0, ci = 0, deleting = false;
    function type() {
        const current = phrases[pi];
        if (!deleting) {
            el.textContent = current.slice(0, ++ci);
            if (ci >= current.length) { deleting = true; setTimeout(type, 2000); return; }
            setTimeout(type, 60 + Math.random() * 40);
        } else {
            el.textContent = current.slice(0, --ci);
            if (ci <= 0) { deleting = false; pi = (pi + 1) % phrases.length; setTimeout(type, 400); return; }
            setTimeout(type, 30);
        }
    }
    type();
}

/* ── SCROLL PROGRESS ── */
function initScrollProgress() {
    const bar = document.getElementById("scrollProgress");
    if (!bar) return;
    window.addEventListener("scroll", () => {
        const h = document.documentElement.scrollHeight - window.innerHeight;
        bar.style.width = h > 0 ? (window.scrollY / h * 100) + "%" : "0%";
    });
}

/* ── GSAP SCROLL ANIMATIONS ── */
function initScrollAnimations() {
    if (typeof gsap === "undefined" || typeof ScrollTrigger === "undefined") return;
    gsap.registerPlugin(ScrollTrigger);

    // Reveal animations
    document.querySelectorAll(".section-header, .about-card, .about-right, .fact-card, .about-skills-preview, .playground-game, .contact-info, .contact-form, .footer-content").forEach((el, i) => {
        gsap.from(el, {
            scrollTrigger: { trigger: el, start: "top 85%", toggleActions: "play none none none" },
            y: 50, opacity: 0, duration: 0.8,
            delay: i * 0.05,
            ease: "power3.out"
        });
    });

    // Project cards stagger
    gsap.from(".project-card", {
        scrollTrigger: { trigger: ".projects-grid", start: "top 80%" },
        y: 60, opacity: 0, duration: 0.7,
        stagger: 0.1,
        ease: "power3.out"
    });

    // Hero content
    gsap.from(".hero-content > *", {
        y: 40, opacity: 0, duration: 0.8,
        stagger: 0.12,
        delay: 0.3,
        ease: "power3.out"
    });
}

/* ── COUNT UP ── */
function initCountUp() {
    const nums = document.querySelectorAll(".fact-number");
    const obs = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const el = entry.target;
            const target = parseInt(el.dataset.count, 10);
            if (isNaN(target)) return;
            let current = 0;
            const step = Math.ceil(target / 60);
            const interval = setInterval(() => {
                current += step;
                if (current >= target) { current = target; clearInterval(interval); }
                el.textContent = current.toLocaleString();
            }, 25);
            obs.unobserve(el);
        });
    }, { threshold: 0.5 });
    nums.forEach(n => obs.observe(n));
}

/* ── MINI SKILL BARS ── */
function initMiniBars() {
    const fills = document.querySelectorAll(".mini-fill");
    const obs = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            entry.target.style.width = entry.target.dataset.width + "%";
            obs.unobserve(entry.target);
        });
    }, { threshold: 0.5 });
    fills.forEach(f => obs.observe(f));
}

/* ── SKILL PLANETS ── */
function initSkillPlanets() {
    const popup     = document.getElementById("skillPopup");
    const popupName = document.getElementById("skillPopupName");
    const popupFill = document.getElementById("skillPopupFill");
    const popupLevel= document.getElementById("skillPopupLevel");
    const popupDesc = document.getElementById("skillPopupDesc");
    const popupClose= document.getElementById("skillPopupClose");
    if (!popup) return;

    document.querySelectorAll(".planet").forEach(p => {
        p.addEventListener("click", () => {
            popupName.textContent = p.dataset.skill;
            popupDesc.textContent = p.dataset.desc;
            popupFill.style.width = "0%";
            popupLevel.textContent = p.dataset.level + "%";
            popup.classList.add("visible");
            setTimeout(() => { popupFill.style.width = p.dataset.level + "%"; }, 100);
        });
    });
    popupClose.addEventListener("click", () => popup.classList.remove("visible"));
    popup.addEventListener("click", e => { if (e.target === popup) popup.classList.remove("visible"); });
}

/* ── PROJECT CARD TOUCH FLIP ── */
function initProjectCards() {
    if (!window.matchMedia("(hover: none)").matches) return;
    document.querySelectorAll(".project-card").forEach(card => {
        card.addEventListener("click", () => card.classList.toggle("flipped"));
    });
}

/* ── BUBBLE GAME ── */
function initBubbleGame() {
    const canvas = document.getElementById("bubbleCanvas");
    const startBtn = document.getElementById("bubbleStart");
    const scoreEl = document.getElementById("bubbleScore");
    const timeEl = document.getElementById("bubbleTime");
    if (!canvas || !startBtn) return;
    const ctx = canvas.getContext("2d");
    let bubbles = [], score = 0, timeLeft = 30, running = false, animId;

    function resize() {
        const rect = canvas.parentElement.getBoundingClientRect();
        canvas.width = canvas.offsetWidth * (window.devicePixelRatio || 1);
        canvas.height = canvas.offsetHeight * (window.devicePixelRatio || 1);
        ctx.scale(window.devicePixelRatio || 1, window.devicePixelRatio || 1);
    }

    function spawnBubble() {
        const r = 12 + Math.random() * 22;
        bubbles.push({
            x: Math.random() * (canvas.offsetWidth - r * 2) + r,
            y: canvas.offsetHeight + r,
            r,
            speed: 0.8 + Math.random() * 1.5,
            color: `hsl(${Math.random() * 40 + 340}, 100%, 55%)`
        });
    }

    function draw() {
        ctx.clearRect(0, 0, canvas.offsetWidth, canvas.offsetHeight);
        bubbles.forEach(b => {
            ctx.beginPath();
            ctx.arc(b.x, b.y, b.r, 0, Math.PI * 2);
            ctx.fillStyle = b.color;
            ctx.fill();
            ctx.strokeStyle = "rgba(255,255,255,.25)";
            ctx.lineWidth = 1;
            ctx.stroke();
            // Skull icon hint
            ctx.fillStyle = "rgba(0,0,0,.35)";
            ctx.font = `${b.r * 0.8}px sans-serif`;
            ctx.textAlign = "center";
            ctx.textBaseline = "middle";
            ctx.fillText("💀", b.x, b.y);
        });
    }

    function update() {
        if (!running) return;
        if (Math.random() > 0.9) spawnBubble();
        bubbles.forEach(b => b.y -= b.speed);
        bubbles = bubbles.filter(b => b.y + b.r > 0);
        draw();
        animId = requestAnimationFrame(update);
    }

    function getCanvasXY(e) {
        const rect = canvas.getBoundingClientRect();
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const clientY = e.touches ? e.touches[0].clientY : e.clientY;
        return { x: clientX - rect.left, y: clientY - rect.top };
    }

    function pop(e) {
        e.preventDefault();
        if (!running) return;
        const { x, y } = getCanvasXY(e);
        for (let i = bubbles.length - 1; i >= 0; i--) {
            const b = bubbles[i];
            if (Math.hypot(x - b.x, y - b.y) < b.r) {
                bubbles.splice(i, 1);
                score++;
                scoreEl.textContent = score;
                break;
            }
        }
    }

    canvas.addEventListener("click", pop);
    canvas.addEventListener("touchstart", pop, { passive: false });

    startBtn.addEventListener("click", () => {
        if (running) return;
        resize();
        score = 0; timeLeft = 30; bubbles = [];
        scoreEl.textContent = 0; timeEl.textContent = 30;
        running = true;
        startBtn.textContent = "IN PROGRESS...";
        startBtn.disabled = true;
        update();
        const timer = setInterval(() => {
            timeLeft--;
            timeEl.textContent = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(timer);
                running = false;
                cancelAnimationFrame(animId);
                startBtn.textContent = "RE-ENGAGE";
                startBtn.disabled = false;
            }
        }, 1000);
    });
}

/* ── DRAG & DROP ── */
function initDragDrop() {
    const items = document.querySelectorAll(".drag-item");
    const zones = document.querySelectorAll(".drop-zone");
    const result = document.getElementById("dragResult");
    let matched = 0;

    // Desktop drag events
    items.forEach(item => {
        item.addEventListener("dragstart", e => {
            e.dataTransfer.setData("text/plain", item.dataset.element);
            item.style.opacity = "0.5";
        });
        item.addEventListener("dragend", () => { item.style.opacity = "1"; });
    });
    zones.forEach(zone => {
        zone.addEventListener("dragover", e => { e.preventDefault(); zone.classList.add("over"); });
        zone.addEventListener("dragleave", () => zone.classList.remove("over"));
        zone.addEventListener("drop", e => {
            e.preventDefault();
            zone.classList.remove("over");
            const data = e.dataTransfer.getData("text/plain");
            handleDrop(data, zone);
        });
    });

    // Touch drag support
    let touchItem = null;
    items.forEach(item => {
        item.addEventListener("touchstart", e => {
            touchItem = item;
            item.style.opacity = "0.5";
        }, { passive: true });
    });
    document.addEventListener("touchend", e => {
        if (!touchItem) return;
        touchItem.style.opacity = "1";
        const touch = e.changedTouches[0];
        const dropTarget = document.elementFromPoint(touch.clientX, touch.clientY);
        if (dropTarget && dropTarget.classList.contains("drop-zone")) {
            handleDrop(touchItem.dataset.element, dropTarget);
        }
        touchItem = null;
    });

    function handleDrop(data, zone) {
        if (zone.classList.contains("correct")) return;
        if (data === zone.dataset.accept) {
            zone.classList.add("correct");
            zone.textContent = "✓ " + data;
            matched++;
            const dragEl = document.querySelector(`.drag-item[data-element="${data}"]`);
            if (dragEl) { dragEl.style.opacity = "0.3"; dragEl.draggable = false; }
            if (matched >= 4) {
                result.textContent = "☢ ALL COMPOUNDS IDENTIFIED!";
                result.style.color = "#00ff88";
            }
        } else {
            zone.classList.add("wrong");
            setTimeout(() => zone.classList.remove("wrong"), 500);
        }
    }
}

/* ── CONTACT FORM ── */
function initContactForm() {
    const form = document.getElementById("contactForm");
    const btn  = document.getElementById("sendBtn");
    if (!form) return;
    form.addEventListener("submit", e => {
        e.preventDefault();
        btn.classList.add("loading");
        setTimeout(() => {
            btn.classList.remove("loading");
            btn.classList.add("sent");
            form.reset();
            setTimeout(() => btn.classList.remove("sent"), 3000);
        }, 2000);
    });
}

/* ── EASTER EGG (Konami Code) ── */
function initEasterEgg() {
    const modal = document.getElementById("easterEggModal");
    const closeBtn = document.getElementById("closeEasterEgg");
    const container = document.getElementById("confettiContainer");
    if (!modal) return;
    const code = [38,38,40,40,37,39,37,39,66,65];
    let pos = 0;
    document.addEventListener("keydown", e => {
        if (e.keyCode === code[pos]) {
            pos++;
            if (pos >= code.length) {
                pos = 0;
                modal.classList.add("visible");
                spawnConfetti(container);
            }
        } else { pos = 0; }
    });
    closeBtn.addEventListener("click", () => {
        modal.classList.remove("visible");
        container.innerHTML = "";
    });
}
function spawnConfetti(container) {
    for (let i = 0; i < 60; i++) {
        const c = document.createElement("div");
        c.style.cssText = `
            position:absolute;
            width:8px;height:8px;
            background:hsl(${Math.random()*360},100%,60%);
            left:${Math.random()*100}%;
            top:-10px;
            border-radius:${Math.random()>0.5?'50%':'2px'};
            animation:confetti-fall ${1.5+Math.random()*2}s ease-in forwards;
            animation-delay:${Math.random()*0.5}s;
        `;
        container.appendChild(c);
    }
    // Inject keyframe if not present
    if (!document.getElementById("confettiStyle")) {
        const style = document.createElement("style");
        style.id = "confettiStyle";
        style.textContent = `
            @keyframes confetti-fall {
                0% { transform: translateY(0) rotate(0deg); opacity:1; }
                100% { transform: translateY(${window.innerHeight}px) rotate(${360+Math.random()*360}deg); opacity:0; }
            }
        `;
        document.head.appendChild(style);
    }
}

/* ── PARALLAX ON MOUSE MOVE ── */
function initParallax() {
    if (window.matchMedia("(max-width: 900px)").matches) return;
    const shapes = document.querySelectorAll(".shape");
    document.addEventListener("mousemove", e => {
        const x = (e.clientX / window.innerWidth - 0.5) * 2;
        const y = (e.clientY / window.innerHeight - 0.5) * 2;
        shapes.forEach((s, i) => {
            const factor = (i + 1) * 8;
            s.style.transform = `translate(${x * factor}px, ${y * factor}px)`;
        });
    });
}

/* ── MASCOT EYES FOLLOW CURSOR ── */
function initMascotEyes() {
    const pupils = document.querySelectorAll(".mascot-pupil");
    if (!pupils.length) return;
    document.addEventListener("mousemove", e => {
        pupils.forEach(p => {
            const eye = p.parentElement;
            const rect = eye.getBoundingClientRect();
            const cx = rect.left + rect.width / 2;
            const cy = rect.top + rect.height / 2;
            const dx = e.clientX - cx;
            const dy = e.clientY - cy;
            const angle = Math.atan2(dy, dx);
            const dist = Math.min(3, Math.hypot(dx, dy) * 0.02);
            p.style.left = (5 + Math.cos(angle) * dist) + "px";
            p.style.top  = (4 + Math.sin(angle) * dist) + "px";
        });
    });
}

})();
