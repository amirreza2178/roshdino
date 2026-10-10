# ROSHDINO — وضعیت کاری فعلی

فاز: Homepage → Mobile-first composition → Accessibility → Performance → SEO → Desktop

## تصمیم‌های ثابت

- Public website first؛ CRM/SaaS/Blog فعلاً نه
- SQLite تا وقتی Lead/Client/Project واقعی نشده
- سه خدمت: Web / Custom Software / Growth
- بدون عدد، تستیمونیال یا کلاینت ساختگی
- JSON-LD فقط با `json_encode`؛ هرگز `@context` خام در Blade
- Process در موبایل: `01 DISCOVER` + توضیح؛ در تبلت ۲ ستون؛ از 1024 چهار ستون
- Growth System در موبایل ستونی است: مسئله / راهکار / رشد

## Mobile Premium V5

پایه CSS حالا mobile-first است، نه desktop + `max-width` patch.

تأیید بصری در Device Mode هنوز با کاربر است؛ ادعای «موبایل عالی است» بدون آن نمی‌شود.

بعد از تغییر CSS:

```powershell
php artisan optimize:clear
npm.cmd run build
php artisan test
```
