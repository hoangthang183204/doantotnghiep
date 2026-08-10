<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class SalaryEditRouteTest extends TestCase
{
    public function test_admin_salary_edit_route_exists(): void
    {
        $route = Route::getRoutes()->getByName('admin.bang-luong.edit-nhan-vien');

        $this->assertNotNull($route);
        $this->assertSame('admin.bang-luong.edit-nhan-vien', $route->getName());
    }
}
