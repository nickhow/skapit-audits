# Remediation Plan (Skapit Audits)

## P0 — Security & data integrity
- [ ] Replace raw SQL string concatenation with bound parameters/query builder in high‑risk paths:
  - `appstarter/app/Controllers/AuditCrud.php`
  - `appstarter/app/Controllers/AccountCrud.php`
  - `appstarter/app/Models/AuditModel.php`
  - `appstarter/app/Models/AccountModel.php`
- [ ] Re‑enable CSRF protection and add CSRF tokens to all POST forms:
  - `appstarter/app/Config/Filters.php`
  - All `appstarter/app/Views/*.php` POST forms
- [ ] Harden authentication redirects to prevent open‑redirects and handle missing headers:
  - `appstarter/app/Controllers/SigninController.php`
- [ ] Ensure sensitive routes are protected by auth filters (validate `/users` vs `users/`):
  - `appstarter/app/Config/Routes.php`
  - `appstarter/app/Config/Filters.php`

## P1 — Signup flow correctness
- [ ] Normalize user email field usage (`user_email` vs `email`) across:
  - `appstarter/app/Controllers/SignupController.php`
  - `appstarter/app/Models/UserModel.php`
  - DB schema (if needed)
- [ ] Fix audit‑account signup form to include required fields (email) and proper defaults:
  - `appstarter/app/Controllers/AuditCrud.php`
  - `appstarter/app/Views/signup-account.php`

## P2 — Maintainability
- [ ] Reduce controller SQL: move queries to models/services where practical.
- [ ] Add basic tests for signup, login redirect, and CSRF‑protected forms.
- [ ] Document expected auth/role flows in README or a short docs page.
