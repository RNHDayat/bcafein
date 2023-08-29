<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\CredentialController;
use App\Http\Controllers\Api\EducationController;
use App\Http\Controllers\Api\FollowUserController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\FollowCategoryController;
use App\Http\Controllers\Api\KnowFieldController;
use App\Http\Controllers\Api\PostingController;
use App\Http\Controllers\Api\ReplyController;
use App\Http\Controllers\Api\VoteController;
use App\Http\Controllers\Api\ProvinceController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::name('api.')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('createAccount', [AuthController::class, 'createAccount'])->name('createAccount');
    Route::post('generateKodeVerifikasi', [AuthController::class, 'generateKodeVerifikasi'])->name('generateKodeVerifikasi');
    Route::post('setPass/{token}', [AuthController::class, 'changePassword'])->name('changePassword');

    Route::get('showUser/{id}', [EmployeeController::class, 'show'])->name('showUser');
    Route::get('verifyEmail/{tokenMail}', [AuthController::class, 'verifyEmail'])->name('verifyEmail');
    Route::get('verifyEmailManual/{email}', [AuthController::class, 'verifyEmailManual'])->name('verifyEmailManual');

    // In Group Route
    Route::group(['middleware' => 'jwt.verify'], function () {
        Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::get('profile', [AuthController::class, 'showProfile'])->name('auth.profile');

        Route::group(['prefix' => 'province'], function () {
            Route::get('/', [ProvinceController::class, 'index'])->name('province.index');
            Route::get('/show/{id}', [ProvinceController::class, 'show'])->name('province.show');
        });

        Route::group(['prefix' => 'city'], function () {
            Route::get('/', [CityController::class, 'index'])->name('city.index');
            Route::get('/show/{id}', [CityController::class, 'show'])->name('city.show');
        });

        Route::group(['prefix' => 'education'], function () {
            Route::get('/', [EducationController::class, 'index'])->name('education.index');
            Route::get('/show/{id}', [EducationController::class, 'show'])->name('education.show');
            Route::post('/store', [EducationController::class, 'store'])->name('education.store');
            Route::post('/update/{id}', [EducationController::class, 'update'])->name('education.update');
            Route::get('/userDestroy/{id}', [EducationController::class, 'userDestroy'])->name('education.destroy');
        });

        Route::group(['prefix' => 'credential'], function () {
            Route::get('/indexUser', [CredentialController::class, 'indexUser'])->name('credential.user.index');
            Route::get('/show/{id}', [CredentialController::class, 'show'])->name('credential.show');
            Route::post('/store', [CredentialController::class, 'store'])->name('credential.store');
            Route::post('/update/{id}', [CredentialController::class, 'update'])->name('credential.update');
        });

        Route::group(['prefix' => 'knowField'], function () {
            Route::get('/', [KnowFieldController::class, 'index'])->name('knowField.index');
            Route::get('/show/{id}', [KnowFieldController::class, 'show'])->name('knowField.show');
            Route::post('/store', [KnowFieldController::class, 'store'])->name('knowField.store');
        });

        Route::group(['prefix' => 'category'], function () {
            Route::get('/', [CategoryController::class, 'index'])->name('category.index');
            Route::get('/show/{id}', [CategoryController::class, 'show'])->name('category.show');
            Route::post('/store', [CategoryController::class, 'store'])->name('category.store');
        });

        Route::group(['prefix' => 'followcat'], function () {
            Route::get('/', [FollowCategoryController::class, 'index'])->name('followcat.index');
            Route::get('/followcat/{id}', [FollowCategoryController::class, 'userFollowCategory'])->name('followcat.userfolcat');
            Route::get('/unfollowcat/{id}', [FollowCategoryController::class, 'userUnFollowCategory'])->name('followcat.userunfolcat');
        });

        Route::group(['prefix' => 'followuser'], function () {
            Route::get('/', [FollowUserController::class, 'indexFollowTHEM'])->name('followuser.indexFollowTHEM');
            Route::get('/followme', [FollowUserController::class, 'indexFollowME'])->name('followuser.indexFollowME');
            Route::get('/waitthem', [FollowUserController::class, 'indexWaitingTHEM'])->name('followuser.indexWaitingTHEM');
            Route::get('/waitme', [FollowUserController::class, 'indexWaitingME'])->name('followuser.indexWaitingME');
            Route::get('/unfolthem', [FollowUserController::class, 'indexUnFolTHEM'])->name('followuser.indexUnFolTHEM');
            Route::get('/unfolme', [FollowUserController::class, 'indexUnFolME'])->name('followuser.indexUnFolME');
            Route::get('/following/{id}', [FollowUserController::class, 'following'])->name('followuser.following');
            // Route::get('/followers', 'FollowUserController@followers');
            Route::get('/show/{id}', [FollowUserController::class, 'show'])->name('followuser.show');
            Route::get('/followers', [FollowUserController::class, 'followers'])->name('followuser.followers');
        });
        /** This is routes for Regular User
         * ==========================================
         * |                                        |
         * |    INI ADALAH RUTE REGULAR USER KSB    |
         * |                                        |
         * ==========================================
         */
        Route::group(['middleware' => 'regular_user'], function () {
            Route::get('home', [PostingController::class, 'index'])->name('home');
            Route::group(['prefix' => 'posting'], function () {
                Route::get('/', [PostingController::class, 'index'])->name('posting.index');
                Route::get('/following', [PostingController::class, 'indexFollowing'])->name('posting.indexFollowing');
                Route::get('/profile/{id}', [PostingController::class, 'profile'])->name('posting.profile');
                Route::get('/show/{id}', [PostingController::class, 'show'])->name('posting.show');
                Route::get('/detail/{id}', [PostingController::class, 'detailPost'])->name('posting.detailPost');
                Route::post('/store', [PostingController::class, 'store'])->name('posting.store');
            });
            Route::group(['prefix' => 'reply'], function () {
                Route::get('/', [ReplyController::class, 'index'])->name('reply.index');
                Route::get('/show/{id}', [ReplyController::class, 'show'])->name('reply.show');
                Route::post('/store', [ReplyController::class, 'store'])->name('reply.store');
            });
            Route::group(['prefix' => 'vote'], function () {
                Route::get('/', [VoteController::class, 'index'])->name('vote.index');
                Route::get('/show/{id}', [VoteController::class, 'show'])->name('vote.show');
                Route::post('/store', [VoteController::class, 'store'])->name('vote.store');
            });
            Route::get('/user', function () {
                return response()->json('Iki user regular_user cak...');
            });
        });
        /** This is routes for Admins
         * ===========================================
         * |                                         |
         * |    INI ADALAH RUTE USER ADMINS BIASA    |
         * |                                         |
         * ===========================================
         */
        Route::group(['middleware' => 'admins'], function () {
            // Route::get('/admin', function(){
            //     return response()->json('Iki user admins cak...');
            // });
            Route::group(['prefix' => 'province'], function () {
                Route::post('/store', [ProvinceController::class, 'store'])->name('province.store');
                Route::post('/update/{id}', [ProvinceController::class, 'update'])->name('province.update');
                Route::get('/destroy/{id}', [ProvinceController::class, 'destroy'])->name('province.destroy');
            });

            Route::group(['prefix' => 'city'], function () {
                Route::post('/store', [CityController::class, 'store'])->name('city.store');
                Route::post('/update/{id}', [CityController::class, 'update'])->name('city.update');
                Route::get('/destroy/{id}', [CityController::class, 'destroy'])->name('city.destroy');
            });

            Route::group(['prefix' => 'credential'], function () {
                Route::get('/indexAdmin', [CredentialController::class, 'indexAdmin'])->name('credential.admin.index');
                Route::get('/destroy/{id}', [CredentialController::class, 'destroy'])->name('credential.destroy');
            });

            Route::group(['prefix' => 'education'], function () {
                Route::get('/adminDestroy/{id}', [EducationController::class, 'adminDestroy'])->name('education.admin.destroy');
            });

            Route::group(['prefix' => 'knowField'], function () {
                Route::get('/indexAdmin', [KnowFieldController::class, 'indexAdmin'])->name('knowField.admin.index');
                Route::post('/storeAdmin', [KnowFieldController::class, 'storeAdmin'])->name('knowField.admin.store');
                Route::post('/update/{id}', [KnowFieldController::class, 'update'])->name('knowField.update');
                Route::get('/validateKnowledgeField/{id}', [KnowFieldController::class, 'validateKnowledgeField'])->name('knowField.validate');
                Route::get('/destroy/{id}', [KnowFieldController::class, 'destroy'])->name('knowField.destroy');
            });

            Route::group(['prefix' => 'category'], function () {
                Route::get('/indexAdmin', [CategoryController::class, 'indexAdmin'])->name('category.admin.index');
                Route::post('/storeAdmin', [CategoryController::class, 'storeAdmin'])->name('category.admin.store');
                Route::post('/update/{id}', [CategoryController::class, 'update'])->name('category.update');
                Route::get('/validateCategory/{id}', [CategoryController::class, 'validateCategory'])->name('category.validate');
                Route::get('/destroy/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
            });

            Route::group(['prefix' => 'followcat'], function () {
                Route::get('/indexAdmin', [FollowCategoryController::class, 'indexAdmin'])->name('followcat.admin.index');
                Route::post('/update/{id}', [FollowCategoryController::class, 'update'])->name('followcat.update');
                Route::post('/hidden/{id}', [FollowCategoryController::class, 'hiddenFollowing'])->name('followcat.hidden');
            });
        });

        /**This is routes for Super Admin
         * ==========================================
         * |                                        |
         * |    INI ADALAH RUTE USER SUPER ADMIN    |
         * |                                        |
         * ==========================================
         */

        Route::group(['middleware' => 'super_admin'], function () {
            Route::get('/superadmin', function () {
                return response()->json('Iki user super_admin cak...');
            });
        });

        /** This is routes for Director
         * ======================================
         * |                                    |
         * |    INI ADALAH RUTE USER DIRECTOR   |
         * |                                    |
         * ======================================
         */
        Route::group(['middleware' => 'director'], function () {
            Route::get('/director', function () {
                return response()->json('Iki user director cak...');
            });
        });

        /** This is routes for KSB Leader
         * ==========================================
         * |                                        |
         * |    INI ADALAH RUTE USER KSB LEADER     |
         * |                                        |
         * ==========================================
         */
        Route::group(['middleware' => 'lead_ksb'], function () {
            Route::get('/lead_ksb', function () {
                return response()->json('Iki user lead_ksb cak...');
            });
        });

        /** This is routes for Dominants Kader KS App
         * ==============================================
         * |                                            |
         * |    INI ADALAH RUTE USER DOMINANT KADER     |
         * |                                            |
         * ==============================================
         */
        Route::group(['middleware' => 'dominants_kader'], function () {
            Route::get('/dominant', function () {
                return response()->json('Iki user dominants_kader cak...');
            });
        });
    });
});
