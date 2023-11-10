<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\RpcppeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\PropertyTypeController;
use App\Http\Controllers\PropertyTypeLowController;
use App\Http\Controllers\PropertyTypeHighController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\EnduserController;
use App\Http\Controllers\ReportsController;

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

Route::get('/', function () {
    return view('login');
});

//Login
Route::get('/login',[LoginController::class,'getLogin'])->name('getLogin');
Route::post('/login',[LoginController::class,'postLogin'])->name('postLogin');

//Middleware
Route::group(['middleware'=>['login_auth']],function(){
    Route::get('/dashboard',[MasterController::class,'dashboard'])->name('dashboard');

    //View
    Route::prefix('/view')->group(function () {
        // Route::get('/', [ViewController::class, 'index'])->name('manage-index');

        Route::prefix('/property')->group(function () {
            Route::post('list/create', [PropertyTypeController::class, 'ppeCreate'])->name('ppeCreate');
            Route::get('/listPPE', [PropertyTypeController::class, 'ppeRead'])->name('ppeRead');
            Route::get('list/edit/{id}', [PropertyTypeController::class, 'ppeEdit'])->name('ppeEdit');
            Route::post('list/update', [PropertyTypeController::class, 'ppeUpdate'])->name('ppeUpdate');
            Route::get('list/delete/{id}', [PropertyTypeController::class, 'ppeDelete'])->name('ppeDelete');

            Route::post('listLV/create', [PropertyTypeLowController::class, 'lvCreate'])->name('lvCreate');
            Route::get('/listLV', [PropertyTypeLowController::class, 'lvRead'])->name('lvRead');
            Route::get('list/{id}/LVedit', [PropertyTypeLowController::class, 'lvEdit'])->name('lvEdit');
            Route::post('listLV/update', [PropertyTypeLowController::class, 'lvUpdate'])->name('lvUpdate');
            Route::get('listLV/delete/{id}', [PropertyTypeLowController::class, 'lvDelete'])->name('lvDelete');

            Route::post('listHV/create', [PropertyTypeHighController::class, 'hvCreate'])->name('hvCreate');
            Route::get('/listHV', [PropertyTypeHighController::class, 'hvRead'])->name('hvRead');
            Route::get('list/{id}/HVedit', [PropertyTypeHighController::class, 'hvEdit'])->name('hvEdit');
            Route::post('listHV/update', [PropertyTypeHighController::class, 'hvUpdate'])->name('hvUpdate');
            Route::get('listHV/delete/{id}', [PropertyTypeHighController::class, 'hvDelete'])->name('hvDelete');
        });

        Route::prefix('/unit')->group(function () {
            Route::get('/list', [UnitController::class, 'unitRead'])->name('unitRead');
            Route::post('/list', [UnitController::class, 'unitCreate'])->name('unitCreate');
            Route::get('list/edit/{id}', [UnitController::class, 'unitEdit'])->name('unitEdit');
            Route::post('list/update', [UnitController::class, 'unitUpdate'])->name('unitUpdate');
            Route::get('list/delete/{id}', [UnitController::class, 'unitDelete'])->name('unitDelete');
        });

        Route::prefix('/item')->group(function () {
            Route::get('/list', [ItemController::class, 'itemRead'])->name('itemRead');
            Route::post('/list', [ItemController::class, 'itemCreate'])->name('itemCreate');
            Route::get('list/edit/{id}', [ItemController::class, 'itemEdit'])->name('itemEdit');
            Route::post('list/update', [ItemController::class, 'itemUpdate'])->name('itemUpdate');
            Route::get('list/delete/{id}', [ItemController::class, 'itemDelete'])->name('itemDelete'); 
        });

        Route::prefix('/office')->group(function () {
            Route::get('/list', [OfficeController::class, 'officeRead'])->name('officeRead');
            Route::post('/list', [OfficeController::class, 'officeCreate'])->name('officeCreate');
            Route::get('list/edit/{id}', [OfficeController::class, 'officeEdit'])->name('officeEdit');
            Route::post('list/update', [OfficeController::class, 'officeUpdate'])->name('officeUpdate');
            Route::get('list/delete/{id}', [OfficeController::class, 'officeDelete'])->name('officeDelete');
        });

        Route::prefix('/accntperson')->group(function () {
            Route::get('/list', [EnduserController::class, 'accountableRead'])->name('accountableRead');
            Route::post('/list', [EnduserController::class, 'accountableCreate'])->name('accountableCreate');
            Route::get('list/edit/{id}', [EnduserController::class, 'accountableEdit'])->name('accountableEdit');
            Route::post('list/update', [EnduserController::class, 'accountableUpdate'])->name('accountableUpdate');
            Route::get('list/delete/{id}', [EnduserController::class, 'accountableDelete'])->name('accountableDelete');
        });
    });

    //Purchase
    Route::prefix('/purchases')->group(function () {
        Route::get('/list/all', [PurchaseController::class, 'purchaseREAD'])->name('purchaseREAD');
        Route::get('/list/ppe', [PurchaseController::class, 'purchaseppeREAD'])->name('purchaseppeREAD');
        Route::get('/list/high', [PurchaseController::class, 'purchasehighREAD'])->name('purchasehighREAD');
        Route::get('/list/low', [PurchaseController::class, 'purchaselowREAD'])->name('purchaselowREAD');
        Route::post('/list/add', [PurchaseController::class, 'purchaseCreate'])->name('purchaseCreate');
        Route::get('/list/edit/{id}', [PurchaseController::class, 'purchaseEdit'])->name('purchaseEdit');
        Route::post('/list/update', [PurchaseController::class, 'purchaseUpdate'])->name('purchaseUpdate');
        Route::get('/list/cat/{id}/{mode}', [PurchaseController::class, 'purchaseCat'])->name('purchaseCat');
        Route::get('/list/prnt/{id}', [PurchaseController::class, 'purchasePrntSticker'])->name('purchasePrntSticker');
        Route::get('/list/delete/{id}', [PurchaseController::class, 'purchaseDelete'])->name('purchaseDelete');

        Route::get('/list/sticker', [PurchaseController::class, 'purchaseStickerTemplate'])->name('purchaseStickerTemplate');
        Route::get('/list/sticker/pdf', [PurchaseController::class, 'purchaseStickerTemplatePDF'])->name('purchaseStickerTemplatePDF');
    });

    //Inventory
    Route::prefix('/inventory')->group(function () {
        Route::get('/invlist', [InventoryController::class, 'inventoryRead'])->name('inventoryRead');
        Route::get('/invRpcppeReports', [RpcppeController::class, 'inventory_RPCPPEreports'])->name('inventory_RPCPPEreports');
        Route::get('/invRpcppePDF', [RpcppeController::class, 'inventory_RPCPPEpdf'])->name('inventory_RPCPPEpdf');

    });

    //Reports
    Route::prefix('/reports')->group(function () {
        Route::get('/rpcppe/option', [ReportsController::class, 'rpcppeOption'])->name('rpcppeOption');
        Route::get('/rpcppe/reports/gen', [ReportsController::class, 'rpcppeOptionReportGen'])->name('rpcppeOptionReportGen');
        Route::get('/rpcsep/option', [ReportsController::class, 'rpcsepOption'])->name('rpcsepOption');
        Route::get('/rpcsep/reports/gen', [ReportsController::class, 'rpcsepOptionReportGen'])->name('rpcsepOptionReportGen');
        Route::get('/ics/option', [ReportsController::class, 'icsOption'])->name('icsOption');
        Route::get('/ics/reports/gen', [ReportsController::class, 'icsOptionReportGen'])->name('icsOptionReportGen');
        Route::get('/par/option', [ReportsController::class, 'parOption'])->name('parOption');
        Route::post('/par/reports/gen', [ReportsController::class, 'parOptionReportGen'])->name('parOptionReportGen');
        Route::get('/par/itemList/{id}', [ReportsController::class, 'itemList'])->name('itemList');
    });


    //Users
    Route::prefix('/users')->group(function () {
        Route::get('/list',[UserController::class,'userRead'])->name('userRead');
        Route::post('/list', [UserController::class, 'userCreate'])->name('userCreate');
        Route::get('list/edit/{id}', [UserController::class, 'userEdit'])->name('userEdit');
        Route::post('list/update', [UserController::class, 'userUpdate'])->name('userUpdate');
        Route::get('list/delete/{id}', [UserController::class, 'userDelete'])->name('userDelete');
    });

    //Settings
    Route::prefix('/settings')->group(function () {
        Route::get('/account-settings',[SettingsController::class,'user_settings'])->name('user_settings');
        Route::post('/account-settings/update',[SettingsController::class,'profileUpdate'])->name('profileUpdate');
        Route::post('/acccount-settings/updatePass',[SettingsController::class,'profilePassUpdate'])->name('profilePassUpdate');
        Route::get('/system-name',[SettingsController::class,'setting_list'])->name('setting_list');
        Route::post('/system-name',[SettingsController::class,'upload'])->name('upload');
    });
    
    //Logout
    Route::get('/logout',[MasterController::class,'logout'])->name('logout');
});
