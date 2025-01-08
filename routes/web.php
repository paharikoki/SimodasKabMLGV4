<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FileHistoryController;
use App\Http\Controllers\FileTrashBastV1Controller;
use App\Http\Controllers\FileTrashBastV2Controller;
use App\Http\Controllers\FileTrashBastV3Controller;
use App\Http\Controllers\FileTrashBastV4Controller;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\InventarisRuangController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MasterRuanganController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\TransaksiPeminjamanController;
use App\Http\Controllers\SoftwareController;
use App\Models\InventarisRuang;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Row;

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

Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout']);


Route::group(['middleware' => ['level:Administrator']], function(){
    Route::get('/account-management', [AccountController::class, 'index']);
    Route::get('/account-management/add-account', [AccountController::class, 'create']);
    Route::post('/account-management/add-account', [AccountController::class, 'store']);
    Route::get('/account-management/{id}/edit', [AccountController::class, 'edit']);
    Route::put('/account-management/{id}/edit', [AccountController::class, 'update']);
    Route::delete('/account-management/{id}', [AccountController::class, 'destroy']);

});

Route::group(['middleware' => ['auth', 'BackButton']], function(){

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');


    Route::get('/asset-management', [AssetController::class, 'index']);
    Route::get('/asset-management/show-image/{id}', [AssetController::class, 'show'])->name('asset-management.show-image');
    Route::get('/asset-management/show-file/{id}', [AssetController::class, 'showFile']);
    Route::get('/asset-management/show-bast/{id}', [AssetController::class, 'showBast'])->name('asset-management.show-bast');
    Route::get('/asset-management/label/{id}', [AssetController::class, 'generateLabel'])->name('asset-management.label');
    Route::post('/asset-management/export-data', [AssetController::class, 'assetExportExcel']);
    Route::post('/asset-management/export-data-pdf', [AssetController::class, 'assetExportPDF']);
    Route::get('/asset-export', [AssetController::class, 'assetExportExcelBlank'])->name('asset.exportBlank');



    Route::get('/bast', [DistributionController::class, 'index']);
    Route::get('/bast/{id}/generate-pdf-v1', [DistributionController::class, 'generatePDFV1']);
    Route::get('/bast/{id}/generate-pdf-v2', [DistributionController::class, 'generatePDFV2']);
    Route::get('/bast/{id}/generate-pdf-v3', [DistributionController::class, 'generatePDFV3']);
    Route::get('/bast/{id}/generate-pdf-v4', [DistributionController::class, 'generatePDFV4']);




    Route::get('/employee-list', [EmployeeController::class, 'index']);
    Route::get('/employee-list/export-data', [EmployeeController::class, 'exportEmployee']);
    Route::get('/employee-list/data-pdf', [EmployeeController::class, 'generatePDF']);

    Route::get('/bast/add-bast', [DistributionController::class, 'create']);
    Route::post('/bast/add-bast', [DistributionController::class, 'store']);
    Route::get('/bast/{id}/edit', [DistributionController::class, 'edit']);
    Route::put('/bast/{id}/edit', [DistributionController::class, 'update']);
    Route::delete('/bsat/{id}', [DistributionController::class, 'destroy']);


    Route::delete('/asset-management/{id}', [AssetController::class, 'destroy'])->name('asset-management.destroy');
    Route::get('/asset-management/add-asset-data', [AssetController::class, 'create']);
    Route::post('/asset-management/add-asset-data', [AssetController::class, 'store']);
    Route::get('/asset-management/{id}/edit', [AssetController::class, 'edit'])->name('asset-management.edit');
    Route::put('/asset-management/{id}/edit', [AssetController::class, 'update']);
    Route::post('/asset-management/import-data', [AssetController::class, 'assetImport']);
    // AJAX DataTables
    Route::get('/asset-management/tangible-assets', [AssetController::class, 'tangibleAssets'])->name('asset-management.tangible-assets');


    Route::get('/employee-list/add-employee-data', [EmployeeController::class, 'create']);
    Route::post('/employee-list/add-employee-data', [EmployeeController::class, 'store']);
    Route::delete('/employee-list/{id}', [EmployeeController::class, 'destroy']);
    Route::get('/employee-list/{id}/edit', [EmployeeController::class, 'edit']);
    Route::put('/employee-list/{id}/edit', [EmployeeController::class, 'update']);
    Route::post('/employee-list/import-data', [EmployeeController::class, 'importEmployee']);

    Route::get('/account-management/edit', [AccountController::class, 'edit']);
    Route::put('/account-management/edit', [AccountController::class, 'update']);

    Route::get('/account-management/update-password', [AccountController::class, 'editPassword']);
    Route::put('/account-management/update-password', [AccountController::class, 'updatePassword']);

    Route::get('/asset-management/show-non-physical-image/{id}', [AssetController::class, 'showNonPhysicalImage'])->name('show-non-physical-image');
    Route::get('physical-pictures/{file}', FileController::class)->name('physical-pictures');
    Route::get('show-bast/{file}', PdfController::class)->name('show-bast');
    Route::get('history-bast/{file}', FileHistoryController::class)->name('history-bast');



    Route::get('trash-bast/v1/{file}', FileTrashBastV1Controller::class)->name('trash-bast-v1');
    Route::get('trash-bast/v2/{file}', FileTrashBastV2Controller::class)->name('trash-bast-v2');
    Route::get('trash-bast/v3/{file}', FileTrashBastV1Controller::class)->name('trash-bast-v3');
    Route::get('trash-bast/v4/{file}', FileTrashBastV2Controller::class)->name('trash-bast-v4');


    Route::get('/bast/trash-bast', [DistributionController::class, 'indexTrashBast']);
    Route::get('/bast/trash-bast/view-v1/{id}', [DistributionController::class, 'showBastV1']);
    Route::get('/bast/trash-bast/view-v2/{id}', [DistributionController::class, 'showBastV2']);
    Route::get('/bast/trash-bast/view-v3/{id}', [DistributionController::class, 'showBastV3']);
    Route::get('/bast/trash-bast/view-v4/{id}', [DistributionController::class, 'showBastV4']);
    Route::delete('/bast/trash-bast/force-delete/{id}', [DistributionController::class, 'forceDeleteBast']);


    Route::get('/asset-management/recapitulation', [AssetController::class, 'generateRecapitulation']);
    Route::post('/asset-management/recapitulation', [AssetController::class, 'generateRecapitulation']);

    Route::get('/docs-history', [HistoryController::class, 'index']);
    Route::post('/docs-history', [HistoryController::class, 'store']);
    Route::get('/docs-history/show/{id}', [HistoryController::class, 'showBast']);
    Route::delete('/docs-history/delete/{id}', [HistoryController::class, 'destroy']);


    Route::get('/software-files/{software}', [SoftwareController::class, 'downloadFile'])->name('software-files');


    Route::get('/transaksi-peminjaman', [TransaksiPeminjamanController::class, 'index']);
    Route::get('/transaksi-peminjaman/createGet', [TransaksiPeminjamanController::class, 'createGet'])->name('createGet');
    Route::post('/transaksi-peminjaman/createPost', [TransaksiPeminjamanController::class, 'createPost']);
    Route::get('/transaksi-peminjaman/show/{id}', [TransaksiPeminjamanController::class, 'showById'])->name('show');
    Route::get('/transaksi-peminjaman/edit/{id}', [TransaksiPeminjamanController::class, 'edit'])->name('edit');
    Route::delete('/transaksi-peminjaman/deleted/{id}', [TransaksiPeminjamanController::class, 'deleted'])->name('deleted');
    Route::post('/transaksi-peminjaman/updatePost', [TransaksiPeminjamanController::class, 'updatePost']);
    Route::get('/transaksi-peminjaman/printPdf/{id}', [TransaksiPeminjamanController::class, 'printPdf']);
    //new-route
    Route::get('/transaksi-peminjaman/getBarangByTahun', [TransaksiPeminjamanController::class, 'getBarangByTahun']);

    Route::get('/inventaris-ruang', [InventarisRuangController::class, 'index']);
    Route::get('/inventaris-ruang/createGet', [InventarisRuangController::class, 'createGet']);
    Route::post('/inventaris-ruang/createPost', [InventarisRuangController::class, 'createPost']);
    Route::get('/inventaris-ruang/edit/{id}', [InventarisRuangController::class, 'edit']);
    Route::post('/inventaris-ruang/updatePost', [InventarisRuangController::class, 'updatePost']);
    Route::delete('/inventaris-ruang/deleted/{id}', [InventarisRuangController::class, 'deleted'])->name('deleted');
    Route::get('/inventaris-ruang/show/{id}', [InventarisRuangController::class, 'showById'])->name('show');
    Route::get('/inventaris-ruang/printPdf/{id}', [InventarisRuangController::class, 'printPdf']);
    //new-route
    Route::get('/inventaris-ruang/getBarangByTahun', [InventarisRuangController::class, 'getBarangByTahun']);

    Route::get('/master-ruang', [MasterRuanganController::class, 'index']);
    Route::get('/master-ruang/createGet', [MasterRuanganController::class, 'createGet']);
    Route::post('/master-ruang/createPost', [MasterRuanganController::class, 'createPost']);
    Route::get('/master-ruang/edit/{id}', [MasterRuanganController::class, 'edit']);
    Route::post('/master-ruang/updatePost', [MasterRuanganController::class, 'updatePost']);
    Route::delete('/master-ruang/deleted/{id}', [MasterRuanganController::class, 'deleted'])->name('deleted');
    Route::get('/master-ruang/show/{id}', [MasterRuanganController::class, 'showById'])->name('show');
});

Route::get('/detail-assets/{id}', [AssetController::class, 'details'])->name('detail-assets');


Route::fallback(function(){
    return view('page/error-page');
});












