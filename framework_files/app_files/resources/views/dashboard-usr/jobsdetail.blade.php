@extends('layouts.admin')
@section('title', 'Jobs')
@section('page1', 'Jobs')
@section('page2', 'Jobs Detail')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table">
                            <div class="row">
                                <div class="col-6 align-items-center">
                                    <h2 class="mb-0">Detail Jobs</h2>
                                </div>
                                <div class="col-6 text-end align-items-center">
                                    <a class="btn bg-gradient-warning mb-0" href="/jobs"><i class="material-icons text-sm">arrow_back</i>&nbsp;&nbsp;Back</a>
                                </div>
                            </div>
                            <br>  
                            <div class="row">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label">Jobs</label>
                                            <input type="text" id="" class="form-control" value="{{ $jobs->masterjobs->jobs_name }}" disabled readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label">Project Name</label>
                                            <input type="text" id="" class="form-control" value="{{ $jobs->project->project_name }}" disabled readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label">Jenkins User</label>
                                            <input type="text" id="" class="form-control" value="{{ $jobs->jenkins->username }}" disabled readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-control-label">Build Number</label>
                                            <input type="text" id="" class="form-control" value="{{ $jobs->build_number }}" disabled readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-control-label">Build Time</label>
                                            <input type="text" id="" class="form-control" value="{{ $jobs->build_time }}" disabled readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-control-label">Date Time</label>
                                            <input type="text" id="" class="form-control" value="{{ $jobs->created_at }}" disabled readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-control-label">Status</label>
                                            <input type="text" id="" class="form-control" value="{{ $jobs->status }}" disabled readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection