const { getAlgoliaResults } = require('@algolia/autocomplete-js');

const searchClient = require('../client');
const { mapToAlgoliaFilters, groupBy } = require('../utils');
const { SearchArticleResult, NoResults } = require('../templates');

const getArticlesSearchSource = (query, state) => {
    const tagsByFacet = groupBy(
        state.context.tagsPlugin.tags,
        (tag) => tag.facet,
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
                            hitsPerPage: 5,
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
