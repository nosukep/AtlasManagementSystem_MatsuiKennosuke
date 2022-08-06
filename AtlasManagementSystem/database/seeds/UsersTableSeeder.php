<?php

use Illuminate\Database\Seeder;
use App\Models\Users\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'over_name' => 'ユーザー',
            'under_name' => '一',
            'over_name_kana' => 'ユーザー',
            'under_name_kana' => 'イチ',
            'mail_address' => '1@atlas.com',
            'sex' => '1',
            'birth_day' => '2000-01-01',
            'role' => '4',
            'password' => Hash::make('user01'),
        ]);
    }
}
