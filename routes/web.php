<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OutstockController;
use App\Http\Controllers\InStockController;
use App\Http\Controllers\StockCheckController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\ScanController;


// =========================
// Public Routes (Welcome + Authentication)
// =========================
// Show a welcome page at the site root before login
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
// Registration routes (show form + submit)
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// =========================
// Protected Routes
// =========================
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // =========================
    // Admin Routes (CRUD + Reports + Labels)
    // =========================
    Route::middleware('role:admin')->group(function () {

        // CRUD (admin only) - exclude index/show/create/store which are viewable by other roles
        Route::resource('products', ProductController::class)->except(['index', 'show', 'create', 'store']);
        // Categories managed below (gudang and admin will have full CRUD)

        // Reports
        Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
        Route::get('/reports/in', [ReportsController::class, 'in'])->name('reports.in');
        Route::get('/reports/out', [ReportsController::class, 'out'])->name('reports.out');
        Route::get('/reports/export', [ReportsController::class, 'export'])->name('reports.export');

        // Labeling
        Route::get('/labels/{id}', [LabelController::class, 'show'])->name('labels.show');
        Route::get('/labels', [LabelController::class, 'printAll'])->name('labels.print');
        Route::get('/labels/export', [LabelController::class, 'exportPdf'])->name('labels.export');

        // User management (admin)
        Route::resource('users', App\Http\Controllers\UsersController::class);
        Route::post('/users/{user}/reset-password', [App\Http\Controllers\UsersController::class, 'resetPassword'])->name('users.resetPassword');

        // Run storage:link
        Route::post('/admin/storage-link', function () {
            Artisan::call('storage:link');
            return redirect()->back()->with('success', 'storage:link executed');
        })->name('admin.storage.link');

        // Allow admin to delete individual stock movement records (safely revert quantities)
        Route::delete('/instock/{id}', [\App\Http\Controllers\InStockController::class, 'destroy'])->name('instock.destroy');
        Route::delete('/outstock/{id}', [\App\Http\Controllers\OutstockController::class, 'destroy'])->name('outstock.destroy');
    });

    // Produk - tampilan hanya-baca untuk cashier/gudang/admin
    Route::middleware('role:cashier,gudang,admin')->group(function () {
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        // Ensure numeric id for product parameter so 'create' won't be captured as {product}
        Route::get('/products/{product}', [ProductController::class, 'show'])->whereNumber('product')->name('products.show');
    });

    // Categories - allow gudang and admin full CRUD
    Route::middleware('role:gudang,admin')->group(function () {
        Route::resource('categories', CategoryController::class);
    });

    // Produk - create/store permitted for gudang + admin
    Route::middleware('role:gudang,admin')->group(function () {
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    });


    // =========================
    // Cashier/Admin Routes (Outstock)
    // =========================
    // Allow cashiers and admins to record and view outstock
    Route::middleware('role:cashier,admin')->group(function () {
        Route::get('/outstock/create', [OutstockController::class, 'create'])->name('outstock.create');
        Route::post('/outstock', [OutstockController::class, 'store'])->name('outstock.store');
        Route::get('/outstock/success', [OutstockController::class, 'success'])->name('outstock.success');
        // Riwayat stok keluar for cashier and admin
        Route::get('/outstock/history', [OutstockController::class, 'history'])->name('outstock.history');
    });


    // =========================
    // Instock (Cashier + Gudang)
    // =========================
        // Instock (Gudang/Admin can create; Cashier + Gudang can view history)
        Route::middleware('role:gudang,admin')->group(function () {
            Route::get('/instock/create', [InStockController::class, 'create'])->name('instock.create');
            Route::post('/instock', [InStockController::class, 'store'])->name('instock.store');
            Route::get('/instock/success', [InStockController::class, 'success'])->name('instock.success');
        });
        // Riwayat stok masuk for cashier, gudang, and admin
        Route::middleware('role:cashier,gudang,admin')->group(function () {
            Route::get('/instock/history', [InStockController::class, 'history'])->name('instock.history');
        });


    // =========================
    // Stock Check (Gudang + Admin)
    // =========================
    Route::middleware('role:gudang,admin')->group(function () {
        Route::get('/stock/check', [StockCheckController::class, 'index'])->name('stock.check');
    });


    // =========================
    // Scanning (Cashier + Gudang + Admin)
    // =========================
    Route::middleware('role:cashier,gudang,admin')->group(function () {
        Route::get('/transactions/scan', [ScanController::class, 'index'])->name('transactions.scan');
        Route::get('/api/products/scan', [ScanController::class, 'searchByCode'])->name('api.products.scan');
    });
    
});
