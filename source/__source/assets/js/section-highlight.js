import _ from 'lodash'

var lastId = ''

var toc = document.querySelector('.toc')
var tocItems = toc.querySelectorAll("a")
var scrollItems = []

tocItems.forEach(function(item){
    var href = item.href
    if (href.length) scrollItems.push(document.getElementById(href.split('#').pop()))
})

window.addEventListener('scroll', _.throttle(function(){
    
    var cur = scrollItems.reduce(function(prev, item){
        if (item && item.getBoundingClientRect().top < window.innerHeight * 0.25) {
            return item;
        } else {
            return prev;
        }
    });
    
    var id = cur ? cur.id : ''
    
    if (lastId !== id) {
        if (lastId) {
            var el = document.querySelector('a[href="#' + lastId + '"]');
            if (el) {
                el.parentElement.classList.remove('current')
            }
        }
        lastId = id
        var el = document.querySelector('a[href="#' + id + '"]');
        if (el) {
            el.parentElement.classList.add('current')

            document.querySelectorAll('.foldable.visible').forEach(function(el){
                el.classList.remove('visible')
            })


            if (el.parentElement.classList.contains('foldable')) {
                el.parentElement.classList.add('visible')
            }
            var nextEl = el.parentElement.nextElementSibling;
            while (nextEl) {
                if (nextEl.classList.contains('foldable')) {
                    nextEl.classList.add('visible')
                } else {
                    break
                }
                nextEl = nextEl.nextElementSibling;
            }
            var prevEl = el.parentElement;
            while (prevEl) {
                if (prevEl.classList.contains('foldable')) {
                    prevEl.classList.add('visible')
                } else {
                    break
                }
                prevEl = prevEl.previousElementSibling;
            }
        }
    }
}, 300))

