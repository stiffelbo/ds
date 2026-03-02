<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagesSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $pages = [
            [
                'name' => 'users',
                'endpoint' => '/users',
                'frontend_url' => '/users', // <-- zdecyduj: systemowe czy edytowalne
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'pages',
                'endpoint' => '/pages',
                'frontend_url' => '/pages',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // 1) Upsert: wstaw brakujące, a istniejącym aktualizuj tylko pola systemowe
        DB::table('pages')->upsert(
            $pages,
            ['name'],                 // klucz unikalny
            ['endpoint', 'frontend_url', 'updated_at'] // tylko te pola nadpisujemy
        );
    }
}
