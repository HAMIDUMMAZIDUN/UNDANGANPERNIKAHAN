import './bootstrap';
window.toggleSidebar = function () {
  const sidebar = document.getElementById('sidebar');
  sidebar.classList.toggle('translate-x-0');
  sidebar.classList.toggle('-translate-x-full');
};
