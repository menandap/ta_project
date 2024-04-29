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
use Auth;
use Illuminate\Support\Facades\Artisan;

class UserDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function mydashboard(){
        $jobJenkins = Jenkins::count();
        $jobDocker = Docker::count();
        $jobServer = Server::count();
        $jobJobs = Jobs::count();
        $jobProject = Project::count();
        $jobTemplate = Template::count();
        $jobCount = MasterJobs::count();

        $newestJobs = Jobs::latest()->take(5)->get();
        $newestProject = Project::latest()->take(5)->get();
         
        $data = compact('jobJenkins', 'jobDocker', 'jobServer', 'jobJobs', 'jobProject', 'jobTemplate', 'jobCount');

         // Pass both $data and $newestJobs to the view
        return view('dashboard-usr.dashboard', [
            'data' => $data,
            'newestJobs' => $newestJobs,
            'newestProject' => $newestProject,
        ]);
    }

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

            // Create a response containing build information
            $latestBuild = [
                'BuildNumber' => $buildNumber,
                'Result' => $buildResult,
                'Timestamp' => $timestampDate,
                'Duration' => $buildDuration
                // Add more fields as needed
            ];
            return $latestBuild; // Return build information array
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

            // Create a response containing build information
            $latestBuild = [
                'BuildNumber' => $buildNumber,
                'Result' => $buildResult,
                'Timestamp' => $timestampDate,
                'Duration' => $buildDuration
                // Add more fields as needed
            ];

            return response()->json($latestBuild); // Return build information in JSON format
        } else {
            return response()->json(['error' => 'Failed to fetch build information']);
        }
    }

    public function get_build_console($jobs_id, $project_id)
    {
        $project = Project::with('server', 'template', 'docker', 'user','jenkins')
        ->where('id', '=', $project_id)
        ->first();

        $jobs = Jobs::where('id', '=', $jobs_id)
            ->first();
        
        $master_jobs = MasterJobs::where('id', '=', $jobs->id_jobs)
            ->first();
        
        $job_name = $master_jobs->jobs_name;

        $build_number = $jobs->build_number;
        $jenkins_user_url = $project->jenkins->jenkins_url;

        $jenkins_url = "{$jenkins_user_url}/job/{$master_jobs->jobs_name}/{$build_number}/consoleText";

        $jenkinsUsername = $project->jenkins->username;  
        $jenkinsApiToken = $project->jenkins->token; 

        $response = Http::withBasicAuth($jenkinsUsername, $jenkinsApiToken)->get($jenkins_url);

        if ($response->status() == 200) {
            $consoleText = $response->body();
            return view('dashboard-usr.consolejobs', compact('consoleText','project_id','job_name','build_number'));
        } else {
            return response()->json(['error' => 'Failed to fetch console text'], 500);
        }

        // return $jenkins_url;
    }

    public function jobs_jenkins($jobs_id, $project_id) {
        // Call get_latest_jobs method to retrieve build information
        $latestBuildResponse = $this->get_latest_jobs($jobs_id, $project_id);
        // Decode the JSON response to an associative array
        $latestBuild = $latestBuildResponse->original;
        // Access the 'BuildNumber' value from the returned JSON
        $buildNumber = $latestBuild['BuildNumber'] ?? null;

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

        $sonarqube = $project->sonarqube_token;
    
        $server_ip = $project->server->server_ip;  
        $username = $project->server->username; 
        $password = $project->server->password;

        $docker_username = $project->docker->username; 
        $docker_password = $project->docker->password; 

        $jenkins_user_url = $project->jenkins->jenkins_url;

        if ($jobs_id == 1){
            $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/buildWithParameters?token={$token}&project_name={$project_name}&server_ip={$server_ip}&username={$username}&password={$password}";
        } elseif($jobs_id == 2){
            $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/buildWithParameters?token={$token}&project_name={$project_name}&project_type={$project_type}&project_repo={$project_repo}&server_ip={$server_ip}&username={$username}&password={$password}";
        } elseif($jobs_id == 3){
            $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/buildWithParameters?token={$token}&project_name={$project_name}&project_type={$project_type}&project_repo={$project_repo}&repo_pull={$repo_pull}&server_ip={$server_ip}&username={$username}&password={$password}";
        } elseif($jobs_id == 4){
            $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/buildWithParameters?token={$token}&project_name={$project_name}&server_ip={$server_ip}&username={$username}&password={$password}";
        } elseif($jobs_id == 5){
            $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/buildWithParameters?token={$token}&project_name={$project_name}&server_ip={$server_ip}&username={$username}&password={$password}";
        } elseif($jobs_id == 6){
            $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/buildWithParameters?token={$token}&project_name={$project_name}&server_ip={$server_ip}&username={$username}&password={$password}";
        } elseif($jobs_id == 8){
            $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/buildWithParameters?token={$token}&project_name={$project_name}&server_ip={$server_ip}&username={$username}&password={$password}";
        } elseif($jobs_id == 9){
            $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/buildWithParameters?token={$token}&sonarqube={$sonarqube}&project_name={$project_name}&server_ip={$server_ip}&username={$username}&password={$password}";
        } elseif($jobs_id == 10){
            $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/buildWithParameters?token={$token}&project_name={$project_name}&server_ip={$server_ip}&username={$username}&password={$password}&docker_username={$docker_username}&docker_password={$docker_password}";
        } elseif($jobs_id == 11){
            $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/buildWithParameters?token={$token}&project_name={$project_name}&server_ip={$server_ip}&username={$username}&password={$password}";
        } elseif($jobs_id == 7){
            $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/buildWithParameters?token={$token}&project_name={$project_name}&server_ip={$server_ip}&username={$username}&password={$password}";
        } else {
            return Redirect::to("/project/$project_id/show")->with(['error' => 'something error happend']);
        }

        $jenkinsUsername = $project->jenkins->username;  
        $jenkinsApiToken = $project->jenkins->token; 
        $response = Http::withBasicAuth($jenkinsUsername, $jenkinsApiToken)->post($jenkins_url);

        if ($response->status() == 201) {
            $users = Auth::user()->id;
            $now = Carbon::now()->addHours(8);

            $build_number = is_numeric($buildNumber) ? $buildNumber + 1 : 1;
            
            Jobs::create([
                'id_jobs' => $jobs_id, 
                'id_project' => $project->id,
                'id_jenkins' => $project->jenkins->id,
                'id_user' => $users,
                'build_number' =>  $build_number,
                'build_time' => 0, //need to edit more
                'status' => 'process',
                'created_at' => $now, 
                'updated_at' => $now, 
            ]);

            return Redirect::to("/project/$project_id/show")->with(['success' => 'Jenkins ' . $job_name . ' triggered successfully']);

            // // Start an asynchronous task to retrieve Jenkins information
            // dispatch(function () use ($jobs_id, $project_id, $build_number) {
            //     $jobs = MasterJobs::where('id', '=', $jobs_id)
            //     ->first();
            //     $job_name = $jobs->jobs_name;

            //     $maxAttempts = 12; // 12 attempts * 5 seconds = 60 seconds (1 minute)
            //     $attempt = 1;
            //     $buildJobs = null;

            //     while ($attempt <= $maxAttempts) {
            //         // Get job information
            //         $buildJobs = $this->get_build_jobs($jobs_id, $project_id, $build_number);

            //         if ($buildJobs !== null) {
            //             // If job information is received, break the loop
            //             break;
            //         }

            //         sleep(5); // Wait for 5 seconds before next attempt
            //         $attempt++;
            //     }

            //     if ($buildJobs !== null) {
            //         $buildResult = $buildJobs['Result'];
            //         $duration = $buildJobs['Duration'];

            //         // Update Jobs model based on retrieved information
            //         Jobs::where('id', $jobs_id)->update([
            //             'status' => $buildResult,
            //             'build_time' => $duration,
            //             // Update other fields as needed
            //         ]);
            //         return Redirect::to("/project/$project_id/show")->with(['success' => 'Jenkins ' . $job_name . ' triggered successfully']);

            //     } else {
            //         // Handle the case where job information couldn't be retrieved within 1 minute
            //         return Redirect::to("/project/$project_id/show")->with(['error' => 'Failed to get more information on Jenkins (already 1 minute)' . $job_name]);
            //     }
            // })->afterResponse(); // Run this asynchronously after the response is sent

        } else {
            return Redirect::to("/project/$project_id/show")->with(['error' => 'Failed to trigger Jenkins ' . $job_name]);
        }
        
        // return $jenkins_url;
    }

    // // MY BUILD PROCESS
    // public function pull_project_template($id) {
    //     $project = Project::with('server', 'template', 'docker', 'user','jenkins')
    //         ->where('id', '=', $id)
    //         ->first();

    //     $job_name = "pull_project_template";
    //     $token = "pull_project_token";
        
    //     $project_type = $project->template->template_type; 
    //     $project_name = $project->project_name;
    //     $repo_pull = $project->project_repo;
    //     $project_repo = $project->template->template_repo;
    
    
    //     $server_ip = $project->server->server_ip;  
    //     $username = $project->server->username; 
    //     $password = $project->server->password;

    //     $jenkins_user_url = $project->jenkins->jenkins_url;

    //     $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/buildWithParameters?token={$token}&project_name={$project_name}&project_type={$project_type}&project_repo={$project_repo}&repo_pull={$repo_pull}&server_ip={$server_ip}&username={$username}&password={$password}";
    
    //     $jenkinsUsername = $project->jenkins->username;  
    //     $jenkinsApiToken = $project->jenkins->token; 
    
    //     $response = Http::withBasicAuth($jenkinsUsername, $jenkinsApiToken)->post($jenkins_url);

    //     $users = Auth::user()->id;
    //     $now = Carbon::now()->addHours(8); 

    //     Jobs::create([
    //         'id_jobs' => 2, //need to edit more
    //         'id_project' => $project->id,
    //         'id_jenkins' => $project->jenkins->id,
    //         'id_user' => $users,
    //         'build_number' => 0, //need to edit more
    //         'build_time' => 0, //need to edit more
    //         'status' => 'null',
    //         'created_at' => $now, 
    //         'updated_at' => $now, 
    //     ]);

    //     if ($response->status() == 201) {
    //         return Redirect::to("/project/$id/show")->with(['success' => 'Jenkins ' . $job_name . ' triggered successfully']);
    //     } else {
    //         return Redirect::to("/project/$id/show")->with(['success' => 'Failed to trigger Jenkins ' . $job_name]);
    //     }

    //     //    return $jenkins_url;
    // }

    // public function make_project_template($id) {
    //     $project = Project::with('server', 'template', 'docker', 'user','jenkins')
    //     ->where('id', '=', $id)
    //     ->first();

    // $job_name = "make_project_template";
    // $token = "make_project_token";
    
    // $project_type = $project->template->template_type; 
    // $project_name = $project->project_name; 
    // $project_repo = $project->template->template_repo;


    // $server_ip = $project->server->server_ip; 
    // $username = $project->server->username; 
    // $password = $project->server->password; 

    // $jenkins_user_url = $project->jenkins->jenkins_url;

    // $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/buildWithParameters?token={$token}&project_name={$project_name}&project_type={$project_type}&project_repo={$project_repo}&server_ip={$server_ip}&username={$username}&password={$password}";

    // $jenkinsUsername = $project->jenkins->username;  
    // $jenkinsApiToken = $project->jenkins->token;  

    // $response = Http::withBasicAuth($jenkinsUsername, $jenkinsApiToken)->post($jenkins_url);

    // $users = Auth::user()->id;
    // $now = Carbon::now()->addHours(8); 

    // Jobs::create([
    //     'id_jobs' => 3, //need to edit more
    //     'id_project' => $project->id,
    //     'id_jenkins' => $project->jenkins->id,
    //     'id_user' => $users,
    //     'build_number' => 0, //need to edit more
    //     'build_time' => 0, //need to edit more
    //     'status' => 'null',
    //     'created_at' => $now, 
    //     'updated_at' => $now, 
    // ]);

    // if ($response->status() == 201) {
    //     return Redirect::to("/project/$id/show")->with(['success' => 'Jenkins ' . $job_name . ' triggered successfully']);
    // } else {
    //     return Redirect::to("/project/$id/show")->with(['success' => 'Failed to trigger Jenkins ' . $job_name]);
    // }

    // }

    // public function build_image_docker($id) {
    //     $project = Project::with('server', 'template', 'docker', 'user','jenkins')
    //         ->where('id', '=', $id)
    //         ->first();

    //     $job_name = "build_image_docker";
    //     $token = "build_image_token";
    
    //     $project_name = $project->project_name; 
    
    //     $docker_username = $project->docker->username; 
    //     $docker_password = $project->docker->password; 
    
    //     $server_ip = $project->server->server_ip; 
    //     $username = $project->server->username; 
    //     $password = $project->server->password; 

    //     $jenkins_user_url = $project->jenkins->jenkins_url;
    
    //     $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/buildWithParameters?token={$token}&project_name={$project_name}&server_ip={$server_ip}&username={$username}&password={$password}&docker_username={$docker_username}&docker_password={$docker_password}";
    
    //     $jenkinsUsername = $project->jenkins->username;  
    //     $jenkinsApiToken = $project->jenkins->token;  
    
    //     $response = Http::withBasicAuth($jenkinsUsername, $jenkinsApiToken)->post($jenkins_url);

    //     $users = Auth::user()->id;
    //     $now = Carbon::now()->addHours(8); 
    
    //     if ($response->status() == 201) {
    //         Jobs::create([
    //             'id_jobs' => 6, //need to edit more
    //             'id_project' => $project->id,
    //             'id_jenkins' => $project->jenkins->id,
    //             'id_user' => $users,
    //             'build_number' => 0, //need to edit more
    //             'build_time' => 0, //need to edit more
    //             'status' => 'null',
    //             'created_at' => $now, 
    //             'updated_at' => $now, 
    //         ]);
    //         return Redirect::to("/project/$id/show")->with(['success' => 'Jenkins ' . $job_name . ' triggered successfully']);
    //     } else {
    //         return Redirect::to("/project/$id/show")->with(['success' => 'Failed to trigger Jenkins ' . $job_name]);
    //     }

    //     // return $jenkins_url;

    // }

    // public function deploy_image_docker($id) {
    //     $project = Project::with('server', 'template', 'docker', 'user','jenkins')
    //         ->where('id', '=', $id)
    //         ->first();

    //     $job_name = "deploy_image_docker";
    //     $token = "deploy_docker_token";
    
    //     $project_name = $project->project_name; 
    
    //     $server_ip = $project->server->server_ip; 
    //     $username = $project->server->username; 
    //     $password = $project->server->password; 

    //     $jenkins_user_url = $project->jenkins->jenkins_url;
    
    //     $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/buildWithParameters?token={$token}&project_name={$project_name}&server_ip={$server_ip}&username={$username}&password={$password}";
    
    //     $jenkinsUsername = $project->jenkins->username;  
    //     $jenkinsApiToken = $project->jenkins->token; 
    
    //     $response = Http::withBasicAuth($jenkinsUsername, $jenkinsApiToken)->post($jenkins_url);

    //     $users = Auth::user()->id;
    //     $now = Carbon::now()->addHours(8); 

    //     Jobs::create([
    //         'id_jobs' => 5, //need to edit more
    //         'id_project' => $project->id,
    //         'id_jenkins' => $project->jenkins->id,
    //         'id_user' => $users,
    //         'build_number' => 0, //need to edit more
    //         'build_time' => 0, //need to edit more
    //         'status' => 'null',
    //         'created_at' => $now, 
    //         'updated_at' => $now, 
    //     ]);
    
    //     if ($response->status() == 201) {
    //         return Redirect::to("/project/$id/show")->with(['success' => 'Jenkins ' . $job_name . ' triggered successfully']);
    //     } else {
    //         return Redirect::to("/project/$id/show")->with(['success' => 'Failed to trigger Jenkins ' . $job_name]);
    //     }
    // }

    // public function deploy_image_kubernetes($id) {
    //     $project = Project::with('server', 'template', 'docker', 'user','jenkins')
    //         ->where('id', '=', $id)
    //         ->first();

    //     $job_name = "deploy_image_kubernetes";
    //     $token = "deploy_kubernetes_token";
    
    //     $project_name = $project->project_name; 
    
    
    //     $server_ip = $project->server->server_ip; 
    //     $username = $project->server->username; 
    //     $password = $project->server->password; 

    //     $jenkins_user_url = $project->jenkins->jenkins_url;
    
    //     $jenkins_url = "{$jenkins_user_url}/job/{$job_name}/buildWithParameters?token={$token}&project_name={$project_name}&server_ip={$server_ip}&username={$username}&password={$password}";
    
    //     $jenkinsUsername = $project->jenkins->username;  
    //     $jenkinsApiToken = $project->jenkins->token; 
    
    //     $response = Http::withBasicAuth($jenkinsUsername, $jenkinsApiToken)->post($jenkins_url);

    //     $users = Auth::user()->id;
    //     $now = Carbon::now()->addHours(8); 

    //     Jobs::create([
    //         'id_jobs' => 4, //need to edit more
    //         'id_project' => $project->id,
    //         'id_jenkins' => $project->jenkins->id,
    //         'id_user' => $users,
    //         'build_number' => 0, //need to edit more
    //         'build_time' => 0, //need to edit more
    //         'status' => 'null',
    //         'created_at' => $now, 
    //         'updated_at' => $now, 
    //     ]);
    
    //     if ($response->status() == 201) {
    //         return Redirect::to("/project/$id/show")->with(['success' => 'Jenkins ' . $job_name . ' triggered successfully']);
    //     } else {
    //         return Redirect::to("/project/$id/show")->with(['success' => 'Failed to trigger Jenkins ' . $job_name]);
    //     }
    // }

    // JENKINS
    public function jenkins(){
        $jenkins = DB::table('tb_user_jenkins')
            ->select('tb_user_jenkins.*')->paginate(10);
        Paginator::useBootstrap();
        return view('dashboard-usr.jenkinslist', compact('jenkins'));
    }

    public function jenkins_show($id){
        $jenkins = Jenkins::where('id', '=', $id)->first();
        return view('dashboard-usr.jenkinsdetail', compact('jenkins'));
    }

    public function jenkins_create(){
    
        return view('dashboard-usr.jenkinsadd');
    }

    public function jenkins_store(Request $request){
        $jenkins = $request->all();
        Jenkins::create($jenkins);
        return Redirect::to('/jenkins')->with(['success' => 'Berhasil menambahkan jenkins']);
    }

    public function jenkins_edit($id){
        $users = Auth::user()->id;
        $jenkins = jenkins::find($id);
        return view('dashboard-usr.jenkinsedit', compact('jenkins'));
    }

    public function jenkins_update(Request $request, $id, jenkins $jenkins){
        $jenkins = Jenkins::find($id);
        // return $jenkins;
        $data = $request->all();
        $jenkins->fill($data)->save();
        return Redirect::to('/jenkins')->with(['success' => 'Berhasil mengedit jenkins']);
    }

    public function jenkins_delete($id){
        jenkins::where('id',$id)->delete();

        return Redirect::to('/jenkins')->with(['error' => 'Berhasil menghapus jenkins']);
    }

    // DOCKER
    public function docker(){
        $users = Auth::user()->id;
        $docker = DB::table('tb_user_docker')
            ->select('tb_user_docker.*')->paginate(10);
        Paginator::useBootstrap();
        return view('dashboard-usr.dockerlist', compact('docker'));
    }

    public function docker_show($id){
        $docker = Docker::where('id', '=', $id)->first();
        return view('dashboard-usr.dockerdetail', compact('docker'));
    }

    public function docker_create(){
       
        return view('dashboard-usr.dockeradd');
    }

    public function docker_store(Request $request){
        $docker = $request->all();
        Docker::create($docker);

        return Redirect::to('/docker')->with(['success' => 'Berhasil menambahkan docker']);
    }

    public function docker_edit($id){
        $users = Auth::user()->id;
        $docker = docker::find($id);
        return view('dashboard-usr.dockeredit', compact('docker'));
    }

    public function docker_update(Request $request, $id, docker $docker){

        $data = $request->all();
        $docker = docker::find($id);
        $docker->fill($data)->save();

        return Redirect::to('/docker')->with(['success' => 'Berhasil mengedit docker']);
    }

    public function docker_delete($id){
        Docker::where('id',$id)->delete();
        return Redirect::to('/docker')->with(['error' => 'Berhasil menghapus docker']);
    }

    // SERVER
    public function server(){
        $server = DB::table('tb_server')
            ->select('tb_server.*')->paginate(10);
        Paginator::useBootstrap();
        return view('dashboard-usr.serverlist', compact('server'));
    }

    public function server_show($id){
        $server = server::where('id', '=', $id)->first();
        return view('dashboard-usr.serverdetail', compact('server'));
    }

    public function server_create(){

        return view('dashboard-usr.serveradd');
    }

    public function server_store(Request $request){
        $server = $request->all();
        Server::create($server);
        return Redirect::to('/server')->with(['success' => 'Berhasil menambahkan server']);
    }

    public function server_edit($id){
        $users = Auth::user()->id;
        $server = server::find($id);
        return view('dashboard-usr.serveredit', compact('server'));
    }

    public function server_update(Request $request, $id, server $server){

        $data = $request->all();
        $server = server::find($id);
        $server->fill($data)->save();

        return Redirect::to('/server')->with(['success' => 'Berhasil mengedit server']);
    }

    public function server_delete($id){
        Server::where('id',$id)->delete();

        return Redirect::to('/server')->with(['error' => 'Berhasil menghapus server']);
    }

    // TEMPLATE
    public function template(){
        $template = DB::table('tb_template_project')
            ->select('tb_template_project.*')->paginate(10);
        Paginator::useBootstrap();
        return view('dashboard-usr.templatelist', compact('template'));
    }

    public function template_show($id){
        $template = template::where('id', '=', $id)->first();
        return view('dashboard-usr.templatedetail', compact('template'));
    }

    public function template_create(){
      
        return view('dashboard-usr.templateadd');
    }

    public function template_store(Request $request){
        $template = $request->all();
        Template::create($template);
        return Redirect::to('/template')->with(['success' => 'Berhasil menambahkan template']);
    }

    public function template_edit($id){
        $users = Auth::user()->id;
        $template = template::find($id);
        return view('dashboard-usr.templateedit', compact('template'));
    }

    public function template_update(Request $request, $id, template $template){

        $data = $request->all();
        $template = template::find($id);
        $template->fill($data)->save();

        return Redirect::to('/template')->with(['success' => 'Berhasil mengedit template']);
    }

    public function template_delete($id){
        Template::where('id',$id)->delete();
        return Redirect::to('/template')->with(['error' => 'Berhasil menghapus template']);
    }

    // MASTERJOBS
    public function masterjobs(){
        $masterjobs = DB::table('tb_master_jobs')
            ->select('tb_master_jobs.*')->paginate(10);
        Paginator::useBootstrap();
        return view('dashboard-usr.masterjobslist', compact('masterjobs'));
    }

    public function masterjobs_show($id){
        $masterjobs = masterjobs::where('id', '=', $id)->first();
        return view('dashboard-usr.masterjobsdetail', compact('masterjobs'));
    }

    public function masterjobs_create(){
      
        return view('dashboard-usr.masterjobsadd');
    }

    public function masterjobs_store(Request $request){
        $masterjobs = $request->all();
        Masterjobs::create($masterjobs);
        return Redirect::to('/masterjobs')->with(['success' => 'Berhasil menambahkan masterjobs']);
    }

    public function masterjobs_edit($id){
        $users = Auth::user()->id;
        $masterjobs = masterjobs::find($id);
        return view('dashboard-usr.masterjobsedit', compact('masterjobs'));
    }

    public function masterjobs_update(Request $request, $id, masterjobs $masterjobs){

        $data = $request->all();
        $masterjobs = masterjobs::find($id);
        $masterjobs->fill($data)->save();

        return Redirect::to('/masterjobs')->with(['success' => 'Berhasil mengedit masterjobs']);
    }

    public function masterjobs_delete($id){
        Masterjobs::where('id',$id)->delete();
        return Redirect::to('/masterjobs')->with(['error' => 'Berhasil menghapus masterjobs']);
    }

    // PROJECT
    public function project(){
        $users = Auth::user()->id;
        $project = Project::with('server','template','docker', 'user','jenkins')->paginate(10);
        Paginator::useBootstrap();
        return view('dashboard-usr.projectlist', compact('project'));
    }

    // public function project_show($id){
    //     $project = Project::with('server', 'template', 'docker', 'user','jenkins')
    //         ->where('id', '=', $id)
    //         ->first();
    //     $masterjobs = MasterJobs::get();
    //     $jobs = Jobs::with('masterjobs', 'user', 'jenkins', 'project')
    //         ->where('id_project', '=', $id)
    //         ->orderBy('id', 'desc')
    //         ->paginate(5);
    //     Paginator::useBootstrap();

    //     // Trigger the ProcessJobsCommand
    //     Artisan::call('jobs:process');
    //     return view('dashboard-usr.projectdetail', compact('project','masterjobs','jobs'));
    // }

    public function checkStatus($id) {
        $job = Job::findOrFail($id); // Fetch the job by ID
        return response()->json(['status' => $job->status]);
    }

    public function project_show($id)
    {
        $project = Project::with('server', 'template', 'docker', 'user','jenkins')
            ->where('id', '=', $id)
            ->first();
        $masterjobs = MasterJobs::get();
        $jobs = Jobs::with('masterjobs', 'user', 'jenkins', 'project')
            ->where('id_project', '=', $id)
            ->orderBy('id', 'desc')
            ->paginate(5);
        Paginator::useBootstrap();

        // Trigger the ProcessJobsCommand
        Artisan::call('jobs:process');

        return view('dashboard-usr.projectdetail', compact('project', 'masterjobs', 'jobs'));
    }
    

    public function project_create($type){
        $user = Auth::user()->id;
        if ($type === 'template') {
            $types = 1;
        } elseif ($type === 'repo') {
            $types = 2;
        }
        $jenkins = Jenkins::get();
        $template = Template::get();
        $server = Server::get();
        $docker = Docker::get();
        return view('dashboard-usr.projectadd', compact('types','server','template','docker','user','jenkins'));
    }

    public function project_store(Request $request){
        $project = $request->all();
        Project::create($project);
        // return $project;

        return Redirect::to('/project')->with(['success' => 'Berhasil menambahkan project']);
    }

    public function project_edit($id){
        $user = Auth::user()->id;
        $project = project::find($id);

        $jenkins = Jenkins::get();
        $template = Template::get();
        $server = Server::get();
        $docker = Docker::get();

        return view('dashboard-usr.projectedit', compact('project', 'server','template','docker','user','jenkins'));
    }

    public function project_update(Request $request, $id, project $project){

        $data = $request->all();
        $project = project::find($id);
        $project->fill($data)->save();

        return Redirect::to('/project')->with(['success' => 'Berhasil mengedit project']);
    }

    public function project_delete($id){
        project::where('id',$id)->delete();

        return Redirect::to('/project')->with(['error' => 'Berhasil menghapus project']);
    }

    // JOBS
    public function jobs(){
        $users = Auth::user()->id;
        $jobs = Jobs::with('masterjobs', 'user', 'jenkins', 'project')
            ->orderBy('id', 'desc')
            ->paginate(10);
        Paginator::useBootstrap();
        return view('dashboard-usr.jobslist', compact('jobs'));
    }

    public function jobs_show($id){
        $jobs = jobs::where('id', '=', $id)->first();
        return view('dashboard-usr.jobsdetail', compact('jobs'));
    }

    public function jobs_create(){
        $users = Auth::user()->id;
        $undangan = Undangan::where('id_user', '=', $users)->get();
        return view('dashboard-usr.jobsadd', compact('undangan'));
    }

    public function jobs_store(Request $request){
        $jobs = $request->all();
        jobs::create($jobs);

        return Redirect::to('/jobs')->with(['success' => 'Berhasil menambahkan jobs']);
    }

    public function jobs_edit($id){
        $users = Auth::user()->id;
        $jobs = jobs::find($id);

        return view('dashboard-usr.jobsedit', compact('jobs'));
    }

    public function jobs_update(Request $request, $id, jobs $jobs){

        $data = $request->all();
        $jobs = jobs::find($id);
        $jobs->fill($data)->save();

        return Redirect::to('/jobs')->with(['success' => 'Berhasil mengedit jobs']);
    }

    public function jobs_delete($id){
        jobs::where('id',$id)->delete();

        return Redirect::to('/jobs')->with(['error' => 'Berhasil menghapus jobs']);
    }
}
