import smoothscroll from 'smoothscroll-polyfill';
import 'smoothscroll-anchor-polyfill';

smoothscroll.polyfill();

if (localStorage.theme === 'dark') {
    document.querySelectorAll('.theme_dark').forEach(el => el.checked = true)
} else if (localStorage.theme === 'light') {
    document.querySelectorAll('.theme_light').forEach(el => el.checked = true)
} else {
    document.querySelectorAll('.theme_auto').forEach(el => el.checked = true)
}

var toc = document.getElementById('toc')
if (toc && window.matchMedia('(min-width: 1024px)').matches) {
    toc.open = true
}
