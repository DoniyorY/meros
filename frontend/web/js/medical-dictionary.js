(function () {
    'use strict';

    var filters = document.querySelector('[data-medical-dictionary-filters]');

    if (!filters) {
        return;
    }

    var search = filters.querySelector('[data-dictionary-search]');
    var category = filters.querySelector('[data-dictionary-category]');
    var type = filters.querySelector('[data-dictionary-type]');
    var parameterPrefix = 'MedicalDictionarySearch';
    var debounceTimer;
    var activeRequest;

    function parameter(name) {
        return parameterPrefix + '[' + name + ']';
    }

    function filtersUrl() {
        var url = new URL(filters.dataset.url, window.location.origin);

        if (search.value.trim()) {
            url.searchParams.set(parameter('query'), search.value.trim());
        }
        if (category.value) {
            url.searchParams.set(parameter('category_id'), category.value);
        }
        if (type.value) {
            url.searchParams.set(parameter('type'), type.value);
        }

        return url;
    }

    function replaceResults(html) {
        var documentFragment = document.createRange().createContextualFragment(html);
        var nextResults = documentFragment.querySelector('[data-dictionary-results]');
        var currentResults = document.querySelector('[data-dictionary-results]');

        if (nextResults && currentResults) {
            currentResults.replaceWith(nextResults);
        }
    }

    function load(url, updateHistory) {
        if (activeRequest) {
            activeRequest.abort();
        }

        var request = new AbortController();
        activeRequest = request;
        filters.setAttribute('aria-busy', 'true');

        fetch(url.toString(), {
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            signal: request.signal
        }).then(function (response) {
            if (!response.ok) {
                throw new Error('Dictionary request failed with status ' + response.status);
            }
            return response.text();
        }).then(function (html) {
            replaceResults(html);
            if (updateHistory) {
                window.history.pushState({}, '', url.toString());
            }
        }).catch(function (error) {
            if (error.name !== 'AbortError') {
                window.location.assign(url.toString());
            }
        }).finally(function () {
            if (activeRequest === request) {
                activeRequest = null;
                filters.removeAttribute('aria-busy');
            }
        });
    }

    function applyFilters() {
        window.clearTimeout(debounceTimer);
        load(filtersUrl(), true);
    }

    search.addEventListener('input', function () {
        window.clearTimeout(debounceTimer);
        debounceTimer = window.setTimeout(applyFilters, 350);
    });
    category.addEventListener('change', applyFilters);
    type.addEventListener('change', applyFilters);

    document.addEventListener('click', function (event) {
        var reset = event.target.closest('[data-dictionary-reset]');
        var pageLink = event.target.closest('.meros-dictionary-pagination a');

        if (reset) {
            search.value = '';
            category.value = '';
            type.value = '';
            applyFilters();
            search.focus();
        } else if (pageLink) {
            event.preventDefault();
            load(new URL(pageLink.href, window.location.origin), true);
        }
    });

    search.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && search.value) {
            search.value = '';
            applyFilters();
        }
    });

    window.addEventListener('popstate', function () {
        var url = new URL(window.location.href);
        search.value = url.searchParams.get(parameter('query')) || '';
        category.value = url.searchParams.get(parameter('category_id')) || '';
        type.value = url.searchParams.get(parameter('type')) || '';
        load(url, false);
    });
}());
