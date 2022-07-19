const { readDOMDepth } = require('./utils');
const { SEARCH_MARK_ELEMENT_NAME } = require('./consts');

document.addEventListener('DOMContentLoaded', () => {
    const url = new URL(window.location.href);
    const { q, section } = Object.fromEntries(url.searchParams);

    const sectionEl = document.getElementById(section);
    let didAnyScroll = false;
    if (!!q && sectionEl) {
        const phraseRegExp = new RegExp(`(${q})`, 'gi');
        if (sectionEl.tagName === 'DETAILS') {
            sectionEl.setAttribute('open', true);
        }

        const nextEl = sectionEl.children.length > 0 ? sectionEl : sectionEl.nextElementSibling;
        readDOMDepth(nextEl, (el, isChild = false) => {
            if (!isChild && el.tagName === 'DETAILS' && phraseRegExp.test(el.textContent)) {
                el.setAttribute('open', true);
            }

            if (!isChild && /* el.tagName.startsWith('H') */ el?.getAttribute('id') && el?.tagName !== 'SUP') {
                return false;
            }

            if (el.data) {
                const div = document.createElement('div');
                const parentEl = el.parentElement;
                const lenBeforeReplace = parentEl.outerHTML.length;
                el.parentNode.insertBefore(div, el);
                div.insertAdjacentHTML(
                    'afterend',
                    el.data.replace(phraseRegExp, `<${SEARCH_MARK_ELEMENT_NAME}>$1</${SEARCH_MARK_ELEMENT_NAME}>`),
                );

                div.remove();
                el.remove();
                if (lenBeforeReplace !== parentEl.outerHTML.length && !didAnyScroll) {
                    parentEl.scrollIntoView();
                    didAnyScroll = true;
                }
            }
            return true;
        }, [SEARCH_MARK_ELEMENT_NAME]);
    }
});
