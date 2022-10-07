let isDetachedMode = null;
let query = null;
let refreshMethod = null;
let cachedArticles = [];
let cachedTags = [];
let isUsingCachedData = false;
let searchInternalError = false;

const getIsDetachedMode = () => isDetachedMode;

const setIsDetachedMode = (value) => {
    isDetachedMode = value;
};

const getQuery = () => query;

const setQuery = (value) => {
    query = value;
};

const getRefreshMethod = () => refreshMethod;

const setRefreshMethod = (value) => {
    refreshMethod = value;
};

const getCachedArticles = () => cachedArticles;

const setCachedArticles = (value) => {
    cachedArticles = value;
};

const getCachedTags = () => cachedTags;

const setCachedTags = (value) => {
    cachedTags = value;
};

const getIsUsingCachedData = () => isUsingCachedData;

const setIsUsingCachedData = (value) => {
    isUsingCachedData = value;
};

const getSearchInternalError = () => searchInternalError;

const setSearchInternalError = (value) => {
    searchInternalError = value;
};

module.exports = {
    getIsDetachedMode,
    setIsDetachedMode,
    getQuery,
    setQuery,
    getRefreshMethod,
    setRefreshMethod,
    getCachedArticles,
    setCachedArticles,
    getCachedTags,
    setCachedTags,
    getIsUsingCachedData,
    setIsUsingCachedData,
    getSearchInternalError,
    setSearchInternalError,
};
