@extends('layouts.app')

@section('title', 'رشدینو | طراحی سایت، نرم‌افزار اختصاصی و رشد دیجیتال')
@section('meta_description', 'رشدینو یک استودیو رشد دیجیتال است؛ طراحی و توسعه وب، نرم‌افزار اختصاصی و راهکارهای رشد برای کسب‌وکارهایی که می‌خواهند تجربه دیجیتال قابل‌استفاده و قابل‌توسعه داشته باشند.')
@section('og_title', 'رشدینو | استودیو رشد دیجیتال')
@section('og_description', 'وب، نرم‌افزار و راهکارهای رشد دیجیتال برای حل مسئله واقعی کسب‌وکار.')

@section('content')
    <section class="hero section">
        <div class="shell hero__layout">
            <div class="hero__content reveal">
                <p class="eyebrow"><span></span> DIGITAL GROWTH STUDIO</p>
                <h1>کسب‌وکارت را <em>دیجیتال‌تر، ساده‌تر و قابل‌رشدتر</em> کن.</h1>
                <p class="hero__lead">
                    از طراحی و توسعه وب و نرم‌افزار اختصاصی تا راهکارهای رشد دیجیتال؛
                    مسئله کسب‌وکارت را بررسی می‌کنیم و راهکاری می‌سازیم که واقعاً قابل استفاده باشد.
                </p>

                <div class="hero__actions">
                    <a href="#contact" class="button button--lime">شروع یک پروژه <span aria-hidden="true">←</span></a>
                    <a href="#work" class="button button--ghost">دیدن نمونه‌کار <span aria-hidden="true">↓</span></a>
                </div>

                <a href="#contact" class="micro-link">
                    هنوز نمی‌دانی دقیقاً به چه چیزی نیاز داری؟ <strong>مسئله‌ات را بفرست.</strong>
                </a>

                <div class="hero__stats" aria-label="حوزه‌های فعالیت">
                    <div><strong>WEB</strong><span>تجربه دیجیتال</span></div>
                    <div><strong>SOFTWARE</strong><span>سیستم اختصاصی</span></div>
                    <div><strong>GROWTH</strong><span>مسیر رشد</span></div>
                </div>
            </div>

            <div class="hero-visual reveal" aria-label="نمایی از رویکرد رشدینو">
                <div class="hero-visual__orb" aria-hidden="true"></div>
                <div class="hero-visual__content">
                    <div class="hero-visual__top">
                        <span>ROSHDINO / 01</span>
                        <span>GROWTH SYSTEM</span>
                    </div>
                    <div class="hero-visual__headline">
                        <span>مسئله</span>
                        <i></i>
                        <span>راهکار</span>
                        <i></i>
                        <span>رشد</span>
                    </div>
                    <div class="hero-visual__flow">
                        <div><small>01</small><b>DISCOVER</b><span>مسئله را می‌فهمیم</span></div>
                        <div><small>02</small><b>BUILD</b><span>راهکار را می‌سازیم</span></div>
                        <div><small>03</small><b>GROW</b><span>بهبود را اندازه می‌گیریم</span></div>
                    </div>
                    <div class="hero-visual__footer">
                        <span>WEB × SOFTWARE × GROWTH</span>
                        <span class="pulse-dot" aria-hidden="true"></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section section--problem" id="problem">
        <div class="shell">
            <div class="section-intro reveal">
                <p class="eyebrow"><span></span> THE REAL PROBLEM</p>
                <h2>هر پروژه باید یک <em>مسئله واقعی</em> را حل کند.</h2>
                <p>قبل از انتخاب تکنولوژی، باید بفهمیم چه چیزی در کسب‌وکار اصطکاک ایجاد کرده است.</p>
            </div>

            <div class="problem-list">
                <article class="problem-item reveal">
                    <span>01</span>
                    <div>
                        <h3>سایت داری، اما مشتری نمی‌آورد؟</h3>
                        <p>مشکل همیشه ظاهر سایت نیست. مسیر کاربر، پیام، اعتماد و نقطه تبدیل را باید کنار هم دید.</p>
                    </div>
                    <span class="problem-arrow" aria-hidden="true">↗</span>
                </article>
                <article class="problem-item reveal">
                    <span>02</span>
                    <div>
                        <h3>فرآیندهایت دستی و پراکنده شده‌اند؟</h3>
                        <p>وقتی سفارش، مشتری و عملیات در چند ابزار جدا هستند، یک سیستم متناسب با فرآیند واقعی می‌تواند مسیر را ساده کند.</p>
                    </div>
                    <span class="problem-arrow" aria-hidden="true">↗</span>
                </article>
                <article class="problem-item reveal">
                    <span>03</span>
                    <div>
                        <h3>مخاطب داری، اما مسیر رشد نداری؟</h3>
                        <p>جذب مخاطب پایان مسیر نیست؛ تجربه، محتوا، اعتماد و تبدیل باید در یک سیستم کنار هم قرار بگیرند.</p>
                    </div>
                    <span class="problem-arrow" aria-hidden="true">↗</span>
                </article>
            </div>
        </div>
    </section>

    <section class="section section--ivory" id="services">
        <div class="shell">
            <div class="section-intro section-intro--dark reveal">
                <p class="eyebrow"><span></span> WHAT WE BUILD</p>
                <h2>راهکاری بساز که <em>برای کسب‌وکار کار کند.</em></h2>
                <p>سه مسیر اصلی داریم؛ اما نقطه شروع همیشه مسئله توست، نه تکنولوژی.</p>
            </div>

            <div class="service-list">
                <article class="service-row reveal">
                    <div class="service-row__number">01</div>
                    <div class="service-row__main">
                        <h3>طراحی و توسعه وب</h3>
                        <p>سایت‌هایی سریع، حرفه‌ای و قابل توسعه که فقط زیبا نیستند؛ برای کسب‌وکار کار می‌کنند.</p>
                        <ul><li>UX و UI</li><li>توسعه اختصاصی</li><li>SEO و Performance</li></ul>
                    </div>
                    <a href="#contact" class="service-row__link" aria-label="درخواست درباره طراحی و توسعه وب">↗</a>
                </article>

                <article class="service-row reveal">
                    <div class="service-row__number">02</div>
                    <div class="service-row__main">
                        <h3>نرم‌افزار اختصاصی</h3>
                        <p>فرآیندهای پیچیده کسب‌وکارت را به یک سیستم ساده، قابل کنترل و قابل توسعه تبدیل می‌کنیم.</p>
                        <ul><li>سیستم‌های اختصاصی</li><li>اتوماسیون</li><li>داشبورد و Workflow</li></ul>
                    </div>
                    <a href="#contact" class="service-row__link" aria-label="درخواست درباره نرم‌افزار اختصاصی">↗</a>
                </article>

                <article class="service-row reveal">
                    <div class="service-row__number">03</div>
                    <div class="service-row__main">
                        <h3>راهکارهای رشد دیجیتال</h3>
                        <p>از ساختار سایت و محتوا تا تجربه کاربر و مسیر تبدیل؛ نقاط اصطکاک را پیدا می‌کنیم و بهبود می‌دهیم.</p>
                        <ul><li>Conversion</li><li>Content</li><li>SEO و Analytics</li></ul>
                    </div>
                    <a href="#contact" class="service-row__link" aria-label="درخواست درباره راهکارهای رشد دیجیتال">↗</a>
                </article>
            </div>
        </div>
    </section>

    <section class="section section--process" id="process">
        <div class="shell">
            <div class="section-intro reveal">
                <p class="eyebrow"><span></span> HOW WE WORK</p>
                <h2>از مسئله تا <em>راهکار قابل استفاده.</em></h2>
                <p>فرآیند شفاف است تا بدانی در هر مرحله چه اتفاقی می‌افتد.</p>
            </div>

            <div class="process-line">
                <article class="process-step reveal">
                    <span>01</span>
                    <h3>DISCOVER</h3>
                    <p>شناخت کسب‌وکار، کاربر و مسئله اصلی.</p>
                </article>
                <article class="process-step reveal">
                    <span>02</span>
                    <h3>DESIGN</h3>
                    <p>تعریف مسیر، تجربه و راهکار مناسب.</p>
                </article>
                <article class="process-step reveal">
                    <span>03</span>
                    <h3>BUILD</h3>
                    <p>پیاده‌سازی تمیز، تست‌پذیر و قابل توسعه.</p>
                </article>
                <article class="process-step reveal">
                    <span>04</span>
                    <h3>GROW</h3>
                    <p>اندازه‌گیری، یادگیری و بهبود مستمر.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="section section--case" id="work">
        <div class="shell">
            <div class="section-intro reveal">
                <p class="eyebrow"><span></span> SELECTED WORK</p>
                <h2>نمونه‌کار را <em>با واقعیت</em> می‌سنجیم.</h2>
                <p>به‌جای عددسازی، پروژه واقعی و تصمیم‌های واقعی را نشان می‌دهیم.</p>
            </div>

            <article class="case-study reveal">
                <div class="case-study__visual">
                    <span class="case-study__tag">CASE STUDY / 001</span>
                    <div class="case-study__screen">
                        <div class="case-study__screen-top"><span>PERSONNEL SYSTEM</span><span>ROSHDINO</span></div>
                        <div class="case-study__screen-body">
                            <span>EMPLOYEE</span><strong>HR MANAGEMENT</strong><span>ATTENDANCE / LEAVE / WORKFLOW</span>
                        </div>
                    </div>
                </div>
                <div class="case-study__content">
                    <p class="eyebrow"><span></span> Personal System Faraja</p>
                    <h3>یک سیستم واقعی برای مدیریت فرآیندهای پرسنلی.</h3>
                    <div class="case-grid">
                        <div><small>مسئله</small><p>مدیریت اطلاعات پرسنل و فرآیندهای مرتبط نیازمند یک سیستم یکپارچه بود.</p></div>
                        <div><small>راهکار</small><p>یک سیستم Laravel-based با ساختار قابل توسعه و workflowهای مشخص.</p></div>
                    </div>
                    <div class="case-tech"><span>Laravel</span><span>PHP</span><span>MariaDB</span><span>Blade</span></div>
                    <a href="#contact" class="text-link">درباره یک پروژه مشابه صحبت کنیم <span aria-hidden="true">←</span></a>
                </div>
            </article>
        </div>
    </section>

    <section class="section section--trust" id="about">
        <div class="shell trust">
            <div class="trust__statement reveal">
                <p class="eyebrow"><span></span> OUR PRINCIPLE</p>
                <h2>تکنولوژی برای ما هدف نیست؛ <em>ابزار حل مسئله است.</em></h2>
            </div>
            <div class="trust__copy reveal">
                <p>رشدینو از ترکیب توسعه نرم‌افزار، تجربه کاربر و نگاه رشد ساخته می‌شود. هدف، ساخت چیزی است که بعد از تحویل هم برای کسب‌وکار قابل استفاده و قابل توسعه بماند.</p>
                <div class="trust__tags"><span>UX</span><span>SEO</span><span>PERFORMANCE</span><span>SECURITY</span><span>TESTING</span><span>SCALABILITY</span></div>
            </div>
        </div>
    </section>

    <section class="section section--faq" id="faq">
        <div class="shell faq-layout">
            <div class="section-intro reveal">
                <p class="eyebrow"><span></span> FAQ</p>
                <h2>قبل از شروع، <em>سؤال‌هایت را جواب بدهیم.</em></h2>
            </div>
            <div class="faq-list">
                <details class="faq-item reveal"><summary>اگر دقیقاً ندانم چه چیزی لازم دارم چه؟</summary><p>اشکالی ندارد. نقطه شروع، مسئله و هدف کسب‌وکار است؛ در گفت‌وگوی اولیه کمک می‌کنیم نیاز واقعی مشخص شود.</p></details>
                <details class="faq-item reveal"><summary>قیمت پروژه چگونه تعیین می‌شود؟</summary><p>بعد از مشخص شدن دامنه کار، پیچیدگی، طراحی، توسعه و خروجی مورد انتظار، محدوده مناسب پروژه تعیین می‌شود.</p></details>
                <details class="faq-item reveal"><summary>آیا پروژه بعداً قابل توسعه است؟</summary><p>بله. معماری و انتخاب تکنولوژی بر اساس نیاز واقعی و مسیر رشد پروژه انجام می‌شود تا توسعه آینده منطقی بماند.</p></details>
                <details class="faq-item reveal"><summary>بعد از تحویل پروژه چه اتفاقی می‌افتد؟</summary><p>در صورت نیاز، پشتیبانی، نگهداری و توسعه‌های بعدی می‌تواند به‌صورت جداگانه ادامه پیدا کند.</p></details>
            </div>
        </div>
    </section>

    <section class="section section--contact" id="contact">
        <div class="shell contact-cta reveal">
            <p class="eyebrow"><span></span> LET'S START</p>
            <h2>یک مسئله دیجیتال داری؟ <em>از همین‌جا شروع کنیم.</em></h2>
            <p>لازم نیست از قبل دقیقاً بدانی چه چیزی باید ساخته شود. مسئله را توضیح بده؛ مسیر مناسب را با هم پیدا می‌کنیم.</p>
            <a href="mailto:hello@roshdino.test" class="button button--lime button--large">شروع گفتگو <span aria-hidden="true">←</span></a>
            <small>این آدرس در نسخه توسعه‌ای سایت قرار دارد و قبل از انتشار عمومی با راه ارتباطی واقعی جایگزین می‌شود.</small>
        </div>
    </section>
@endsection

@push('head')
    <script type="application/ld+json">
        {!! json_encode([
            '@' . 'context' => 'https://schema.org',
            '@' . 'type' => 'Organization',
            'name' => 'رشدینو',
            'alternateName' => 'ROSHDINO',
            'url' => url('/'),
            'description' => 'استودیو رشد دیجیتال برای طراحی و توسعه وب، نرم‌افزار اختصاصی و راهکارهای رشد دیجیتال.',
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
@endpush
