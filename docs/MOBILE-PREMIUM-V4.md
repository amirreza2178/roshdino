# ROSHDINO Mobile Premium

V4 patchهای `max-width` بود. V5 پایه را mobile-first کرد.

فایل جایگزین:

`resources/css/app.css`

سپس:

```powershell
php artisan optimize:clear
npm.cmd run build
php artisan test
```

ترکیب هدف:

- کمتر از 360px: CTAها و حوزه‌ها ستونی
- 360–767: CTA دو ستونه؛ Process و Growth System ستونی
- 768–1023: Process دو ستون
- 1024+: ترکیب دسکتاپ

تأیید بصری هنوز با Device Mode است؛ بدون آن ادعا نکنید موبایل کامل است.
