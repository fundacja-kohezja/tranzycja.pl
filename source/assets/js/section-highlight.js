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
                el.classList.remove('current')
            }
        }
        lastId = id
        var el = document.querySelector('a[href="#' + id + '"]');
        if (el) {
            el.classList.add('current')
        }
    }
}, 300))

