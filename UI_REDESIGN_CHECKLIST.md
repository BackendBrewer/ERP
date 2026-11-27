# Academy ERP UI redesign checklist

Design direction: navy sidebar, warm off-white canvas, teal primary accent, and restrained amber highlights. The ERP uses a custom stylesheet served from `public/css`; Tailwind 4 is present in the Vite build but is not used by the ERP views.

Initial discovery found 12 existing Blade view files in `resources/views` (the initial estimate was 11). Subject Groups and Admission Inquiries views were added in later feature work; all 14 application views are now listed and complete below, along with five shared components.

## Design system

- [x] `public/css/erp-theme.css` — shared responsive design system, dark theme, and print rules
- [x] `public/js/erp-app.js` — shared navigation, theme, search, and form interactions

## Reusable components

- [x] `resources/views/components/icon.blade.php`
- [x] `resources/views/components/page-header.blade.php`
- [x] `resources/views/components/card.blade.php`
- [x] `resources/views/components/empty-state.blade.php`
- [x] `resources/views/components/status-badge.blade.php`

## Views

- [x] `resources/views/layouts/app.blade.php` — master authenticated and guest layouts
- [x] `resources/views/partials/status.blade.php` — flash status message
- [x] `resources/views/partials/pagination.blade.php` — pagination controls
- [x] `resources/views/auth/login.blade.php` — staff login form
- [x] `resources/views/institute/dashboard.blade.php` — institute overview and setup shortcuts
- [x] `resources/views/institute/academic-setup/index.blade.php` — class and section lists and forms
- [x] `resources/views/institute/subjects/index.blade.php` — subject list and form
- [x] `resources/views/institute/subject-groups/index.blade.php` — subject group directory and creation form
- [x] `resources/views/institute/admission-inquiries/index.blade.php` — admission inquiry directory and form
- [x] `resources/views/super-admin/dashboard.blade.php` — platform overview and academy shortcuts
- [x] `resources/views/super-admin/academies/index.blade.php` — academy directory
- [x] `resources/views/super-admin/academies/create.blade.php` — academy creation form
- [x] `resources/views/super-admin/institute-admins/create.blade.php` — Institute Admin creation form
- [x] `resources/views/welcome.blade.php` — public landing page

