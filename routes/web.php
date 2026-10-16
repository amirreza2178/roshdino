<?php

use App\Http\Controllers\ConvertAcceptedProposalController;
use App\Http\Controllers\LeadIndexController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ShowHomeController;
use App\Http\Controllers\ShowLoginController;
use App\Http\Controllers\ShowPasswordController;
use App\Http\Controllers\ShowPublishedPageController;
use App\Http\Controllers\ShowPublishedServiceController;
use App\Http\Controllers\ShowPublishedWorkController;
use App\Http\Controllers\Site\CategoryController;
use App\Http\Controllers\Site\ContentHomeController;
use App\Http\Controllers\Site\HomeCopyController;
use App\Http\Controllers\Site\MediaController;
use App\Http\Controllers\Site\PageController;
use App\Http\Controllers\Site\ServiceController;
use App\Http\Controllers\Site\SettingController;
use App\Http\Controllers\Site\WorkController;
use App\Http\Controllers\StoreLeadController;
use App\Http\Controllers\StoreLeadConversationController;
use App\Http\Controllers\StoreLeadFollowUpController;
use App\Http\Controllers\StoreLeadMeetingController;
use App\Http\Controllers\StoreLeadProposalController;
use App\Http\Controllers\StoreProjectDeliveryController;
use App\Http\Controllers\StoreProjectSupportController;
use App\Http\Controllers\StoreProjectTaskController;
use App\Http\Controllers\UpdateProjectTaskStatusController;
use App\Http\Controllers\StoreLoginController;
use App\Http\Controllers\UpdateLeadStatusController;
use App\Http\Controllers\UpdateLeadValueController;
use App\Http\Controllers\UpdatePasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/', ShowHomeController::class)->name('home');

Route::post('/leads', StoreLeadController::class)
    ->middleware('throttle:5,1')
    ->name('leads.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', ShowLoginController::class)->name('login');
    Route::post('/login', StoreLoginController::class)
        ->middleware('throttle:5,1')
        ->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', LogoutController::class)->name('logout');
    Route::get('/password', ShowPasswordController::class)->name('password.edit');
    Route::put('/password', UpdatePasswordController::class)
        ->middleware('throttle:5,1')
        ->name('password.update');
    Route::get('/leads', LeadIndexController::class)->name('leads.index');
    Route::patch('/leads/{lead}', UpdateLeadStatusController::class)->name('leads.status.update');
    Route::patch('/leads/{lead}/value', UpdateLeadValueController::class)->name('leads.value.update');
    Route::post('/leads/{lead}/follow-ups', StoreLeadFollowUpController::class)->name('leads.follow-ups.store');
    Route::post('/leads/{lead}/conversations', StoreLeadConversationController::class)->name('leads.conversations.store');
    Route::post('/leads/{lead}/meetings', StoreLeadMeetingController::class)->name('leads.meetings.store');
    Route::post('/leads/{lead}/proposals', StoreLeadProposalController::class)->name('leads.proposals.store');
    Route::post('/leads/{lead}/proposals/{proposal}/convert', ConvertAcceptedProposalController::class)
        ->scopeBindings()
        ->name('leads.proposals.convert');
    Route::post('/leads/{lead}/projects/{project}/deliveries', StoreProjectDeliveryController::class)
        ->scopeBindings()
        ->name('leads.projects.deliveries.store');
    Route::post('/leads/{lead}/projects/{project}/supports', StoreProjectSupportController::class)
        ->scopeBindings()
        ->name('leads.projects.supports.store');
    Route::post('/leads/{lead}/projects/{project}/tasks', StoreProjectTaskController::class)
        ->scopeBindings()
        ->name('leads.projects.tasks.store');
    Route::patch('/leads/{lead}/projects/{project}/tasks/{task}', UpdateProjectTaskStatusController::class)
        ->scopeBindings()
        ->name('leads.projects.tasks.status.update');

    Route::get('/site', ContentHomeController::class)->name('site.home');
    Route::get('/site/home', [HomeCopyController::class, 'edit'])->name('site.home-copy.edit');
    Route::put('/site/home', [HomeCopyController::class, 'update'])->name('site.home-copy.update');
    Route::get('/site/pages', [PageController::class, 'index'])->name('site.pages.index');
    Route::get('/site/pages/create', [PageController::class, 'create'])->name('site.pages.create');
    Route::post('/site/pages', [PageController::class, 'store'])->name('site.pages.store');
    Route::get('/site/pages/{page}/edit', [PageController::class, 'edit'])->name('site.pages.edit');
    Route::put('/site/pages/{page}', [PageController::class, 'update'])->name('site.pages.update');
    Route::delete('/site/pages/{page}', [PageController::class, 'destroy'])->name('site.pages.destroy');
    Route::get('/site/services', [ServiceController::class, 'index'])->name('site.services.index');
    Route::get('/site/services/create', [ServiceController::class, 'create'])->name('site.services.create');
    Route::post('/site/services', [ServiceController::class, 'store'])->name('site.services.store');
    Route::get('/site/services/{service}/edit', [ServiceController::class, 'edit'])->name('site.services.edit');
    Route::put('/site/services/{service}', [ServiceController::class, 'update'])->name('site.services.update');
    Route::delete('/site/services/{service}', [ServiceController::class, 'destroy'])->name('site.services.destroy');
    Route::get('/site/works', [WorkController::class, 'index'])->name('site.works.index');
    Route::get('/site/works/create', [WorkController::class, 'create'])->name('site.works.create');
    Route::post('/site/works', [WorkController::class, 'store'])->name('site.works.store');
    Route::get('/site/works/{work}/edit', [WorkController::class, 'edit'])->name('site.works.edit');
    Route::put('/site/works/{work}', [WorkController::class, 'update'])->name('site.works.update');
    Route::delete('/site/works/{work}', [WorkController::class, 'destroy'])->name('site.works.destroy');
    Route::get('/site/categories', [CategoryController::class, 'index'])->name('site.categories.index');
    Route::get('/site/categories/create', [CategoryController::class, 'create'])->name('site.categories.create');
    Route::post('/site/categories', [CategoryController::class, 'store'])->name('site.categories.store');
    Route::get('/site/categories/{category}/edit', [CategoryController::class, 'edit'])->name('site.categories.edit');
    Route::put('/site/categories/{category}', [CategoryController::class, 'update'])->name('site.categories.update');
    Route::delete('/site/categories/{category}', [CategoryController::class, 'destroy'])->name('site.categories.destroy');
    Route::get('/site/settings', [SettingController::class, 'edit'])->name('site.settings.edit');
    Route::put('/site/settings', [SettingController::class, 'update'])->name('site.settings.update');
    Route::get('/site/media', [MediaController::class, 'index'])->name('site.media.index');
    Route::post('/site/media', [MediaController::class, 'store'])->name('site.media.store');
    Route::delete('/site/media/{medium}', [MediaController::class, 'destroy'])->name('site.media.destroy');
});

Route::get('/services/{slug}', ShowPublishedServiceController::class)
    ->name('services.show')
    ->where('slug', '[^/]+');

Route::get('/work/{slug}', ShowPublishedWorkController::class)
    ->name('works.show')
    ->where('slug', '[^/]+');

Route::get('/{slug}', ShowPublishedPageController::class)
    ->name('pages.show')
    ->where('slug', '[^/]+');
