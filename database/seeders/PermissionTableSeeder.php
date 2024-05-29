<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            //For roll and permission
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',

            //For Role and permission
            'role-and-permission-list',

            //For Resource
            'resource-list',

            //For User
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',

            //For Team
            'team-list',
            'team-create',
            'team-edit',
            'team-delete',

            //For About
            'about-list',
            'about-create',
            'about-edit',
            'about-delete',

            //For Slider
            'slider-list',
            'slider-create',
            'slider-edit',
            'slider-delete',


            //For News
            'news-list',
            'news-create',
            'news-edit',
            'news-delete',

            //Site Setting
            'site-setting',

            //Dashboard
            'login-log-list',


        ];
        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
            }
        }
    }
}
