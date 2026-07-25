<?php

namespace App\Enums\Uam;

enum PermissionEnum: string
{
    // General > Dashboard
    case ReadGeneralDashboard = 'READ_GENERAL_DASHBOARD';

    /**
     * Uam Module
     */
        // Uam > User
    case CreateUamUser = 'CREATE_UAM_USER';
    case ReadUamUser = 'READ_UAM_USER';
    case UpdateUamUser = 'UPDATE_UAM_USER';
    case DeleteUamUser = 'DELETE_UAM_USER';

        // Uam > Role
    case CreateUamRole = 'CREATE_UAM_ROLE';
    case ReadUamRole = 'READ_UAM_ROLE';
    case UpdateUamRole = 'UPDATE_UAM_ROLE';
    case DeleteUamRole = 'DELETE_UAM_ROLE';

        // Uam > Permission
    case CreateUamPermission = 'CREATE_UAM_PERMISSION';
    case ReadUamPermission = 'READ_UAM_PERMISSION';
    case UpdateUamPermission = 'UPDATE_UAM_PERMISSION';
    case DeleteUamPermission = 'DELETE_UAM_PERMISSION';

    /**
     * Employee Module
     */
        // Employee > Employees
    case CreateEmployeeEmployee = 'CREATE_EMPLOYEE_EMPLOYEE';
    case ReadEmployeeEmployee = 'READ_EMPLOYEE_EMPLOYEE';
    case UpdateEmployeeEmployee = 'UPDATE_EMPLOYEE_EMPLOYEE';
    case DeleteEmployeeEmployee = 'DELETE_EMPLOYEE_EMPLOYEE';

    /**
     * Payroll Module
     */
        // Payroll > Salary Head
    case CreatePayrollSalaryHead = 'CREATE_PAYROLL_SALARY_HEAD';
    case ReadPayrollSalaryHead = 'READ_PAYROLL_SALARY_HEAD';
    case UpdatePayrollSalaryHead = 'UPDATE_PAYROLL_SALARY_HEAD';
    case DeletePayrollSalaryHead = 'DELETE_PAYROLL_SALARY_HEAD';

        // Payroll > Salary Structure
    case CreatePayrollSalaryStructure = 'CREATE_PAYROLL_SALARY_STRUCTURE';
    case ReadPayrollSalaryStructure = 'READ_PAYROLL_SALARY_STRUCTURE';
    case UpdatePayrollSalaryStructure = 'UPDATE_PAYROLL_SALARY_STRUCTURE';
    case DeletePayrollSalaryStructure = 'DELETE_PAYROLL_SALARY_STRUCTURE';

        // Payroll > Employee Salary Profile
    case CreatePayrollEmployeeSalaryProfile = 'CREATE_PAYROLL_EMPLOYEE_SALARY_PROFILE';
    case ReadPayrollEmployeeSalaryProfile = 'READ_PAYROLL_EMPLOYEE_SALARY_PROFILE';
    case UpdatePayrollEmployeeSalaryProfile = 'UPDATE_PAYROLL_EMPLOYEE_SALARY_PROFILE';
    case DeletePayrollEmployeeSalaryProfile = 'DELETE_PAYROLL_EMPLOYEE_SALARY_PROFILE';

        // Payroll > Salary Disbursement Batch
    case ReadPayrollSalaryDisbursementBatch = 'READ_PAYROLL_SALARY_DISBURSEMENT_BATCH';
    case CreatePayrollSalaryDisbursementBatch = 'CREATE_PAYROLL_SALARY_DISBURSEMENT_BATCH';
    case UpdatePayrollSalaryDisbursementBatch = 'UPDATE_PAYROLL_SALARY_DISBURSEMENT_BATCH';
    case DeletePayrollSalaryDisbursementBatch = 'DELETE_PAYROLL_SALARY_DISBURSEMENT_BATCH';
    case ProcessPayrollSalaryDisbursementBatch = 'PROCESS_PAYROLL_SALARY_DISBURSEMENT_BATCH';
    case ApprovePayrollSalaryDisbursementBatch = 'APPROVE_PAYROLL_SALARY_DISBURSEMENT_BATCH';
    case DisbursePayrollSalaryDisbursementBatch = 'DISBURSE_PAYROLL_SALARY_DISBURSEMENT_BATCH';

    /**
     * Configuration Module
     */
        // Configuration > Company
    case CreateConfigurationCompany = 'CREATE_CONFIGURATION_COMPANY';
    case ReadConfigurationCompany = 'READ_CONFIGURATION_COMPANY';
    case UpdateConfigurationCompany = 'UPDATE_CONFIGURATION_COMPANY';
    case DeleteConfigurationCompany = 'DELETE_CONFIGURATION_COMPANY';

        // Configuration > Branch
    case CreateConfigurationBranch = 'CREATE_CONFIGURATION_BRANCH';
    case ReadConfigurationBranch = 'READ_CONFIGURATION_BRANCH';
    case UpdateConfigurationBranch = 'UPDATE_CONFIGURATION_BRANCH';
    case DeleteConfigurationBranch = 'DELETE_CONFIGURATION_BRANCH';

        // Configuration > Division
    case CreateConfigurationDivision = 'CREATE_CONFIGURATION_DIVISION';
    case ReadConfigurationDivision = 'READ_CONFIGURATION_DIVISION';
    case UpdateConfigurationDivision = 'UPDATE_CONFIGURATION_DIVISION';
    case DeleteConfigurationDivision = 'DELETE_CONFIGURATION_DIVISION';

        // Configuration > Department
    case CreateConfigurationDepartment = 'CREATE_CONFIGURATION_DEPARTMENT';
    case ReadConfigurationDepartment = 'READ_CONFIGURATION_DEPARTMENT';
    case UpdateConfigurationDepartment = 'UPDATE_CONFIGURATION_DEPARTMENT';
    case DeleteConfigurationDepartment = 'DELETE_CONFIGURATION_DEPARTMENT';

    // Configuration > Unit
    case CreateConfigurationUnit = 'CREATE_CONFIGURATION_UNIT';
    case ReadConfigurationUnit = 'READ_CONFIGURATION_UNIT';
    case UpdateConfigurationUnit = 'UPDATE_CONFIGURATION_UNIT';
    case DeleteConfigurationUnit = 'DELETE_CONFIGURATION_UNIT';

        // Configuration > Designation
    case CreateConfigurationDesignation = 'CREATE_CONFIGURATION_DESIGNATION';
    case ReadConfigurationDesignation = 'READ_CONFIGURATION_DESIGNATION';
    case UpdateConfigurationDesignation = 'UPDATE_CONFIGURATION_DESIGNATION';
    case DeleteConfigurationDesignation = 'DELETE_CONFIGURATION_DESIGNATION';

        // Configuration > Position
    case CreateConfigurationPosition = 'CREATE_CONFIGURATION_POSITION';
    case ReadConfigurationPosition = 'READ_CONFIGURATION_POSITION';
    case UpdateConfigurationPosition = 'UPDATE_CONFIGURATION_POSITION';
    case DeleteConfigurationPosition = 'DELETE_CONFIGURATION_POSITION';

        // Configuration > Position Group
    case CreateConfigurationPositionGroup = 'CREATE_CONFIGURATION_POSITION_GROUP';
    case ReadConfigurationPositionGroup = 'READ_CONFIGURATION_POSITION_GROUP';
    case UpdateConfigurationPositionGroup = 'UPDATE_CONFIGURATION_POSITION_GROUP';
    case DeleteConfigurationPositionGroup = 'DELETE_CONFIGURATION_POSITION_GROUP';

        // Configuration > Hrbp
    case CreateConfigurationHrbp = 'CREATE_CONFIGURATION_HRBP';
    case ReadConfigurationHrbp = 'READ_CONFIGURATION_HRBP';
    case UpdateConfigurationHrbp = 'UPDATE_CONFIGURATION_HRBP';
    case DeleteConfigurationHrbp = 'DELETE_CONFIGURATION_HRBP';

        // Configuration > Shift
    case CreateConfigurationShift = 'CREATE_CONFIGURATION_SHIFT';
    case ReadConfigurationShift = 'READ_CONFIGURATION_SHIFT';
    case UpdateConfigurationShift = 'UPDATE_CONFIGURATION_SHIFT';
    case DeleteConfigurationShift = 'DELETE_CONFIGURATION_SHIFT';

        // Configuration > Approval Level
    case CreateConfigurationApprovalLevel = 'CREATE_CONFIGURATION_APPROVAL_LEVEL';
    case ReadConfigurationApprovalLevel = 'READ_CONFIGURATION_APPROVAL_LEVEL';
    case UpdateConfigurationApprovalLevel = 'UPDATE_CONFIGURATION_APPROVAL_LEVEL';
    case DeleteConfigurationApprovalLevel = 'DELETE_CONFIGURATION_APPROVAL_LEVEL';

        // Configuration > Approval Flow
    case CreateConfigurationApprovalFlow = 'CREATE_CONFIGURATION_APPROVAL_FLOW';
    case ReadConfigurationApprovalFlow = 'READ_CONFIGURATION_APPROVAL_FLOW';
    case UpdateConfigurationApprovalFlow = 'UPDATE_CONFIGURATION_APPROVAL_FLOW';
    case DeleteConfigurationApprovalFlow = 'DELETE_CONFIGURATION_APPROVAL_FLOW';

    /**
     * Product Module
     */
        // Product > Category
    case CreateProductCategory = 'CREATE_PRODUCT_CATEGORY';
    case ReadProductCategory = 'READ_PRODUCT_CATEGORY';
    case UpdateProductCategory = 'UPDATE_PRODUCT_CATEGORY';
    case DeleteProductCategory = 'DELETE_PRODUCT_CATEGORY';

        // Product > Brand
    case CreateProductBrand = 'CREATE_PRODUCT_BRAND';
    case ReadProductBrand = 'READ_PRODUCT_BRAND';
    case UpdateProductBrand = 'UPDATE_PRODUCT_BRAND';
    case DeleteProductBrand = 'DELETE_PRODUCT_BRAND';

        // Product > Unit
    case CreateProductUnit = 'CREATE_PRODUCT_UNIT';
    case ReadProductUnit = 'READ_PRODUCT_UNIT';
    case UpdateProductUnit = 'UPDATE_PRODUCT_UNIT';
    case DeleteProductUnit = 'DELETE_PRODUCT_UNIT';

        // Product > Product
    case CreateProductProduct = 'CREATE_PRODUCT_PRODUCT';
    case ReadProductProduct = 'READ_PRODUCT_PRODUCT';
    case UpdateProductProduct = 'UPDATE_PRODUCT_PRODUCT';
    case DeleteProductProduct = 'DELETE_PRODUCT_PRODUCT';
}
