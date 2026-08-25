(function () {
    'use strict';

    var filters = document.querySelector('[data-medical-dictionary-filters]');

    if (!filters) {
        return;
    }

    var search = filters.querySelector('[data-dictionary-search]');
    var category = filters.querySelector('[data-dictionary-category]');
    var items = Array.prototype.slice.call(document.querySelectorAll('[data-dictionary-item]'));
    var noResults = document.querySelector('[data-dictionary-no-results]');
    var resultCount = document.querySelector('[data-dictionary-count]');
    var reset = document.querySelector('[data-dictionary-reset]');
    var locale = document.documentElement.lang || undefined;

    function normalize(value) {
        return String(value || '').trim().toLocaleLowerCase(locale);
    }

    function filterTerms() {
        var query = normalize(search.value);
        var selectedCategory = category.value;
        var visibleCount = 0;

        items.forEach(function (item) {
            var matchesSearch = !query || normalize(item.dataset.search).includes(query);
            var matchesCategory = !selectedCategory || item.dataset.category === selectedCategory;
            var isVisible = matchesSearch && matchesCategory;

            item.hidden = !isVisible;
            if (isVisible) {
                visibleCount += 1;
            }
        });

        resultCount.textContent = visibleCount;
        noResults.hidden = visibleCount !== 0;
        reset.hidden = !query && !selectedCategory;
    }

    search.addEventListener('input', filterTerms);
    category.addEventListener('change', filterTerms);
    category.addEventListener('input', filterTerms);

    reset.addEventListener('click', function () {
        search.value = '';
        category.value = '';
        filterTerms();
        search.focus();
    });

    search.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && search.value) {
            search.value = '';
            filterTerms();
        }
    });

    filterTerms();
}());
