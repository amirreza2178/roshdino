# بازگردانی فرانت عمومی ROSHDINO

سند مرجع برای برگرداندن ظاهر و رفتار سایت عمومی (نه پنل staff) با کمترین کار.  
تاریخ وضعیت: ۱۶ اکتبر ۲۰۲۶ — شامل دکمه «تماس با ما» (`tel:`) در هدر دسکتاپ کنار «شروع پروژه».

---

## ۱. چه چیزی را کپی/نگه دارید

### استایل و اسکریپت (منبع اصلی)

| مسیر | نقش |
|------|-----|
| `resources/css/app.css` | تمام استایل سایت عمومی، توکن رنگ، هدر، سکشن‌ها، فرم سرنخ |
| `resources/js/app.js` | منوی موبایل، اسکرول هدر، مخفی‌کردن CTA پایین موبایل |
| `vite.config.js` | ورودی Vite: `app.css` + `app.js` |
| `package.json` | Tailwind 4، Vite 8، فونت Vazirmatn |

### لایه و قطعات مشترک

| مسیر | نقش |
|------|-----|
| `resources/views/layouts/app.blade.php` | قالب RTL، SEO پایه، `@vite`, navbar، footer، CTA موبایل |
| `resources/views/components/navbar.blade.php` | هدر: لینک‌ها، «تماس با ما» (دسکتاپ)، «شروع پروژه» |
| `resources/views/components/footer.blade.php` | فوتر و شبکه‌های اجتماعی |
| `resources/views/partials/lead-form.blade.php` | فرم سرنخ + تماس مستقیم (تلگرام / تلفن) |

### صفحات عمومی

| مسیر | نقش |
|------|-----|
| `resources/views/home.blade.php` | صفحهٔ اصلی (hero، خدمات، نمونه‌کار، FAQ، contact) |
| `resources/views/pages/show.blade.php` | صفحات CMS (about، contact و …) |
| `resources/views/services/show.blade.php` | جزئیات هر خدمت |
| `resources/views/works/show.blade.php` | جزئیات نمونه‌کار |

### استاتیک

| مسیر | نقش |
|------|-----|
| `public/favicon.svg` | آیکون |
| `public/build/*` | خروجی build (بعد از `npm run build`؛ در git ممکن است نباشد) |

### وابستگی حداقلی بک‌اند (بدون این‌ها Blade خطا می‌دهد)

- متغیر `$site` از `SiteSetting::publicProfile()` در `AppServiceProvider` (برند، `phone`, `telegram`, SEO).
- شماره تماس هدر: `$site['phone']` — از تنظیمات سایت یا `config/roshdino.php` / env `ROSHDINO_CONTACT_PHONE`.
- روت‌هایی که در viewها هست: `home`, `pages.show`, `leads.store` و دادهٔ `$page` / `$service` / `$work` / `$home` بسته به کنترلر.

پنل staff (`resources/views/layouts/staff.blade.php`, `resources/views/site/**`, `resources/views/leads/**`) جزو این سند نیست؛ برای بازگردانی سایت بازدیدکننده لازم نیست.

---

## ۲. بازگردانی سریع از Git

اگر فقط فرانت خراب شده و بقیهٔ repo سالم است:

```powershell
git checkout HEAD -- resources/css/app.css resources/js/app.js vite.config.js package.json package-lock.json
git checkout HEAD -- resources/views/layouts/app.blade.php
git checkout HEAD -- resources/views/components/navbar.blade.php resources/views/components/footer.blade.php
git checkout HEAD -- resources/views/partials/lead-form.blade.php
git checkout HEAD -- resources/views/home.blade.php resources/views/pages/show.blade.php
git checkout HEAD -- resources/views/services/show.blade.php resources/views/works/show.blade.php
git checkout HEAD -- public/favicon.svg
npm install
npm run build
php artisan test --filter=HomePageTest
```

برای بازگردانی از یک commit مشخص، به‌جای `HEAD` همان SHA را بگذارید.

---

## ۳. آرشیو دستی (بدون Git)

یک پوشه مثلاً `_frontend-snapshot/` بسازید و فقط فایل‌های جدول بخش ۱ را کپی کنید.  
بازگردانی: فایل‌ها را روی مسیر اصلی برگردانید → `npm install` → `npm run build`.

---

## ۴. رفتار UI مهم (برای مقایسه بعد از restore)

- **RTL** در `layouts/app.blade.php`: `lang="fa"` `dir="rtl"`.
- **هدر دسکتاپ** (`min-width: 1024px`): `.nav__actions` با دو دکمه در صورت وجود شماره — `تماس با ما` → `tel:…`، `شروع پروژه` → `home?intent=request#contact`.
- **موبایل**: منوی همبرگری + CTA چسبان پایین `.mobile-cta`؛ دکمه تماس در هدر دسکتاپ است نه منوی موبایل.
- **CSS هدر**: کلاس‌های `.nav__actions`, `.nav__call` در `app.css` کنار `.nav__desktop`.
- **تست smoke**: `tests/Feature/HomePageTest.php` — سکشن‌ها، ناوبری، `tel:` در فرم تماس.

---

## ۵. توسعهٔ محلی

```powershell
npm run dev
# در کنارش سرور Laravel (مثلاً php artisan serve یا Herd)
```

اگر استایل/JS عوض نشد: `npm run build` و hard refresh مرورگر.

---

## ۶. عمداً خارج از scope

- مایگریشن، مدل، seed محتوا
- پنل مدیریت محتوا و leads
- ایمیل و نوتیفیکیشن سرنخ

برای «همان صفحهٔ اول با همان متن» علاوه بر فرانت، دیتابیس/seed محتوا (`SiteContentSeeder` و …) جداگانه لازم است.
