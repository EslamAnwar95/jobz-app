<?php

namespace App\Http\Controllers\Api\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\JobApplicationRequest;
use App\Jobs\ProcessJobApplication;
use App\Traits\HandlesQueuedFileUpload;

class JobApplicationController extends Controller
{
    use HandlesQueuedFileUpload;
    public function apply(JobApplicationRequest $request, $jobId)
    {
        try {
            $candidateId = auth('candidate_api')->id();

            $job = Job::find($jobId);
            if (!$job) {
                return response()->json([
                    'status' => false,
                    'message' => 'Job not found.'
                ], 404);
            }
       
            $alreadyApplied = JobApplication::where('candidate_id', $candidateId)
                ->where('job_post_id', $jobId)
                ->exists();
              
            if ($alreadyApplied) {
                return response()->json([
                    'status' => false,
                    'message' => 'You have already applied for this job.'
                ], 409);
            }

            // Store the application in the database
       
        $application = JobApplication::create([
            'candidate_id' => $candidateId,
            'job_post_id' => $jobId,
            'cover_letter' => null,
            'resume_path' => null, 
        ]);

      
        $resumePath = $this->moveToPublicTemp($request->file('resume'), 'resumes/tmp');
        $coverLetterPath = $this->moveToPublicTemp($request->file('cover_letter'), 'cover_letters/tmp');

        ProcessJobApplication::dispatch($application->id, $resumePath, $coverLetterPath);

        
        return response()->json([
            'status' => true,
            'message' => 'Application submitted. Resume will be processed shortly.',
            'data' => $application
        ], 202);
        } catch (\Exception $e) {


            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while applying for the job.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
