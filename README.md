# Kishan Goswami — Developer Portfolio

A cyberpunk-themed, interactive developer portfolio built with vanilla HTML, CSS, and JavaScript.

![Status](https://img.shields.io/badge/status-OPERATIONAL-ff0044?style=flat-square)
![Tech](https://img.shields.io/badge/stack-HTML%20%7C%20CSS%20%7C%20JS-0a0a0f?style=flat-square&labelColor=ff0044)

## Preview

> *"System Breach Detected"* — A dangerous, neon-red cyberpunk aesthetic with scanline overlays, glitch effects, and HUD-style UI elements.

## Sections

| # | Section | Description |
|---|---------|-------------|
| 01 | **Hero** | 3D wireframe scene (Three.js), particle background, glitch name, terminal typing effect |
| 02 | **About** | Animated mascot, fun-fact counters, threat-level skill bars, classified intel card |
| 03 | **Skills** | Interactive solar system — tap any orbiting planet to view skill details |
| 04 | **Projects** | 3D flip cards with project details, status badges, and links |
| 05 | **Playground** | Bubble Assault game + chemical element drag-and-drop quiz |
| 06 | **Contact** | Secure transmission form with animated send states |

## Tech Stack

- **HTML5** — Semantic markup, accessible structure
- **CSS3** — Glassmorphism, CSS Grid, Flexbox, custom properties, keyframe animations
- **JavaScript** (Vanilla ES6+) — All interactivity, games, cursor, typing
- **[Three.js](https://threejs.org/)** r128 — 3D wireframe hero scene
- **[GSAP](https://gsap.com/)** 3.12.5 + ScrollTrigger — Scroll-driven animations
- **[Particles.js](https://vincentgarreau.com/particles.js/)** 2.0.0 — Background particle effects

## Features

- **Cyberpunk Aesthetic** — Scanline overlay, vignette, noise texture, hex-grid patterns, HUD corners
- **Glitch Effects** — CSS pseudo-element glitch on headings and loader text
- **Custom Cursor** — Dot + ring + red trail canvas (desktop only)
- **Skull Loader** — SVG draw animation with scrolling warning messages and progress bar
- **Typing Terminal** — `root@kishan:~$` prefix with rotating phrases
- **Solar System Skills** — Orbiting planets with click-to-inspect popup
- **3D Flip Project Cards** — Hover on desktop, tap on touch devices
- **Bubble Assault Game** — Canvas game with 30s timer and skull targets
- **Drag & Drop Quiz** — Match chemical compounds to names (touch-supported)
- **Konami Code Easter Egg** — `↑↑↓↓←→←→BA` triggers a confetti modal
- **Mascot Eyes** — Follow mouse cursor in real-time
- **Responsive Design** — Three breakpoints: desktop (>900px), tablet (≤900px), mobile (≤600px)
- **Reduced Motion** — Respects `prefers-reduced-motion` system preference
- **Scroll Progress Bar** — Red gradient bar at top of viewport

## Setup

1. Clone or copy the project into your web server directory:
   ```
   portfolio/
   ├── index.html
   ├── style.css
   ├── script.js
   ├── README.md
   └── assets/
       ├── models/
       └── animations/
   ```

2. Open in browser:
   ```
   http://localhost/portfolio/
   ```

No build tools, no dependencies to install — all libraries load via CDN.

## Responsive Breakpoints

| Breakpoint | Behavior |
|------------|----------|
| **> 900px** | Full experience — custom cursor, all orbits, 3-column grids |
| **≤ 900px** | Mobile nav overlay, hidden cursor/scanlines, 1-column layouts, reduced orbits |
| **≤ 600px** | Compact spacing, stacked CTAs, minimal orbits, smaller solar system |

## Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## License

© 2026 Kishan Goswami. All rights reserved.
