<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRegisterRequest;
use App\Http\Requests\CompanyLoginRequest;

use Illuminate\Support\Facades\Hash;

use App\Models\Company;
use App\Traits\HandlesPassportToken;

class AuthController extends Controller
{
    
    use HandlesPassportToken;

    public function register(CompanyRegisterRequest $request)
    {
        try {
             Company::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'industry' => $request->industry,
                'website'  => $request->website,
                'location' => $request->location,
                'logo'     => null // ممكن تتعامل معاه بالـ media أو upload لاحقًا
            ]);

            $result = $this->issueAccessToken($request->email, $request->password, 'companies');

            if (!$result['success']) {
                return response()->json([
                    'status' => false,
                    'message' => $result['message'],
                    'errors' => $result['errors'] ?? null,
                ], $result['status']);
            }

            return response()->json([
                'status' => true,
                'message' => 'Company registered and logged in successfully.',
                'data' => [
                    'token' => $result['token']
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong during company registration.'
            ], 500);
        }
    }

    public function login(CompanyLoginRequest $request)
    {
        try {
            $company = Company::where('email', $request->email)->first();

            if (!$company || !Hash::check($request->password, $company->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid credentials.',
                ], 401);
            }

            $result = $this->issueAccessToken($request->email, $request->password, 'companies');

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
                'message' => 'Something went wrong during company login.'
            ], 500);
        }
    }
}
