@extends('layouts.admin')
@section('title', 'Project')
@section('page1', 'Project')
@section('page2', 'Project Detail')

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible text-white" role="alert">
            <span class="text-sm">{{ $message }}</span>
            <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible text-white" role="alert">
            <span class="text-sm">{{ $message }}</span>
            <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table">
                            <div class="row">
                                <div class="col-6 align-items-center">
                                    <h2 class="mb-0">Detail Project</h2>
                                </div>
                                <div class="col-6 text-end align-items-center">
                                    <a class="btn bg-gradient-warning mb-0" href="/project"><i class="material-icons text-sm">arrow_back</i>&nbsp;&nbsp;Back</a>
                                </div>
                            </div>

                            <div class="row">   
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label">Project Name</label>
                                            <input type="text" id="" class="form-control" value="{{ $project->project_name }}" disabled readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label">Project Type</label>
                                            <input type="text" id="" class="form-control" value="{{ $project->template->template_type }}" disabled readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label">Server Deployed</label>
                                            <input type="text" id="" class="form-control" value="{{ $project->server->server_ip }}" disabled readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label">Docker User</label>
                                            <input type="text" id="" class="form-control" value="{{ $project->docker->username}}" disabled readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label">Orign Pull Repo</label>
                                            <input type="text" id="" class="form-control" value="{{ $project->project_repo }}" disabled readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label">Date Time</label>
                                            <input type="text" id="" class="form-control" value="{{ $project->created_at }}" disabled readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Post Repository</label>
                                            <input type="text" id="" class="form-control" value="{{ $project->post_repo}}" disabled readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Jenkins User</label>
                                            <input type="text" id="" class="form-control" value="{{ $project->jenkins->username }}" disabled readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pb-4 pt-5">
                                    <div class="col-6 align-items-center">
                                        <h2 class="mb-0">Trigerr Jenkins</h2>
                                    </div>
                                </div>
                                <!-- <div class="row">
                                    @foreach($masterjobs as $masterjob)
                                        <div class="col-lg-4 mb-3">
                                            <form action="/build/{{ $masterjob->jobs_name }}/{{ $project->id }}" method="POST">
                                                @csrf
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-success w-100">
                                                        Trigger {{ $masterjob->jobs_name }}
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    @endforeach
                                </div> -->
                                <div class="row">
                                    @foreach($masterjobs as $masterjob)
                                        <div class="col-lg-4 mb-3">
                                            <form action="/build/jobs_jenkins/{{ $masterjob->id }}/{{ $project->id }}" method="POST">
                                                @csrf
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-success w-100">
                                                        Trigger {{ $masterjob->jobs_name }}
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row pb-4 pt-5">
                                    <div class="col-6 align-items-center">
                                        <h2 class="mb-0">List Jobs</h2>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    @if(isset($jobs))
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-lg font-weight-bolder ps-2">No.</th>
                                            <th class="text-uppercase text-secondary text-lg font-weight-bolder ps-2">Jobs</th>
                                            <th class="text-uppercase text-secondary text-lg font-weight-bolder ps-2">Project Name</th>
                                            <th class="text-uppercase text-secondary text-lg font-weight-bolder ps-2">Jenkins</th>
                                            <th class="text-uppercase text-secondary text-lg font-weight-bolder ps-2">Build Number</th>
                                            <th class="text-uppercase text-secondary text-lg font-weight-bolder ps-2">Build Time</th>
                                            <th class="text-uppercase text-secondary text-lg font-weight-bolder ps-2">DateTime</th>
                                            <th class="text-uppercase text-secondary text-lg font-weight-bolder ps-2">Status</th>
                                            <th colspan="2" class="text-uppercase text-secondary text-lg font-weight-bolder ps-2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($jobs as $jobss)
                                        <tr>
                                            <td><p class="text-md font-weight-normal mb-0">{{ $jobs->firstItem()+$loop->index }}</p></th>
                                            <td><p class="text-md font-weight-normal mb-0">{{ $jobss->masterjobs->jobs_name }}</p></td>
                                            <td><p class="text-md font-weight-normal mb-0">{{ $jobss->project->project_name }}</p></td>
                                            <td><p class="text-md font-weight-normal mb-0">{{ $jobss->jenkins->username }}</p></td>
                                            <td><p class="text-md font-weight-normal mb-0">{{ $jobss->build_number }}</p></td>
                                            <td><p class="text-md font-weight-normal mb-0">{{ $jobss->build_time }} sec</p></td>
                                            <td><p class="text-md font-weight-normal mb-0">{{ $jobss->created_at }}</p></td>
                                            <td>
                                                <span class="badge badge-dot me-4">
                                                    @if($jobss->status=='null')
                                                        <i class="bg-danger"></i>
                                                    @else
                                                        <i class="bg-success"></i>
                                                    @endif
                                                    <span class="text-dark text-xs">{{ $jobss->status }}</span>
                                                </span>
                                            </td>
                                            <!-- <td><p class="text-md font-weight-normal mb-0">{{ $jobss->status }}</p></td> -->
                                            <td class="align-middle text-center">
                                                <div class="d-flex align-items-center">
                                                    <a href="/get_build_console/{{ $jobss->id }}/{{ $project->id }}" class="m-1 btn bg-gradient-info"><i class="material-icons text-sm me-2">visibility</i>View Logs</a>
                                                    <a class="btn bg-gradient-warning mb-0" href="/project/{{ $project->id }}/show"><i class="material-icons text-sm">refresh</i>&nbsp;&nbsp;Check</a>
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- @if($jobss->status == 'process')
                                            <script>
                                                function checkStatus_{{ $jobss->id }}() {
                                                    $.ajax({
                                                        url: '/check_job_status/{{ $jobss->id }}',
                                                        type: 'GET',
                                                        success: function(response) {
                                                            console.log(response);
                                                            if (response.status !== 'process') {
                                                                clearInterval(interval_{{ $jobss->id }});
                                                                location.reload();
                                                                
                                                            }
                                                        }
                                                    });
                                                var interval_{{ $jobss->id }} = setInterval(checkStatus_{{ $jobss->id }}, 5000);
                                            </script>
                                        @endif -->
                                        @endforeach
                                    </tbody>
                                    @endif
                                </table>
                            </div>
                            {{ $jobs->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- <script>
function checkJobStatus(jobId) {
    setInterval(function() {
        $.ajax({
            url: '/check_job_status/' + jobId,
            type: 'GET',
            success: function(response) {
                if (response.status !== 'process') {
                    // If status changes, update the specific job status on the page
                    $('#job_status_' + jobId).text(response.status);
                }
            }
        });
    }, 5000); // Check status every 5 seconds
}
</script> -->
@endsection