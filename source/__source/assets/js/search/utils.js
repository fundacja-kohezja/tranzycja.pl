const readDOMDepth = (el, fn, excludeTags = []) => {
    if (el) {
        if (el.childNodes.length > 0 && !excludeTags.includes(el.tagName)) {
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

const mapToAlgoliaNegativeFilters = (tags, facetsToNegate, operator = 'AND') => tags
    .map(({ label, facet }) => {
        const filter = `${facet}:"${label}"`;
        return facetsToNegate.includes(facet) && `NOT ${filter}`;
    })
    .filter(Boolean)
    .join(` ${operator} `);

const groupBy = (items, key) => items.reduce((acc, item) => {
    if (!Object.prototype.hasOwnProperty.call(acc, key)) {
        acc[key] = [];
    }
    acc[key].push(item);
    return acc;
}, {});

module.exports = {
    readDOMDepth,
    mapToAlgoliaFilters,
    mapToAlgoliaNegativeFilters,
    groupBy,
};
