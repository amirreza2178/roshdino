# ROSHDINO Mobile Premium V3

## هدف
این نسخه روی mobile-first composition در عرض‌های 320 تا 639 پیکسل تمرکز دارد.

## تغییرات
- کاهش فضای عمودی Hero در موبایل برای افزایش محتوای قابل مشاهده در اولین viewport.
- محدودسازی عرض H1 و تیترهای بخش‌ها به 100٪ کانتینر.
- `text-wrap: balance` برای تیترهای فارسی.
- `overflow-wrap: anywhere` برای جلوگیری از overflow واژه‌های بلند.
- تبدیل CTAهای Hero به grid دو ستونه در موبایل و تک‌ستونه زیر 360px.
- جلوگیری از کشیدگی دکمه‌ها و عناصر grid.
- کوچک‌سازی و کنترل بهتر آمار Hero.
- بازترکیب Growth System در موبایل به ساختار عمودی.
- کنترل overflow متن‌های انگلیسی داخل Hero Visual.
- کاهش radius و padding Hero Visual در موبایل.
- کنترل بهتر Service Row و Case Study در عرض‌های باریک.
- حداقل ارتفاع مناسب برای FAQ summary.
- safe-area برای CTA شناور موبایل.
- تست JSON-LD به‌صورت واقعی با `json_decode` اضافه شده تا خرابی Blade directive دوباره از دست تست فرار نکند.

## QA الزامی
1. 320×568
2. 360×800
3. 375×768
4. 390×844
5. 768×1024
6. 1440×900

## قانون
قبل از تغییر بعدی، ابتدا screenshot موبایل 375 و 390 بررسی شود. اگر overflow یا clipping وجود داشت، Desktop polish متوقف می‌شود تا Mobile اول اصلاح شود.
