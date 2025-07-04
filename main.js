// Handle initial scroll on page load if hash exists
window.addEventListener("load", () => {
  const hash = window.location.hash;
  if (hash) {
    setTimeout(() => {
      const target = document.querySelector(hash);
      if (target) {
        target.scrollIntoView({ behavior: "smooth" });
      }
    }, 100);
  } else {
    window.scrollTo(0, 0);
  }

  setTimeout(() => {
    history.pushState("", document.title, window.location.pathname + window.location.search);
  }, 50);
});

// Dropdown menu toggle
function dropdownMenu() {
  const menu = document.getElementById("dropdownclick");
  menu.classList.toggle("responsive");
  document.body.classList.toggle("noscroll");
}

// Close menu on link click
function closeDropdown() {
  const menu = document.getElementById("dropdownclick");
  menu.classList.remove("responsive");
  document.body.classList.remove("noscroll");
}

// Auto-close mobile navbar when a link is clicked
document.addEventListener("DOMContentLoaded", () => {
  const menu = document.getElementById("dropdownclick");
  const links = menu.querySelectorAll("a");

  links.forEach(link => {
    link.addEventListener("click", () => {
      if (menu.classList.contains("responsive")) {
        closeDropdown();
      }
    });
  });
});

// Hide glow dot for mobile
if (window.innerWidth > 768) {
  const glowDot = document.createElement('div');
  glowDot.classList.add('glow-dot');
  document.body.appendChild(glowDot);

  document.addEventListener('mousemove', (e) => {
    glowDot.style.left = e.clientX + 'px';
    glowDot.style.top = e.clientY + 'px';
  });
}

// Line animation on scroll
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      const el = entry.target;
      const lines = el.innerHTML.trim().split('<br>');
      el.innerHTML = '';
      lines.forEach((line, index) => {
        const span = document.createElement('span');
        span.innerHTML = line;
        span.style.animationDelay = `${index * 0.4}s`;
        el.appendChild(span);
      });
      observer.unobserve(el);
    }
  });
});
document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));

// Mobile-friendly flip for gallery cards
function isTouchDevice() {
  return 'ontouchstart' in window || navigator.maxTouchPoints > 0;
}
if (isTouchDevice()) {
  const cards = document.querySelectorAll('.gallery-card');
  cards.forEach(card => {
    card.addEventListener('click', function (e) {
      if (!this.classList.contains('touch-flip')) {
        e.preventDefault();
        cards.forEach(c => {
          if (c !== this) c.classList.remove('touch-flip');
        });
        this.classList.add('touch-flip');
      } else {
        this.classList.remove('touch-flip');
      }
    });
  });
  document.addEventListener('click', function (e) {
    if (![...cards].some(card => card.contains(e.target))) {
      cards.forEach(card => card.classList.remove('touch-flip'));
    }
  });
}

// Tooltip for services
const tooltip = document.getElementById('serviceTooltip');
if (tooltip) {
  const tooltipContent = tooltip.querySelector('.tooltip-content');
  let currentItem = null;
  document.querySelectorAll('.service-item').forEach(item => {
    item.addEventListener('mouseenter', (e) => {
      if (window.innerWidth <= 768) return;
      showTooltip(item);
    });
    item.addEventListener('mouseleave', () => {
      if (window.innerWidth <= 768) return;
      tooltip.style.display = 'none';
    });
    item.addEventListener('click', (e) => {
      if (window.innerWidth > 768) return;
      e.stopPropagation();
      if (currentItem === item) {
        tooltip.style.display = 'none';
        currentItem = null;
      } else {
        showTooltip(item);
        currentItem = item;
      }
    });
  });
  document.body.addEventListener('click', () => {
    tooltip.style.display = 'none';
    currentItem = null;
  });

  function showTooltip(item) {
    const description = item.getAttribute('data-service');
    const rect = item.getBoundingClientRect();
    tooltipContent.textContent = description;
    tooltip.style.display = 'block';
    const tooltipHeight = tooltip.offsetHeight;
    const tooltipWidth = tooltip.offsetWidth;
    let left = rect.left + window.scrollX + rect.width / 2 - tooltipWidth / 2;
    const maxLeft = document.body.clientWidth - tooltipWidth - 10;
    if (left < 10) left = 10;
    if (left > maxLeft) left = maxLeft;
    tooltip.style.left = `${left}px`;
    tooltip.style.top = `${rect.top + window.scrollY - tooltipHeight - 10}px`;
  }
}

// Navbar active link on scroll
const sections = document.querySelectorAll("section");
const navLinks = document.querySelectorAll(".navbar a");
window.addEventListener("scroll", () => {
  let currentSectionId = "";
  let minDistance = window.innerHeight;
  sections.forEach(section => {
    const rect = section.getBoundingClientRect();
    const distance = Math.abs(rect.top);
    if (rect.top <= window.innerHeight / 2 && distance < minDistance) {
      minDistance = distance;
      currentSectionId = section.getAttribute("id");
    }
  });
  navLinks.forEach(link => {
    link.classList.remove("active-link");
    if (link.getAttribute("href") === "#" + currentSectionId) {
      link.classList.add("active-link");
    }
  });
});

// Highlight gallery page nav link if in individual page
document.addEventListener("DOMContentLoaded", () => {
  const currentPage = window.location.pathname.split("/").pop().toLowerCase();
  const galleryPages = ["portraits.html", "weddings.html", "events.html", "fashion.html", "landscapes.html", "food.html"];
  const galleriesLink = document.querySelector('a[href="index.html#galleries"]');
  if (galleriesLink && galleryPages.includes(currentPage)) {
    galleriesLink.classList.add("active-link");
  }
});

// Lightbox functionality
const lightbox = document.getElementById("lightbox");
const lightboxImg = lightbox.querySelector(".lightbox-img");
const closeBtn = document.querySelector(".close-lightbox");
const prevBtn = document.querySelector(".prev");
const nextBtn = document.querySelector(".next");
const zoomBtn = document.getElementById("zoomBtn");
const fullscreenBtn = document.getElementById("fullscreenBtn");
const images = Array.from(document.querySelectorAll(".grid-item img"));
let currentIndex = 0;
let zoomed = false;

function showImage(index) {
  currentIndex = index;
  lightboxImg.src = images[index].src;
  lightbox.style.display = "flex";
  lightboxImg.classList.remove("zoomed");
}
images.forEach((img, index) => {
  img.addEventListener("click", () => showImage(index));
});
prevBtn.addEventListener("click", () => showImage((currentIndex - 1 + images.length) % images.length));
nextBtn.addEventListener("click", () => showImage((currentIndex + 1) % images.length));
closeBtn.addEventListener("click", () => {
  lightbox.style.display = "none";
  exitFullscreen();
});
lightbox.addEventListener("click", (e) => {
  if (e.target === lightbox) {
    lightbox.style.display = "none";
    exitFullscreen();
  }
});
zoomBtn.addEventListener("click", () => {
  zoomed = !zoomed;
  lightboxImg.style.transform = zoomed ? "scale(1.8)" : "scale(1)";
  lightboxImg.style.cursor = zoomed ? "move" : "default";
});
fullscreenBtn.addEventListener("click", () => {
  if (!document.fullscreenElement) {
    lightbox.requestFullscreen();
  } else {
    document.exitFullscreen();
  }
});
function exitFullscreen() {
  if (document.fullscreenElement) {
    document.exitFullscreen();
  }
}
document.addEventListener("keydown", (e) => {
  if (lightbox.style.display === "flex") {
    if (e.key === "ArrowRight") nextBtn.click();
    if (e.key === "ArrowLeft") prevBtn.click();
    if (e.key === "Escape") closeBtn.click();
  }
});

// Booking modal
function openBookingModal() {
  document.getElementById("bookingModal").style.display = "block";
}
function closeBookingModal() {
  document.getElementById("bookingModal").style.display = "none";
}
window.addEventListener("click", function (e) {
  const modal = document.getElementById("bookingModal");
  if (e.target === modal) {
    modal.style.display = "none";
  }
});

// Availability modal
function openAvailabilityModal() {
  document.getElementById("availabilityModal").style.display = "block";
  fetch("fetch_availability_json.php")
    .then(res => res.json())
    .then(events => {
      const el = document.getElementById('availability-calendar');
      el.innerHTML = '';
      const calendar = new FullCalendar.Calendar(el, {
        initialView: 'dayGridMonth',
        height: 500,
        events: events,
        eventDisplay: 'block'
      });
      calendar.render();
    });
}
function closeAvailabilityModal() {
  document.getElementById("availabilityModal").style.display = "none";
}
