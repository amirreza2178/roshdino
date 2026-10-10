<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function test_home_page_loads_successfully(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('رشدینو')
            ->assertSee('کسب‌وکارت را')
            ->assertSee('DIGITAL GROWTH STUDIO');
    }

    public function test_home_page_contains_core_conversion_sections(): void
    {
        $response = $this->get(route('home'));

        $response
            ->assertSee('شروع یک پروژه')
            ->assertSee('مسئله‌ات را بفرست')
            ->assertSee('طراحی و توسعه وب')
            ->assertSee('نرم‌افزار اختصاصی')
            ->assertSee('راهکارهای رشد دیجیتال')
            ->assertSee('Personal System Faraja')
            ->assertSee('شروع گفتگو');
    }

    public function test_home_page_contains_main_navigation_sections(): void
    {
        $response = $this->get(route('home'));

        $response
            ->assertSee('id="services"', false)
            ->assertSee('id="work"', false)
            ->assertSee('id="process"', false)
            ->assertSee('id="about"', false)
            ->assertSee('id="faq"', false)
            ->assertSee('id="contact"', false);
    }

    public function test_home_page_contains_accessibility_hooks(): void
    {
        $response = $this->get(route('home'));

        $response
            ->assertSee('پرش به محتوای اصلی')
            ->assertSee('aria-expanded="false"', false)
            ->assertSee('aria-controls="mobile-navigation"', false);
    }

    public function test_home_page_contains_valid_organization_schema(): void
    {
        $html = $this->get(route('home'))->assertOk()->getContent();

        preg_match('/<script type="application\/ld\+json">\s*(.*?)\s*<\/script>/s', $html, $matches);

        $this->assertNotEmpty($matches[1] ?? null, 'Organization JSON-LD script was not found.');

        $schema = json_decode($matches[1], true);

        $this->assertSame(JSON_ERROR_NONE, json_last_error());
        $this->assertSame('https://schema.org', $schema['@context'] ?? null);
        $this->assertSame('Organization', $schema['@type'] ?? null);
        $this->assertSame('رشدینو', $schema['name'] ?? null);
        $this->assertSame('ROSHDINO', $schema['alternateName'] ?? null);
    }

    public function test_home_page_contains_seo_foundation(): void
    {
        $response = $this->get(route('home'));

        $response
            ->assertSee('<meta name="description"', false)
            ->assertSee('<link rel="canonical"', false)
            ->assertSee('application/ld+json', false)
            ->assertSee('"@type": "Organization"', false)
            ->assertSee('lang="fa"', false)
            ->assertSee('dir="rtl"', false)
            ->assertSee('viewport-fit=cover', false);
    }

    public function test_home_page_contains_process_and_low_friction_cta(): void
    {
        $response = $this->get(route('home'));

        $response
            ->assertSee('DISCOVER')
            ->assertSee('DESIGN')
            ->assertSee('BUILD')
            ->assertSee('GROW')
            ->assertSee('شروع یک پروژه')
            ->assertSee('mailto:hello@roshdino.test', false);
    }
}
