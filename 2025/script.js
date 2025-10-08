const canvas = document.getElementById('confetti');
const ctx = canvas.getContext('2d');
const textContainer = document.getElementById('text-container');
const logoContainer = document.getElementById('logo-container');
const body = document.body;

canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

let particles = [];
const colors = ["#f4a29d", "#e0ded3", "#8c2633", "#ffd700"];

// Clase para las partículas
class Particle {
  constructor(x, y, direction) {
    this.x = x;
    this.y = y;
    this.size = Math.random() * 5 + 1;
    this.color = colors[Math.floor(Math.random() * colors.length)];
    this.speedX = direction * (Math.random() * 3 + 1);
    this.speedY = Math.random() * 3 - 1.5;
    this.opacity = 1;
  }

  update() {
    this.x += this.speedX;
    this.y += this.speedY;
    this.opacity -= 0.01;
  }

  draw() {
    ctx.globalAlpha = this.opacity;
    ctx.beginPath();
    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
    ctx.fillStyle = this.color;
    ctx.fill();
    ctx.closePath();
  }
}

// Generar partículas
function generateParticles() {
  for (let i = 0; i < 12; i++) {
    const x = Math.random() < 0.5 ? 0 : canvas.width; // Desde los lados
    const y = Math.random() * canvas.height;
    const direction = x === 0 ? 1 : -1;
    particles.push(new Particle(x, y, direction));
  }
}

// Animar partículas
function animateParticles() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);

  particles.forEach((particle, index) => {
    if (particle.opacity <= 0) {
      particles.splice(index, 1);
    } else {
      particle.update();
      particle.draw();
    }
  });

  requestAnimationFrame(animateParticles);
}

// Ocultar texto y mostrar logo
setTimeout(() => {
  textContainer.style.opacity = "0"; // Ocultar texto
  setTimeout(() => {
    logoContainer.style.opacity = "1";
    logoContainer.style.transform = "translate(-50%, -50%) scale(0.5)";
  }, 1000);
}, 4000);

// Redirigir con desvanecimiento sutil
setTimeout(() => {
  logoContainer.style.animation = "logoExit 1.5s forwards";
  body.style.opacity = "0"; // Desvanecer todo
  setTimeout(() => {
    window.location.href = "https://cas.edu.gt/2025/index.html"; // Cambia por tu URL
  }, 1500);
}, 3000);

// Iniciar confeti
generateParticles();
setInterval(generateParticles, 500);
animateParticles();
