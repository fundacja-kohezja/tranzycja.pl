const { autocomplete } = require('@algolia/autocomplete-js');
const translate = require('../i18n');

const tagsPlugin = require('./tags');
const getArticlesSearchSource = require('./sources/articles');
const getTagsSearchSource = require('./sources/tags');
const getEmptySearchInputSource = require('./sources/emptySearchInput');
const getCachedArticlesSearchSource = require('./sources/cachedArticles');
const { setRefreshMethod, getIsUsingCachedData, setIsUsingCachedData } = require('./states');
const { useCachedArticles } = require('./cachedSource');

const searchConfig = {
    placeholder: translate('search.placeholder'),
    openOnFocus: true,
    classNames: {
        submitButton: 'hidden',
        form: 'search-form',
        input: 'search-input',
        panel: 'z-10',
    },
    translations: {
        clearButtonTitle: 'Wyczyść',
        detachedCancelButtonText: 'Zamknij',
        submitButtonTitle: 'Wyszukaj',
    },
    plugins: [tagsPlugin],
    getSources({ query, state }) {
        const oldUsingCached = getIsUsingCachedData();
        const usingCached = !query || query.trim().length < 3;
        if (oldUsingCached !== usingCached && usingCached && state.context.tagsPlugin.tags.length) {
            useCachedArticles(state.context.tagsPlugin.tags);
        }

        setIsUsingCachedData(usingCached);
        if (usingCached) {
            return [
                getEmptySearchInputSource(query, state),
                getCachedArticlesSearchSource(state),
            ];
        }

        return [
            getTagsSearchSource(query, state),
            getArticlesSearchSource(query, state),
        ];
    },
    onStateChange: (state) => {
        if (document.getElementById('autocomplete-search-container')) {
            if (!state.prevState.isOpen && state.state.isOpen) {
                document.body.classList.remove('search-close');
                document.body.classList.add('search-open');
            }
            if (state.prevState.isOpen && !state.state.isOpen) {
                document.body.classList.add('search-close');
                document.body.classList.remove('search-open');
            }
        }
    },
};

const injectInput = () => {
    const { refresh } = autocomplete({
        ...document.getElementById('autocomplete-search-container') ? {
            container: '#autocomplete-search-container',
        } : {
            container: '#autocomplete-search-container-menu',
        },
        ...searchConfig,
    });
    setRefreshMethod(refresh);
};

module.exports = injectInput;
