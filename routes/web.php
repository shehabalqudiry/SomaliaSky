<?php

use App\Models\User;
use App\Helpers\MainHelper;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FCMController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\HelperController;
use App\Http\Controllers\SliderController;
use App\Notifications\GeneralNotification;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SiteMapController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\MenuLinkController;
use App\Http\Controllers\TrafficsController;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\FooterLinkController;
use App\Http\Controllers\RedirectionController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ContactReplyController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\Frontend\FrontController;
use App\Http\Controllers\Frontend\FrontStoreController;
use App\Http\Controllers\Frontend\FrontCategoryController;
use App\Http\Controllers\Frontend\FrontAnnouncementController;
use App\Http\Controllers\Frontend\NotificationsController as UserNotification;

Route::post('api/save-token', [FCMController::class, 'index']);

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
        'name'=> 'front.',
    ],
    function () {

        Route::get('/', [FrontController::class, 'index'])->name('home');
        Auth::routes();
        Route::view('about', 'front.pages.about')->name('about');
        Route::view('privacy_policy', 'front.pages.privacy')->name('privacy_policy');

        Route::view('welcome', 'welcome')->name('welcome');
        Route::view('contact', 'front.pages.contact')->name('contact');
        Route::post('contact', [FrontController::class,'contact_post'])->name('contact-post');
        Route::get('blocked', [HelperController::class,'blocked_user'])->name('blocked');


        Route::name('front.')->group(function () {
            Route::get('users/show/{user}', [FrontStoreController::class,'user_show'])->name('users.show');

            Route::prefix('categories')->group(function () {
                Route::get('', [FrontCategoryController::class,'index'])->name('categories.index');
                Route::get('category/{category}', [FrontCategoryController::class,'show'])->name('categories.show');
            });

            Route::prefix('stores')->group(function () {
                Route::get('', [FrontStoreController::class,'index'])->name('stores.index');
                Route::get('show/{store}', [FrontStoreController::class,'show'])->name('stores.show');
                Route::resource('announcements', FrontAnnouncementController::class)->only('index', 'show');
            });

            // Route::get('announcements/{announcement}', [FrontController::class,'announcement'])->name('announcement.show');

            Route::middleware(['auth'])->group(function () {
                Route::prefix('notifications')->name('notifications.')->group(function () {
                    Route::get('/', [UserNotification::class,'index'])->name('index');
                    Route::get('/ajax', [UserNotification::class,'notifications_ajax'])->name('ajax');
                    Route::post('/see', [UserNotification::class,'notifications_see'])->name('see');
                });
                Route::controller(HomeController::class)->group(function () {
                    Route::get('/chats', 'index')->name('chat');
                    Route::post('/chats', 'createChat')->name('home.createChat');
                    Route::get('/profile', 'profile')->name('profile');
                    Route::get('/profile-edit', 'profile_edit')->name('profile.edit');
                    Route::post('/profile-update', 'profile_update')->name('profile.update');
                });
                // Announcement Vip message view
                Route::get('announcement-created/{announcement}', function ($announcement) {
                    $announcement = App\Models\Announcement::where('number', $announcement)->first();
                    if (!$announcement || $announcement->blocked == 1) {
                        return abort(404);
                    }
                    return view('front.stores.announcement_vip', compact('announcement'));
                })->name('store.announcement_vip');


                Route::controller(FrontStoreController::class)->group(function () {
                    Route::get('my-store', 'my_store')->name('store.my_store');
                    Route::post('my-store', 'my_store_save')->name('store.my_store_save');
                    Route::get('editStore', 'my_store_edit')->name('store.edit');
                    Route::post('editStore', 'my_store_update')->name('store.my_store_update');
                });

                Route::resource('announcements', FrontAnnouncementController::class)->except('index', 'show');
                Route::get('createStoreAnnouncement', [FrontAnnouncementController::class, 'createStoreAnnouncement'])->name('createStoreAnnouncement');

                // Route::get('my-store', [FrontStoreController::class, 'my_store'])->name('store.my_store');

                Route::prefix('upload')->name('upload.')->group(function () {
                    Route::post('/image', [HelperController::class,'upload_image'])->name('image');
                    Route::post('/file', [HelperController::class,'upload_file'])->name('file');
                    Route::post('/remove-file', [HelperController::class,'remove_file'])->name('remove-file');
                });
            });
        });
    }
);

Route::prefix('admin')->middleware(['auth','ActiveAccount', 'IsAdmin'])->name('admin.')->group(function () {
    Route::get('/', [AdminController::class,'index'])->name('index');

    Route::middleware(['CheckRole:ADMIN'])->group(function () {
        Route::resource('sliders', SliderController::class);
        Route::resource('announcements', AnnouncementController::class);
        Route::resource('countries', CountryController::class);
        Route::resource('currencies', CurrencyController::class);
        Route::resource('cities', CityController::class);
        Route::resource('states', StateController::class);
        Route::resource('stores', StoreController::class);
        Route::resource('files', FileController::class);
        Route::post('contacts/resolve', [ContactController::class,'resolve'])
                ->can('resolve', \App\Models\Contact::class)->name('contacts.resolve');
        Route::resource('articles', ArticleController::class);
        Route::resource('contacts', ContactController::class);
        Route::resource('menus', MenuController::class);
        Route::resource('users', UserController::class);
        Route::resource('packages', PackageController::class);
        Route::resource('subscriptions', SubscriptionController::class);
        // Route::resource('products',ProductController::class);
        Route::resource('pages', PageController::class);
        Route::resource('tables', TableController::class);
        Route::resource('contact-replies', ContactReplyController::class);
        Route::post('faqs/order', [FaqController::class,'order'])->name('faqs.order');
        Route::resource('faqs', FaqController::class);
        Route::post('menu-links/get-type', [MenuLinkController::class,'getType'])->name('menu-links.get-type');
        Route::post('menu-links/order', [MenuLinkController::class,'order'])->name('menu-links.order');
        Route::resource('menu-links', MenuLinkController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('redirections', RedirectionController::class);
        Route::get('traffics', [TrafficsController::class,'index'])->name('traffics.index');
        Route::get('traffics/{traffic}/logs', [TrafficsController::class,'logs'])->name('traffics.logs');
        Route::get('error-reports', [TrafficsController::class,'error_reports'])->name('traffics.error-reports');
        Route::get('error-reports/{report}', [TrafficsController::class,'error_report'])->name('traffics.error-report');
        Route::post('footer-links/order', [FooterLinkController::class,'order'])->name('footer-links.order');
        Route::resource('footer-links', FooterLinkController::class);
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingController::class,'index'])->name('index');
            Route::put('/{settings}/update', [SettingController::class,'update'])->name('update');
        });
    });

    Route::prefix('upload')->name('upload.')->group(function () {
        Route::post('/image', [HelperController::class,'upload_image'])->name('image');
        Route::post('/file', [HelperController::class,'upload_file'])->name('file');
        Route::post('/remove-file', [HelperController::class,'remove_file'])->name('remove-file');
    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class,'index'])->name('index')->can('control', User::class);
        Route::get('/edit', [ProfileController::class,'edit'])->name('edit')->can('control', User::class);
        Route::put('/update', [ProfileController::class,'update'])->name('update')->can('control', User::class);
        Route::put('/update-password', [ProfileController::class,'update_password'])->name('update-password')->can('control', User::class);
        Route::put('/update-email', [ProfileController::class,'update_email'])->name('update-email')->can('control', User::class);
    });

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationsController::class,'index'])->name('index');
        Route::get('/ajax', [NotificationsController::class,'notifications_ajax'])->name('ajax');
        Route::post('/see', [NotificationsController::class,'notifications_see'])->name('see');
    });


});

Route::get('robots.txt', [HelperController::class,'robots']);
Route::get('manifest.json', [HelperController::class,'manifest'])->name('manifest');
Route::get('sitemap.xml', [SiteMapController::class,'sitemap']);
Route::get('sitemaps/links', [SiteMapController::class, 'custom_links']);
Route::get('sitemaps/{name}/{page}/sitemap.xml', [SiteMapController::class,'viewer']);

Route::controller(FrontController::class)->group(function () {
    // Route::get('page/{page}', 'page')->name('page.show');
    // Route::get('category/{category}', 'category')->name('category.show');
    // Route::get('article/{article}', 'article')->name('article.show');
    // Route::get('blog', 'blog')->name('blog');
    // Route::post('contact', 'contact_post')->name('contact-post');
});
