document.querySelectorAll(".btn").forEach((b) => {
  b.onmouseleave = (e) => {
    e.target.style.background = null;
    e.target.style.borderImage = null;
  };

  b.addEventListener("mousemove", (e) => {
    const rect = e.target.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    e.target.style.background = `radial-gradient(circle at ${x}px ${y}px , var(--color1), var(--color2) )`;
    e.target.style.borderImage = `radial-gradient(20% 75% at ${x}px ${y}px , var(--color3), var(--color4) ) 1 / 1px / 0px stretch `;
  });
});