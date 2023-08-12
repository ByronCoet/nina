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
        // \App\Models\Company::factory(3)->create();
        // \App\Models\User::factory(30)->create();        
        $this->call(RoleSeeder::class);
        \App\Models\Company::factory(3)
            ->has(\App\Models\User::factory(5))            
            ->has(\App\Models\Campaign::factory(3))            
            ->create();

            /*        
            $table->id();
            $table->foreignId('company_id')->constrained('companies');
            $table->string('name');
            $table->string('surname');
            $table->string('mobile');
            $table->string('role')->default('user');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('consent')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            */

        $user = \App\Models\User::create(
            [
                'company_id'        => 1,
                'name'              => 'Byron',
                'surname'           => 'Coetzee',
                'mobile'            => '071625793',                
                'role'              => 'super',
                'email'             => 'byroncoetzee@hotmail.com',
                'consent'           => 0,
                'password'          => Hash::make('password'),
                'email_verified_at' => '2022-09-21 12:01:06',
                'created_at'        => Carbon::now()->toDateTimeString(),
                'updated_at'        => Carbon::now()->toDateTimeString()
            ]
        );
        //assign role
        // $user->assignRole('Administrator');
    }
}
