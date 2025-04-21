<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Client;
use Illuminate\Support\Str;

class PassportClientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            [
                'name' => 'Company Password Grant Client',
                'provider' => 'companies',
            ],
            [
                'name' => 'Candidate Password Grant Client',
                'provider' => 'candidates',
            ],
        ];

        foreach ($clients as $client) {
            $exists = DB::table('oauth_clients')
                ->where('provider', $client['provider'])
                ->where('password_client', true)
                ->first();

            if (!$exists) {
                Client::create([
                    'name' => $client['name'],
                    'secret' => Str::random(40),
                    'redirect' => 'http://localhost',
                    'personal_access_client' => false,
                    'password_client' => true,
                    'revoked' => false,
                    'provider' => $client['provider'],
                ]);
            }
        }
    }
}
