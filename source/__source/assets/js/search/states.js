let isDetachedMode = null;
let query = null;

const getIsDetachedMode = () => isDetachedMode;

const setIsDetachedMode = (value) => {
    isDetachedMode = value;
};

const getQuery = () => query;

const setQuery = (value) => {
    query = value;
};

module.exports = {
    getIsDetachedMode,
    setIsDetachedMode,
    getQuery,
    setQuery,
};
