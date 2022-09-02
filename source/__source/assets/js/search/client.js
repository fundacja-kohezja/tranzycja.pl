const algoliasearch = require('algoliasearch/lite');
const { ALGOLIA_SEARCH_ONLY_API_KEY } = require('./consts');

const searchClient = algoliasearch(
    'C8U4P0CC81',
    ALGOLIA_SEARCH_ONLY_API_KEY,
);

module.exports = searchClient;
