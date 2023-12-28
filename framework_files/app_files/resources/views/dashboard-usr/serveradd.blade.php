@extends('layouts.admin')
@section('title', 'Server')
@section('page1', 'Server')
@section('page2', 'Server Add')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table">
                            <div class="row">
                                <div class="col-6 align-items-center">
                                    <h2 class="mb-0">Add Server</h2>
                                </div>
                                <div class="col-6 text-end align-items-center">
                                    <a class="btn bg-gradient-warning mb-0" href="/server"><i class="material-icons text-sm">arrow_back</i>&nbsp;&nbsp;Back</a>
                                </div>
                            </div>
                            <br>
                            <form action="/server/store" method="POST">
                            @csrf
                            <div class="row">
                                <div class="row mt-4">
                                    <div class="col-lg-4">
                                        <div class="input-group input-group-static mb-4">
                                            <label class="form-control-label">Server IP</label>
                                            <input type="text" name="server_ip" id="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="input-group input-group-static mb-4">
                                            <label class="form-control-label">Username</label>
                                            <input type="text" name="username" id="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="input-group input-group-static mb-4">
                                            <label class="form-control-label">Password</label>
                                            <input type="text" name="password" id="" class="form-control">
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
