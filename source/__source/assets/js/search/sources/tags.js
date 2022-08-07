const { getAlgoliaFacets } = require('@algolia/autocomplete-js');

const searchClient = require('../client');
const { getIsDetachedMode } = require('../states');
const { transformTagResponse } = require('../tags');
const { SimpleArticleTag, ArticleTag } = require('../templates');
const { LIMIT_SEARCH_TAGS } = require('../consts');
const { mapToAlgoliaNegativeFilters } = require('../utils');

const getTagsSearchSource = (query, state) => ({
    sourceId: 'articles_tags',
    getItems() {
        return getAlgoliaFacets({
            searchClient,
            queries: [
                {
                    indexName: 'tags',
                    facet: 'name',
                    params: {
                        facetQuery: query,
                        maxFacetHits: LIMIT_SEARCH_TAGS,
                        filters: mapToAlgoliaNegativeFilters(
                            state.context.tagsPlugin.tags,
                            ['name'],
                        ),
                    },
                },
            ],
            transformResponse: transformTagResponse,
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
