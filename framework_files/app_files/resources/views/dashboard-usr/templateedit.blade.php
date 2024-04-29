@extends('layouts.admin')
@section('title', 'Template ')
@section('page1', 'Template ')
@section('page2', 'Template  Edit')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table">
                            <div class="row">
                                <div class="col-6 align-items-center">
                                    <h2 class="mb-0">Edit Template </h2>
                                </div>
                                <div class="col-6 text-end align-items-center">
                                    <a class="btn bg-gradient-warning mb-0" href="/template"><i class="material-icons text-sm">arrow_back</i>&nbsp;&nbsp;Back</a>
                                </div>
                            </div>
                            <br>
                            <form action="/template/{{$template->id}}/update" method="POST">
                            @csrf
                            <div class="row">
                                <div class="row mt-4">
                                    <div class="col-lg-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label class="form-control-label">Template Type</label>
                                            <input type="text" name="template_type" id="" class="form-control" value="{{$template->template_type}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label class="form-control-label">Template Repo</label>
                                            <input type="text" name="template_repo" id="" class="form-control" value="{{$template->template_repo}}">
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
