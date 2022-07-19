const { getAlgoliaFacets } = require('@algolia/autocomplete-js');

const searchClient = require('../client');
const { getIsDetachedMode } = require('../states');
const { transformTagResponse } = require('../tags');
const { SimpleArticleTag, ArticleTag } = require('../templates');

const getTagsSearchSource = (query, state) => ({
    sourceId: 'articles_tags',
    getItems() {
        return getAlgoliaFacets({
            searchClient,
            queries: [
                {
                    indexName: 'articles',
                    facet: 'tags',
                    params: {
                        facetQuery: query,
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
        ...!getIsDetachedMode() && {
            footer: ({ html }) => (
                html`<hr class="my-4"/>`
            ),
        },
        item: ({ item, html }) => (
            !getIsDetachedMode() ? ArticleTag(item, false, html) : SimpleArticleTag(item, html)
        ),
    },
});

module.exports = getTagsSearchSource;
