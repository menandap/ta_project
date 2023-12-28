<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Jobs; 
use App\Models\Project;
use App\Models\MasterJobs;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ProcessJobsCommand extends Command
{
    protected $signature = 'jobs:process';

    protected $description = 'Process jobs and update status from Jenkins';

    public function handle()
    {
        $this->info('Before fetching jobs.');
        $jobs = Jobs::where('status', '=', 'process')->orderBy('id')->get();
        $this->info("After fetching jobs. Jobs count: {$jobs->count()}");

        foreach ($jobs as $job) {
            $buildInfo = null;
            $startTime = time();
        
            while (true) {
                // If more than 10 seconds have passed or we have the build result, break the loop
                if (time() - $startTime > 30 || ($buildInfo !== null && isset($buildInfo['Result']))) {
                    break;
                }
        
                $buildInfo = $this->get_build_jobs($job->id, $job->id_project, $job->build_number);
        
                if ($buildInfo === null || !isset($buildInfo['Result'])) {
                    sleep(5);
                }
            }
        
            if ($buildInfo !== null && isset($buildInfo['Result'])) {
                $job->status = $buildInfo['Result'];
                $job->build_time = $buildInfo['Duration'];
                $job->save();
                // $this->info("Job {$job->masterjobs->jobs_name} ({$job->build_number}) processed successfully.");
                // return Redirect::to("/project/$job->id_project/show")->with(['success' => 'Job ' . $job->masterjobs->jobs_name . '(' . $job->build_number . ')' . 'processed successfully.']);
            } else {
                // return response()->json(['error' => 'Build information is null or incomplete'], 400);
                // $this->info("Build information is null or incomplete for Job {$job->masterjobs->jobs_name} ({$job->build_number}). Moving to the next job.");
                // return Redirect::to("/project/$job->id_project/show")->with(['error' => 'Build information is null or incomplete for Job' . $job->masterjobs->jobs_name . '(' . $job->build_number . ')' ]);
            }
        }

        $this->info('All jobs processed successfully.');
    }

    private function get_build_jobs($jobs_id, $project_id, $build_id)
    {
        $project = Project::with('server', 'template', 'docker', 'user', 'jenkins')
            ->where('id', '=', $project_id)
            ->first();

        $jobs = Jobs::where('id', '=', $jobs_id)
            ->first();

        $master_jobs = MasterJobs::where('id', '=', $jobs->id_jobs)
            ->first();
        
        $this->info("Jobs ID : {$jobs_id}");
        $this->info("Jobs value : {$jobs}");
        $this->info("Master Jobs value : {$master_jobs}");

        if ($master_jobs !== null) {
            $job_name = $jobs->jobs_name;
            $token = $jobs->jobs_token;
            $jenkins_user_url = $project->jenkins->jenkins_url;

            $jenkinsUsername = $project->jenkins->username;
            $jenkinsApiToken = $project->jenkins->token;

            $jenkins_url = "{$jenkins_user_url}/job/{$master_jobs->jobs_name}/{$build_id}/api/json";

            // Add debug info to display Jenkins URL
            $this->info("Jenkins URL: {$jenkins_url}");

            // Log that we're trying to fetch build information
            $this->info("Fetching build information for Job ID: {$jobs_id}, Project ID: {$project_id}, Build ID: {$build_id}");

            $response = Http::withBasicAuth($jenkinsUsername, $jenkinsApiToken)->get($jenkins_url);

            if ($response->successful()) {
                $buildInfo = $response->json();

                // Check if the job is completed
                if ($buildInfo['result'] !== null) {
                    // Extract relevant build information
                    $buildNumber = $buildInfo['number'] ?? null;
                    $buildResult = $buildInfo['result'] ?? null;
                    $buildTimestamp = $buildInfo['timestamp'] / 1000 ?? null; // Convert milliseconds to seconds
                    $buildDuration = $buildInfo['duration'] / 1000 ?? null; // Convert milliseconds to seconds

                    // Convert timestamp to a human-readable date
                    $timestampDate = $buildTimestamp ? Carbon::createFromTimestamp($buildTimestamp)->toDateTimeString() : null;

                    // Create a response containing build information
                    $latestBuild = [
                        'BuildNumber' => $buildNumber,
                        'Result' => $buildResult,
                        'Timestamp' => $timestampDate,
                        'Duration' => $buildDuration
                        // Add more fields as needed
                    ];
                    return $latestBuild; // Return build information array
                }
            }
        }

        // Log that build information retrieval failed
        $this->info("Failed to fetch build information for Job ID: {$jobs_id}, Project ID: {$project_id}, Build ID: {$build_id}");
        return null; // Return null if job is not found or not completed
    }
  
}
