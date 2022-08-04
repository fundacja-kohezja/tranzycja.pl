const { autocomplete } = require('@algolia/autocomplete-js');

const tagsPlugin = require('./tags');
const getArticlesSearchSource = require('./sources/articles');
const getTagsSearchSource = require('./sources/tags');
const getEmptySearchInputSource = require('./sources/emptySearchInput');

require('@algolia/autocomplete-theme-classic');

const searchConfig = {
    placeholder: 'Co Cię interesuje?',
    debug: true,
    openOnFocus: true,
    classNames: {
        submitButton: 'hidden',
        form: 'search-form',
        input: 'search-input',
        panel: 'z-10',
    },
    plugins: [tagsPlugin],
    getSources({ query, state }) {
        if (!query || query.trim().length < 3) {
            return [
                getEmptySearchInputSource(query, state),
            ];
        }
        return [
            getTagsSearchSource(query, state),
            getArticlesSearchSource(query, state),
        ];
    },
};

const injectInput = () => {
    autocomplete({
        ...document.getElementById('autocomplete-search-container') ? {
            container: '#autocomplete-search-container',
        } : {
            container: '#autocomplete-search-container-menu',
        },
        ...searchConfig,
    });
};

module.exports = injectInput;
