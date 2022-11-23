<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Models\State;
use App\Models\User;
use App\Models\UserDetails;
use App\Http\Livewire\Customer;
use App\Http\Livewire\CustomerUpdate;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\Cleaner;
use App\Http\Controllers\Cleaner\CleanerController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

//Home
Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('sigup', function () {
    $title = array(
        'active' => 'signup',
    );
    return view('home.signup', compact('title'));
})->name('signup');

Route::get('signup-customers', function () {
    $title = array(
        'active' => 'signup-customers',
    );
    $states = State::all();
    return view('auth.register-customer', compact('title', 'states'));
})->name('signup-customers');

Route::get('signup-cleaner', function () {
    $title = array(
        'active' => 'signup-cleaner',
    );
    $states = State::all();
    return view('auth.register-cleaner', compact('title', 'states'));
})->name('signup-cleaner');

Route::get('help', function () {
    $title = array(
        'active' => 'help',
    );
    return view('home.help-center', compact('title'));
})->name('help-center');

Route::get('search-result', function () {
    $title = array(
        'active' => 'search-result',
    );
    return view('home.search-result', compact('title'));
})->name('search-result');

Route::get('profile/{id}', function () {
    $title = array(
        'active' => 'profile',
    );
    return view('home.profile', compact('title'));
})->name('profile');

Route::get('terms-and-conditions', function () {
    $title = array(
        'active' => 'terms-and-conditions',
    );
    return view('home.terms-and-conditions', compact('title'));
})->name('terms-and-conditions');

//Admin Section
Route::middleware(['auth', 'verified'])->group(function () {

    Route::prefix('admin')->group(function () {

        Route::get('/customer', function () {
            $title = array(
                'active' => 'customer',
            );
            return view('admin.customer.customer');
        })->name('admin.customer');

        //Admin Customer
        Route::get('/customer/{id}', function () {
            $title = array(
                'active' => 'customer-edit',
            );

            return view('admin.customer.customer-edit', ["id" => request()->id]);
        })->name('admin.customer.show');

        //Admin cleaner
        Route::get('/cleaner', function () {
            $title = array(
                'active' => 'cleaner',
            );
            return view('admin.cleaner.cleaner');
        })->name('admin.cleaner');

        //Admin Cleaner Update
        Route::get('/cleaner/{id}', function () {
            $title = array(
                'active' => 'admin-account',
            );
            return view('admin.cleaner.cleaner-edit', ["id" => request()->id]);
        })->name('admin.cleaner.show');

        //Admin Support
        Route::get('/support', function () {
            $title = array(
                'active' => 'admin-support',
            );
            return view('admin.support.support');
        })->name('admin.support');

        // Team Section  
        Route::get('/cleaner/team/{id}', [AdminController::class, 'teamView'])->name('admin.cleaner.team');
    });
    //services
    Route::prefix('services')->group(function () {
        Route::get('/', function () {
            $title = array(
                'title' => 'Services',
                'active' => 'services',
            );
            return view('admin.services.index', compact('title'));
        })->name('admin.services.index');
    });
});

//customer
Route::middleware(['auth', 'verified'])->group(function () {

    Route::prefix('customer')->group(function () {

        Route::get('/account', function () {
            $title = array(
                'title' => 'Account',
                'active' => 'account',
            );
            return view('customer.account.account', compact('title'));
        })->name('customer.account');
    });

    //cleaner
    Route::prefix('cleaner')->group(function () {
        Route::get('/account', function () {
            $title = array(
                'title' => 'Account',
                'active' => 'account',
            );
            return view('cleaner.account.account', compact('title'));
        })->name('cleaner.account');

        Route::get('/team', function () {
            $title = array(
                'title' => 'Team',
                'active' => 'team',
            );
            return view('cleaner.team.team', compact('title'));
        })->name('cleaner.team');

        Route::controller(CleanerController::class)->group(function () {
            Route::get('/reviews', function () {
                $title = array(
                    'title' => 'Reviews',
                    'active' => 'reviews',
                );
                return view('cleaner.reviews.reviews', compact('title'));
            })->name('cleaner.reviews');
        });
        //availability
        Route::prefix('availability')->group(function () {
            Route::controller(Cleaner\AvailabilityController::class)->group(function () {
                Route::get('/', 'index')->name('cleaner.availability.index');
                Route::post('/jobs', 'jobs')->name('cleaner.availability.jobs');
                Route::post('/buffer', 'buffer')->name('cleaner.availability.buffer');
                Route::post('/time', 'time')->name('cleaner.availability.time');
            });
        });
        //billing
        Route::prefix('billing')->group(function () {
            Route::controller(Cleaner\billing\BillingController::class)->group(function () {
                Route::get('/', 'index')->name('cleaner.billing.billing');
                Route::get('/accountdetails', 'bankAccount')->name('cleaner.billing.editBankAccount');
                Route::get('/editpayment', 'editpayment')->name('cleaner.billing.editPaymentMethod');
                Route::post('/profile/bankInfoStore', 'bankingInfoStore')->name('cleaner.billing.bankInfoStore');
                Route::get('/profile/connect-account', 'connectAccount')->name('cleaner.billing.connectAccount');
                Route::get('/banking-info-error', 'bankingInfoError')->name('cleaner.billing.error');
                Route::get('/banking-info-success', 'bankingInfoSuccess')->name('cleaner.billing.success');


                //services
                Route::prefix('services')->group(function () {
                    Route::controller(Cleaner\ServicesController::class)->group(function () {
                        Route::get('/', 'index')->name('cleaner.services.index');
                    });
                });
            });
            //Support
            Route::get('/support', function () {
                $title = array(
                    'title' => 'Support',
                    'active' => 'support',
                );
                return view('cleaner.support.support', compact('title'));
            })->name('cleaner.support.service');

            // Notification
            Route::prefix('notification')->group(function () {
                Route::controller(Cleaner\notification\NotificationController::class)->group(function () {
                    Route::get('/', 'index')->name('cleaner.notification.index');
                });
            });
        });

        // Route::get('search',[CleanerController::class,'index'])->name('search');
    });
});
