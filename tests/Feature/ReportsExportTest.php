<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;

class ReportsExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_export_reports()
    {
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'role_text' => 'admin',
        ]);

        $category = Category::create(['name' => 'Cat A', 'description' => '']);
        Product::create([
            'name' => 'Product A',
            'sku' => 'P001',
            'description' => '',
            'category_id' => $category->id,
            'price' => 10.5,
            'quantity' => 3,
            'minimum_stock' => 1,
        ]);

        $response = $this->actingAs($admin)->get('/reports/export');

        $response->assertStatus(200);

        $contentType = $response->headers->get('content-type');
        $this->assertStringContainsString('text/csv', $contentType);

        // Streamed responses may not contain body here; assert headers
        $contentDisposition = $response->headers->get('content-disposition');
        $this->assertStringContainsString('attachment', strtolower($contentDisposition ?? ''));
        $this->assertStringContainsString('inventory_report_', $contentDisposition ?? '');
    }

    public function test_non_admin_cannot_export_reports()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->create([
            'email' => 'cashier@example.test',
            'password' => bcrypt('password'),
            'role' => 'cashier',
            'role_text' => 'cashier',
        ]);

        $response = $this->actingAs($user)->get('/reports/export');
        $response->assertStatus(403);
    }
}
