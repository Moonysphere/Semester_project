// Toggle sidebar
const sidebar = document.getElementById("sidebar");
const sidebarToggle = document.getElementById("sidebarToggle");
const sidebarContainer = document.querySelector(".sidebar-container");
const sidebarOverlay = document.getElementById("sidebarOverlay");
const body = document.body;

sidebarToggle.addEventListener("click", () => {
  sidebar.classList.toggle("closed");

  body.classList.toggle("sidebar-closed");

  const isClosed = sidebar.classList.contains("closed");
  localStorage.setItem("sidebarClosed", isClosed);

  window.dispatchEvent(
    new CustomEvent("sidebar-toggle", {
      detail: { isClosed },
    }),
  );
});

document.addEventListener("DOMContentLoaded", () => {
  const wasClosed = localStorage.getItem("sidebarClosed") === "true";
  if (wasClosed) {
    sidebar.classList.add("closed");
    body.classList.add("sidebar-closed");
  }
});

sidebarOverlay.addEventListener("click", () => {
  sidebarContainer.classList.remove("mobile-open");
});

const links = document.querySelectorAll(".sidebar-link");
links.forEach((link) => {
  link.addEventListener("click", function (e) {
    links.forEach((l) => l.classList.remove("active"));
    this.classList.add("active");
  });
});

window.addEventListener("sidebar-toggle", (e) => {
  console.log("Sidebar toggled:", e.detail.isClosed ? "closed" : "open");
});
