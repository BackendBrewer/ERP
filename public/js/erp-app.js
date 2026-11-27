'use strict';

(() => {
    const root = document.documentElement;
    const themeStorageKey = 'academy-erp-theme';
    const storedTheme = window.localStorage.getItem(themeStorageKey);

    if (storedTheme === 'dark' || storedTheme === 'light') {
        root.dataset.theme = storedTheme;
    }

    const themeButton = document.querySelector('[data-theme-toggle]');
    themeButton?.setAttribute('aria-pressed', String(root.dataset.theme === 'dark'));
    themeButton?.setAttribute('aria-label', root.dataset.theme === 'dark' ? 'Switch to light theme' : 'Switch to dark theme');
    themeButton?.addEventListener('click', () => {
        const nextTheme = root.dataset.theme === 'dark' ? 'light' : 'dark';
        root.dataset.theme = nextTheme;
        window.localStorage.setItem(themeStorageKey, nextTheme);
        themeButton.setAttribute('aria-pressed', String(nextTheme === 'dark'));
        themeButton.setAttribute('aria-label', nextTheme === 'dark' ? 'Switch to light theme' : 'Switch to dark theme');
    });

    const sidebar = document.querySelector('[data-sidebar]');
    const backdrop = document.querySelector('[data-sidebar-backdrop]');
    const menuButton = document.querySelector('[data-sidebar-toggle]');
    const appShell = document.querySelector('.app-shell');
    const collapseButton = document.querySelector('[data-sidebar-collapse]');
    const sidebarCollapsed = window.localStorage.getItem('academy-erp-sidebar') === 'collapsed';
    if (sidebarCollapsed && window.matchMedia('(min-width: 761px)').matches) {
        appShell?.classList.add('sidebar-collapsed');
        collapseButton?.setAttribute('aria-pressed', 'true');
    }
    collapseButton?.addEventListener('click', () => {
        const collapsed = appShell?.classList.toggle('sidebar-collapsed') ?? false;
        window.localStorage.setItem('academy-erp-sidebar', collapsed ? 'collapsed' : 'expanded');
        collapseButton.setAttribute('aria-pressed', String(collapsed));
        collapseButton.setAttribute('aria-label', collapsed ? 'Expand sidebar' : 'Collapse sidebar');
    });
    const closeSidebar = () => {
        sidebar?.classList.remove('is-open');
        backdrop?.classList.remove('is-visible');
        menuButton?.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('sidebar-open');
    };

    menuButton?.addEventListener('click', () => {
        const isOpen = sidebar?.classList.toggle('is-open') ?? false;
        backdrop?.classList.toggle('is-visible', isOpen);
        menuButton.setAttribute('aria-expanded', String(isOpen));
        document.body.classList.toggle('sidebar-open', isOpen);
    });
    backdrop?.addEventListener('click', closeSidebar);
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeSidebar();
            document.querySelectorAll('[data-notification-panel]').forEach((panel) => {
                panel.hidden = true;
            });
        }
    });

    document.querySelectorAll('[data-notification-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const panel = document.querySelector('[data-notification-panel]');
            if (panel) {
                panel.hidden = !panel.hidden;
                button.setAttribute('aria-expanded', String(!panel.hidden));
            }
        });
    });

    document.querySelectorAll('[data-password-toggle]').forEach((button) => {
        const input = document.getElementById(button.dataset.passwordToggle);
        if (!(input instanceof HTMLInputElement)) return;
        button.hidden = false;
        button.parentElement?.classList.add('has-toggle');
        button.addEventListener('click', () => {
            const showing = input.type === 'password';
            input.type = showing ? 'text' : 'password';
            button.textContent = showing ? 'Hide password' : 'Show password';
            button.setAttribute('aria-pressed', String(showing));
        });
    });

    const filterTable = (input, table) => {
        const query = input.value.trim().toLocaleLowerCase();
        const rows = [...table.querySelectorAll('tbody tr[data-searchable]')];
        let visibleCount = 0;
        rows.forEach((row) => {
            const matches = row.textContent.toLocaleLowerCase().includes(query);
            row.hidden = !matches;
            if (matches) visibleCount += 1;
        });
        const empty = table.closest('.surface')?.querySelector('[data-search-empty]');
        if (empty) empty.hidden = rows.length === 0 || visibleCount !== 0;
    };

    const filterList = (input, list) => {
        const query = input.value.trim().toLocaleLowerCase();
        const entries = [...list.querySelectorAll('[data-searchable]')];
        let visibleCount = 0;
        entries.forEach((entry) => {
            const matches = entry.textContent.toLocaleLowerCase().includes(query);
            entry.hidden = !matches;
            if (matches) visibleCount += 1;
        });
        const empty = list.closest('.surface')?.querySelector('[data-search-empty]');
        if (empty && entries.length > 0) empty.hidden = visibleCount !== 0;
    };

    document.querySelectorAll('[data-table-search]').forEach((input) => {
        const table = document.getElementById(input.dataset.tableSearch);
        if (!table) return;
        input.addEventListener('input', () => filterTable(input, table));
    });

    document.querySelectorAll('[data-list-search]').forEach((input) => {
        const list = document.getElementById(input.dataset.listSearch);
        if (!list) return;
        input.addEventListener('input', () => filterList(input, list));
    });

    document.querySelectorAll('[data-subject-filter]').forEach((input) => {
        const picker = input.closest('[data-subject-picker]');
        if (!picker) return;

        const options = [...picker.querySelectorAll('[data-subject-option]')];
        const emptyMessage = picker.querySelector('[data-subject-empty]');
        const selectedCount = picker.querySelector('[data-subject-count]');

        const updateSubjectOptions = () => {
            const query = input.value.trim().toLocaleLowerCase();
            let visibleCount = 0;
            options.forEach((option) => {
                const matches = option.textContent.toLocaleLowerCase().includes(query);
                option.hidden = !matches;
                if (matches) visibleCount += 1;
            });
            if (emptyMessage) emptyMessage.hidden = visibleCount !== 0;
        };

        const updateSelectedCount = () => {
            const count = picker.querySelectorAll('input[name="subject_ids[]"]:checked').length;
            if (selectedCount) selectedCount.textContent = `${count} selected`;
        };

        input.addEventListener('input', updateSubjectOptions);
        picker.querySelectorAll('input[name="subject_ids[]"]').forEach((checkbox) => {
            checkbox.addEventListener('change', updateSelectedCount);
        });
        updateSelectedCount();
    });

    document.querySelectorAll('[data-global-search]').forEach((input) => {
        input.addEventListener('input', () => {
            document.querySelectorAll('table[data-global-searchable]').forEach((table) => filterTable(input, table));
            document.querySelectorAll('[data-list-search]').forEach((listSearch) => {
                const list = document.getElementById(listSearch.dataset.listSearch);
                if (list) filterList(input, list);
            });
        });
    });

    document.querySelectorAll('form[data-loading-form]').forEach((form) => {
        let pending;

        form.addEventListener('submit', (event) => {
            if (event.defaultPrevented) return;
            if (pending) {
                event.preventDefault();
                return;
            }

            const button = event.submitter ?? form.querySelector('button[type="submit"]');
            if (!(button instanceof HTMLButtonElement)) return;

            pending = { button, children: [...button.childNodes] };
            button.setAttribute('aria-disabled', 'true');
            form.setAttribute('aria-busy', 'true');

            const spinner = document.createElement('span');
            spinner.className = 'spinner';
            spinner.setAttribute('aria-hidden', 'true');
            button.replaceChildren(spinner, document.createTextNode('Processing…'));
        });

        window.addEventListener('pageshow', () => {
            if (!pending) return;
            pending.button.replaceChildren(...pending.children);
            pending.button.removeAttribute('aria-disabled');
            form.removeAttribute('aria-busy');
            pending = null;
        });
    });

    document.querySelectorAll('[data-auto-dismiss]').forEach((notice) => {
        window.setTimeout(() => {
            notice.classList.add('is-dismissing');
            window.setTimeout(() => notice.remove(), 240);
        }, 5000);
    });

    document.querySelectorAll('[data-current-year]').forEach((element) => {
        element.textContent = String(new Date().getFullYear());
    });
})();
