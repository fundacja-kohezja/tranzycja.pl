const pl = require('./labels/pl.yml');
const en = require('./labels/en.yml');

const labels = { pl, en };

const traversePath = (object, path) => path.split('.').reduce((res, prop) => res?.[prop], object);
const getLangKey = () => (document.location.pathname.startsWith('/en') ? 'en' : 'pl');
const translate = (label) => traversePath(labels[getLangKey()].default, label) || (
    // eslint-disable-next-line no-console
    console.warn(`Missing translation ${label} for lang ${getLangKey()}`)
);

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-i18n-attrs]').forEach((node) => {
        const attributes = node.getAttribute('data-i18n-attrs');
        attributes.split(',').forEach((attrName) => {
            const translated = translate(
                node.getAttribute(`data-i18n-${attrName}`),
            );

            if (attrName === 'text') {
                let textNode = null;
                if (node.childNodes.length > 0) {
                    [textNode] = Array.from(node.childNodes).filter(
                        (child) => child.nodeType === 3 && child.nodeValue.trim().length > 0,
                    );
                }
                if (!textNode) {
                    textNode = document.createTextNode('');
                    node.appendChild(textNode);
                }
                textNode.nodeValue = translated;
            } else { node.setAttribute(attrName, translated); }
        });
    });
});

module.exports = translate;
