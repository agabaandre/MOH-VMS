<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Permission\Entities\Permission;
use Modules\Role\Entities\Role;
use App\Models\User;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear only roles and permissions
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Role::truncate();
        Permission::truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $permissions = [
            'General' => [],
            'Dashboard' => [],
            'Employee' => [
                'employee_management',
            ],
            'Vehicle Management' => [
                'vehicle_management',
                'vehicle_type_management',
                'vehicle_division_management',
                'vehicle_facility_management',
                'vehicle_ownership_type_management',
                'document_type_management',
                'legal_document_management',
            ],
            'Vehicle Requisition' => [
                'vehicle_requisition_type_management',
                'vehicle_requisition_management',
                'vehicle_requisition_purpose_management',
                'vehicle_route_management',
                'pick_drop_requisition',
            ],
            'Vehicle Insurance' => [
                'vehicle_insurance_company_management',
                'vehicle_insurance_recurring_period_management',
                'insurance_management',
            ],
            'Refueling' => [
                'fuel_type_management',
                'fuel_station_management',
                'refueling_management',
                'refueling_requisition_management',
            ],
            'Inventory' => [
                'inventory_category_management',
                'inventory_location_management',
                'inventory_parts_management',
                'inventory_parts_usage_management',
                'inventory_vendor_management',
                'expense_management',
                'expense_approval',
                'expense_type_management',
                'trip_type_management',
                'inventory_stock_management',
            ],
            'Vehicle Maintenance' => [
                'vehicle_maintenance_management',
                'vehicle_maintenance_type_management',
                'vehicle_maintenance_approval',
            ],
            'Purchase' => [
                'purchase_management',
                'purchase_approval',
            ],
            'Report' => [
                'report_management',
                'employee_report',
                'driver_report',
                'vehicle_report',
                'vehicle_requisition_report',
                'pickdrop_requisition_report',
                'refuel_requisition_report',
                'purchase_report',
                'expense_report',
                'maintenance_report',
            ],
            'User' => [
                'user_management',
                'role_management',
                'permission_management',
            ],
            'Setting' => [
                'setting_management',
                'mail_setting_management',
                'env_setting_management',
                'language_setting_management',
            ],
        ];
        
        $roles = [
            'User' => [],
        ];

        $administrator = Role::create(['name' => 'Administrator']);

        foreach ($permissions as $group => $groups) {

            foreach ($groups as $permission) {
                Permission::create([
                    'name' => $permission,
                    'group' => $group,
                ])->assignRole($administrator);
            }
        }

        foreach ($roles as $role => $permissions) {
            $role = Role::create(['name' => $role]);
            $role->givePermissionTo($permissions);
        }

        // Find and update Admin user's permissions
        $adminUser = User::where('name', 'Admin')->first();
        if ($adminUser) {
            $adminUser->syncRoles(['Administrator']);
        }
    }
}
