const {
    getCachedArticles,
    setCachedArticles,
    getRefreshMethod,
    setCachedTags,
    getCachedTags,
} = require('./states');
const { LIMIT_SEARCH_ARTICLES, LIMIT_SEARCH_TAGS } = require('./consts');

let cachedArticlesResponse = [];
let cachedTagsResponse = [];

const collectArticlesFromCache = (response, tags) => {
    if (tags.length > 0) {
        response.forEach((cachedArticle) => {
            const cachedArticles = getCachedArticles();
            if (cachedArticles.length < LIMIT_SEARCH_ARTICLES) {
                let passedTags = 0;
                tags.forEach(({ label: selectedTag }) => {
                    if (cachedArticle.tags.includes(selectedTag)) {
                        passedTags += 1;
                    }
                });
                if (passedTags === tags.length) {
                    setCachedArticles([cachedArticle, ...cachedArticles]);
                }
            }
        });
    }
};

const collectTagsFromCache = (response, tags) => {
    response.forEach((cachedTag) => {
        const cachedTags = getCachedTags();
        if (cachedTags.length < LIMIT_SEARCH_TAGS) {
            const tagObject = {
                label: cachedTag,
            };
            let shouldBeSaved = true;
            if (tags.length) {
                tags.forEach(({ label: selectedTag }) => {
                    if (cachedTag === selectedTag) {
                        shouldBeSaved = false;
                    }
                });
            }

            if (shouldBeSaved) {
                setCachedTags([...getCachedTags(), tagObject]);
            }
        }
    });
};

const useCachedArticles = (selectedTags) => new Promise(() => {
    setCachedArticles([]);
    new Promise((loadResponseResolve) => {
        if (cachedArticlesResponse.length) {
            collectArticlesFromCache(cachedArticlesResponse, selectedTags);
            loadResponseResolve();
        }
        fetch('/assets/search-caches/articles_with_tags.json')
            .then((response) => response.json())
            .then((cachedArticles) => {
                cachedArticlesResponse = cachedArticles;
                collectArticlesFromCache(cachedArticlesResponse, selectedTags);
                loadResponseResolve();
            });
    }).then(() => {
        getRefreshMethod()();
    });
});

const useCachedTags = (selectedTags = []) => new Promise(() => {
    setCachedTags([]);
    new Promise((loadResponseResolve) => {
        if (cachedTagsResponse.length) {
            collectTagsFromCache(cachedTagsResponse, selectedTags);
            loadResponseResolve();
        }
        fetch('/assets/search-caches/tags.json')
            .then((response) => response.json())
            .then((cachedTags) => {
                cachedTagsResponse = Object.keys(Object.fromEntries(
                    Object.entries(cachedTags).sort((a, b) => b[1] - a[1]),
                ));
                collectTagsFromCache(cachedTagsResponse, selectedTags);
                loadResponseResolve();
            });
    }).then(() => {
        getRefreshMethod()();
    });
});

module.exports = {
    useCachedArticles,
    useCachedTags,
};
