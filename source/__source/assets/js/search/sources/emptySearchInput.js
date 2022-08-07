const { getIsDetachedMode, getCachedTags } = require('../states');
const {
    BeginningHint,
    BeginningHintWithTag,
    SimpleArticleTag,
    ArticleTag,
} = require('../templates');

const getEmptySearchInputSource = (query, state) => ({
    sourceId: 'articles_tags',
    getItems: () => getCachedTags(),
    onSelect({ item, setQuery }) {
        if (item.label.toLowerCase().includes(query.toLowerCase())) {
            setQuery('');
        }
    },
    templates: {
        header: ({ html }) => (
            !state.context.tagsPlugin.tags.length
                ? html`${BeginningHint(html)}${getIsDetachedMode() && html`<hr class="my-2"/>`}`
                : html`${BeginningHintWithTag(html)}${getIsDetachedMode() && html`<hr class="my-2"/>`}`
        ),
        item: ({ item, html }) => (
            !getIsDetachedMode() ? ArticleTag(item, false, html) : SimpleArticleTag(item, html)
        ),
    },
});

module.exports = getEmptySearchInputSource;
