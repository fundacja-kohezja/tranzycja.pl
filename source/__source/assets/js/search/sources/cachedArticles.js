const { SimpleSearchArticleResult, NoResults } = require('../templates');
const { getCachedArticles, getIsDetachedMode } = require('../states');

const getCachedArticlesSearchSource = (state) => ({
    sourceId: 'articles_search',
    getItems: () => getCachedArticles(),
    onSelect({ item }) {
        const url = new URL(`${window.location.origin}/${item.redirect}`);
        window.location.href = url.href;
    },
    templates: {
        ...state.context.tagsPlugin.tags.length && {
            ...!getIsDetachedMode() && {
                header: ({ html }) => (
                    html`<hr class="my-4"/>`
                ),
            },
            noResults: ({ html }) => (
                NoResults(html)
            ),
        },
        item: ({ item, html }) => (
            SimpleSearchArticleResult(item, html)
        ),
    },
});
module.exports = getCachedArticlesSearchSource;
