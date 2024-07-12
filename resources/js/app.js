import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// app.js
// window.addEventListener("scroll", function() {
//     var footer = document.querySelector('.fixed-footer');
//     if (footer) {
//         var distanceFromTop = footer.getBoundingClientRect().top;
//         var windowHeight = window.innerHeight;
//         if (distanceFromTop <= windowHeight) {
//             footer.style.display = 'block';
//         } else {
//             footer.style.display = 'none';
//         }
//     }
// });
