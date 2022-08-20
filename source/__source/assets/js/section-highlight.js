import _ from 'lodash';

let lastId = '';

const toc = document.querySelector('.toc');
const tocItems = toc.querySelectorAll('a');
const scrollItems = [];

tocItems.forEach((item) => {
    const { href } = item;
    if (href.length) scrollItems.push(document.getElementById(href.split('#').pop()));
});

window.addEventListener('scroll', _.throttle(() => {
    const cur = scrollItems.reduce((prev, item) => {
        if (item && item.getBoundingClientRect().top < window.innerHeight * 0.25) {
            return item;
        }
        return prev;
    });

    const id = cur ? cur.id : '';

    if (lastId !== id) {
        if (lastId) {
            var el = document.querySelector(`a[href="#${lastId}"]`);
            if (el) {
                el.parentElement.classList.remove('current');
            }
        }
        lastId = id;
        var el = document.querySelector(`a[href="#${id}"]`);
        if (el) {
            el.parentElement.classList.add('current');

            document.querySelectorAll('.foldable.visible').forEach((el) => {
                el.classList.remove('visible');
            });

            if (el.parentElement.classList.contains('foldable')) {
                el.parentElement.classList.add('visible');
            }
            let nextEl = el.parentElement.nextElementSibling;
            while (nextEl) {
                if (nextEl.classList.contains('foldable')) {
                    nextEl.classList.add('visible');
                } else {
                    break;
                }
                nextEl = nextEl.nextElementSibling;
            }
            let prevEl = el.parentElement;
            while (prevEl) {
                if (prevEl.classList.contains('foldable')) {
                    prevEl.classList.add('visible');
                } else {
                    break;
                }
                prevEl = prevEl.previousElementSibling;
            }
        }
    }
}, 300));
