@extends('layouts.admin')
@section('title', 'Jenkins')
@section('page1', 'Jenkins')
@section('page2', 'Jenkins Edit')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table">
                            <div class="row">
                                <div class="col-6 align-items-center">
                                    <h2 class="mb-0">Edit Jenkins</h2>
                                </div>
                                <div class="col-6 text-end align-items-center">
                                    <a class="btn bg-gradient-warning mb-0" href="/jenkins"><i class="material-icons text-sm">arrow_back</i>&nbsp;&nbsp;Back</a>
                                </div>
                            </div>
                            <br>
                            <form action="/jenkins/{{$jenkins->id}}/update" method="POST">
                            @csrf
                            <div class="row">
                                <div class="row">
                                    <div class="col-lg-4 mt-3">
                                        <div class="input-group input-group-static mb-4">
                                            <label class="form-control-label">Username</label>
                                            <input type="text" name="username" id="" class="form-control" value="{{$jenkins->username}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mt-3">
                                        <div class="input-group input-group-static mb-4">
                                            <label class="form-control-label">Token</label>
                                            <input type="text" name="token" id="" class="form-control" value="{{$jenkins->token}}">
                                        </div>
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-lg">
                                        <div class="input-group input-group-static mb-4">
                                            <label class="form-control-label">Jenkins URL</label>
                                            <input type="text" name="jenkins_url" id="" class="form-control" value="{{$jenkins->jenkins_url}}">
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
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
