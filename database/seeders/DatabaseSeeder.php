<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $c1 = \App\Models\Company::create( ['company_name' => "AICC"]);
        $c2 = \App\Models\Company::create( ['company_name' => "Company A"]);
        $c3 = \App\Models\company::create( ['company_name' => "Company B"]);
        $c4 = \App\Models\Company::create( ['company_name' => "Company C"]);

        $ca = \App\Models\Campaign::create( [
            'company_id' => $c1->id,
            'campaign_name' => "Campaign 1", 
            'campaign_start' => Carbon::create('2023', '08', '01'),
            'campaign_end' => Carbon::create('2023', '10', '30'),
        ]);
        $ca = \App\Models\Campaign::create( [
            'company_id' => $c1->id,
            'campaign_name' => "Campaign 2", 
            'campaign_start' => Carbon::create('2023', '08', '01'),
            'campaign_end' => Carbon::create('2023', '10', '30'),
        ]);

        $ca = \App\Models\Campaign::create( [
            'company_id' => $c1->id,
            'campaign_name' => "Campaign 3", 
            'campaign_start' => Carbon::create('2023', '08', '01'),
            'campaign_end' => Carbon::create('2023', '10', '30'),
        ]);

        $ca = \App\Models\Campaign::create( [
            'company_id' => $c2->id,
            'campaign_name' => "Campaign 1", 
            'campaign_start' => Carbon::create('2023', '08', '01'),
            'campaign_end' => Carbon::create('2023', '10', '30'),
        ]);
        $ca = \App\Models\Campaign::create( [
            'company_id' => $c2->id,
            'campaign_name' => "Campaign 2", 
            'campaign_start' => Carbon::create('2023', '08', '01'),
            'campaign_end' => Carbon::create('2023', '10', '30'),
        ]);
        $ca = \App\Models\Campaign::create( [
            'company_id' => $c2->id,
            'campaign_name' => "Campaign 3", 
            'campaign_start' => Carbon::create('2023', '08', '01'),
            'campaign_end' => Carbon::create('2023', '10', '30'),
        ]);

        $ca = \App\Models\Campaign::create( [
            'company_id' => $c3->id,
            'campaign_name' => "Campaign 1", 
            'campaign_start' => Carbon::create('2023', '08', '01'),
            'campaign_end' => Carbon::create('2023', '10', '30'),
        ]);
        $ca = \App\Models\Campaign::create( [
            'company_id' => $c3->id,
            'campaign_name' => "Campaign 2", 
            'campaign_start' => Carbon::create('2023', '08', '01'),
            'campaign_end' => Carbon::create('2023', '10', '30'),
        ]);
        $ca = \App\Models\Campaign::create( [
            'company_id' => $c3->id,
            'campaign_name' => "Campaign 3", 
            'campaign_start' => Carbon::create('2023', '08', '01'),
            'campaign_end' => Carbon::create('2023', '10', '30'),
        ]);

        // $c1->users()->save(\App\Models\User::factory()->count(5)->create());

        for ($i=0; $i < 5 ; $i++) { 
            
            $u = \App\Models\User::factory()->state(['company_id' => $c1->id])->create();
            $u = \App\Models\User::factory()->state(['company_id' => $c2->id])->create();
            $u = \App\Models\User::factory()->state(['company_id' => $c3->id])->create();
            $u = \App\Models\User::factory()->state(['company_id' => $c4->id])->create();
            
        }
        

        // \App\Models\Company::factory(3)->create();
        // \App\Models\User::factory(30)->create();        
        /*
        $this->call(RoleSeeder::class);
        \App\Models\Company::factory(3)
            ->has(\App\Models\User::factory(5))            
            ->has(\App\Models\Campaign::factory(3))            
            ->create();           
            */

        $useradmin = \App\Models\User::create(
            [
                'company_id'        => 1,
                'name'              => 'Byron',
                'surname'           => 'Coetzee',
                'mobile'            => '071625793',                
                'role'              => 'superadmin',
                'email'             => 'byroncoetzee@hotmail.com',
                'consent'           => 0,
                'password'          => Hash::make('password'),
                'email_verified_at' => '2022-09-21 12:01:06',
                'created_at'        => Carbon::now()->toDateTimeString(),
                'updated_at'        => Carbon::now()->toDateTimeString()
            ]
        );

        $companyadmin = \App\Models\User::create(
            [
                'company_id'        => $c1->id,
                'name'              => 'Company',
                'surname'           => 'Admin',
                'mobile'            => '071625791',                
                'role'              => 'companyadmin',
                'email'             => 'companyadmin@web-guy.net',
                'consent'           => 0,
                'password'          => Hash::make('password'),
                'email_verified_at' => '2022-09-21 12:01:06',
                'created_at'        => Carbon::now()->toDateTimeString(),
                'updated_at'        => Carbon::now()->toDateTimeString()
            ]
        );

        $receptionist = \App\Models\User::create(
            [
                'company_id'        => $c1->id,
                'name'              => 'Alice',
                'surname'           => 'Receptionist',
                'mobile'            => '071625793',                
                'role'              => 'receptionist',
                'email'             => 'receptionist@web-guy.net',
                'consent'           => 0,
                'password'          => Hash::make('password'),
                'email_verified_at' => '2022-09-21 12:01:06',
                'created_at'        => Carbon::now()->toDateTimeString(),
                'updated_at'        => Carbon::now()->toDateTimeString()
            ]
        );

        $point = \App\Models\Point::create(
            [
                'point_name'        => 'Donated',
                'points'            => 3
            ]
        );

        $point = \App\Models\Point::create(
            [
                'point_name'        => 'Converted',
                'points'            => 1
            ]
        );

        $point = \App\Models\Point::create(
            [
                'point_name'        => 'Supported',
                'points'            => 1
            ]
        );

        //assign role
        // $user->assignRole('Administrator');
    }
}
