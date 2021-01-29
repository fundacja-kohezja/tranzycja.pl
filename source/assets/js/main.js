// window.docsearch = require('docsearch.js'); - to może na później

import smoothscroll from 'smoothscroll-polyfill';
import 'smoothscroll-anchor-polyfill';

smoothscroll.polyfill();

if (localStorage.theme === 'dark') {
    document.getElementById('theme_dark').checked = true
} else if (localStorage.theme === 'light') {
    document.getElementById('theme_light').checked = true
} else {
    document.getElementById('theme_auto').checked = true
}

var toc = document.getElementById('toc')
if (toc && window.matchMedia('(min-width: 1024px)').matches) {
    toc.open = true
}