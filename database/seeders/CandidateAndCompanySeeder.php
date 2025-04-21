<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Candidate;
use App\Models\Company;

class CandidateAndCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ✅ Create a sample candidate
        Candidate::updateOrCreate([
            'email' => 'candidate@example.com',
        ], [
            'name' => 'John Candidate',
            'password' => Hash::make('secret123'),
        ]);

        // ✅ Create a sample company
        Company::updateOrCreate([
            'email' => 'company@example.com',
        ], [
            'name' => 'Tech Corp',
            'industry' => 'Software',
            'location' => 'Cairo',
            'website' => 'https://techcorp.test',
            'password' => Hash::make('secret123'),
        ]);
    }
}
