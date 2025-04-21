<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\JobApplication;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Bus\Queueable;
class ProcessJobApplication implements ShouldQueue
{
    use Queueable, Dispatchable;
    
    protected $applicationId;
    protected $resumePath;
    protected $coverLetterPath;

    public function __construct($applicationId, $resumePath, $coverLetterPath)
    {
        $this->applicationId = $applicationId;
        $this->resumePath = $resumePath;
        $this->coverLetterPath = $coverLetterPath;
    }

    public function handle(): void
    {
        $application = JobApplication::find($this->applicationId);

        if (!$application) return;

        // ✅ ننقل الملفات من tmp → public
        $finalResumePath = Storage::disk('public')->putFile('resumes', storage_path("app/{$this->resumePath}"));
        $finalCoverPath  = Storage::disk('public')->putFile('cover_letters', storage_path("app/{$this->coverLetterPath}"));

        $application->update([
            'resume_path' => $finalResumePath,
            'cover_letter_path' => $finalCoverPath
        ]);
    }
}
