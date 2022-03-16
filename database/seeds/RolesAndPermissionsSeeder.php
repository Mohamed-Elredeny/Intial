<?php
/**
 * Created by PhpStorm.
 * Developer: Tariq Ayman ( tariq.ayman94@gmail.com )
 * Date: 1/24/20, 3:12 PM
 * Last Modified: 1/23/20, 10:53 PM
 * Project Name: Wafaq
 * File Name: RolesAndPermissionsSeeder.php
 */

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::where('id','!=','0')->delete();

        // create permissions
        // admins
        Permission::create(['name' => 'admins.manage']);
        Permission::create(['name' => 'admins.add' ]);
        Permission::create(['name' => 'admins.edit']);
        Permission::create(['name' => 'admins.delete']);

        // newsletters
        Permission::create(['name' => 'newsletters.manage']);
        Permission::create(['name' => 'newsletters.add' ]);
        Permission::create(['name' => 'newsletters.edit']);
        Permission::create(['name' => 'newsletters.delete']);

        // contacts
        Permission::create(['name' => 'contacts.manage']);
        Permission::create(['name' => 'contacts.delete']);


        // subscribers
        Permission::create(['name' => 'subscribers.manage']);
        Permission::create(['name' => 'subscribers.delete']);

        // bank transfers
        Permission::create(['name' => 'bankTransfers.manage']);
        Permission::create(['name' => 'bankTransfers.edit']);



        // permissions
        Permission::create(['name' => 'permissions.manage']);
        Permission::create(['name' => 'permissions.add' ]);
        Permission::create(['name' => 'permissions.edit']);
        Permission::create(['name' => 'permissions.delete']);


        // roles
        Permission::create(['name' => 'roles.manage']);
        Permission::create(['name' => 'roles.add' ]);
        Permission::create(['name' => 'roles.edit']);
        Permission::create(['name' => 'roles.delete']);


        // sliders
        Permission::create(['name' => 'sliders.manage']);
        Permission::create(['name' => 'sliders.add' ]);
        Permission::create(['name' => 'sliders.edit']);
        Permission::create(['name' => 'sliders.delete']);

        // banks
        Permission::create(['name' => 'banks.manage']);
        Permission::create(['name' => 'banks.add' ]);
        Permission::create(['name' => 'banks.edit']);
        Permission::create(['name' => 'banks.delete']);

        // commissions
        Permission::create(['name' => 'commissions.manage']);
        Permission::create(['name' => 'commissions.add' ]);
        Permission::create(['name' => 'commissions.edit']);
        Permission::create(['name' => 'commissions.delete']);

        // taxs
        Permission::create(['name' => 'taxs.manage']);
        Permission::create(['name' => 'taxs.add' ]);
        Permission::create(['name' => 'taxs.edit']);
        Permission::create(['name' => 'taxs.delete']);

        // bank transfers
        Permission::create(['name' => 'bank_transfers.manage']);
        Permission::create(['name' => 'bank_transfers.add' ]);
        Permission::create(['name' => 'bank_transfers.edit']);
        Permission::create(['name' => 'bank_transfers.delete']);

        // job application
        Permission::create(['name' => 'job_applications.manage']);
        Permission::create(['name' => 'job_applications.add' ]);
        Permission::create(['name' => 'job_applications.edit']);
        Permission::create(['name' => 'job_applications.delete']);

        // invoices
        Permission::create(['name' => 'invoices.manage']);
        Permission::create(['name' => 'invoices.add' ]);
        Permission::create(['name' => 'invoices.edit']);
        Permission::create(['name' => 'invoices.delete']);

        // pages
        Permission::create(['name' => 'pages.manage']);
        Permission::create(['name' => 'pages.add' ]);
        Permission::create(['name' => 'pages.edit']);
        Permission::create(['name' => 'pages.delete']);

        // posts
        Permission::create(['name' => 'posts.manage']);
        Permission::create(['name' => 'posts.add' ]);
        Permission::create(['name' => 'posts.edit']);
        Permission::create(['name' => 'posts.delete']);

        // partners
        Permission::create(['name' => 'partners.manage']);
        Permission::create(['name' => 'partners.add' ]);
        Permission::create(['name' => 'partners.edit']);
        Permission::create(['name' => 'partners.delete']);

        // partners
        Permission::create(['name' => 'circles.manage']);
        Permission::create(['name' => 'circles.add' ]);
        Permission::create(['name' => 'circles.edit']);
        Permission::create(['name' => 'circles.delete']);


        // users
        Permission::create(['name' => 'users.manage']);
        Permission::create(['name' => 'users.add' ]);
        Permission::create(['name' => 'users.edit']);
        Permission::create(['name' => 'users.delete']);


        // notifications
        Permission::create(['name' => 'notifications.manage']);
        Permission::create(['name' => 'notifications.add' ]);
        Permission::create(['name' => 'notifications.edit']);
        Permission::create(['name' => 'notifications.delete']);

        // questionnaires
        Permission::create(['name' => 'questionnaires.manage']);
        Permission::create(['name' => 'questionnaires.add' ]);
        Permission::create(['name' => 'questionnaires.edit']);
        Permission::create(['name' => 'questionnaires.delete']);


        // questionnaire_questions
        Permission::create(['name' => 'questionnaire_questions.manage']);
        Permission::create(['name' => 'questionnaire_questions.add' ]);
        Permission::create(['name' => 'questionnaire_questions.edit']);
        Permission::create(['name' => 'questionnaire_questions.delete']);

        // settings
        Permission::create(['name' => 'questionnaire_results.manage']);
        Permission::create(['name' => 'settings.delete']);


        Role::where('id','!=','0')->delete();

        $role = Role::create(['name' => 'super-admin']);

        $role->syncPermissions(Permission::all());
    }
}
