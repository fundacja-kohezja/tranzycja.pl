import injectInput from './input';
import tagsPlugin from './tags';
import registerListeners from './listeners';

document.addEventListener('DOMContentLoaded', () => {
    injectInput();
    registerListeners();
    document.getElementById('autocomplete-search-container')?.addEventListener('click', (e) => {
        if (e.target && ((e.target.className.includes('search-tag') || e.target.parentElement.className.includes('search-tag')))) {
            const tagEl = e.target.className.includes('search-tag') ? e.target : e.target.parentElement;
            if (tagEl.className.includes('search-tag')) {
                tagsPlugin.data.setTags(
                    tagsPlugin.data.tags.filter(
                        ({ label }) => label !== tagEl.children[0].textContent,
                    ),
                );
            }
        }
    });
});
