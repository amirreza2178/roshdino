<?php

namespace Tests\Feature;

use App\Enums\CategoryType;
use App\Enums\PublishStatus;
use App\Models\Category;
use App\Models\Media;
use App\Models\Page;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\User;
use App\Models\Work;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ManageSiteContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_open_the_content_panel(): void
    {
        $this->get(route('site.home'))->assertRedirect(route('login'));
        $this->get(route('site.pages.index'))->assertRedirect(route('login'));
        $this->post(route('site.pages.store'), [])->assertRedirect(route('login'));
    }

    public function test_staff_publishes_a_page_with_seo_and_keeps_drafts_hidden(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('site.pages.store'), [
                'title' => 'درباره همکاری',
                'slug' => 'Login',
                'status' => PublishStatus::Published->value,
                'body' => 'این نشانی آزاد نیست.',
                'indexable' => '1',
            ])
            ->assertSessionHasErrors('slug');

        $this->actingAs($user)
            ->post(route('site.pages.store'), [
                'title' => 'درباره همکاری',
                'slug' => 'about-work',
                'status' => PublishStatus::Draft->value,
                'body' => 'مسئله را قبل از ساخت روشن می‌کنیم. <strong>مهم</strong>',
                'meta_title' => 'درباره همکاری | رشدینو',
                'meta_description' => 'توضیح متا برای صفحه همکاری.',
                'canonical' => 'https://roshdino.test/about-work',
                'og_title' => 'درباره همکاری',
                'og_description' => 'توضیح اشتراک صفحه همکاری.',
                'indexable' => '0',
            ])
            ->assertRedirect(route('site.pages.index'));

        $this->get('/about-work')->assertNotFound();

        $page = Page::query()->firstOrFail();

        $this->actingAs($user)
            ->put(route('site.pages.update', $page), [
                'title' => 'درباره همکاری',
                'slug' => 'about-work',
                'status' => PublishStatus::Published->value,
                'body' => 'مسئله را قبل از ساخت روشن می‌کنیم. <strong>مهم</strong>',
                'meta_title' => 'درباره همکاری | رشدینو',
                'meta_description' => 'توضیح متا برای صفحه همکاری.',
                'canonical' => 'https://roshdino.test/about-work',
                'og_title' => 'درباره همکاری',
                'og_description' => 'توضیح اشتراک صفحه همکاری.',
                'indexable' => '0',
            ])
            ->assertRedirect(route('site.pages.index'));

        $this->get(route('pages.show', 'about-work'))
            ->assertOk()
            ->assertSee('مسئله را قبل از ساخت روشن می‌کنیم.')
            ->assertSee('&lt;strong&gt;مهم&lt;/strong&gt;', false)
            ->assertDontSee('<strong>مهم</strong>', false)
            ->assertSee('noindex, nofollow', false)
            ->assertSee('https://roshdino.test/about-work', false)
            ->assertSee('درباره همکاری | رشدینو', false);
    }

    public function test_published_service_has_benefits_process_and_its_own_call_to_action(): void
    {
        $category = Category::query()->create([
            'type' => CategoryType::Service,
            'name' => 'وب',
            'slug' => 'web',
        ]);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('site.services.store'), [
                'title' => 'طراحی فروشگاه',
                'slug' => 'shop',
                'category_id' => $category->id,
                'description' => 'مسیر خرید را کوتاه می‌کنیم.',
                'benefits' => "پرداخت روشن\nاعتماد در صفحه محصول",
                'process' => "شناخت | اصطکاک خرید را پیدا می‌کنیم.\nساخت | مسیر را ساده می‌کنیم.",
                'cta_label' => 'شروع گفتگوی فروشگاه',
                'cta_url' => '/?intent=request&service=web#contact',
                'interest' => 'web',
                'status' => PublishStatus::Draft->value,
                'position' => 1,
                'indexable' => '1',
            ])
            ->assertRedirect(route('site.services.index'));

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('طراحی و توسعه وب')
            ->assertDontSee('طراحی فروشگاه');

        $service = Service::query()->firstOrFail();

        $this->actingAs($user)
            ->put(route('site.services.update', $service), [
                'title' => 'طراحی فروشگاه',
                'slug' => 'shop',
                'category_id' => $category->id,
                'description' => 'مسیر خرید را کوتاه می‌کنیم.',
                'benefits' => "پرداخت روشن\nاعتماد در صفحه محصول",
                'process' => "شناخت | اصطکاک خرید را پیدا می‌کنیم.\nساخت | مسیر را ساده می‌کنیم.",
                'cta_label' => 'شروع گفتگوی فروشگاه',
                'cta_url' => '/?intent=request&service=web#contact',
                'interest' => 'web',
                'status' => PublishStatus::Published->value,
                'position' => 1,
                'indexable' => '1',
            ])
            ->assertRedirect(route('site.services.index'));

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('طراحی فروشگاه')
            ->assertSee('شروع گفتگوی فروشگاه')
            ->assertSee('service=web', false);

        $this->get(route('services.show', 'shop'))
            ->assertOk()
            ->assertSee('پرداخت روشن')
            ->assertSee('اصطکاک خرید را پیدا می‌کنیم.')
            ->assertSee('شروع گفتگوی فروشگاه')
            ->assertSee('وب');
    }

    public function test_work_shows_a_documented_result_only_when_it_is_written(): void
    {
        $workCategory = Category::query()->create([
            'type' => CategoryType::Work,
            'name' => 'سیستم',
            'slug' => 'system',
        ]);
        $serviceCategory = Category::query()->create([
            'type' => CategoryType::Service,
            'name' => 'وب',
            'slug' => 'web',
        ]);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('site.works.store'), [
                'title' => 'سامانه انبار',
                'summary' => 'یک سیستم برای موجودی واقعی.',
                'slug' => 'warehouse',
                'category_id' => $serviceCategory->id,
                'problem' => 'موجودی در چند فایل جدا بود.',
                'solution' => 'یک مسیر واحد برای ورود و خروج کالا.',
                'technologies' => "Laravel\nMariaDB",
                'status' => PublishStatus::Published->value,
                'position' => 1,
                'indexable' => '1',
            ])
            ->assertSessionHasErrors('category_id');

        $this->actingAs($user)
            ->post(route('site.works.store'), [
                'title' => 'سامانه انبار',
                'summary' => 'یک سیستم برای موجودی واقعی.',
                'slug' => 'warehouse',
                'category_id' => $workCategory->id,
                'problem' => 'موجودی در چند فایل جدا بود.',
                'solution' => 'یک مسیر واحد برای ورود و خروج کالا.',
                'technologies' => "Laravel\nMariaDB",
                'status' => PublishStatus::Published->value,
                'position' => 1,
                'indexable' => '1',
            ])
            ->assertRedirect(route('site.works.index'));

        $this->get(route('works.show', 'warehouse'))
            ->assertOk()
            ->assertSee('موجودی در چند فایل جدا بود.')
            ->assertSee('Laravel')
            ->assertDontSee('نتیجه');

        $work = Work::query()->firstOrFail();

        $this->actingAs($user)
            ->put(route('site.works.update', $work), [
                'title' => 'سامانه انبار',
                'summary' => 'یک سیستم برای موجودی واقعی.',
                'slug' => 'warehouse',
                'category_id' => $workCategory->id,
                'problem' => 'موجودی در چند فایل جدا بود.',
                'solution' => 'یک مسیر واحد برای ورود و خروج کالا.',
                'technologies' => "Laravel\nMariaDB",
                'result' => 'شمارش موجودی از سه فایل به یک صفحه منتقل شد.',
                'status' => PublishStatus::Published->value,
                'position' => 1,
                'indexable' => '1',
            ])
            ->assertRedirect(route('site.works.index'));

        $this->get(route('works.show', 'warehouse'))
            ->assertOk()
            ->assertSee('نتیجه')
            ->assertSee('شمارش موجودی از سه فایل به یک صفحه منتقل شد.')
            ->assertDontSee('یادگیری');

        $this->actingAs($user)
            ->put(route('site.works.update', $work), [
                'title' => 'سامانه انبار',
                'summary' => 'یک سیستم برای موجودی واقعی.',
                'slug' => 'warehouse',
                'category_id' => $workCategory->id,
                'problem' => 'موجودی در چند فایل جدا بود.',
                'solution' => 'یک مسیر واحد برای ورود و خروج کالا.',
                'technologies' => "Laravel\nMariaDB",
                'result' => 'شمارش موجودی از سه فایل به یک صفحه منتقل شد.',
                'learning' => 'قبل از ساخت، یک بار مسیر واقعی انبار را با تیم روی کاغذ رد کردیم.',
                'status' => PublishStatus::Published->value,
                'position' => 1,
                'indexable' => '1',
            ])
            ->assertRedirect(route('site.works.index'));

        $this->get(route('works.show', 'warehouse'))
            ->assertOk()
            ->assertSee('یادگیری')
            ->assertSee('قبل از ساخت، یک بار مسیر واقعی انبار را با تیم روی کاغذ رد کردیم.');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('سامانه انبار')
            ->assertDontSee('Personal System Faraja');
    }

    public function test_settings_change_the_public_brand_contact_and_homepage_seo(): void
    {
        $user = User::factory()->create();

        config([
            'roshdino.contact.telegram' => 'amirreza2178',
            'roshdino.contact.phone' => '09029196802',
        ]);

        $this->actingAs($user)
            ->put(route('site.settings.update'), [
                'brand_name' => 'رشدینو استودیو',
                'brand_latin' => 'ROSHDINO',
                'contact_phone' => '02100000000',
                'contact_telegram' => '@amirreza2178',
                'social_linkedin' => 'https://www.linkedin.com/company/roshdino',
                'footer_text' => 'متن پاصفحه از تنظیمات.',
                'seo_title' => 'عنوان سئو از تنظیمات',
                'seo_description' => 'توضیح سئو از تنظیمات سایت.',
                'seo_canonical' => 'https://roshdino.test/',
                'seo_og_title' => 'عنوان اشتراک',
                'seo_og_description' => 'توضیح اشتراک صفحه اصلی.',
                'seo_indexable' => '0',
            ])
            ->assertRedirect(route('site.settings.edit'));

        $this->assertSame('amirreza2178', SiteSetting::query()->where('key', 'contact_telegram')->value('value'));

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('رشدینو استودیو')
            ->assertSee('02100000000')
            ->assertSee('href="https://t.me/amirreza2178"', false)
            ->assertSee('https://www.linkedin.com/company/roshdino', false)
            ->assertSee('متن پاصفحه از تنظیمات.')
            ->assertSee('عنوان سئو از تنظیمات', false)
            ->assertSee('https://roshdino.test/', false)
            ->assertSee('noindex, nofollow', false);
    }

    public function test_upload_rejects_a_fake_image_and_shrinks_a_large_one(): void
    {
        if (! function_exists('imagecreatetruecolor')) {
            $this->markTestSkipped('GD is not available.');
        }

        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('site.media.store'), [
                'image' => UploadedFile::fake()->create('shell.php', 20, 'application/x-php'),
            ])
            ->assertSessionHasErrors('image');

        $this->assertSame(0, Media::query()->count());

        $large = $this->jpeg(1800, 40);

        $this->actingAs($user)
            ->post(route('site.media.store'), [
                'image' => $large,
            ])
            ->assertRedirect(route('site.media.index'));

        $media = Media::query()->firstOrFail();

        $this->assertLessThanOrEqual(1600, $media->width);
        $this->assertSame('image/jpeg', $media->mime);
        $this->assertStringStartsWith('media/', $media->path);
        $this->assertStringNotContainsString('wide-photo', $media->path);
        Storage::disk('public')->assertExists($media->path);

        $this->actingAs($user)
            ->delete(route('site.media.destroy', $media))
            ->assertRedirect(route('site.media.index'));

        $this->assertSame(0, Media::query()->count());
        Storage::disk('public')->assertMissing($media->path);
    }

    public function test_pages_are_listed_twenty_at_a_time(): void
    {
        $user = User::factory()->create();

        foreach (range(1, 21) as $number) {
            Page::query()->create([
                'title' => 'صفحه '.$number,
                'slug' => 'page-'.$number,
                'status' => PublishStatus::Draft,
                'body' => 'متن '.$number,
            ]);
        }

        $this->actingAs($user)
            ->get(route('site.pages.index'))
            ->assertOk()
            ->assertSee('<h2>صفحه 21</h2>', false)
            ->assertDontSee('<h2>صفحه 1</h2>', false);

        $this->actingAs($user)
            ->get(route('site.pages.index', ['page' => 2]))
            ->assertOk()
            ->assertSee('<h2>صفحه 1</h2>', false)
            ->assertDontSee('<h2>صفحه 21</h2>', false);
    }

    private function jpeg(int $width, int $height): UploadedFile
    {
        $image = imagecreatetruecolor($width, $height);
        $color = imagecolorallocate($image, 20, 40, 30);
        imagefilledrectangle($image, 0, 0, $width, $height, $color);
        $path = tempnam(sys_get_temp_dir(), 'roshdino');
        imagejpeg($image, $path, 90);
        imagedestroy($image);

        return new UploadedFile($path, 'wide-photo.jpg', 'image/jpeg', null, true);
    }
}
