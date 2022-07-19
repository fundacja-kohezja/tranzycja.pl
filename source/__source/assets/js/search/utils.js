const readDOMDepth = (el, fn, excludeTags = []) => {
    if (el) {
        if (el.childNodes.length > 0) {
            Array.from(el.childNodes)
                .forEach(
                    (child) => !excludeTags.includes(child.tagName)
                    && readDOMDepth(child, (arg) => fn(arg, true)),
                );
        }
        const next = fn(el);
        if (!next) {
            return;
        }
        readDOMDepth(el.nextElementSibling, fn);
    }
};

const mapToAlgoliaFilters = (tagsByFacet, operator = 'AND') => Object.keys(tagsByFacet)
    .map((facet) => `(${tagsByFacet[facet]
        .map(({ label }) => `${facet}:"${label}"`)
        .join(' AND ')})`)
    .join(` ${operator} `);

const groupBy = (items, predicate) => items.reduce((acc, item) => {
    const key = predicate(item);
    if (!Object.prototype.hasOwnProperty.call(acc, key)) {
        acc[key] = [];
    }
    acc[key].push(item);
    return acc;
}, {});

module.exports = {
    readDOMDepth,
    mapToAlgoliaFilters,
    groupBy,
};
