<?php

namespace Database\Seeders;

use App\Enums\Uam\PermissionEnum;
use App\Models\Uam\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{

    const LABEL_CREATE = 'Create';
    const LABEL_READ = 'Read';
    const LABEL_UPDATE = 'Update';
    const LABEL_DELETE = 'Delete';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissions = [
            // Dashboard
            ['module' => 'General', 'group' => 'General > Dashboard', 'name' => PermissionEnum::ReadGeneralDashboard->value, 'display_name' => self::LABEL_READ],

            /**
             * Uam Module
             */

            // User
            ['module' => 'Uam', 'group' => 'Uam > User', 'name' => PermissionEnum::CreateUamUser->value, 'display_name' => self::LABEL_CREATE],
            ['module' => 'Uam', 'group' => 'Uam > User', 'name' => PermissionEnum::ReadUamUser->value, 'display_name' => self::LABEL_READ],
            ['module' => 'Uam', 'group' => 'Uam > User', 'name' => PermissionEnum::UpdateUamUser->value, 'display_name' => self::LABEL_UPDATE],
            ['module' => 'Uam', 'group' => 'Uam > User', 'name' => PermissionEnum::DeleteUamUser->value, 'display_name' => self::LABEL_DELETE],

            // Role
            ['module' => 'Uam', 'group' => 'Uam > Role', 'name' => PermissionEnum::CreateUamRole->value, 'display_name' => self::LABEL_CREATE],
            ['module' => 'Uam', 'group' => 'Uam > Role', 'name' => PermissionEnum::ReadUamRole->value, 'display_name' => self::LABEL_READ],
            ['module' => 'Uam', 'group' => 'Uam > Role', 'name' => PermissionEnum::UpdateUamRole->value, 'display_name' => self::LABEL_UPDATE],
            ['module' => 'Uam', 'group' => 'Uam > Role', 'name' => PermissionEnum::DeleteUamRole->value, 'display_name' => self::LABEL_DELETE],

            // Permission
            ['module' => 'Uam', 'group' => 'Uam > Permission', 'name' => PermissionEnum::CreateUamPermission->value, 'display_name' => self::LABEL_CREATE],
            ['module' => 'Uam', 'group' => 'Uam > Permission', 'name' => PermissionEnum::ReadUamPermission->value, 'display_name' => self::LABEL_READ],
            ['module' => 'Uam', 'group' => 'Uam > Permission', 'name' => PermissionEnum::UpdateUamPermission->value, 'display_name' => self::LABEL_UPDATE],
            ['module' => 'Uam', 'group' => 'Uam > Permission', 'name' => PermissionEnum::DeleteUamPermission->value, 'display_name' => self::LABEL_DELETE],

            /**
             * Employee Module
             */

            // Employee
            ['module' => 'Employee', 'group' => 'Employee > Employees', 'name' => PermissionEnum::CreateEmployeeEmployee->value, 'display_name' => self::LABEL_CREATE],
            ['module' => 'Employee', 'group' => 'Employee > Employees', 'name' => PermissionEnum::ReadEmployeeEmployee->value, 'display_name' => self::LABEL_READ],
            ['module' => 'Employee', 'group' => 'Employee > Employees', 'name' => PermissionEnum::UpdateEmployeeEmployee->value, 'display_name' => self::LABEL_UPDATE],
            ['module' => 'Employee', 'group' => 'Employee > Employees', 'name' => PermissionEnum::DeleteEmployeeEmployee->value, 'display_name' => self::LABEL_DELETE],

            /**
             * Payroll Module
             */

            // Salary Head
            ['module' => 'Payroll', 'group' => 'Payroll > Salary Head', 'name' => PermissionEnum::CreatePayrollSalaryHead->value, 'display_name' => self::LABEL_CREATE],
            ['module' => 'Payroll', 'group' => 'Payroll > Salary Head', 'name' => PermissionEnum::ReadPayrollSalaryHead->value, 'display_name' => self::LABEL_READ],
            ['module' => 'Payroll', 'group' => 'Payroll > Salary Head', 'name' => PermissionEnum::UpdatePayrollSalaryHead->value, 'display_name' => self::LABEL_UPDATE],
            ['module' => 'Payroll', 'group' => 'Payroll > Salary Head', 'name' => PermissionEnum::DeletePayrollSalaryHead->value, 'display_name' => self::LABEL_DELETE],

            // Salary Structure
            ['module' => 'Payroll', 'group' => 'Payroll > Salary Structure', 'name' => PermissionEnum::CreatePayrollSalaryStructure->value, 'display_name' => self::LABEL_CREATE],
            ['module' => 'Payroll', 'group' => 'Payroll > Salary Structure', 'name' => PermissionEnum::ReadPayrollSalaryStructure->value, 'display_name' => self::LABEL_READ],
            ['module' => 'Payroll', 'group' => 'Payroll > Salary Structure', 'name' => PermissionEnum::UpdatePayrollSalaryStructure->value, 'display_name' => self::LABEL_UPDATE],
            ['module' => 'Payroll', 'group' => 'Payroll > Salary Structure', 'name' => PermissionEnum::DeletePayrollSalaryStructure->value, 'display_name' => self::LABEL_DELETE],

            // Employee Salary Profile
            ['module' => 'Payroll', 'group' => 'Payroll > Employee Salary Profile', 'name' => PermissionEnum::CreatePayrollEmployeeSalaryProfile->value, 'display_name' => self::LABEL_CREATE],
            ['module' => 'Payroll', 'group' => 'Payroll > Employee Salary Profile', 'name' => PermissionEnum::ReadPayrollEmployeeSalaryProfile->value, 'display_name' => self::LABEL_READ],
            ['module' => 'Payroll', 'group' => 'Payroll > Employee Salary Profile', 'name' => PermissionEnum::UpdatePayrollEmployeeSalaryProfile->value, 'display_name' => self::LABEL_UPDATE],
            ['module' => 'Payroll', 'group' => 'Payroll > Employee Salary Profile', 'name' => PermissionEnum::DeletePayrollEmployeeSalaryProfile->value, 'display_name' => self::LABEL_DELETE],

            // Salary Disbursement Batch
            ['module' => 'Payroll', 'group' => 'Payroll > Salary Disbursement Batch', 'name' => PermissionEnum::CreatePayrollSalaryDisbursementBatch->value, 'display_name' => self::LABEL_CREATE],
            ['module' => 'Payroll', 'group' => 'Payroll > Salary Disbursement Batch', 'name' => PermissionEnum::ReadPayrollSalaryDisbursementBatch->value, 'display_name' => self::LABEL_READ],
            ['module' => 'Payroll', 'group' => 'Payroll > Salary Disbursement Batch', 'name' => PermissionEnum::UpdatePayrollSalaryDisbursementBatch->value, 'display_name' => self::LABEL_UPDATE],
            ['module' => 'Payroll', 'group' => 'Payroll > Salary Disbursement Batch', 'name' => PermissionEnum::DeletePayrollSalaryDisbursementBatch->value, 'display_name' => self::LABEL_DELETE],

            // Process Payroll Salary Disbursement Batch
            ['module' => 'Payroll', 'group' => 'Payroll > Salary Disbursement Batch', 'name' => PermissionEnum::ProcessPayrollSalaryDisbursementBatch->value, 'display_name' => 'Process'],
            // Approve Payroll Salary Disbursement Batch
            ['module' => 'Payroll', 'group' => 'Payroll > Salary Disbursement Batch', 'name' => PermissionEnum::ApprovePayrollSalaryDisbursementBatch->value, 'display_name' => 'Approve'],
            // Disburse Payroll Salary Disbursement Batch
            ['module' => 'Payroll', 'group' => 'Payroll > Salary Disbursement Batch', 'name' => PermissionEnum::DisbursePayrollSalaryDisbursementBatch->value, 'display_name' => 'Disburse'],

            /**
             * Configuration Module
             */

            // Company
            ['module' => 'Configuration', 'group' => 'Configuration > Company', 'name' => PermissionEnum::CreateConfigurationCompany->value, 'display_name' => self::LABEL_CREATE],
            ['module' => 'Configuration', 'group' => 'Configuration > Company', 'name' => PermissionEnum::ReadConfigurationCompany->value, 'display_name' => self::LABEL_READ],
            ['module' => 'Configuration', 'group' => 'Configuration > Company', 'name' => PermissionEnum::UpdateConfigurationCompany->value, 'display_name' => self::LABEL_UPDATE],
            ['module' => 'Configuration', 'group' => 'Configuration > Company', 'name' => PermissionEnum::DeleteConfigurationCompany->value, 'display_name' => self::LABEL_DELETE],

            // Branch
            ['module' => 'Configuration', 'group' => 'Configuration > Branch', 'name' => PermissionEnum::CreateConfigurationBranch->value, 'display_name' => self::LABEL_CREATE],
            ['module' => 'Configuration', 'group' => 'Configuration > Branch', 'name' => PermissionEnum::ReadConfigurationBranch->value, 'display_name' => self::LABEL_READ],
            ['module' => 'Configuration', 'group' => 'Configuration > Branch', 'name' => PermissionEnum::UpdateConfigurationBranch->value, 'display_name' => self::LABEL_UPDATE],
            ['module' => 'Configuration', 'group' => 'Configuration > Branch', 'name' => PermissionEnum::DeleteConfigurationBranch->value, 'display_name' => self::LABEL_DELETE],

            // Division
            ['module' => 'Configuration', 'group' => 'Configuration > Division', 'name' => PermissionEnum::CreateConfigurationDivision->value, 'display_name' => self::LABEL_CREATE],
            ['module' => 'Configuration', 'group' => 'Configuration > Division', 'name' => PermissionEnum::ReadConfigurationDivision->value, 'display_name' => self::LABEL_READ],
            ['module' => 'Configuration', 'group' => 'Configuration > Division', 'name' => PermissionEnum::UpdateConfigurationDivision->value, 'display_name' => self::LABEL_UPDATE],
            ['module' => 'Configuration', 'group' => 'Configuration > Division', 'name' => PermissionEnum::DeleteConfigurationDivision->value, 'display_name' => self::LABEL_DELETE],

            // Unit
            ['module' => 'Configuration', 'group' => 'Configuration > Unit', 'name' => PermissionEnum::CreateConfigurationUnit->value, 'display_name' => self::LABEL_CREATE],
            ['module' => 'Configuration', 'group' => 'Configuration > Unit', 'name' => PermissionEnum::ReadConfigurationUnit->value, 'display_name' => self::LABEL_READ],
            ['module' => 'Configuration', 'group' => 'Configuration > Unit', 'name' => PermissionEnum::UpdateConfigurationUnit->value, 'display_name' => self::LABEL_UPDATE],
            ['module' => 'Configuration', 'group' => 'Configuration > Unit', 'name' => PermissionEnum::DeleteConfigurationUnit->value, 'display_name' => self::LABEL_DELETE],

            // Department
            ['module' => 'Configuration', 'group' => 'Configuration > Department', 'name' => PermissionEnum::CreateConfigurationDepartment->value, 'display_name' => self::LABEL_CREATE],
            ['module' => 'Configuration', 'group' => 'Configuration > Department', 'name' => PermissionEnum::ReadConfigurationDepartment->value, 'display_name' => self::LABEL_READ],
            ['module' => 'Configuration', 'group' => 'Configuration > Department', 'name' => PermissionEnum::UpdateConfigurationDepartment->value, 'display_name' => self::LABEL_UPDATE],
            ['module' => 'Configuration', 'group' => 'Configuration > Department', 'name' => PermissionEnum::DeleteConfigurationDepartment->value, 'display_name' => self::LABEL_DELETE],

            // Designation
            ['module' => 'Configuration', 'group' => 'Configuration > Designation', 'name' => PermissionEnum::CreateConfigurationDesignation->value, 'display_name' => self::LABEL_CREATE],
            ['module' => 'Configuration', 'group' => 'Configuration > Designation', 'name' => PermissionEnum::ReadConfigurationDesignation->value, 'display_name' => self::LABEL_READ],
            ['module' => 'Configuration', 'group' => 'Configuration > Designation', 'name' => PermissionEnum::UpdateConfigurationDesignation->value, 'display_name' => self::LABEL_UPDATE],
            ['module' => 'Configuration', 'group' => 'Configuration > Designation', 'name' => PermissionEnum::DeleteConfigurationDesignation->value, 'display_name' => self::LABEL_DELETE],

            // Position
            ['module' => 'Configuration', 'group' => 'Configuration > Position', 'name' => PermissionEnum::CreateConfigurationPosition->value, 'display_name' => self::LABEL_CREATE],
            ['module' => 'Configuration', 'group' => 'Configuration > Position', 'name' => PermissionEnum::ReadConfigurationPosition->value, 'display_name' => self::LABEL_READ],
            ['module' => 'Configuration', 'group' => 'Configuration > Position', 'name' => PermissionEnum::UpdateConfigurationPosition->value, 'display_name' => self::LABEL_UPDATE],
            ['module' => 'Configuration', 'group' => 'Configuration > Position', 'name' => PermissionEnum::DeleteConfigurationPosition->value, 'display_name' => self::LABEL_DELETE],

            // Position Group
            ['module' => 'Configuration', 'group' => 'Configuration > Position Group', 'name' => PermissionEnum::CreateConfigurationPositionGroup->value, 'display_name' => self::LABEL_CREATE],
            ['module' => 'Configuration', 'group' => 'Configuration > Position Group', 'name' => PermissionEnum::ReadConfigurationPositionGroup->value, 'display_name' => self::LABEL_READ],
            ['module' => 'Configuration', 'group' => 'Configuration > Position Group', 'name' => PermissionEnum::UpdateConfigurationPositionGroup->value, 'display_name' => self::LABEL_UPDATE],
            ['module' => 'Configuration', 'group' => 'Configuration > Position Group', 'name' => PermissionEnum::DeleteConfigurationPositionGroup->value, 'display_name' => self::LABEL_DELETE],

            // Hrbp
            ['module' => 'Configuration', 'group' => 'Configuration > Hrbp', 'name' => PermissionEnum::CreateConfigurationHrbp->value, 'display_name' => self::LABEL_CREATE],
            ['module' => 'Configuration', 'group' => 'Configuration > Hrbp', 'name' => PermissionEnum::ReadConfigurationHrbp->value, 'display_name' => self::LABEL_READ],
            ['module' => 'Configuration', 'group' => 'Configuration > Hrbp', 'name' => PermissionEnum::UpdateConfigurationHrbp->value, 'display_name' => self::LABEL_UPDATE],
            ['module' => 'Configuration', 'group' => 'Configuration > Hrbp', 'name' => PermissionEnum::DeleteConfigurationHrbp->value, 'display_name' => self::LABEL_DELETE],

            // Approval Level
            ['module' => 'Configuration', 'group' => 'Configuration > Approval Level', 'name' => PermissionEnum::CreateConfigurationApprovalLevel->value, 'display_name' => self::LABEL_CREATE],
            ['module' => 'Configuration', 'group' => 'Configuration > Approval Level', 'name' => PermissionEnum::ReadConfigurationApprovalLevel->value, 'display_name' => self::LABEL_READ],
            ['module' => 'Configuration', 'group' => 'Configuration > Approval Level', 'name' => PermissionEnum::UpdateConfigurationApprovalLevel->value, 'display_name' => self::LABEL_UPDATE],
            ['module' => 'Configuration', 'group' => 'Configuration > Approval Level', 'name' => PermissionEnum::DeleteConfigurationApprovalLevel->value, 'display_name' => self::LABEL_DELETE],

            // Approval Flow
            ['module' => 'Configuration', 'group' => 'Configuration > Approval Flow', 'name' => PermissionEnum::CreateConfigurationApprovalFlow->value, 'display_name' => self::LABEL_CREATE],
            ['module' => 'Configuration', 'group' => 'Configuration > Approval Flow', 'name' => PermissionEnum::ReadConfigurationApprovalFlow->value, 'display_name' => self::LABEL_READ],
            ['module' => 'Configuration', 'group' => 'Configuration > Approval Flow', 'name' => PermissionEnum::UpdateConfigurationApprovalFlow->value, 'display_name' => self::LABEL_UPDATE],
            ['module' => 'Configuration', 'group' => 'Configuration > Approval Flow', 'name' => PermissionEnum::DeleteConfigurationApprovalFlow->value, 'display_name' => self::LABEL_DELETE],

            // Shift
            ['module' => 'Configuration', 'group' => 'Configuration > Shift', 'name' => PermissionEnum::CreateConfigurationShift->value, 'display_name' => self::LABEL_CREATE],
            ['module' => 'Configuration', 'group' => 'Configuration > Shift', 'name' => PermissionEnum::ReadConfigurationShift->value, 'display_name' => self::LABEL_READ],
            ['module' => 'Configuration', 'group' => 'Configuration > Shift', 'name' => PermissionEnum::UpdateConfigurationShift->value, 'display_name' => self::LABEL_UPDATE],
            ['module' => 'Configuration', 'group' => 'Configuration > Shift', 'name' => PermissionEnum::DeleteConfigurationShift->value, 'display_name' => self::LABEL_DELETE],

            /**
             * Leave Module
             */

            // Leave Type
            ['module' => 'Leave', 'group' => 'Leave > Leave Type', 'name' => PermissionEnum::CreateLeaveLeaveType->value, 'display_name' => self::LABEL_CREATE],
            ['module' => 'Leave', 'group' => 'Leave > Leave Type', 'name' => PermissionEnum::ReadLeaveLeaveType->value, 'display_name' => self::LABEL_READ],
            ['module' => 'Leave', 'group' => 'Leave > Leave Type', 'name' => PermissionEnum::UpdateLeaveLeaveType->value, 'display_name' => self::LABEL_UPDATE],
            ['module' => 'Leave', 'group' => 'Leave > Leave Type', 'name' => PermissionEnum::DeleteLeaveLeaveType->value, 'display_name' => self::LABEL_DELETE],

            /**
             * Product Module
             */

            // Category
            ['module' => 'Product', 'group' => 'Product > Category', 'name' => PermissionEnum::CreateProductCategory->value, 'display_name' => self::LABEL_CREATE],
            ['module' => 'Product', 'group' => 'Product > Category', 'name' => PermissionEnum::ReadProductCategory->value, 'display_name' => self::LABEL_READ],
            ['module' => 'Product', 'group' => 'Product > Category', 'name' => PermissionEnum::UpdateProductCategory->value, 'display_name' => self::LABEL_UPDATE],
            ['module' => 'Product', 'group' => 'Product > Category', 'name' => PermissionEnum::DeleteProductCategory->value, 'display_name' => self::LABEL_DELETE],

            // Brand
            ['module' => 'Product', 'group' => 'Product > Brand', 'name' => PermissionEnum::CreateProductBrand->value, 'display_name' => self::LABEL_CREATE],
            ['module' => 'Product', 'group' => 'Product > Brand', 'name' => PermissionEnum::ReadProductBrand->value, 'display_name' => self::LABEL_READ],
            ['module' => 'Product', 'group' => 'Product > Brand', 'name' => PermissionEnum::UpdateProductBrand->value, 'display_name' => self::LABEL_UPDATE],
            ['module' => 'Product', 'group' => 'Product > Brand', 'name' => PermissionEnum::DeleteProductBrand->value, 'display_name' => self::LABEL_DELETE],

            // Unit
            ['module' => 'Product', 'group' => 'Product > Unit', 'name' => PermissionEnum::CreateProductUnit->value, 'display_name' => self::LABEL_CREATE],
            ['module' => 'Product', 'group' => 'Product > Unit', 'name' => PermissionEnum::ReadProductUnit->value, 'display_name' => self::LABEL_READ],
            ['module' => 'Product', 'group' => 'Product > Unit', 'name' => PermissionEnum::UpdateProductUnit->value, 'display_name' => self::LABEL_UPDATE],
            ['module' => 'Product', 'group' => 'Product > Unit', 'name' => PermissionEnum::DeleteProductUnit->value, 'display_name' => self::LABEL_DELETE],

            // Product
            ['module' => 'Product', 'group' => 'Product > Product', 'name' => PermissionEnum::CreateProductProduct->value, 'display_name' => self::LABEL_CREATE],
            ['module' => 'Product', 'group' => 'Product > Product', 'name' => PermissionEnum::ReadProductProduct->value, 'display_name' => self::LABEL_READ],
            ['module' => 'Product', 'group' => 'Product > Product', 'name' => PermissionEnum::UpdateProductProduct->value, 'display_name' => self::LABEL_UPDATE],
            ['module' => 'Product', 'group' => 'Product > Product', 'name' => PermissionEnum::DeleteProductProduct->value, 'display_name' => self::LABEL_DELETE],

        ];


        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                [
                    'guard_name' => 'web',
                    'module' => $permission['module'],
                    'group' => $permission['group'],
                    'display_name' => $permission['display_name'],
                ]
            );
        }

        // Delete permissions not in code
        Permission::whereNotIn('name', array_column($permissions, 'name'))->delete();
    }
}
