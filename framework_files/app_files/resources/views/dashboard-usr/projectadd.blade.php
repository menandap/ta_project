@extends('layouts.admin')
@section('title', 'Project')
@section('page1', 'Project')
@section('page2', 'Project Add')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table">
                            <div class="row">
                                <div class="col-6 align-items-center">
                                    <h2 class="mb-0">Add Project</h2>
                                </div>
                                <div class="col-6 text-end align-items-center">
                                    <a class="btn bg-gradient-warning mb-0" href="/project"><i class="material-icons text-sm">arrow_back</i>&nbsp;&nbsp;Back</a>
                                </div>
                            </div>
                            <br>
                            <form action="/project/store" method="POST">
                            @csrf
                            <div class="row">
                                <div class="row">
                                    <div class="col-lg-4 mt-3">
                                        <div class="input-group input-group-static mb-4">
                                            <label class="form-control-label">Project Name</label>
                                            <input type="text" name="project_name" id="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mt-3">
                                        <div class="input-group input-group-static mb-4">
                                        <label class="form-control-label">Select Type Template</label>
                                        <select class="form-control" name="id_project_template">
                                            @foreach($template as $templates)
                                                <option value="{{$templates->id}}">{{$templates->template_type}}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mt-3">
                                        <div class="input-group input-group-static mb-4">
                                            <label class="form-control-label">Select Server Deployed</label>
                                            <select class="form-control" name="id_server">
                                            @foreach($server as $servers)
                                                <option value="{{$servers->id}}">{{$servers->server_ip}}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-lg-4 mt-3">
                                        <div class="input-group input-group-static mb-4">
                                            <label class="form-control-label">Select Docker User</label>
                                            <select class="form-control" name="id_docker">
                                            @foreach($docker as $dockers)
                                                <option value="{{$dockers->id}}">{{$dockers->username}}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                    </div>
                                    @if ($types == 1)
                                    <div class="col-lg-4 mt-3" style="display: none;">
                                        <div class="input-group input-group-static mb-4">
                                            <label class="form-control-label">Orign Pull Repo</label>
                                            <input type="text" name="project_repo" id="" class="form-control" value="none">
                                        </div>
                                    </div>
                                    @else
                                    <div class="col-lg-4 mt-3">
                                        <div class="input-group input-group-static mb-4">
                                            <label class="form-control-label">Orign Pull Repo</label>
                                            <input type="text" name="project_repo" id="" class="form-control" value="keep none if didnt have orign repo or change it">
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-lg-4 mt-3">
                                        <div class="input-group input-group-static mb-4">
                                            <label class="form-control-label">Select Jenkins User</label>
                                            <select class="form-control" name="id_user_jenkins">
                                            @foreach($jenkins as $jenkin)
                                                <option value="{{$jenkin->id}}">{{$jenkin->username}}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mt-3" style="display: none;">
                                        <div class="input-group input-group-static mb-4">
                                            <label class="form-control-label">Post Repo</label>
                                            <input type="text" name="post_repo" id="" class="form-control" value="none">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mt-3" style="display: none;">
                                        <div class="input-group input-group-static mb-4">
                                            <label class="form-control-label">User</label>
                                            <input type="text" name="id_user" id="" class="form-control" value="{{$user}}">
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
