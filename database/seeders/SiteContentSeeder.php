<?php

namespace Database\Seeders;

use App\Enums\CategoryType;
use App\Enums\PublishStatus;
use App\Models\Category;
use App\Models\HomePage;
use App\Models\Page;
use App\Models\Service;
use App\Models\Work;
use Illuminate\Database\Seeder;

class SiteContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCatalog();
        $this->seedPages();
        $this->seedEmptyServiceProcesses();
        $this->plainServiceVoice();
        $this->deepenServiceVoice();
        $this->softenAboutPage();
        $this->plainWorkVoice();
        $this->studioShopVoice();
        $this->brandCaseStudyVoice();
        $this->separateWebProblemFromProcess();
        $this->seedHomePage();
    }

    private function seedHomePage(): void
    {
        if (HomePage::query()->exists()) {
            return;
        }

        HomePage::query()->create([
            'story' => HomePage::defaults(),
        ]);
    }

    private function seedCatalog(): void
    {
        if (Category::query()->exists() || Service::query()->exists() || Work::query()->exists()) {
            return;
        }

        $web = Category::query()->create([
            'type' => CategoryType::Service,
            'name' => 'وب',
            'slug' => 'web',
        ]);
        $software = Category::query()->create([
            'type' => CategoryType::Service,
            'name' => 'نرم‌افزار',
            'slug' => 'software',
        ]);
        $growth = Category::query()->create([
            'type' => CategoryType::Service,
            'name' => 'رشد',
            'slug' => 'growth',
        ]);
        $systems = Category::query()->create([
            'type' => CategoryType::Work,
            'name' => 'سیستم اختصاصی',
            'slug' => 'custom-system',
        ]);

        $services = [
            [
                'category_id' => $web->id,
                'title' => 'طراحی و توسعه وب',
                'slug' => 'web',
                'description' => 'سایتی که فقط قشنگ نباشد. زود باز شود، حرف کسب‌وکار را روشن بگوید، و آدم بداند قدم بعدی چیست.',
                'benefits' => ['ظاهر و کاربرد روشن', 'ساخت مخصوص همین کار', 'سرعت خوب و پیدا شدن در جستجو'],
                'cta_url' => '/?intent=request&service=web#contact',
                'interest' => 'web',
                'position' => 1,
            ],
            [
                'category_id' => $software->id,
                'title' => 'نرم‌افزار اختصاصی',
                'slug' => 'software',
                'description' => 'کارهایی که الان در دفتر و پیام و فایل پخش شده، در یک سیستم جمع می‌شود تا هر روز بشود با آن کار کرد.',
                'benefits' => ['یک جا برای کار روزانه', 'کم شدن کار دستی', 'وضعیت کار، در یک نگاه'],
                'cta_url' => '/?intent=request&service=software#contact',
                'interest' => 'software',
                'position' => 2,
            ],
            [
                'category_id' => $growth->id,
                'title' => 'راهکارهای رشد دیجیتال',
                'slug' => 'growth',
                'description' => 'مخاطب می‌آید، ولی خرید یا تماس نمی‌گیرد. پیدا می‌کنیم گیر کجاست و همان مسیر را ساده‌تر می‌کنیم.',
                'benefits' => ['حرفی که آدم بفهمد', 'مسیر کوتاه تا تماس', 'فهمیدن اینکه کجا رها می‌کند'],
                'cta_url' => '/?intent=request&service=growth#contact',
                'interest' => 'growth',
                'position' => 3,
            ],
        ];

        foreach ($services as $service) {
            Service::query()->create([
                ...$service,
                'process' => [],
                'cta_label' => 'حرف بزنیم',
                'status' => PublishStatus::Published,
            ]);
        }

        Work::query()->create([
            'category_id' => $systems->id,
            'title' => 'Personal System Faraja',
            'summary' => 'سیستم شرکت برای کار روزانهٔ پرسنل: اطلاعات، حضور، مرخصی و مساعده.',
            'slug' => 'personal-system-faraja',
            'problem' => 'حضور، مرخصی و مساعده در فایل و پیام پخش بود. برای یک جواب باید چند جا را می‌گشتی.',
            'solution' => 'این سیستم را در شرکت ساختم تا اطلاعات پرسنل، حضور، درخواست مرخصی و مساعده در یک مسیر دیده شود.',
            'technologies' => ['Laravel', 'PHP', 'MariaDB', 'Blade'],
            'result' => null,
            'status' => PublishStatus::Published,
            'position' => 1,
        ]);

        $shop = Category::query()->create([
            'type' => CategoryType::Work,
            'name' => 'فروشگاه',
            'slug' => 'shop',
        ]);

        Work::query()->create([
            'category_id' => $shop->id,
            'title' => 'فروشگاه لاراولی',
            'summary' => 'فروشگاهی با مسیر کامل خرید؛ از دیدن کالا تا ثبت سفارش و مدیریت موجودی، برای کسب‌وکاری که ویترین ساده دیگر جواب کارش را نمی‌دهد.',
            'slug' => 'laravel-shop',
            'problem' => 'کالا، سفارش و موجودی اگر جدا بمانند، فروش یک کسب‌وکار بزرگ در چند جا پخش می‌شود. مشتری مسیر خرید را گم می‌کند و برای یک وضعیت باید چند جا را گشت.',
            'solution' => 'ویترین، صفحهٔ کالا، سبد، ثبت سفارش، موجودی و پنل مدیریت در یک فروشگاه کنار هم نشسته‌اند. مسیر طوری چیده شده که نیاز جدی‌تر کسب‌وکار — کالاهای بیشتر، قانون موجودی، یا مرحلهٔ تازه‌ای در خرید — به همین ساخت اضافه شود.',
            'technologies' => ['Laravel', 'PHP', 'MariaDB', 'Blade'],
            'result' => null,
            'status' => PublishStatus::Published,
            'position' => 2,
        ]);
    }

    private function seedEmptyServiceProcesses(): void
    {
        $processes = [
            'web' => [
                ['title' => 'شنیدن', 'body' => 'کسب‌وکار، مخاطب و جایی که آدم صفحه را رها می‌کند را درمی‌آوریم.'],
                ['title' => 'چیدن', 'body' => 'صفحه، پیام و دکمهٔ بعدی را قبل از ظاهر مشخص می‌کنیم.'],
                ['title' => 'ساختن', 'body' => 'سایت را سریع، قابل استفاده و آمادهٔ گسترش می‌سازیم.'],
                ['title' => 'سپردن', 'body' => 'مرز کار، تحویل و ادامهٔ احتمالی را روشن می‌گوییم تا چیزی در هوا نماند.'],
            ],
            'software' => [
                ['title' => 'برای چه کسی', 'body' => 'سفارش، موجودی، مشتری یا مرخصی و مساعده در پیام، اکسل و دفتر پخش است.'],
                ['title' => 'درد آشنا', 'body' => 'برای یک وضعیت باید چند جا را بگردی، و اگر یک نفر نباشد کار می‌خوابد.'],
                ['title' => 'انتخاب', 'body' => 'فقط کار تکراری روزانه را سیستم می‌کنیم، نه هر خواستهٔ پراکنده‌ای.'],
                ['title' => 'ساختن', 'body' => 'همان مسیر در یک نرم‌افزار می‌نشیند تا آخرین وضعیت در یک جا دیده شود.'],
                ['title' => 'سپردن', 'body' => 'تیم بدون توضیح اضافه کار روز را انجام می‌دهد، حتی اگر یک نفر غایب باشد.'],
            ],
            'growth' => [
                ['title' => 'برای چه کسی', 'body' => 'وقتی بازدید یا دنبال‌کننده داری، ولی درخواست و تماس به آن اندازه نیست.'],
                ['title' => 'پیدا کردن گیر', 'body' => 'صفحه را مثل مشتری رد می‌کنیم و می‌بینیم کجا متوقف می‌شود.'],
                ['title' => 'روشن کردن حرف', 'body' => 'یک پیام اصلی می‌ماند، نه فهرست شلوغ خدمت و تکنولوژی.'],
                ['title' => 'راحت کردن تصمیم', 'body' => 'فرم کوتاه، دکمهٔ روشن، و راه تماس دم دست. بدون اصرار الکی.'],
                ['title' => 'ادامه', 'body' => 'بهتر کردن بعدی فقط بعد از استفادهٔ واقعی معنی دارد، نه با عدد ساختگی.'],
            ],
        ];

        foreach ($processes as $slug => $process) {
            $service = Service::query()->where('slug', $slug)->first();

            if ($service === null || $service->process !== []) {
                continue;
            }

            $service->process = $process;
            $service->save();
        }

        $software = Service::query()->where('slug', 'software')->first();

        if (($software?->process[0]['body'] ?? null) === 'وقتی سفارش، مشتری یا کار شرکت در چند ابزار و فایل جدا مانده است.') {
            $software->process = $processes['software'];
            $software->save();
        }
    }

    private function plainServiceVoice(): void
    {
        $voices = [
            'سایت‌هایی سریع، حرفه‌ای و قابل توسعه که فقط زیبا نیستند؛ برای کسب‌وکار کار می‌کنند.' => [
                'slug' => 'web',
                'description' => 'سایتی که فقط قشنگ نباشد. زود باز شود، حرف کسب‌وکار را روشن بگوید، و آدم بداند قدم بعدی چیست.',
                'benefits' => ['ظاهر و کاربرد روشن', 'ساخت مخصوص همین کار', 'سرعت خوب و پیدا شدن در جستجو'],
            ],
            'فرآیندهای پیچیده کسب‌وکارت را به یک سیستم ساده، قابل کنترل و قابل توسعه تبدیل می‌کنیم.' => [
                'slug' => 'software',
                'description' => 'کارهایی که الان در دفتر و پیام و فایل پخش شده، در یک سیستم جمع می‌شود تا هر روز بشود با آن کار کرد.',
                'benefits' => ['یک جا برای کار روزانه', 'کم شدن کار دستی', 'وضعیت کار، در یک نگاه'],
            ],
            'از ساختار سایت و محتوا تا تجربه کاربر و مسیر تبدیل؛ نقاط اصطکاک را پیدا می‌کنیم و بهبود می‌دهیم.' => [
                'slug' => 'growth',
                'description' => 'مخاطب می‌آید، ولی خرید یا تماس نمی‌گیرد. پیدا می‌کنیم گیر کجاست و همان مسیر را ساده‌تر می‌کنیم.',
                'benefits' => ['حرفی که آدم بفهمد', 'مسیر کوتاه تا تماس', 'فهمیدن اینکه کجا رها می‌کند'],
            ],
        ];

        foreach ($voices as $previous => $voice) {
            $service = Service::query()->where('slug', $voice['slug'])->where('description', $previous)->first();

            if ($service === null) {
                continue;
            }

            $service->description = $voice['description'];
            $service->benefits = $voice['benefits'];
            $service->cta_label = 'حرف بزنیم';
            $service->process = [];
            $service->save();
        }

        $this->seedEmptyServiceProcesses();
    }

    private function deepenServiceVoice(): void
    {
        $voices = [
            'web' => [
                'from' => 'سایتی که فقط قشنگ نباشد. زود باز شود، حرف کسب‌وکار را روشن بگوید، و آدم بداند قدم بعدی چیست.',
                'description' => 'سایتی که مشتری را جلو ببرد: حرف کسب‌وکار روشن، مسیر کوتاه تا تماس، و ساختاری که بعداً بشود گسترش داد.',
                'benefits' => ['مسیر روشن تا تماس یا خرید', 'ساختی که بعد از تحویل هم بشود با آن کار کرد', 'پیدا شدن در جستجو، بدون شلوغی الکی'],
            ],
            'software' => [
                'from' => 'کارهایی که الان در دفتر و پیام و فایل پخش شده، در یک سیستم جمع می‌شود تا هر روز بشود با آن کار کرد.',
                'description' => 'کار روزانه را از دفتر و پیام و فایل درمی‌آوریم و در سیستمی می‌گذاریم که تیم واقعاً با آن کار کند.',
                'benefits' => ['یک مسیر برای کار تکراری', 'درخواست، وضعیت و سابقه در یک جا', 'کم شدن رفت‌وبرگشت دستی'],
            ],
            'growth' => [
                'from' => 'مخاطب می‌آید، ولی خرید یا تماس نمی‌گیرد. پیدا می‌کنیم گیر کجاست و همان مسیر را ساده‌تر می‌کنیم.',
                'description' => 'مخاطب هست، اقدام نیست. پیام، اعتماد و مسیر صفحه را طوری می‌چینیم که تصمیم ساده‌تر شود.',
                'benefits' => ['حرفی که مخاطب همان اول بفهمد', 'مسیر کوتاه از توجه تا تماس', 'دیدن اینکه آدم کجا رها می‌کند'],
            ],
        ];

        foreach ($voices as $slug => $voice) {
            $service = Service::query()->where('slug', $slug)->where('description', $voice['from'])->first();

            if ($service === null) {
                continue;
            }

            $service->description = $voice['description'];
            $service->benefits = $voice['benefits'];
            $service->process = [];
            $service->save();
        }

        $this->seedEmptyServiceProcesses();
    }

    private function plainWorkVoice(): void
    {
        $company = Work::query()->where('slug', 'personal-system-faraja')->first();

        if ($company !== null && ! str_contains((string) $company->solution, 'مساعده')) {
            $company->summary = 'سیستم شرکت برای کار روزانهٔ پرسنل: اطلاعات، حضور، مرخصی و مساعده.';
            $company->problem = 'حضور، مرخصی و مساعده در فایل و پیام پخش بود. برای یک جواب باید چند جا را می‌گشتی.';
            $company->solution = 'این سیستم را در شرکت ساختم تا اطلاعات پرسنل، حضور، درخواست مرخصی و مساعده در یک مسیر دیده شود.';
            $company->save();
        }

        if (Work::query()->where('slug', 'laravel-shop')->exists()) {
            return;
        }

        $shop = Category::query()->firstOrCreate(
            ['type' => CategoryType::Work, 'slug' => 'shop'],
            ['name' => 'فروشگاه'],
        );

        Work::query()->create([
            'category_id' => $shop->id,
            'title' => 'فروشگاه لاراولی',
            'summary' => 'فروشگاهی با مسیر کامل خرید؛ از دیدن کالا تا ثبت سفارش و مدیریت موجودی، برای کسب‌وکاری که ویترین ساده دیگر جواب کارش را نمی‌دهد.',
            'slug' => 'laravel-shop',
            'problem' => 'کالا، سفارش و موجودی اگر جدا بمانند، فروش یک کسب‌وکار بزرگ در چند جا پخش می‌شود. مشتری مسیر خرید را گم می‌کند و برای یک وضعیت باید چند جا را گشت.',
            'solution' => 'ویترین، صفحهٔ کالا، سبد، ثبت سفارش، موجودی و پنل مدیریت در یک فروشگاه کنار هم نشسته‌اند. مسیر طوری چیده شده که نیاز جدی‌تر کسب‌وکار — کالاهای بیشتر، قانون موجودی، یا مرحلهٔ تازه‌ای در خرید — به همین ساخت اضافه شود.',
            'technologies' => ['Laravel', 'PHP', 'MariaDB', 'Blade'],
            'result' => null,
            'status' => PublishStatus::Published,
            'position' => 2,
        ]);
    }

    private function brandCaseStudyVoice(): void
    {
        $farajaLearning = 'یکپارچه‌سازی وقتی ارزش دارد که تیم بدون آموزش طولانی کار روز را تمام کند؛ هر صفحه یک تصمیم اصلی دارد.';
        $faraja = Work::query()->where('slug', 'personal-system-faraja')->first();

        if ($faraja !== null && str_contains((string) $faraja->solution, 'درخواست مرخصی و مساعده') && in_array($faraja->learning, [null, $farajaLearning], true)) {
            $faraja->summary = 'سیستم داخلی برای کار روزانهٔ پرسنل: اطلاعات، حضور، درخواست مرخصی و مساعده — یک مسیر به‌جای فایل و پیام.';
            $faraja->problem = 'حضور، درخواست مرخصی و مساعده بین فایل، پیام و دفتر پخش بود. برای یک وضعیت باید چند جا را می‌گشتی و پاسخ دیر می‌رسید.';
            $faraja->solution = 'همان کارهای تکراری در یک سیستم جمع شد: پروفایل پرسنل، ثبت حضور، درخواست مرخصی و مساعده، با گردش روشن برای نقش‌های مختلف — نه یک صفحهٔ تزئینی.';
            $faraja->learning = $farajaLearning;
            $faraja->result = null;
            $faraja->save();
        }

        $shopLearning = 'قبل از ظاهر، مسیر خرید را با سناریوی واقعی قدم‌به‌قدم چک کردیم تا بین دیدن کالا و ثبت سفارش اصطکاک کم بماند.';
        $shop = Work::query()->where('slug', 'laravel-shop')->first();

        if ($shop !== null && str_contains((string) $shop->solution, 'پنل مدیریت') && in_array($shop->learning, [null, $shopLearning], true)) {
            $shop->summary = 'فروشگاه با مسیر خرید کامل — از دیدن کالا تا سبد، ثبت سفارش و موجودی — برای کسب‌وکاری که ویترین ساده دیگر جواب کارش را نمی‌دهد.';
            $shop->problem = 'کالا، سفارش و موجودی وقتی جدا بمانند، فروش یک کسب‌وکار بزرگ در چند جا پخش می‌شود. مشتری بین ویترین و خرید گم می‌شود و برای یک وضعیت باید چند جا را گشت.';
            $shop->solution = 'ویترین، صفحهٔ کالا، سبد، ثبت سفارش، موجودی و پنل مدیریت در یک فروشگاه کنار هم چیده شدند. ساختار طوری است که قانون موجودی، کالای بیشتر یا مرحلهٔ تازه در خرید — بدون از نو شروع کردن — به همین مسیر اضافه شود.';
            $shop->learning = $shopLearning;
            $shop->result = null;
            $shop->save();
        }
    }

    private function separateWebProblemFromProcess(): void
    {
        $service = Service::query()->where('slug', 'web')->first();

        if ($service === null) {
            return;
        }

        $first = $service->process[0] ?? null;

        if (($first['title'] ?? null) !== 'برای چه کسی') {
            return;
        }

        if (($first['body'] ?? null) !== 'وقتی سایت نداری، یا سایت هست ولی بازدیدکننده نمی‌داند قدم بعدی چیست.') {
            return;
        }

        $service->process = array_values(array_slice($service->process, 1));
        $service->save();
    }

    private function studioShopVoice(): void
    {
        $shop = Work::query()->where('slug', 'laravel-shop')->first();

        if ($shop === null || $shop->summary !== 'فروشگاهی که تنها ساختم؛ از دیدن کالا تا ثبت سفارش، همراه با آموزش همان مسیر.') {
            return;
        }

        $shop->summary = 'فروشگاهی با مسیر کامل خرید؛ از دیدن کالا تا ثبت سفارش و مدیریت موجودی، برای کسب‌وکاری که ویترین ساده دیگر جواب کارش را نمی‌دهد.';
        $shop->problem = 'کالا، سفارش و موجودی اگر جدا بمانند، فروش یک کسب‌وکار بزرگ در چند جا پخش می‌شود. مشتری مسیر خرید را گم می‌کند و برای یک وضعیت باید چند جا را گشت.';
        $shop->solution = 'ویترین، صفحهٔ کالا، سبد، ثبت سفارش، موجودی و پنل مدیریت در یک فروشگاه کنار هم نشسته‌اند. مسیر طوری چیده شده که نیاز جدی‌تر کسب‌وکار — کالاهای بیشتر، قانون موجودی، یا مرحلهٔ تازه‌ای در خرید — به همین ساخت اضافه شود.';
        $shop->save();
    }

    private function softenAboutPage(): void
    {
        $page = Page::query()->where('slug', 'about')->first();

        if ($page === null || ! str_contains($page->body, 'اصطکاک تصمیم')) {
            return;
        }

        $page->body = <<<'TEXT'
رشدینو جای ویترین نیست. کمک می‌کنیم کسب‌وکار بفهمد گیر کارش کجاست و همان را درست کند.

سه کار می‌کنیم، نه بیشتر: سایت، نرم‌افزار مخصوص خود کسب‌وکار، و رشد دیده شدن و تماس گرفتن.

اول گوش می‌دهیم. بعد مسیر را ساده می‌چینیم. بعد می‌سازیم، طوری که بعد از تحویل هم بشود با آن کار کرد. اگر ادامه لازم باشد، جدا و روشن می‌گوییم.

نمونه‌کار فقط کاری است که واقعاً ساخته شده. عدد و نتیجه را وقتی می‌نویسیم که ثبت شده باشد.
TEXT;
        $page->save();
    }

    private function seedPages(): void
    {
        $this->publishPage(
            'about',
            'درباره رشدینو',
            <<<'TEXT'
رشدینو جای ویترین نیست. کمک می‌کنیم کسب‌وکار بفهمد گیر کارش کجاست و همان را درست کند.

سه کار می‌کنیم، نه بیشتر: سایت، نرم‌افزار مخصوص خود کسب‌وکار، و رشد دیده شدن و تماس گرفتن.

اول گوش می‌دهیم. بعد مسیر را ساده می‌چینیم. بعد می‌سازیم، طوری که بعد از تحویل هم بشود با آن کار کرد. اگر ادامه لازم باشد، جدا و روشن می‌گوییم.

نمونه‌کار فقط کاری است که واقعاً ساخته شده. عدد و نتیجه را وقتی می‌نویسیم که ثبت شده باشد.
TEXT,
            [
                'meta_title' => 'درباره رشدینو | روش کار',
                'meta_description' => 'روش کار رشدینو: کشف مسئله، طراحی مسیر، ساخت قابل استفاده، و بهبود وقتی استفاده واقعی باشد.',
                'og_title' => 'درباره رشدینو',
                'og_description' => 'وب، نرم‌افزار اختصاصی و رشد دیجیتال با روشی که قبل از ساخت، مسئله و مرز کار را روشن می‌کند.',
            ],
        );

        $this->publishPage(
            'contact',
            'تماس با رشدینو',
            'لازم نیست از قبل دقیقاً بدانی چه چیزی باید ساخته شود. مسئله را بفرست، فقط راه تماس را بگذار، یا زمان جلسه را با ساعت تهران رزرو کن.',
            [
                'meta_title' => 'تماس با رشدینو | درخواست یا جلسه',
                'meta_description' => 'درخواست، راه تماس یا رزرو جلسه با رشدینو. بعد از ثبت، با همین راه ارتباطی برمی‌گردیم.',
                'og_title' => 'تماس با رشدینو',
                'og_description' => 'مسئله را بفرست، راه تماس را بگذار، یا جلسه را رزرو کن.',
            ],
        );
    }

    /**
     * @param  array{meta_title: string, meta_description: string, og_title: string, og_description: string}  $seo
     */
    private function publishPage(string $slug, string $title, string $body, array $seo): void
    {
        if (Page::query()->where('slug', $slug)->exists()) {
            return;
        }

        $page = Page::query()->create([
            'title' => $title,
            'slug' => $slug,
            'status' => PublishStatus::Published,
            'body' => trim($body),
        ]);

        $page->saveSeo([
            ...$seo,
            'indexable' => true,
        ]);
    }
}
