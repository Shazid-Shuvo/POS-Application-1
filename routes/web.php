<?php

use App\Http\Controllers\categoryController;
use App\Http\Controllers\customerController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\invoiceController;
use App\Http\Controllers\productController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\userController;
use App\Http\Middleware\tokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;

//API ROUTES

Route::post('/user-registration', [userController::class, 'userRegistration']);
Route::post('/user-login', [userController::class,'userLogin']);
Route::post('/otp-send', [userController::class,'sendOtpCode']);
Route::post('/otp-verify', [userController::class,'verifyOtp']);
Route::post('/reset-password', [userController::class,'resetPassword'])
    ->middleware(tokenVerificationMiddleware::class);
Route::get('/user-profile',[UserController::class,'UserProfile'])
    ->middleware([TokenVerificationMiddleware::class]);
Route::post('/user-update',[UserController::class,'updateProfile'])
    ->middleware([TokenVerificationMiddleware::class]);

//Logout

Route::get('/logout', [userController::class,'userLogout']);

//PAGE ROUTES


Route::get('/userRegistration', [userController::class,'userRegistrationPage']);
Route::get('/userLogin', [userController::class,'userLoginPage']);
Route::get('/sendOtp', [userController::class,'sendOtpCodePage']);
Route::get('/verifyOtp', [userController::class,'verifyOtpPage']);
Route::get('/resetPassword', [userController::class,'resetPasswordPage'])
    ->middleware(tokenVerificationMiddleware::class);
Route::get('/dashboard', [dashboardController::class,'dashboardPage'])
    ->middleware(tokenVerificationMiddleware::class);
Route::get('/userProfile', [userController::class,'profilePage'])
    ->middleware(tokenVerificationMiddleware::class);
Route::get('/categoryPage', [categoryController::class,'CategoryPage'])
    ->middleware(tokenVerificationMiddleware::class);
Route::get('/customerPage', [customerController::class,'customerPage'])
    ->middleware(tokenVerificationMiddleware::class);
Route::get('/productPage', [productController::class,'productPage'])
    ->middleware(tokenVerificationMiddleware::class);
Route::get('/invoicePage',[invoiceController::class,'invoicePage'])
    ->middleware([TokenVerificationMiddleware::class]);
Route::get('/salePage',[invoiceController::class,'salePage'])
    ->middleware([TokenVerificationMiddleware::class]);
Route::get('/reportPage',[ReportController::class,'ReportPage'])
    ->middleware([TokenVerificationMiddleware::class]);

// Category API

Route::post("/create-category",[categoryController::class,'CategoryCreate'])->middleware([tokenVerificationMiddleware::class]);
Route::get("/list-category",[categoryController::class,'CategoryList'])->middleware([tokenVerificationMiddleware::class]);
Route::post("/delete-category",[CategoryController::class,'CategoryDelete'])->middleware([tokenVerificationMiddleware::class]);
Route::post("/update-category",[categoryController::class,'CategoryUpdate'])->middleware([tokenVerificationMiddleware::class]);

//Customer API

Route::post("/create-customer",[customerController::class,'customerCreate'])->middleware([tokenVerificationMiddleware::class]);
Route::get("/list-customer",[customerController::class,'customerList'])->middleware([tokenVerificationMiddleware::class]);
Route::post("/delete-customer",[customerController::class,'customerDelete'])->middleware([tokenVerificationMiddleware::class]);
Route::post("/update-customer",[customerController::class,'customerUpdate'])->middleware([tokenVerificationMiddleware::class]);

//product API


Route::post("/update-product-quantity",[productController::class,'updateQuantity'])->middleware([tokenVerificationMiddleware::class]);
Route::post("/product-by-id",[productController::class,'productByID'])->middleware([tokenVerificationMiddleware::class]);
Route::post("/create-product",[productController::class,'createProduct'])->middleware([tokenVerificationMiddleware::class]);
Route::get("/list-product",[productController::class,'productList'])->middleware([tokenVerificationMiddleware::class]);
Route::post("/delete-product",[productController::class,'deleteProduct'])->middleware([tokenVerificationMiddleware::class]);
Route::post("/update-product",[productController::class,'updateProduct'])->middleware([tokenVerificationMiddleware::class]);

// Invoice

Route::post("/invoice-create",[invoiceController::class,'invoiceCreate'])->middleware([TokenVerificationMiddleware::class]);
Route::get("/invoice-select",[invoiceController::class,'invoiceSelect'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/invoice-details",[invoiceController::class,'invoiceDetails'])->middleware([TokenVerificationMiddleware::class]);
Route::post("/invoice-delete",[invoiceController::class,'invoiceDelete'])->middleware([TokenVerificationMiddleware::class]);


//Dashboard API

Route::get("/summary",[dashboardController::class,'Summary'])->middleware([TokenVerificationMiddleware::class]);
Route::get("/sales-report/{FormDate}/{ToDate}",[ReportController::class,'SalesReport'])->middleware([TokenVerificationMiddleware::class]);
