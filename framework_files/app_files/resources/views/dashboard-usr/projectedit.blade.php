@extends('layouts.admin')
@section('title', 'Project')
@section('page1', 'Project')
@section('page2', 'Project Edit')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table">
                            <div class="row">
                                <div class="col-6 align-items-center">
                                    <h2 class="mb-0">Edit Project</h2>
                                </div>
                                <div class="col-6 text-end align-items-center">
                                    <a class="btn bg-gradient-warning mb-0" href="/project"><i class="material-icons text-sm">arrow_back</i>&nbsp;&nbsp;Back</a>
                                </div>
                            </div>
                            <br>
                            <form action="/project/{{$project->id}}/update" method="POST">
                            @csrf
                            <div class="row">
                                <div class="row">
                                    <div class="col-lg-4 mt-3">
                                        <div class="input-group input-group-static mb-4">
                                            <label class="form-control-label">Project Name</label>
                                            <input type="text" name="project_name" id="" class="form-control" value="{{$project->project_name}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mt-3">
                                        <div class="input-group input-group-static mb-4">
                                            <label class="form-control-label">Select Type Template</label>
                                            <select class="form-control" name="id_project_template">
                                                <option selected value="{{$project->template->id}}">{{$project->template->template_type}}</option>
                                                @foreach($template as $templates)
                                                    @if($templates->id==$project->template->id)

                                                    @else
                                                        <option value="{{$templates->id}}">{{$templates->template_type}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mt-3">
                                        <div class="input-group input-group-static mb-4">
                                            <label class="form-control-label">Select Server Deployed</label>
                                            <select class="form-control" name="id_server">
                                                <option selected value="{{$project->server->id}}">{{$project->server->server_ip}}</option>
                                                @foreach($server as $servers)
                                                @if($servers->id==$project->server->id)

                                                @else
                                                    <option value="{{$servers->id}}">{{$servers->server_ip}}</option>
                                                @endif
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
                                                <option selected value="{{$project->docker->id}}">{{$project->docker->username}}</option>
                                                @foreach($docker as $dockers)
                                                @if($dockers->id==$project->docker->id)

                                                @else
                                                    <option value="{{$dockers->id}}">{{$dockers->username}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mt-3">
                                        <div class="input-group input-group-static mb-4">
                                            <label class="form-control-label">Orign Pull Repo</label>
                                            <input type="text" name="project_repo" id="" class="form-control" value="{{$project->project_repo}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mt-3">
                                        <div class="input-group input-group-static mb-4">
                                            <label class="form-control-label">Select Jenkins User</label>
                                            <select class="form-control" name="id_user_jenkins">
                                            <option selected value="{{$project->jenkins->id}}">{{$project->jenkins->username}}</option>
                                                @foreach($jenkins as $jenkin)
                                                @if($jenkin->id==$project->jenkins->id)

                                                @else
                                                    <option value="{{$jenkin->id}}">{{$jenkin->username}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mt-3" style="display: none;">
                                        <div class="input-group input-group-static mb-4">
                                            <label class="form-control-label">Post Repo</label>
                                            <input type="text" name="post_repo" id="" class="form-control" value="{{$project->post_repo}}">
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
