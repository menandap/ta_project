@extends('layouts.admin')
@section('title', 'Jobs')
@section('page1', 'Jobs')
@section('page2', 'Jobs List')

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
                                    <h2 class="mb-0">Jobs List</h2>
                                </div>
                                <!-- <div class="col-6 text-end align-items-center">
                                    <a class="btn bg-gradient-success mb-0" href="/jobs/create"><i class="material-icons text-sm">add</i>&nbsp;&nbsp;Add Jobs</a>
                                </div> -->
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-lg font-weight-bolder ps-2">No.</th>
                                            <th class="text-uppercase text-secondary text-lg font-weight-bolder ps-2">Jobs</th>
                                            <th class="text-uppercase text-secondary text-lg font-weight-bolder ps-2">Project Name</th>
                                            <!-- <th class="text-uppercase text-secondary text-lg font-weight-bolder ps-2">Jenkins</th> -->
                                            <th class="text-uppercase text-secondary text-lg font-weight-bolder ps-2">Build Number</th>
                                            <!-- <th class="text-uppercase text-secondary text-lg font-weight-bolder ps-2">Build Time</th>
                                            <th class="text-uppercase text-secondary text-lg font-weight-bolder ps-2">DateTime</th> -->
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
                                            <!-- <td><p class="text-md font-weight-normal mb-0">{{ $jobss->jenkins->username }}</p></td> -->
                                            <td><p class="text-md font-weight-normal mb-0">{{ $jobss->build_number }}</p></td>
                                            <!-- <td><p class="text-md font-weight-normal mb-0">{{ $jobss->build_time }} sec</p></td>
                                            <td><p class="text-md font-weight-normal mb-0">{{ $jobss->created_at }}</p></td> -->
                                            <td>
                                                <span class="badge badge-dot me-4">
                                                    @if($jobss->status=='success')
                                                        <i class="bg-success"></i>
                                                    @else
                                                        <i class="bg-danger"></i>
                                                    @endif
                                                    <span class="text-dark text-xs">{{ $jobss->status }}</span>
                                                </span>
                                            </td>
                                            <!-- <td><p class="text-md font-weight-normal mb-0">{{ $jobss->status }}</p></td> -->
                                            <td class="align-middle text-center">
                                                <div class="d-flex align-items-center">
                                                    <a href="jobs/{{$jobss->id}}/show" class="m-1 btn bg-gradient-info"><i class="material-icons text-sm me-2">visibility</i>View</a>
                                                    <a href="jobs/{{$jobss->id}}/delete" class="m-1 btn bg-gradient-danger" onclick="return confirm('Apa yakin ingin menghapus data ini?')"><i class="material-icons text-sm me-2">delete</i>Delete</a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $jobs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
