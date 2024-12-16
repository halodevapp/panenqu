<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\ContactFormCategoryController;
use App\Http\Controllers\Api\ContactFormController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\EventGaleryController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\MitraFaqController;
use App\Http\Controllers\Api\MitraFormController;
use App\Http\Controllers\Api\ProductCategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\SiteConfigController;
use App\Http\Controllers\Api\SocmedController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\SubscriberController;
use App\Http\Controllers\Api\TestimoniController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('media/{id}/{name}', [MediaController::class, 'show'])->name('api.media');

Route::middleware('throttle:120,1')->group(function () {
    Route::get('product/category', [ProductCategoryController::class, 'index'])->name('api.product.category.index');
    Route::get('product/category/{slug}', [ProductController::class, 'productByCategory'])->name('api.product.productByCategory');
    Route::get('product/{slug}', [ProductController::class, 'productBySlug'])->name('api.product.productBySlug');

    Route::get('testimoni', TestimoniController::class)->name('api.testimoni');
    Route::get('customer/category/{category}', [CustomerController::class, 'byCategory'])->name('api.customer.byCategory');
    Route::get('customer/category', [CustomerController::class, 'groupByCategory'])->name('api.customer.groupByCategory');
    Route::get('store/category/{category}', [StoreController::class, 'byCategory'])->name('api.store.byCategory');
    Route::get('mitra/faq', MitraFaqController::class)->name('api.mitra.faq');

    Route::get('article', [ArticleController::class, 'index'])->name('api.article.index');
    Route::get('article/{slug}', [ArticleController::class, 'articleBySlug'])->name('api.article.articleBySlug');
    Route::get('article/category/{slug}', [ArticleController::class, 'articleByCategory'])->name('api.article.articleByCategory');
    Route::get('utility', [SiteConfigController::class, 'siteConfig'])->name('api.utility');
    Route::get('page', [SiteConfigController::class, 'pageConfig'])->name('api.page');
    Route::get('popup', [SiteConfigController::class, 'popupConfig'])->name('api.popup');

    Route::get('contact/category', ContactFormCategoryController::class)->name('api.contact.category');

    Route::get('search', SearchController::class)->name('api.search');

    Route::get('event/galery', EventGaleryController::class)->name('event.galery');

    Route::get('instagram', [SocmedController::class, 'instagramMedia'])->name('api.instagramMedia');

    Route::middleware('recaptcha')->group(function () {
        Route::post('subscribe', [SubscriberController::class, 'store'])->name('api.subscribe.store');
        Route::post('contact', [ContactFormController::class, 'store'])->name('api.contact.store');
        Route::post('mitra', [MitraFormController::class, 'store'])->name('api.mitra.store');
    });

    Route::get('unsubscribe/{email}', [SubscriberController::class, 'unsubscribe'])->name('api.unsubscribe');
});
