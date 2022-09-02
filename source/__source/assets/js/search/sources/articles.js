const { getAlgoliaResults } = require('@algolia/autocomplete-js');

const searchClient = require('../client');
const { SearchArticleResult, NoResults } = require('../templates');
const { LIMIT_SEARCH_ARTICLES } = require('../consts');
const { mapToAlgoliaFilters, groupBy } = require('../utils');

const getArticlesSearchSource = (query, state) => {
    const tagsByFacet = groupBy(
        state.context.tagsPlugin.tags,
        'tags',
    );
    return {
        sourceId: 'articles_search',
        getItems() {
            return getAlgoliaResults({
                searchClient,
                queries: [
                    {
                        indexName: 'articles',
                        query,
                        params: {
                            hitsPerPage: LIMIT_SEARCH_ARTICLES,
                            attributesToSnippet: ['content:35'],
                            filters: mapToAlgoliaFilters(tagsByFacet),
                        },
                    },
                ],
            });
        },
        onSelect({ item }) {
            const url = new URL(`${window.location.origin}/${item.redirect}`);
            url.searchParams.append('section', item.section);
            // eslint-disable-next-line no-underscore-dangle
            const value = item?._highlightResult?.content?.value;
            if (value) {
                const matches = Array.from(value.matchAll(/__aa-highlight__(.*?)__\/aa-highlight__/g)).sort((a, b) => b[1].length - a[1].length)[0];
                if (matches?.[1]) {
                    url.searchParams.append('q', matches[1]);
                }
            }
            window.location.href = url.href;
        },
        templates: {
            noResults: ({ html }) => (
                NoResults(html)
            ),
            item: ({ item, components, html }) => (
                SearchArticleResult(item, components.Snippet, html)
            ),
        },
    };
};
module.exports = getArticlesSearchSource;
