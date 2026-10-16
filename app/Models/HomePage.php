<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class HomePage extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = ['story'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'story' => 'array',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function present(): array
    {
        $page = self::query()->first();

        return is_array($page?->story) ? $page->story : self::defaults();
    }

    /**
     * @param  array<string, mixed>  $home
     * @return array<string, mixed>
     */
    public static function withBlankSlots(array $home): array
    {
        foreach (['problem' => 'items', 'process' => 'steps', 'principle' => 'items', 'faq' => 'items'] as $section => $key) {
            $rows = $home[$section][$key] ?? [];

            if (count($rows) < 6) {
                $rows[] = $section === 'faq'
                    ? ['question' => '', 'answer' => '']
                    : ['title' => '', 'body' => ''];
            }

            $home[$section][$key] = $rows;
        }

        return $home;
    }

    public static function emphasized(string $title, string $accent): HtmlString
    {
        $accent = trim($accent);

        if ($accent !== '' && str_contains($title, $accent)) {
            return new HtmlString(e(Str::before($title, $accent)).'<em>'.e($accent).'</em>'.e(Str::after($title, $accent)));
        }

        return new HtmlString(e($title));
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaults(): array
    {
        return [
            'hero' => [
                'eyebrow' => 'استودیو رشد دیجیتال',
                'title' => 'کسب‌وکارت را دیجیتال‌تر، ساده‌تر و قابل‌رشدتر کن.',
                'accent' => 'دیجیتال‌تر، ساده‌تر و قابل‌رشدتر',
                'lead' => 'از طراحی و توسعه وب و نرم‌افزار اختصاصی تا راهکارهای رشد دیجیتال؛ مسئله کسب‌وکارت را بررسی می‌کنیم و راهکاری می‌سازیم که واقعاً قابل استفاده باشد.',
                'note' => 'بدون عددسازی. از مسئله واقعی کسب‌وکار شروع می‌کنیم.',
            ],
            'problem' => [
                'eyebrow' => 'مسئله',
                'title' => 'هر پروژه باید یک مسئله واقعی را حل کند.',
                'accent' => 'مسئله واقعی',
                'lead' => 'قبل از انتخاب تکنولوژی، باید بفهمیم چه چیزی در کسب‌وکار اصطکاک ایجاد کرده است.',
                'items' => [
                    [
                        'title' => 'سایت داری، اما مشتری نمی‌آورد؟',
                        'body' => 'مشکل همیشه ظاهر سایت نیست. مسیر کاربر، پیام، اعتماد و نقطه تبدیل را باید کنار هم دید.',
                    ],
                    [
                        'title' => 'فرآیندهایت دستی و پراکنده شده‌اند؟',
                        'body' => 'وقتی سفارش، مشتری و عملیات در چند ابزار جدا هستند، یک سیستم متناسب با فرآیند واقعی می‌تواند مسیر را ساده کند.',
                    ],
                    [
                        'title' => 'مخاطب داری، اما مسیر رشد نداری؟',
                        'body' => 'جذب مخاطب پایان مسیر نیست؛ تجربه، محتوا، اعتماد و تبدیل باید در یک سیستم کنار هم قرار بگیرند.',
                    ],
                ],
            ],
            'process' => [
                'eyebrow' => 'روش کار',
                'title' => 'از مسئله تا راهکار قابل استفاده.',
                'accent' => 'راهکار قابل استفاده.',
                'lead' => 'فرآیند شفاف است تا بدانی در هر مرحله چه اتفاقی می‌افتد.',
                'steps' => [
                    ['title' => 'شناخت', 'body' => 'شناخت کسب‌وکار، کاربر و مسئله اصلی.'],
                    ['title' => 'طراحی', 'body' => 'تعریف مسیر، تجربه و راهکار مناسب.'],
                    ['title' => 'ساخت', 'body' => 'پیاده‌سازی تمیز، تست‌پذیر و قابل توسعه.'],
                    ['title' => 'رشد', 'body' => 'اندازه‌گیری، یادگیری و بهبود مستمر.'],
                ],
            ],
            'principle' => [
                'eyebrow' => 'اصل کار',
                'title' => 'تکنولوژی برای ما هدف نیست؛ ابزار حل مسئله است.',
                'accent' => 'ابزار حل مسئله است.',
                'lead' => 'رشدینو از ترکیب توسعه نرم‌افزار، تجربه کاربر و نگاه رشد ساخته می‌شود. هدف، ساخت چیزی است که بعد از تحویل هم برای کسب‌وکار قابل استفاده و قابل توسعه بماند.',
                'items' => [
                    [
                        'title' => 'شفاف قبل از شروع',
                        'body' => 'مسئله، خروجی و مرز کار را قبل از ساخت روشن می‌کنیم تا انتظار دو طرف یکی باشد.',
                    ],
                    [
                        'title' => 'قابل استفاده برای تیم واقعی',
                        'body' => 'رابط، سرعت و ساختار طوری ساخته می‌شود که بعد از تحویل، کار روزمره با آن ممکن باشد.',
                    ],
                    [
                        'title' => 'مسیر بعد از تحویل',
                        'body' => 'پشتیبانی و توسعه بعدی جدا و روشن تعریف می‌شود؛ تحویل، پایان مبهم نیست.',
                    ],
                ],
            ],
            'faq' => [
                'eyebrow' => 'سؤال',
                'title' => 'قبل از شروع، سؤال‌هایت را جواب بدهیم.',
                'accent' => 'سؤال‌هایت را جواب بدهیم.',
                'items' => [
                    [
                        'question' => 'اگر دقیقاً ندانم چه چیزی لازم دارم چه؟',
                        'answer' => 'اشکالی ندارد. نقطه شروع، مسئله و هدف کسب‌وکار است؛ در گفت‌وگوی اولیه کمک می‌کنیم نیاز واقعی مشخص شود.',
                    ],
                    [
                        'question' => 'قیمت پروژه چگونه تعیین می‌شود؟',
                        'answer' => 'بعد از مشخص شدن دامنه کار، پیچیدگی، طراحی، توسعه و خروجی مورد انتظار، محدوده مناسب پروژه تعیین می‌شود.',
                    ],
                    [
                        'question' => 'آیا پروژه بعداً قابل توسعه است؟',
                        'answer' => 'بله. معماری و انتخاب تکنولوژی بر اساس نیاز واقعی و مسیر رشد پروژه انجام می‌شود تا توسعه آینده منطقی بماند.',
                    ],
                    [
                        'question' => 'بعد از تحویل پروژه چه اتفاقی می‌افتد؟',
                        'answer' => 'در صورت نیاز، پشتیبانی، نگهداری و توسعه‌های بعدی می‌تواند به‌صورت جداگانه ادامه پیدا کند.',
                    ],
                ],
            ],
        ];
    }
}
