const { getIsDetachedMode, getCachedTags, getSearchInternalError } = require('../states');
const {
    BeginningHint,
    BeginningHintWithTag,
    SimpleArticleTag,
    ArticleTag,
    InternalErrorInfo,
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
        header: ({ html }) => {
            const Header = !state.context.tagsPlugin.tags.length
                ? html`${BeginningHint(html)}`
                : html`${BeginningHintWithTag(html)}`;
            return html`${getSearchInternalError() && InternalErrorInfo(html)}${Header}`;
        },
        item: ({ item, html }) => (
            !getIsDetachedMode() ? ArticleTag(item, false, html) : SimpleArticleTag(item, html)
        ),
    },
});

module.exports = getEmptySearchInputSource;
