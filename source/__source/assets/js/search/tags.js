const { createTagsPlugin } = require('@algolia/autocomplete-plugin-tags');
const { useCachedArticles, useCachedTags } = require('./cachedSource');
const { getIsDetachedMode, getIsUsingCachedData } = require('./states');
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
    onChange({ tags }) {
        if (getIsUsingCachedData()) {
            useCachedArticles(tags);
            useCachedTags(tags);
        }

        requestAnimationFrame(() => {
            if (getIsDetachedMode()) {
                document.body.classList.remove('search-open');
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
        });
    },
});

const transformTagResponse = ({ facetHits }) => facetHits[0].map(
    (hit) => ({
        ...hit,
        facet: 'name',
    }),
).filter(Boolean);

module.exports = tagsPlugin;
module.exports.transformTagResponse = transformTagResponse;
