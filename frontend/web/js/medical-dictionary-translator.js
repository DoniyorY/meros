(function () {
    'use strict';

    var translator = document.querySelector('[data-dictionary-translator]');

    if (!translator) {
        return;
    }

    var form = translator.querySelector('[data-translator-form]');
    var source = translator.querySelector('[data-translator-source]');
    var target = translator.querySelector('[data-translator-target]');
    var query = translator.querySelector('[data-translator-query]');
    var submit = translator.querySelector('[data-translator-submit]');
    var swap = translator.querySelector('[data-translator-swap]');
    var results = translator.querySelector('[data-translator-results]');
    var template = translator.querySelector('[data-translator-template]');
    var requestController = null;

    function showMessage(message, isError) {
        results.replaceChildren();
        var element = document.createElement('p');
        element.className = 'meros-translator-message' + (isError ? ' is-error' : '');
        element.textContent = message;
        results.appendChild(element);
    }

    function renderTerms(terms) {
        results.replaceChildren();

        if (!terms.length) {
            showMessage(translator.dataset.emptyMessage, false);
            return;
        }

        terms.forEach(function (term) {
            var result = template.content.cloneNode(true);
            var targetDictionaryUrl = translator.dataset.dictionaryUrl.replace(/\/(ru|en|uz)(?=\/)/, '/' + target.value);
            result.querySelector('[data-result-source]').textContent = term.source;
            result.querySelector('[data-result-translation]').textContent = term.translation;
            result.querySelector('[data-result-description]').textContent = term.description;
            result.querySelector('[data-result-link]').href = targetDictionaryUrl + '/' + encodeURIComponent(term.slug);
            results.appendChild(result);
        });
    }

    async function translate() {
        var value = query.value.trim();

        if (value.length < 2) {
            query.focus();
            return;
        }

        if (requestController) {
            requestController.abort();
        }
        requestController = new AbortController();
        submit.disabled = true;
        translator.classList.add('is-loading');

        var url = new URL(translator.dataset.translateUrl, window.location.origin);
        url.searchParams.set('query', value);
        url.searchParams.set('from', source.value);
        url.searchParams.set('to', target.value);

        try {
            var response = await fetch(url, {
                headers: {'Accept': 'application/json'},
                signal: requestController.signal
            });
            if (!response.ok) {
                throw new Error('Translation request failed');
            }
            var data = await response.json();
            renderTerms(Array.isArray(data.results) ? data.results : []);
        } catch (error) {
            if (error.name !== 'AbortError') {
                showMessage(translator.dataset.errorMessage, true);
            }
        } finally {
            submit.disabled = false;
            translator.classList.remove('is-loading');
        }
    }

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        translate();
    });

    swap.addEventListener('click', function () {
        var previousSource = source.value;
        source.value = target.value;
        target.value = previousSource;
        if (query.value.trim().length >= 2) {
            translate();
        }
    });
}());
