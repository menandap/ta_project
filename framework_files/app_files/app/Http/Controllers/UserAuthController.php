<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Redirect;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
use App\Models\User;
use App\Models\Docker;
use App\Models\Jenkins;
use App\Models\Jobs;
use App\Models\MasterJobs;
use App\Models\Project;
use App\Models\Server;
use App\Models\Template;
use Illuminate\Support\Facades\Artisan;

use Auth;

class UserAuthController extends Controller
{
    // controller
    public function checkStatus($id){
        $jobStatus = Jobs::where('id_project', $id)->value('status');
        
        return response()->json(['status' => $jobStatus]);
    }

    // public function pipiline($project_id, $job_1, $job_2, $job_3) {
    //     // Call get_latest_jobs method to retrieve build information
    //     $latestBuildResponse = $this->get_latest_jobs(0, $project_id);
    //     // Decode the JSON response to an associative array
    //     $latestBuild = $latestBuildResponse->original;
    //     // Access the 'BuildNumber' value from the returned JSON
    //     $buildNumber = $latestBuild['BuildNumber'] ?? null;

    //     $project = Project::with('server', 'template', 'docker', 'user','jenkins')
    //     ->where('id', '=', $project_id)
    //     ->first();

    //     $jobs = MasterJobs::where('id', '=', 0)
    //     ->first();

    //     $job_name = $jobs->jobs_name;
    //     $token = $jobs->jobs_token;
        
    //     $project_type = $project->template->template_type; 
    //     $project_name = $project->project_name;
    //     $project_repo = $project->template->template_repo;
    //     $repo_pull = $project->project_repo;
    
    //     $server_ip = $project->server->server_ip;  
    //     $username = $project->server->username; 
    //     $password = $project->server->password;

    //     $docker_username = $project->docker->username; 
    //     $docker_password = $project->docker->password; 

    //     $jenkins_user_url = $project->jenkins->jenkins_url;

    //     if ($jobs_id == 1){
    //        // add later
    //     } elseif($jobs_id == 2){
    //         $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/buildWithParameters?token={$token}&project_name={$project_name}&project_type={$project_type}&project_repo={$project_repo}&repo_pull={$repo_pull}&server_ip={$server_ip}&username={$username}&password={$password}";
    //     } elseif($jobs_id == 3){
    //         $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/buildWithParameters?token={$token}&project_name={$project_name}&project_type={$project_type}&project_repo={$project_repo}&server_ip={$server_ip}&username={$username}&password={$password}";
    //     } elseif($jobs_id == 4){
    //         $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/buildWithParameters?token={$token}&project_name={$project_name}&server_ip={$server_ip}&username={$username}&password={$password}";
    //     } elseif($jobs_id == 5){
    //         $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/buildWithParameters?token={$token}&project_name={$project_name}&server_ip={$server_ip}&username={$username}&password={$password}";
    //     } elseif($jobs_id == 6){
    //         $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/buildWithParameters?token={$token}&project_name={$project_name}&server_ip={$server_ip}&username={$username}&password={$password}&docker_username={$docker_username}&docker_password={$docker_password}";
    //     } elseif($jobs_id == 7){
    //         $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/buildWithParameters?token={$token}&project_name={$project_name}&server_ip={$server_ip}&username={$username}&password={$password}&docker_username={$docker_username}&docker_password={$docker_password}&project_type={$project_type}&project_repo={$project_repo}";
    //     }else {
    //         return Redirect::to("/project/$project_id/show")->with(['error' => 'something error happend']);
    //     }

    //     $jenkinsUsername = $project->jenkins->username;  
    //     $jenkinsApiToken = $project->jenkins->token; 
    //     $response = Http::withBasicAuth($jenkinsUsername, $jenkinsApiToken)->post($jenkins_url);

    //     if ($response->status() == 201) {
    //         $now = Carbon::now()->addHours(8);

    //         $build_number = is_numeric($buildNumber) ? $buildNumber + 1 : 1;
            
    //         Jobs::create([
    //             'id_jobs' => $jobs_id, 
    //             'id_project' => $project->id,
    //             'id_jenkins' => $project->jenkins->id,
    //             'id_user' => 0,
    //             'build_number' =>  $build_number,
    //             'build_time' => 0, //need to edit more
    //             'status' => 'process',
    //             'created_at' => $now, 
    //             'updated_at' => $now, 
    //         ]);

    //         return "Jenkins job triggered successfully";

    //     } else {
    //         return "Failed to trigger Jenkins job";
    //     }
        
    // }

    public function get_build_jobs($jobs_id, $project_id, $build_id)
    {
        $project = Project::with('server', 'template', 'docker', 'user','jenkins')
        ->where('id', '=', $project_id)
        ->first();

        $jobs = MasterJobs::where('id', '=', $jobs_id)
            ->first();

        $job_name = $jobs->jobs_name;
        $token = $jobs->jobs_token;
        $jenkins_user_url = $project->jenkins->jenkins_url;

        $jenkinsUsername = $project->jenkins->username;  
        $jenkinsApiToken = $project->jenkins->token; 

        $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/{$build_id}/api/json";

        $response = Http::withBasicAuth($jenkinsUsername, $jenkinsApiToken)->get($jenkins_url);

        if ($response->successful()) {
            $buildInfo = $response->json();

            // Extract relevant build information
            $buildNumber = $buildInfo['number'] ?? null;
            $buildResult = $buildInfo['result'] ?? null;
            $buildTimestamp = $buildInfo['timestamp'] / 1000 ?? null; // Convert milliseconds to seconds
            $buildDuration = $buildInfo['duration'] / 1000 ?? null; // Convert milliseconds to seconds

            // Convert timestamp to a human-readable date
            $timestampDate = $buildTimestamp ? Carbon::createFromTimestamp($buildTimestamp)->toDateTimeString() : null;

            // // Create a response containing build information
            // $latestBuild = [
            //     'BuildNumber' => $buildNumber,
            //     'Result' => $buildResult,
            //     'Timestamp' => $timestampDate,
            //     'Duration' => $buildDuration
            //     // Add more fields as needed
            // ];
            return $buildResult; // Return build information array
        } else {
            return null; // Return null if failed to fetch build information
        }
    }

    public function get_latest_jobs($jobs_id, $project_id)
    {
        $project = Project::with('server', 'template', 'docker', 'user','jenkins')
            ->where('id', '=', $project_id)
            ->first();

        $jobs = MasterJobs::where('id', '=', $jobs_id)
            ->first();

        $job_name = $jobs->jobs_name;
        $token = $jobs->jobs_token;
        $jenkins_user_url = $project->jenkins->jenkins_url;

        $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/lastBuild/api/json";

        $jenkinsUsername = $project->jenkins->username;  
        $jenkinsApiToken = $project->jenkins->token; 

        $response = Http::withBasicAuth($jenkinsUsername, $jenkinsApiToken)->get($jenkins_url);

        if ($response->status() == 200) {
            $buildInfo = $response->json();

            // Extract relevant build information
            $buildNumber = $buildInfo['number'];
            $buildResult = $buildInfo['result'];
            $buildTimestamp = $buildInfo['timestamp'] / 1000; // Convert milliseconds to seconds
            $buildDuration = $buildInfo['duration'] / 1000; // Convert milliseconds to seconds

            // Convert timestamp to a human-readable date
            $timestampDate = Carbon::createFromTimestamp($buildTimestamp)->toDateTimeString();

            // // Create a response containing build information
            // $latestBuild = [
            //     'BuildNumber' => $buildNumber,
            //     'Result' => $buildResult,
            //     'Timestamp' => $timestampDate,
            //     'Duration' => $buildDuration
            //     // Add more fields as needed
            // ];
            return  $buildNumber;
            // return response()->json($latestBuild); // Return build information in JSON format
        } else {
            // return response()->json(['error' => 'Failed to fetch build information']);
            return null;
        }
    }

    public function jobs_jenkins($jobs_id, $project_id) {
    // Call get_latest_jobs method to retrieve build information
    $latestBuildResponse = $this->get_latest_jobs($jobs_id, $project_id);

    if (is_object($latestBuildResponse) && property_exists($latestBuildResponse, 'original')) {
        // Assuming $latestBuildResponse is an HTTP response object
        $latestBuild = $latestBuildResponse->original;
        // Access the 'BuildNumber' value from the returned JSON
        $buildNumber = $latestBuild['BuildNumber'] ?? null;
    } else {
        // Handle the case where the response might be different than expected
        // For example, if it's just the decoded JSON data without the 'original' property
        // In this scenario, $latestBuildResponse directly contains the response data
        $latestBuild = $latestBuildResponse;
        $buildNumber = $latestBuild['BuildNumber'] ?? null;
    }
        

        $project = Project::with('server', 'template', 'docker', 'user','jenkins')
        ->where('id', '=', $project_id)
        ->first();

        $jobs = MasterJobs::where('id', '=', $jobs_id)
        ->first();

        $job_name = $jobs->jobs_name;
        $token = $jobs->jobs_token;
        
        $project_type = $project->template->template_type; 
        $project_name = $project->project_name;
        $project_repo = $project->template->template_repo;
        $repo_pull = $project->project_repo;
    
        $server_ip = $project->server->server_ip;  
        $username = $project->server->username; 
        $password = $project->server->password;

        $docker_username = $project->docker->username; 
        $docker_password = $project->docker->password; 

        $jenkins_user_url = $project->jenkins->jenkins_url;

        if ($jobs_id == 1){
           // add later
        } elseif($jobs_id == 2){
            $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/buildWithParameters?token={$token}&project_name={$project_name}&project_type={$project_type}&project_repo={$project_repo}&repo_pull={$repo_pull}&server_ip={$server_ip}&username={$username}&password={$password}";
        } elseif($jobs_id == 3){
            $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/buildWithParameters?token={$token}&project_name={$project_name}&project_type={$project_type}&project_repo={$project_repo}&server_ip={$server_ip}&username={$username}&password={$password}";
        } elseif($jobs_id == 4){
            $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/buildWithParameters?token={$token}&project_name={$project_name}&server_ip={$server_ip}&username={$username}&password={$password}";
        } elseif($jobs_id == 5){
            $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/buildWithParameters?token={$token}&project_name={$project_name}&server_ip={$server_ip}&username={$username}&password={$password}";
        } elseif($jobs_id == 6){
            $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/buildWithParameters?token={$token}&project_name={$project_name}&server_ip={$server_ip}&username={$username}&password={$password}&docker_username={$docker_username}&docker_password={$docker_password}";
        } elseif($jobs_id == 7){
            $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/buildWithParameters?token={$token}&project_name={$project_name}&server_ip={$server_ip}&username={$username}&password={$password}&docker_username={$docker_username}&docker_password={$docker_password}&project_type={$project_type}&project_repo={$project_repo}";
        }else {
            return Redirect::to("/project/$project_id/show")->with(['error' => 'something error happend']);
        }

        $jenkinsUsername = $project->jenkins->username;  
        $jenkinsApiToken = $project->jenkins->token; 
        $response = Http::withBasicAuth($jenkinsUsername, $jenkinsApiToken)->post($jenkins_url);

        if ($response->status() == 201) {
            $now = Carbon::now()->addHours(8);

            $build_number = is_numeric($buildNumber) ? $buildNumber + 1 : 1;
            
            Jobs::create([
                'id_jobs' => $jobs_id, 
                'id_project' => $project->id,
                'id_jenkins' => $project->jenkins->id,
                'id_user' => 0,
                'build_number' =>  $build_number,
                'build_time' => 0, //need to edit more
                'status' => 'process',
                'created_at' => $now, 
                'updated_at' => $now, 
            ]);

            return "Jenkins job triggered successfully";

        } else {
            return "Failed to trigger Jenkins job";
        }
        
    }

    // public function build_pull_repo() {
    //     $jobName = "build_image_docker";
    //     $token = "build_image_token";
    
    //     $projectName = "flask_app_pull"; // Define project name
    
    //     $dockerUsername = "menandap"; // Define your variable values here
    //     $dockerPassword = "Anandap_19"; // Define your variable values here
    
    //     $serverIp = "34.83.180.44";  // Define your variable values here
    //     $username = "anandaprema185"; // Define your variable values here
    //     $password = "Nanda123"; // Define your variable values here
    
    //     $jenkinsUrl = "http://104.198.106.107:8080/job/{$jobName}/buildWithParameters?token={$token}&project_name={$projectName}&server_ip={$serverIp}&username={$username}&password={$password}&docker_username={$dockerUsername}&docker_password={$dockerPassword}";
    
    //     $jenkinsUsername = 'jenkins';  // Your Jenkins username
    //     $jenkinsApiToken = '11ac3b030cdaca24605f27c073fc10727a';  // Your Jenkins API token
    
    //     $response = Http::withBasicAuth($jenkinsUsername, $jenkinsApiToken)->post($jenkinsUrl);
    
    //     if ($response->status() == 201) {
    //         return "Jenkins job triggered successfully";
    //     } else {
    //         return "Failed to trigger Jenkins job";
    //     }
    // }

    public function actLogin(Request $request)
    {
        // if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        //     return redirect()->route('dashboard');
        // }

        if(!Auth::attempt($request->only('username', 'password'), $request->filled('remember'))){

            return back()->with(['error' => 'invalid username or password']);
        }

        return redirect()->route('mydashboard');
    }

    public function actRegister(Request $request)
    {
        $register = new User();
        $register->name = $request->name;
        $register->username = $request->username;
        $register->password = bcrypt($request->password);
        $register->save();

        return redirect()->route('login')->with('success', 'Registration successful!');
    }


    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
