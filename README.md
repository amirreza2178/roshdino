# ROSHDINO Frontend V2

This pack replaces the public frontend foundation with a dark, mobile-first, conversion-oriented design.

## Replace
- resources/views/layouts/app.blade.php
- resources/views/components/navbar.blade.php
- resources/views/components/footer.blade.php
- resources/views/home.blade.php
- resources/css/app.css
- resources/js/app.js
- tests/Feature/HomePageTest.php
- routes/web.php (only if your current route is still the simple home route)

## Run
npm.cmd run build
php artisan optimize:clear
php artisan test

## Visual QA
Check at:
- 375x768
- 390x844
- 768x1024
- 1366x768
- 1440x900
- 1920x1080

## Important
The email CTA uses hello@roshdino.test as a development placeholder. Replace it before production.
