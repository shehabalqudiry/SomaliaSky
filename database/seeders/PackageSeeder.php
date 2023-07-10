<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Package;
use App\Models\Subscription;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $package = Package::create([
            'title'                 =>"الباقة المجانية",
            'description'           =>"الباقة المجانية والمحدودة بعدد معين من الاعلانات",
            'price'                 => 0,
            'announcement_number'   => 10,
            'time'                  => 365,
        ]);


        foreach (User::where('power', 'USER')->get() as $user) {
            Subscription::create([
                'user_id'           => $user->id,
                'package_id'        => $package->id,
                'price'             => 0,
                'status'            => 1,
                'paid'              => 1,
                'start_date'        => now(),
                'end_date'          => now()->addDays($package->time),
            ]);
        }


    }
}
