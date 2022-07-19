const { createTagsPlugin } = require('@algolia/autocomplete-plugin-tags');
const { getIsDetachedMode } = require('./states');
const { createArticleTagElement, ArticleTag } = require('./templates');

require('@algolia/autocomplete-plugin-tags/dist/theme.css');

const tagsPlugin = createTagsPlugin({
    getTagsSubscribers() {
        return [
            {
                sourceId: 'articles_tags',
                getTag({ item }) {
                    return item;
                },
            },
        ];
    },
    transformSource({ source }) {
        if (!getIsDetachedMode()) {
            return undefined;
        }

        return {
            ...source,
            templates: {
                item: ({ item, html }) => (
                    ArticleTag(item, true, html)
                ),
            },
        };
    },
    onChange({ tags, setIsOpen }) {
        requestAnimationFrame(() => {
            if (getIsDetachedMode()) {
                return;
            }
            const container = document.querySelector('.aa-InputWrapperPrefix');
            const oldTagsContainer = document.querySelector('.search-active-tags');

            const tagsContainer = document.createElement('div');
            tagsContainer.className = 'search-active-tags';
            tagsContainer.innerHTML = tags.map(({ label }) => createArticleTagElement(label, true)).join('');

            if (oldTagsContainer) {
                container.removeChild(oldTagsContainer);
            }

            container.appendChild(tagsContainer);
            setIsOpen(false);
        });
    },
});

const transformTagResponse = (state) => ({ facetHits }) => (
    facetHits[0].map(
        (hit) => !state.context.tagsPlugin.tags.filter(({ label }) => label === hit.label).length
        && ({
            ...hit,
            facet: 'tags',
        }),
    ).filter(Boolean)
);

module.exports = tagsPlugin;
module.exports.transformTagResponse = transformTagResponse;
