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
            const advancedSyntax = query.split(' ').filter((e) => e.trim().length > 0).length > 1;
            return getAlgoliaResults({
                searchClient,
                queries: [
                    {
                        indexName: 'articles',
                        advancedSyntax,
                        query: advancedSyntax ? `"${query}"` : query,
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
                const matches = value.match(/__aa-highlight__(.*?)__\/aa-highlight__/);
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
