'use strict';

document.querySelectorAll('[data-student-directory]').forEach((form) => {
    const query = form.querySelector('[name="q"]');
    const results = document.getElementById('student-directory-results');
    const feedback = document.querySelector('[data-directory-feedback]');
    let timer;
    let pending;

    function cancelSearch() {
        window.clearTimeout(timer);
        pending?.abort();
        pending = null;
        results.removeAttribute('aria-busy');
    }

    async function refresh() {
        const request = new AbortController();
        pending = request;
        const url = new URL(form.action);

        for (const [key, value] of new FormData(form)) {
            if (value !== '') url.searchParams.set(key, value);
        }

        results.setAttribute('aria-busy', 'true');
        feedback.hidden = true;

        try {
            const response = await fetch(url, { signal: request.signal });

            if (pending !== request) return;

            if (response.redirected) {
                window.location.assign(response.url);
                return;
            }

            if (!response.ok) throw new Error('Search failed');
            const page = new DOMParser().parseFromString(await response.text(), 'text/html');
            const updated = page.getElementById(results.id);
            if (!updated) throw new Error('Search results are missing');
            if (pending !== request) return;

            results.replaceChildren(...updated.childNodes);
            window.history.replaceState(window.history.state, '', url);
        } catch (error) {
            if (error.name !== 'AbortError' && pending === request) {
                feedback.textContent = 'Search could not refresh. Select Apply to retry.';
                feedback.hidden = false;
            }
        } finally {
            if (pending === request) {
                pending = null;
                results.removeAttribute('aria-busy');
            }
        }
    }

    function queueSearch() {
        cancelSearch();
        if (form.checkValidity()) timer = window.setTimeout(refresh, 300);
    }

    query.addEventListener('input', queueSearch);
    form.addEventListener('submit', cancelSearch);

    const globalSearch = document.querySelector('[data-global-search]');
    globalSearch?.addEventListener('input', () => {
        query.value = globalSearch.value.slice(0, query.maxLength);
        queueSearch();
    });
});
