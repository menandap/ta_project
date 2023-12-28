@extends('layouts.admin')
@section('title', 'Master Jobs')
@section('page1', 'Master Jobs')
@section('page2', 'Master Jobs Add')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table">
                            <div class="row">
                                <div class="col-6 align-items-center">
                                    <h2 class="mb-0">Add Master Jobs</h2>
                                </div>
                                <div class="col-6 text-end align-items-center">
                                    <a class="btn bg-gradient-warning mb-0" href="/masterjobs"><i class="material-icons text-sm">arrow_back</i>&nbsp;&nbsp;Back</a>
                                </div>
                            </div>
                            <br>
                            <form action="/masterjobs/store" method="POST">
                            @csrf
                            <div class="row">
                                <div class="row mt-4">
                                    <div class="col-lg-4">
                                        <div class="input-group input-group-static mb-4">
                                            <label class="form-control-label">Jobs Name</label>
                                            <input type="text" name="jobs_name" id="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="input-group input-group-static mb-4">
                                            <label class="form-control-label">Jobs Token</label>
                                            <input type="text" name="jobs_token" id="" class="form-control">
                                        </div>
                                    </div>
                                </div> 
                                <div class="row">
                                <div class="row">
                                    <div class="col-lg">
                                        <div class="input-group input-group-static mb-4">
                                            <label class="form-control-label">Description</label>
                                            <textarea class="form-control" name="desc" id="" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success">
                                            Submit
                                        </button>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
