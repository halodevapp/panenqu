<?php

use App\Http\Controllers\ArticleCategoryController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactFormCategoryController;
use App\Http\Controllers\ContactFormController;
use App\Http\Controllers\CustomerCategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventGaleryController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\MitraFormController;
use App\Http\Controllers\MitraFaqController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PopupBannerController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SiteConfigController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\TestimoniController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('authenticate', [AuthController::class, 'authenticate'])->name('authenticate');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logactivities', [DashboardController::class, 'logActivities'])->name('dashboard.logActivities');
    Route::get('/summary', [DashboardController::class, 'summary'])->name('dashboard.summary');

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::post('userRoles', [UserController::class, 'userRoles'])->name('userRoles');
    Route::post('syncUserRoles', [UserController::class, 'syncUserRoles'])->name('syncUserRoles');
    Route::post('syncUserPermissions', [UserController::class, 'syncUserPermissions'])->name('syncUserPermissions');
    Route::post('userPermissions', [UserController::class, 'userPermissions'])->name('userPermissions');
    Route::resource('user', UserController::class);

    Route::post('getRolePermissions', [RoleController::class, 'getRolePermissions'])->name('getRolePermissions');
    Route::post('syncRolePermissions', [RoleController::class, 'syncRolePermissions'])->name('syncRolePermissions');
    Route::post('getPermissionsByRole', [RoleController::class, 'getPermissionsByRole'])->name('getPermissionsByRole');
    Route::resource('role', RoleController::class);

    Route::resource('permission', PermissionController::class);

    Route::delete('media', [MediaController::class, 'destroy'])->name('media.destroy');

    Route::resource('store', StoreController::class);

    Route::resource('product/category', ProductCategoryController::class, [
        'parameters' => [
            'category' => 'product_category'
        ],
        'names' => [
            'index' => 'product-category.index',
            'create' => 'product-category.create',
            'store' => 'product-category.store',
            'edit' => 'product-category.edit',
            'update' => 'product-category.update',
            'destroy' => 'product-category.destroy',
        ]
    ]);
    Route::resource('product', ProductController::class);

    Route::resource('article/category', ArticleCategoryController::class, [
        'parameters' => [
            'category' => 'article_category'
        ],
        'names' => [
            'index' => 'article-category.index',
            'create' => 'article-category.create',
            'store' => 'article-category.store',
            'edit' => 'article-category.edit',
            'update' => 'article-category.update',
            'destroy' => 'article-category.destroy',
        ]
    ]);

    Route::post('article/unpublish', [ArticleController::class, 'unpublish'])->name('article.unpublish');
    Route::resource('article', ArticleController::class);

    Route::resource('mitra/faq', MitraFaqController::class, [
        'parameters' => [
            'faq' => 'mitra_faq'
        ],
        'names' => [
            'index' => 'mitra-faq.index',
            'create' => 'mitra-faq.create',
            'store' => 'mitra-faq.store',
            'edit' => 'mitra-faq.edit',
            'update' => 'mitra-faq.update',
            'destroy' => 'mitra-faq.destroy',
        ]
    ]);

    Route::get('mitra/form/export', [MitraFormController::class, 'export'])->name('mitra-form.export');
    Route::resource('mitra/form', MitraFormController::class, [
        'parameters' => [
            'form' => 'mitra_form'
        ],
        'names' => [
            'index' => 'mitra-form.index',
            'create' => 'mitra-form.create',
            'store' => 'mitra-form.store',
            'edit' => 'mitra-form.edit',
            'update' => 'mitra-form.update',
            'destroy' => 'mitra-form.destroy',
        ]
    ]);

    Route::resource('testimoni', TestimoniController::class);
    Route::resource('contact/form', ContactFormController::class, [
        'parameters' => [
            'form' => 'contact_form'
        ],
        'names' => [
            'index' => 'contact-form.index',
            'create' => 'contact-form.create',
            'store' => 'contact-form.store',
            'edit' => 'contact-form.edit',
            'update' => 'contact-form.update',
            'destroy' => 'contact-form.destroy',
        ]
    ]);
    Route::resource('contact/category', ContactFormCategoryController::class, [
        'parameters' => [
            'category' => 'contact_category'
        ],
        'names' => [
            'index' => 'contact-category.index',
            'create' => 'contact-category.create',
            'store' => 'contact-category.store',
            'edit' => 'contact-category.edit',
            'update' => 'contact-category.update',
            'destroy' => 'contact-category.destroy',
        ]
    ]);

    Route::resource('event/galery', EventGaleryController::class, [
        'parameters' => [
            'galery' => 'event_galery'
        ],
        'names' => [
            'index' => 'event-galery.index',
            'create' => 'event-galery.create',
            'store' => 'event-galery.store',
            'edit' => 'event-galery.edit',
            'update' => 'event-galery.update',
            'destroy' => 'event-galery.destroy',
        ]
    ]);

    Route::resource('customer/category', CustomerCategoryController::class, [
        'parameters' => [
            'category' => 'customer_category'
        ],
        'names' => [
            'index' => 'customer-category.index',
            'create' => 'customer-category.create',
            'store' => 'customer-category.store',
            'edit' => 'customer-category.edit',
            'update' => 'customer-category.update',
            'destroy' => 'customer-category.destroy',
        ]
    ]);

    Route::resource('subscriber', SubscriberController::class);
    Route::resource('customer', CustomerController::class);

    Route::prefix('config')->name('config.')->group(function () {
        Route::get('/notification', [SiteConfigController::class, 'notificationIndex'])->name('notification.index');
        Route::post('/notification', [SiteConfigController::class, 'notificationStore'])->name('notification.store');
        Route::get('/notification/create', [SiteConfigController::class, 'notificationCreate'])->name('notification.create');
        Route::get('/notification/{id}/edit', [SiteConfigController::class, 'notificationEdit'])->name('notification.edit');
        Route::patch('/notification/{id}', [SiteConfigController::class, 'notificationUpdate'])->name('notification.update');
        Route::delete('/notification/{id}', [SiteConfigController::class, 'notificationDestroy'])->name('notification.destroy');

        Route::get('/whatsapp', [SiteConfigController::class, 'whatsappIndex'])->name('whatsapp.index');
        Route::get('/whatsapp/{id}/edit', [SiteConfigController::class, 'whatsappEdit'])->name('whatsapp.edit');
        Route::patch('/whatsapp/{id}', [SiteConfigController::class, 'whatsappUpdate'])->name('whatsapp.update');

        Route::get('/company', [SiteConfigController::class, 'companyIndex'])->name('company.index');
        Route::get('/company/{id}/edit', [SiteConfigController::class, 'companyEdit'])->name('company.edit');
        Route::patch('/company/{id}', [SiteConfigController::class, 'companyUpdate'])->name('company.update');

        Route::get('instagram', [SiteConfigController::class, 'instagramIndex'])->name('instagram.index');
        Route::get('instagram/redirect', [SiteConfigController::class, 'instagramRedirect'])->name('instagram.redirect');
        Route::get('instagram/callback', [SiteConfigController::class, 'instagramCallback'])->name('instagram.callback');

        Route::get('page/ajax/getAll', [SiteConfigController::class, 'pageGetAll'])->name('page.getAll');
        Route::post('page/ajax/create', [SiteConfigController::class, 'pageCreate'])->name('page.pageCreate');
        Route::post('page/ajax/content', [SiteConfigController::class, 'pageContentStore'])->name('page.contentStore');
        Route::patch('page/ajax/content', [SiteConfigController::class, 'pageContentUpdate'])->name('page.contentUpdate');
        Route::post('page/ajax/content/getByID', [SiteConfigController::class, 'pageContentShow'])->name('page.contentShow');
        Route::post('page/ajax/section', [SiteConfigController::class, 'pageSectionStore'])->name('page.sectionStore');

        Route::get('page', [SiteConfigController::class, 'pageIndex'])->name('page.index');
        Route::get('page/{page}/edit', [SiteConfigController::class, 'pageEdit'])->name('page.edit');
        Route::delete('page/{page}', [SiteConfigController::class, 'pageDestroy'])->name('page.destroy');

        Route::get('popup/ajax/getAll', [SiteConfigController::class, 'popupGetAll'])->name('popup.getAll');

        Route::get('/popup', [SiteConfigController::class, 'popupIndex'])->name('popup.index');
        Route::post('/popup', [SiteConfigController::class, 'popupStore'])->name('popup.store');
        Route::get('/popup/create', [SiteConfigController::class, 'popupCreate'])->name('popup.create');
        Route::get('/popup/{id}/edit', [SiteConfigController::class, 'popupEdit'])->name('popup.edit');
        Route::put('/popup/{id}', [SiteConfigController::class, 'popupUpdate'])->name('popup.update');
        Route::delete('/popup/{id}', [SiteConfigController::class, 'popupDestroy'])->name('popup.destroy');
    });
});
