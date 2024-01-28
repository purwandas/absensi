<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;

class GetUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:updates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Updates from Same Scaffolding Applications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $total = count($this->getFiles());
        $failed = 0;
        $success = 0;

        $project = $this->ask('Merge from projects? (name)');

        $originPath = base_path('../'.$project);

        if(!is_dir($originPath)){
            $this->error('Project path was invalid, please check again');
            return;
        }

        foreach ($this->getFiles() as $fileKey => $filePath) {
            $fileOrigin = $originPath.'/'.$filePath;
            $fileDestination = base_path($filePath);

            $this->line('COPYING '.$fileOrigin.' to '.$fileDestination);

            try{
                copy($fileOrigin, $fileDestination);

                $success += 1;
                $this->info('DONE');
            }catch(\Exception $e){
                $failed += 1;
                $this->error('FAILED');
            }

            $this->info('');
        }

        $this->info('');
        $this->info('Project has been updated!');
        $this->line('==========================');
        $this->line('TOTAL : '. $total);
        $this->info('SUCCESS : '. $success);
        $this->error('FAILED : '. $failed);
    }

    // GET FILES MASTER
    public function getFiles()
    {
        $files = [];

        // FILTERS
        $files = array_merge($files, [
            'RoleFilter' => 'app/Components/Filters/ACL/RoleFilter.php',
            'QueryFilters' => 'app/Components/Filters/QueryFilters.php',
        ]);

        // HELPERS
        $files = array_merge($files, [
            'AccessHelper' => 'app/Components/Helpers/AccessHelper.php',
            'DatatableBuilderHelper' => 'app/Components/Helpers/DatatableBuilderHelper.php',
            'FormBuilderHelper' => 'app/Components/Helpers/FormBuilderHelper.php',
            'GlobalHelper' => 'app/Components/Helpers/GlobalHelper.php',
            'ResponseHelper' => 'app/Components/Helpers/ResponseHelper.php',
        ]);

        // EXCEPTIONS
        $files = array_merge($files, [
            'Handler' => 'app/Exceptions/Handler.php',
        ]);

        // EXPORTS
        $files = array_merge($files, [
            'BaseExport' => 'app/Exports/BaseExport.php',
        ]);

        // CONTROLLERS - ROOT
        $files = array_merge($files, [
            'BaseController' => 'app/Http/Controllers/BaseController.php',
            'JobTraceController' => 'app/Http/Controllers/JobTraceController.php',
            // 'ProfileController' => 'app/Http/Controllers/ProfileController.php',
        ]);

        // CONTROLLERS - SETTING
        // $files = array_merge($files, [
        //     'SettingController' => 'app/Http/Controllers/Setting/SettingController.php',
        // ]);

        // CONTROLLERS - ACL
        $files = array_merge($files, [
            'MenuController' => 'app/Http/Controllers/ACL/MenuController.php',
            'PermissionController' => 'app/Http/Controllers/ACL/PermissionController.php',
            'RoleController' => 'app/Http/Controllers/ACL/RoleController.php',
            // 'UserController' => 'app/Http/Controllers/ACL/UserController.php',
        ]);

        // CONTROLLERS - AUTH
        $files = array_merge($files, [
            'AuthenticatedSessionController' => 'app/Http/Controllers/Auth/AuthenticatedSessionController.php',
            'ConfirmablePasswordController' => 'app/Http/Controllers/Auth/ConfirmablePasswordController.php',
            'EmailVerificationNotificationController' => 'app/Http/Controllers/Auth/EmailVerificationNotificationController.php',
            'EmailVerificationPromptController' => 'app/Http/Controllers/Auth/EmailVerificationPromptController.php',
            'MobileAuthenticationController' => 'app/Http/Controllers/Auth/MobileAuthenticationController.php',
            'PasswordController' => 'app/Http/Controllers/Auth/PasswordController.php',
            'PasswordResetLinkController' => 'app/Http/Controllers/Auth/PasswordResetLinkController.php',
            'RegisteredUserController' => 'app/Http/Controllers/Auth/RegisteredUserController.php',
            'VerifyEmailController' => 'app/Http/Controllers/Auth/VerifyEmailController.php',
        ]);

        // MIDDLEWARE
        $files = array_merge($files, [
            'ACL' => 'app/Http/Middleware/ACL.php',
            'Master' => 'app/Http/Middleware/Master.php',
        ]);

        // JOBS
        $files = array_merge($files, [
            'BaseJob' => 'app/Jobs/BaseJob.php',
        ]);

        // MODELS - ROOT
        $files = array_merge($files, [
            'BaseModel' => 'app/Models/BaseModel.php',
            'JobTrace' => 'app/Models/JobTrace.php',
        ]);

        // MODELS - ACL
        $files = array_merge($files, [
            'AccountActivationToken' => 'app/Models/ACL/AccountActivationToken.php',
            'Menu' => 'app/Models/ACL/Menu.php',
            'Permission' => 'app/Models/ACL/Permission.php',
            'Role' => 'app/Models/ACL/Role.php',
            'RolePermission' => 'app/Models/ACL/RolePermission.php',
            // 'User' => 'app/Models/ACL/User',
        ]);

        // JS
        $files = array_merge($files, [
            // 'custom.js' => 'public/js/custom.js',
            'JS:helper.js' => 'public/js/helper.js',
            'JS:variables.js' => 'public/js/variables.js',
        ]);

        // VIEWS - ACL
        $files = array_merge($files, [
            'VIEW:ACL:MENU:index' => 'resources/views/acl/menu/index.blade.php',
            'VIEW:ACL:MENU:modal_form' => 'resources/views/acl/menu/modal_form.blade.php',

            'VIEW:ACL:PERMISSION:index' => 'resources/views/acl/permission/index.blade.php',
            'VIEW:ACL:PERMISSION:MODAL:modal_discover' => 'resources/views/acl/permission/modals/modal_discover.blade.php',
            'VIEW:ACL:PERMISSION:MODAL:modal_form' => 'resources/views/acl/permission/modals/modal_form.blade.php',
            'VIEW:ACL:PERMISSION:MODAL:modal_group' => 'resources/views/acl/permission/modals/modal_group.blade.php',

            'VIEW:ACL:ROLE:index' => 'resources/views/acl/role/index.blade.php',
            'VIEW:ACL:ROLE:modal_form' => 'resources/views/acl/role/modal_form.blade.php',
        ]);

        // VIEWS - AUTH
        $files = array_merge($files, [
            'VIEW:AUTH:confirm-password' => 'resources/views/auth/confirm-password.blade.php',
            'VIEW:AUTH:forgot-password' => 'resources/views/auth/forgot-password.blade.php',
            'VIEW:AUTH:layout' => 'resources/views/auth/layout.blade.php',
            'VIEW:AUTH:login' => 'resources/views/auth/login.blade.php',
            'VIEW:AUTH:register' => 'resources/views/auth/register.blade.php',
            'VIEW:AUTH:reset-password' => 'resources/views/auth/reset-password.blade.php',
            'VIEW:AUTH:verify-email' => 'resources/views/auth/verify-email.blade.php',
        ]);

        // VIEWS - COMPONENTS - METRONIC
        $files = array_merge($files, [
            'VIEW:COMPONENTS:METRONIC:action-group' => 'resources/views/components/metronic/action-group.blade.php',
            'VIEW:COMPONENTS:METRONIC:ajax-form' => 'resources/views/components/metronic/ajax-form.blade.php',
            'VIEW:COMPONENTS:METRONIC:aside-quick-search' => 'resources/views/components/metronic/aside-quick-search.blade.php',
            'VIEW:COMPONENTS:METRONIC:aside-settings' => 'resources/views/components/metronic/aside-settings.blade.php',
            'VIEW:COMPONENTS:METRONIC:breadcrumb' => 'resources/views/components/metronic/breadcrumb.blade.php',
            'VIEW:COMPONENTS:METRONIC:card' => 'resources/views/components/metronic/card.blade.php',
            'VIEW:COMPONENTS:METRONIC:drawer' => 'resources/views/components/metronic/drawer.blade.php',
            'VIEW:COMPONENTS:METRONIC:filter' => 'resources/views/components/metronic/filter.blade.php',
            'VIEW:COMPONENTS:METRONIC:head-meta' => 'resources/views/components/metronic/head-meta.blade.php',
            'VIEW:COMPONENTS:METRONIC:javascripts' => 'resources/views/components/metronic/javascripts.blade.php',
            'VIEW:COMPONENTS:METRONIC:loader' => 'resources/views/components/metronic/loader.blade.php',
            'VIEW:COMPONENTS:METRONIC:menu' => 'resources/views/components/metronic/menu.blade.php',
            'VIEW:COMPONENTS:METRONIC:modal-ajax-form' => 'resources/views/components/metronic/modal-ajax-form.blade.php',
            'VIEW:COMPONENTS:METRONIC:modal-export' => 'resources/views/components/metronic/modal-export.blade.php',
            'VIEW:COMPONENTS:METRONIC:modal-import' => 'resources/views/components/metronic/modal-import.blade.php',
            'VIEW:COMPONENTS:METRONIC:modal-job-trace' => 'resources/views/components/metronic/modal-job-trace.blade.php',
            'VIEW:COMPONENTS:METRONIC:quick-filter' => 'resources/views/components/metronic/quick-filter.blade.php',
            'VIEW:COMPONENTS:METRONIC:scroll-top' => 'resources/views/components/metronic/scroll-top.blade.php',
            'VIEW:COMPONENTS:METRONIC:theme-script' => 'resources/views/components/metronic/theme-script.blade.php',
            'VIEW:COMPONENTS:METRONIC:toastr' => 'resources/views/components/metronic/toastr.blade.php',
        ]);

        // VIEWS - COMPONENTS - VENDOR - DATATABLE BUILDER
        $files = array_merge($files, [
            'VIEW:COMPONENTS:VENDOR:DATATABLE-BUILDER:index' => 'resources/views/components/vendor/datatable-builder/index.blade.php',
        ]);

        // VIEWS - COMPONENTS - VENDOR - FORM BUILDER
        $files = array_merge($files, [
            'VIEW:COMPONENTS:VENDOR:FORM-BUILDER:colorpicker_input' => 'resources/views/components/vendor/form-builder/colorpicker_input.blade.php',
            'VIEW:COMPONENTS:VENDOR:FORM-BUILDER:date_input' => 'resources/views/components/vendor/form-builder/date_input.blade.php',
            'VIEW:COMPONENTS:VENDOR:FORM-BUILDER:email_input' => 'resources/views/components/vendor/form-builder/email_input.blade.php',
            'VIEW:COMPONENTS:VENDOR:FORM-BUILDER:file_input' => 'resources/views/components/vendor/form-builder/file_input.blade.php',
            'VIEW:COMPONENTS:VENDOR:FORM-BUILDER:multiple_column_input' => 'resources/views/components/vendor/form-builder/multiple_column_input.blade.php',
            'VIEW:COMPONENTS:VENDOR:FORM-BUILDER:multiple_input' => 'resources/views/components/vendor/form-builder/multiple_input.blade.php',
            'VIEW:COMPONENTS:VENDOR:FORM-BUILDER:number_input' => 'resources/views/components/vendor/form-builder/number_input.blade.php',
            'VIEW:COMPONENTS:VENDOR:FORM-BUILDER:password_input' => 'resources/views/components/vendor/form-builder/password_input.blade.php',
            'VIEW:COMPONENTS:VENDOR:FORM-BUILDER:radio_input' => 'resources/views/components/vendor/form-builder/radio_input.blade.php',
            'VIEW:COMPONENTS:VENDOR:FORM-BUILDER:select2_input' => 'resources/views/components/vendor/form-builder/select2_input.blade.php',
            'VIEW:COMPONENTS:VENDOR:FORM-BUILDER:select2_multiple_input' => 'resources/views/components/vendor/form-builder/select2_multiple_input.blade.php',
            'VIEW:COMPONENTS:VENDOR:FORM-BUILDER:select_input' => 'resources/views/components/vendor/form-builder/select_input.blade.php',
            'VIEW:COMPONENTS:VENDOR:FORM-BUILDER:switch_input' => 'resources/views/components/vendor/form-builder/switch_input.blade.php',
            'VIEW:COMPONENTS:VENDOR:FORM-BUILDER:text_input' => 'resources/views/components/vendor/form-builder/text_input.blade.php',
            'VIEW:COMPONENTS:VENDOR:FORM-BUILDER:textarea_input' => 'resources/views/components/vendor/form-builder/textarea_input.blade.php',
        ]);

        // VIEWS - EMAIL
        $files = array_merge($files, [
            'VIEW:EMAIL:account-activation' => 'resources/views/email/account-activation.blade.php',
            'VIEW:EMAIL:reset-password' => 'resources/views/email/reset-password.blade.php',
        ]);

        // VIEWS - ERRORS
        $files = array_merge($files, [
            'VIEW:ERRORS:404' => 'resources/views/errors/404.blade.php',
            'VIEW:ERRORS:419' => 'resources/views/errors/419.blade.php',
            'VIEW:ERRORS:422' => 'resources/views/errors/422.blade.php',
        ]);

        // VIEWS - LAYOUTS
        $files = array_merge($files, [
            'VIEW:LAYOUTS:TEMPLATES:METRONIC:admin' => 'resources/views/layouts/templates/metronic/admin.blade.php',
            'VIEW:LAYOUTS:TEMPLATES:METRONIC:base' => 'resources/views/layouts/templates/metronic/base.blade.php',
            'VIEW:LAYOUTS:TEMPLATES:metronic' => 'resources/views/layouts/templates/metronic.blade.php',
        ]);

        // VIEWS - SETTING
        $files = array_merge($files, [
            // 'VIEW:SETTING:profile' => 'resources/views/setting/profile.blade.php',
            'VIEW:SETTING:setting' => 'resources/views/setting/setting.blade.php',
        ]);

        // ROUTES - ACL
        $files = array_merge($files, [
            'ROUTES:ACL:menu' => 'routes/modules/acl/menu.php',
            'ROUTES:ACL:permission' => 'routes/modules/acl/permission.php',
            'ROUTES:ACL:role' => 'routes/modules/acl/role.php',
            'ROUTES:ACL:user' => 'routes/modules/acl/user.php',
        ]);

        // ROUTES - SETTING
        $files = array_merge($files, [
            'ROUTES:SETTING:profile' => 'routes/modules/setting/profile.php',
            'ROUTES:SETTING:setting' => 'routes/modules/setting/setting.php',
        ]);

        // ROUTES - UTILITIES
        $files = array_merge($files, [
            'ROUTES:UTILITIES:job_trace' => 'routes/modules/utilities/job_trace.php',
        ]);

        return $files;
    }
}
