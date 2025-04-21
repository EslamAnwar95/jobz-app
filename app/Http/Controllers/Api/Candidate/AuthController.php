<?php

namespace App\Http\Controllers\Api\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CandidateRegisterRequest;
use App\Http\Requests\CandidateLoginRequest;
use App\Http\Resources\CandidateResource;

use Illuminate\Support\Facades\Log;
use App\Traits\HandlesPassportToken;
// C:\laragon\www\jobz-app\app\Traits\HandlePassportToken.php
class AuthController extends Controller
{

    use HandlesPassportToken;

    public function register(CandidateRegisterRequest $request)
    {
        try {
            // Step 1: Create the candidate
         Candidate::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $result = $this->issueAccessToken($request->email, $request->password, 'candidates');

            if (!$result['success']) {
                return response()->json([
                    'status' => false,
                    'message' => $result['message'],
                    'errors' => $result['errors'] ?? null,
                ], $result['status']);
            }

            return response()->json([
                'status' => true,
                'message' => 'Candidate registered and logged in successfully.',
                'data' => [
                    'token' => $result['token']
                ]
            ], 201);
        } catch (\Exception $e) {           

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong during registration.'
            ], 500);
        }
    }


    public function login(CandidateLoginRequest $request)
    {
        try {

            $candidate = Candidate::where('email', $request->email)->first();

            if (!$candidate || !Hash::check($request->password, $candidate->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid credentials.',
                ], 401);
            }
            $result = $this->issueAccessToken($request->email, $request->password, 'candidates');

            if (!$result['success']) {
                return response()->json([
                    'status' => false,
                    'message' => $result['message'],
                    'errors' => $result['errors'] ?? null
                ], $result['status']);
            }

            return response()->json([
                'status' => true,
                'message' => 'Login successful.',
                'data' => $result['token']
            ]);
        } catch (\Exception $e) {          
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong during login.'
            ], 500);
        }
    }
}
