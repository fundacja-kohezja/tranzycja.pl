const tagsPlugin = require('./tags');
const { setIsDetachedMode, getIsDetachedMode, setSearchInternalError } = require('./states');
const { useCachedArticles } = require('./cachedSource');

const registerListeners = () => {
    setIsDetachedMode(!!document.querySelector('.aa-DetachedSearchButton'));
    document.querySelectorAll('.toggle-search').forEach((el) => el.addEventListener('click', () => {
        const mainSearchContainer = document.getElementById('autocomplete-search-container');
        if (!getIsDetachedMode()) {
            if (mainSearchContainer) {
                document.getElementsByClassName('aa-Input')[0].focus();
            } else {
                document.getElementById('autocomplete-search-container-menu').classList.toggle('hidden');
                const cls = document.querySelector('.slide:not(.no-slide)').classList;
                if (cls.contains('slide-down') || cls.contains('slide-up')) {
                    cls.toggle('slide-up');
                }
                cls.toggle('slide-down');
                document.getElementsByClassName('aa-Input')[0].focus();
            }
        } else {
            document.querySelector('.aa-DetachedSearchButton').click();
        }
    }));

    document.querySelectorAll(
        '#autocomplete-search-container, #autocomplete-search-container-menu',
    ).forEach((el) => el.addEventListener('click', (e) => {
        if (e.target) {
            const tagEl = e.target.closest('.search-tag');
            if (tagEl && tagEl.className.includes('search-tag')) {
                requestAnimationFrame(() => {
                    tagsPlugin.data.setTags(
                        tagsPlugin.data.tags.filter(
                            ({ label }) => label !== tagEl.children[0].textContent,
                        ),
                    );
                    useCachedArticles(tagsPlugin.data.tags);
                });
            }
        }
    }));

    window.addEventListener('unhandledrejection', (e) => {
        setSearchInternalError(true);
        // eslint-disable-next-line no-console
        console.error(e);
    });

    window.matchMedia('(max-width: 680px)').addEventListener('change', (mqEvent) => {
        setIsDetachedMode(mqEvent.matches);
        document.querySelector('.aa-DetachedCancelButton')?.click();
    });
};

module.exports = registerListeners;
