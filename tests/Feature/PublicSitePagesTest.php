<?php

namespace Tests\Feature;

use App\Enums\PublishStatus;
use App\Models\Page;
use App\Models\Service;
use Database\Seeders\SiteContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSitePagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_and_contact_pages_are_public_and_lead_to_a_real_next_step(): void
    {
        $this->seed(SiteContentSeeder::class);

        $about = $this->get(route('pages.show', 'about'))->assertOk();

        $about
            ->assertSee('<h1>درباره رشدینو</h1>', false)
            ->assertSee('اول گوش می‌دهیم')
            ->assertSee('موبایل جزء کار است')
            ->assertSee('برای جستجو قابل فهم است')
            ->assertSee('طراحی و توسعه وب')
            ->assertSee(route('services.show', 'web'), false)
            ->assertSee(route('pages.show', ['slug' => 'contact', 'intent' => 'request']), false)
            ->assertSee('"@type": "AboutPage"', false)
            ->assertDontSee('مشتریان ما')
            ->assertDontSee('٪');

        $this->assertSame(1, substr_count($about->getContent(), '<h1'));

        $contact = $this->get(route('pages.show', ['slug' => 'contact', 'intent' => 'contact']))->assertOk();

        $contact
            ->assertSee('<h1>تماس با رشدینو</h1>', false)
            ->assertSee('for="lead-name"', false)
            ->assertSee('id="lead-name"', false)
            ->assertSee('id="lead-contact"', false)
            ->assertSee('name="contact"', false)
            ->assertSee(route('leads.store'), false)
            ->assertSee('ثبت راه تماس')
            ->assertDontSee('name="problem"', false)
            ->assertSee('"@type": "ContactPage"', false);

        $this->get(route('home'))
            ->assertSee(route('pages.show', 'about'), false)
            ->assertSee(route('pages.show', 'contact'), false);

        $this->get(route('services.show', 'web'))
            ->assertOk()
            ->assertSee('<h1>طراحی و توسعه وب</h1>', false)
            ->assertSee('id="service-problem"', false)
            ->assertSee('از صفحهٔ اول تا', false)
            ->assertSee('اگر سایت داری و کسی اقدام نمی‌کند', false)
            ->assertSee('حرف بزنیم')
            ->assertSee('سایت را سریع، قابل استفاده و آمادهٔ گسترش می‌سازیم.')
            ->assertSee('اگر کار با معرفی و تماس جمع می‌شود')
            ->assertSee(route('works.show', 'laravel-shop'), false)
            ->assertSee('ویترین ساده دیگر جواب کارش را نمی‌دهد')
            ->assertDontSee('برای چه کسی')
            ->assertDontSee('تنها ساختم')
            ->assertDontSee('HOW IT MOVES', false)
            ->assertDontSee('SEO', false)
            ->assertSee('"@type": "Service"', false)
            ->assertSee('"@type": "FAQPage"', false)
            ->assertDontSee('٪');

        $this->get(route('services.show', 'software'))
            ->assertOk()
            ->assertSee('id="service-problem"', false)
            ->assertSee('کار روزانه بین چند جا پخش است')
            ->assertSee('یک سیستم واقعی')
            ->assertSee('Personal System Faraja')
            ->assertSee('دیدن این سیستم')
            ->assertSee('id="service-faq"', false)
            ->assertSee('قبل از ساخت سیستم')
            ->assertSee('"@type": "FAQPage"', false);

        $this->get(route('services.show', 'growth'))
            ->assertOk()
            ->assertSee('id="service-problem"', false)
            ->assertSee('مخاطب هست')
            ->assertSee('بازدید یا دنبال‌کننده هست')
            ->assertSee('id="service-faq"', false)
            ->assertSee('قبل از شروع رشد')
            ->assertSee('عدد ساختگی وعده نمی‌دهیم')
            ->assertSee('"@type": "FAQPage"', false)
            ->assertDontSee('Personal System Faraja');

        $this->get(route('works.show', 'personal-system-faraja'))
            ->assertOk()
            ->assertSee('درخواست مرخصی و مساعده')
            ->assertSee('برای کار مشابه حرف بزنیم')
            ->assertSee('Laravel')
            ->assertSee('"@type": "CreativeWork"', false)
            ->assertDontSee('نتیجه');

        $this->get(route('works.show', 'laravel-shop'))
            ->assertOk()
            ->assertSee('فروشگاه لاراولی')
            ->assertSee('سبد')
            ->assertSee('کسب‌وکار بزرگ')
            ->assertSee('پنل مدیریت')
            ->assertDontSee('تنها ساختم')
            ->assertDontSee('آموزش')
            ->assertDontSee('نتیجه');
    }

    public function test_contact_page_can_book_a_meeting_and_keeps_the_problem_for_a_request(): void
    {
        $this->seed(SiteContentSeeder::class);

        $this->get(route('pages.show', ['slug' => 'contact', 'intent' => 'meeting']))
            ->assertOk()
            ->assertSee('name="meets_at"', false)
            ->assertSee('رزرو جلسه')
            ->assertDontSee('name="problem"', false);

        $this->get(route('pages.show', ['slug' => 'contact', 'intent' => 'request']))
            ->assertOk()
            ->assertSee('name="problem"', false)
            ->assertSee('شروع گفتگو');
    }

    public function test_seeder_adds_missing_pages_without_replacing_existing_content(): void
    {
        Service::query()->create([
            'title' => 'خدمت دستی',
            'slug' => 'manual-service',
            'description' => 'این توضیح نباید عوض شود.',
            'benefits' => [],
            'process' => [],
            'cta_label' => 'گفتگو',
            'status' => PublishStatus::Published,
            'position' => 1,
        ]);

        Page::query()->create([
            'title' => 'درباره ویرایش‌شده',
            'slug' => 'about',
            'status' => PublishStatus::Published,
            'body' => 'متن دستی درباره روش کار.',
        ]);

        $this->seed(SiteContentSeeder::class);

        $this->assertSame(1, Service::query()->count());
        $this->assertSame('خدمت دستی', Service::query()->value('title'));
        $this->assertSame('درباره ویرایش‌شده', Page::query()->where('slug', 'about')->value('title'));
        $this->assertSame('متن دستی درباره روش کار.', Page::query()->where('slug', 'about')->value('body'));
        $this->assertNotNull(Page::query()->where('slug', 'contact')->where('status', PublishStatus::Published)->first());
    }

    public function test_a_public_form_ties_an_error_to_its_field(): void
    {
        $this->seed(SiteContentSeeder::class);

        $this->followingRedirects()
            ->from(route('pages.show', 'contact'))
            ->post(route('leads.store'), [
                'intent' => 'contact',
                'name' => '',
                'contact' => '',
            ])
            ->assertOk()
            ->assertSee('id="lead-name-error"', false)
            ->assertSee('aria-invalid="true"', false)
            ->assertSee('aria-describedby="lead-name-error"', false)
            ->assertSee('id="lead-contact-error"', false);
    }

    public function test_a_draft_page_stays_hidden(): void
    {
        Page::query()->create([
            'title' => 'پیش‌نویس داخلی',
            'slug' => 'internal-note',
            'status' => PublishStatus::Draft,
            'body' => 'این متن عمومی نیست.',
        ]);

        $this->get(route('pages.show', 'internal-note'))->assertNotFound();
    }
}
