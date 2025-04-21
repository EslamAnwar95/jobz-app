<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\JobStoreRequest;
use App\Http\Requests\JobUpdateRequest;
use App\Models\Job;

class JobController extends Controller
{
    public function index()
    {
        try {
            $companyId = auth('company_api')->id();

            $jobs = Job::where('company_id', $companyId)
                ->latest()
                ->paginate(10);

            return response()->json([
                'status' => true,
                'message' => 'Jobs retrieved successfully.',
                'data' => $jobs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while fetching jobs.'
            ], 500);
        }
    }

   
    public function store(JobStoreRequest $request)
    {
        try {
            $job = Job::create([
                'title' => $request->title,
                'description' => $request->description,
                'location' => $request->location,
                'salary_range' => $request->salary_range,
                'is_remote' => $request->is_remote ?? false,
                'published_at' => $request->published_at,
                'company_id' => auth('company_api')->id(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Job created successfully.',
                'data' => $job,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while creating the job.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }


    public function show(int $id)
    {
        try {
            $job = Job::where('id', $id)
                ->where('company_id', auth('company_api')->id())
                ->first();

            if (!$job) {
                return response()->json([
                    'status' => false,
                    'message' => 'Job not found or access denied.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Job retrieved successfully.',
                'data' => $job
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while fetching the job.'
            ], 500);
        }
    }


    public function update(JobUpdateRequest $request, int $id)
    {
        try {
            $job = Job::where('id', $id)
                ->where('company_id', auth('company_api')->id())
                ->first();

            if (!$job) {
                return response()->json([
                    'status' => false,
                    'message' => 'Job not found or you do not have permission.'
                ], 404);
            }

            $job->update($request->only([
                'title',
                'description',
                'location',
                'salary_range',
                'is_remote',
                'published_at'
            ]));

            return response()->json([
                'status' => true,
                'message' => 'Job updated successfully.',
                'data' => $job
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while updating the job.'
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $job = Job::where('id', $id)
                ->where('company_id', auth('company_api')->id())
                ->first();

            if (!$job) {
                return response()->json([
                    'status' => false,
                    'message' => 'Job not found or access denied.'
                ], 404);
            }

            $job->delete();

            return response()->json([
                'status' => true,
                'message' => 'Job soft deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while deleting the job.'
            ], 500);
        }
    }
}
