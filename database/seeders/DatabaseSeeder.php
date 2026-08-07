<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(KuaSettingSeeder::class);
        $this->call(NavbarItemSeeder::class);
        $this->call(LetterTypeSeeder::class);
        $this->call(MarriageServiceSeeder::class);
        $this->call(AnnouncementSeeder::class);
    }
}
