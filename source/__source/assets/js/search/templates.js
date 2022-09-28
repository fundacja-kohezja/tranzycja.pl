const translate = require('../i18n');
const { SEARCH_MARK_ELEMENT_NAME } = require('./consts');

const createArticleTagElement = (label, minusSvgIcon) => (
    `<button class="search-tag">
        <span class="text-white pr-2">${label}</span>
        ${minusSvgIcon
        ? '<svg class="inline text-white" width="9" height="9" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M.188511 1.09748L3.59103 4.5.188511 7.90252c-.251348.25135-.251348.65826 0 .90897.251348.2507.658262.25135.908969 0L4.5 5.40897l3.40252 3.40252c.25135.25135.65826.25135.90897 0 .2507-.25135.25135-.65826 0-.90897L5.40897 4.5l3.40252-3.40252c.25135-.25135.25135-.658264 0-.908969-.25135-.2507052-.65826-.251348-.90897 0L4.5 3.59103 1.09748.188511c-.25135-.251348-.658264-.251348-.908969 0-.2507052.251348-.251348.658262 0 .908969z"/></svg>'
        : '<svg class="inline text-white" width="15" height="15" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M6.99375 11.25H8.11875V8.1375H11.25V7.0125H8.11875V3.75H6.99375V7.0125H3.75V8.1375H6.99375V11.25ZM7.5 15C6.475 15 5.50625 14.8031 4.59375 14.4094C3.68125 14.0156 2.88438 13.4781 2.20312 12.7969C1.52187 12.1156 0.984375 11.3188 0.590625 10.4062C0.196875 9.49375 0 8.51875 0 7.48125C0 6.45625 0.196875 5.4875 0.590625 4.575C0.984375 3.6625 1.52187 2.86875 2.20312 2.19375C2.88438 1.51875 3.68125 0.984375 4.59375 0.590625C5.50625 0.196875 6.48125 0 7.51875 0C8.54375 0 9.5125 0.196875 10.425 0.590625C11.3375 0.984375 12.1313 1.51875 12.8063 2.19375C13.4813 2.86875 14.0156 3.6625 14.4094 4.575C14.8031 5.4875 15 6.4625 15 7.5C15 8.525 14.8031 9.49375 14.4094 10.4062C14.0156 11.3188 13.4813 12.1156 12.8063 12.7969C12.1313 13.4781 11.3375 14.0156 10.425 14.4094C9.5125 14.8031 8.5375 15 7.5 15ZM7.51875 13.875C9.28125 13.875 10.7812 13.2531 12.0187 12.0094C13.2562 10.7656 13.875 9.25625 13.875 7.48125C13.875 5.71875 13.2562 4.21875 12.0187 2.98125C10.7812 1.74375 9.275 1.125 7.5 1.125C5.7375 1.125 4.23438 1.74375 2.99063 2.98125C1.74688 4.21875 1.125 5.725 1.125 7.5C1.125 9.2625 1.74688 10.7656 2.99063 12.0094C4.23438 13.2531 5.74375 13.875 7.51875 13.875Z"/></svg>'
    }
    </button>`
);

const SearchArticleResult = (item, algoliaSnippet, html) => {
    const path = html([item.path.split('-->').join(
        '<svg class="h-5 w-5 transform -rotate-90 inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>',
    )]);

    const snippet = algoliaSnippet({
        hit: item,
        attribute: 'content',
        tagName: SEARCH_MARK_ELEMENT_NAME,
    });

    return html`
    <div class="aa-ItemWrapper">
        <div class="aa-ItemContent">
            <div class="aa-ItemContentBody">
                <div class="aa-ItemContentTitle font-bold">
                    ${path}
                </div>
                <div class="aa-ItemContentDescription px-2">
                    ${snippet}
                </div>
            </div>
        </div>
    </div>`;
};

const SimpleSearchArticleResult = ({ title, lead }, html) => (
    html`
    <div class="aa-ItemWrapper">
        <div class="aa-ItemContent">
            <div class="aa-ItemContentBody">
                <div class="aa-ItemContentTitle font-bold">
                    ${title}
                </div>
                <div class="aa-ItemContentDescription px-2">
                    ${lead}
                </div>
            </div>
        </div>
    </div>`
);

const NoResults = (html) => (
    html`<i>${translate('search.noResults')}</i>`
);

const BeginningHint = (html) => (
    html`
    <p class="m-0 mb-3">
        <i>${translate('search.firstHint')}</i>
    </p>
    `
);

const BeginningHintWithTag = (html) => (
    html`
    <p class="m-0 mb-3">
        <i>${translate('search.secondHint')}</i>
    </p>
    `
);

const SimpleArticleTag = ({ label }, html) => (
    html`
    <div class="aa-ItemWrapper">
        <div class="aa-ItemContent">
            <div class="aa-ItemContentBody">
                <div class="aa-ItemContentTitle font-bold">
                    ${label}
                </div>
            </div>
        </div>
        <svg class="inline ml-auto" width="15" height="15" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M6.99375 11.25H8.11875V8.1375H11.25V7.0125H8.11875V3.75H6.99375V7.0125H3.75V8.1375H6.99375V11.25ZM7.5 15C6.475 15 5.50625 14.8031 4.59375 14.4094C3.68125 14.0156 2.88438 13.4781 2.20312 12.7969C1.52187 12.1156 0.984375 11.3188 0.590625 10.4062C0.196875 9.49375 0 8.51875 0 7.48125C0 6.45625 0.196875 5.4875 0.590625 4.575C0.984375 3.6625 1.52187 2.86875 2.20312 2.19375C2.88438 1.51875 3.68125 0.984375 4.59375 0.590625C5.50625 0.196875 6.48125 0 7.51875 0C8.54375 0 9.5125 0.196875 10.425 0.590625C11.3375 0.984375 12.1313 1.51875 12.8063 2.19375C13.4813 2.86875 14.0156 3.6625 14.4094 4.575C14.8031 5.4875 15 6.4625 15 7.5C15 8.525 14.8031 9.49375 14.4094 10.4062C14.0156 11.3188 13.4813 12.1156 12.8063 12.7969C12.1313 13.4781 11.3375 14.0156 10.425 14.4094C9.5125 14.8031 8.5375 15 7.5 15ZM7.51875 13.875C9.28125 13.875 10.7812 13.2531 12.0187 12.0094C13.2562 10.7656 13.875 9.25625 13.875 7.48125C13.875 5.71875 13.2562 4.21875 12.0187 2.98125C10.7812 1.74375 9.275 1.125 7.5 1.125C5.7375 1.125 4.23438 1.74375 2.99063 2.98125C1.74688 4.21875 1.125 5.725 1.125 7.5C1.125 9.2625 1.74688 10.7656 2.99063 12.0094C4.23438 13.2531 5.74375 13.875 7.51875 13.875Z"/></svg>
    </div>`
);

const InternalErrorInfo = (html) => (
    html`
    <p class="m-0 text-red-600 font-bold mb-3">${translate('search.internalError')}</p>`
);

const ArticleTag = ({ label }, minusSvgIcon, html) => (
    html([createArticleTagElement(label, minusSvgIcon)])
);

module.exports = {
    createArticleTagElement,
    SearchArticleResult,
    SimpleSearchArticleResult,
    NoResults,
    BeginningHint,
    BeginningHintWithTag,
    SimpleArticleTag,
    ArticleTag,
    InternalErrorInfo,
};
