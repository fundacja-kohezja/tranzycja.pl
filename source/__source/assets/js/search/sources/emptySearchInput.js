const { getAlgoliaFacets } = require('@algolia/autocomplete-js');

const searchClient = require('../client');
const { getIsDetachedMode } = require('../states');
const { transformTagResponse } = require('../tags');
const { BeginningHint, SimpleArticleTag, ArticleTag } = require('../templates');

const getEmptySearchInputSource = (query, state) => ({
    sourceId: 'articles_tags',
    getItems() {
        return getAlgoliaFacets({
            searchClient,
            queries: [
                {
                    indexName: 'articles',
                    facet: 'tags',
                    params: {
                        facetQuery: '*',
                        maxFacetHits: 5,
                    },
                },
            ],
            transformResponse: transformTagResponse(state),
        });
    },
    onSelect({ item, setQuery }) {
        if (item.label.toLowerCase().includes(query.toLowerCase())) {
            setQuery('');
        }
    },
    templates: {
        header: ({ html }) => (
            html`${BeginningHint(html)}${getIsDetachedMode() && html`<hr class="my-2"/>`}`
        ),
        item: ({ item, html }) => (
            !getIsDetachedMode() ? ArticleTag(item, false, html) : SimpleArticleTag(item, html)
        ),
    },
});

module.exports = getEmptySearchInputSource;
