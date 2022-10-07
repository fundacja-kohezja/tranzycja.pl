const { readDOMDepth } = require('./utils');
const { SEARCH_MARK_ELEMENT_NAME, SEARCH_MARK_LINE_ELEMENT_NAME } = require('./consts');

const markPhraseInSection = (q, section) => {
    const sectionEl = document.getElementById(section);
    let didAnyScroll = false;
    if (sectionEl) {
        const phraseRegExp = new RegExp(`(${q})`, 'gi');
        const lineRegExp = new RegExp(`((?!\\s)[^.]*${q}[^.]*(?=\\.|$))`, 'gim');

        let shouldStop = (el) => el.tagName.startsWith('H');
        if (sectionEl.tagName === 'DETAILS') {
            sectionEl.setAttribute('open', true);
            shouldStop = () => true;
        }

        if (!q) {
            sectionEl.scrollIntoView();
            return;
        }

        const nextEl = sectionEl.children.length > 0 ? sectionEl : sectionEl.nextElementSibling;
        readDOMDepth(nextEl, (el, isChild = false) => {
            if (!isChild && el.tagName === 'DETAILS' && phraseRegExp.test(el.textContent)) {
                el.setAttribute('open', true);
            }

            if (!isChild && shouldStop(el) && el.id && el.tagName !== 'SUP') {
                return false;
            }

            if (el.data) {
                const div = document.createElement('div');
                const parentEl = el.parentElement;
                const lenBeforeReplace = parentEl.outerHTML.length;
                el.parentNode.insertBefore(div, el);
                let markedData = el.data.replace(lineRegExp, `<${SEARCH_MARK_LINE_ELEMENT_NAME}>$1</${SEARCH_MARK_LINE_ELEMENT_NAME}>`);
                markedData = markedData.replace(phraseRegExp, `<${SEARCH_MARK_ELEMENT_NAME}>$1</${SEARCH_MARK_ELEMENT_NAME}>`);
                div.insertAdjacentHTML(
                    'afterend',
                    markedData,
                );

                div.remove();
                el.remove();
                if (lenBeforeReplace !== parentEl.outerHTML.length && !didAnyScroll) {
                    parentEl.scrollIntoView();
                    didAnyScroll = true;
                }
            }
            return true;
        }, [SEARCH_MARK_ELEMENT_NAME, SEARCH_MARK_LINE_ELEMENT_NAME, 'ASIDE']);
    }
};

window.addEventListener('load', () => {
    const url = new URL(window.location.href);
    const { q, section } = Object.fromEntries(url.searchParams);
    markPhraseInSection(q, section);
});
