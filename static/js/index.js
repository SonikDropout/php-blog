(function () {
  // toggle button functionality
  var toggle = document.querySelector('.toggle');
  var navbar = document.querySelector('.navbar');
  toggle.addEventListener('click', function () {
    navbar.classList.toggle('visible');
  })
  // back to top anchor functionality
  var content = document.querySelector('.content');
  var contentCoords = content.getBoundingClientRect();
  toTop.style.left = (contentCoords.right - 40) + 'px';

  window.onscroll = function () {
    if (window.scrollY > 100) {
      toTop.classList.remove('hidden');
    } else {
      toTop.classList.add('hidden');
    }
  };
}());