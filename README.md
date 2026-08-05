# Bachat

Personal Expense Tracker PWA built with Laravel 10 for local use at `http://localhost/tracker`.

## What’s included

- MPIN-based registration and login
- Opening balance onboarding
- Dashboard with quick income and expense entry
- Transaction history with search, filters, edit, delete, and pagination
- Monthly summary screen
- Settings with MPIN change, theme, auto-lock, export, and logout
- PWA manifest, service worker, offline page, and installable icon set
- Glassmorphism UI with an iPhone-style translucent gradient look

## Local setup

1. Configure MySQL in `.env`
2. Run `php artisan migrate --seed`
3. Serve the app from the `tracker` folder so it opens at `http://localhost/tracker`

## Notes

- The app uses session-based authentication with a single personal user flow.
- MPINs are hashed before storage.
- The PWA caches only the app shell and static assets, not transaction data.
