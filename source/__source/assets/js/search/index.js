const { useCachedTags } = require('./cachedSource');
const injectInput = require('./input');
const registerListeners = require('./listeners');

document.addEventListener('DOMContentLoaded', () => {
    useCachedTags();
    injectInput();
    registerListeners();
});
