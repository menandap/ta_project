@extends('layouts.admin')
@section('title', 'Master Jobs')
@section('page1', 'Master Jobs')
@section('page2', 'Master Jobs List')

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
                                    <h2 class="mb-0">Master Jobs List</h2>
                                </div>
                                <div class="col-6 text-end align-items-center">
                                    <a class="btn bg-gradient-success mb-0" href="/masterjobs/create"><i class="material-icons text-sm">add</i>&nbsp;&nbsp;Add Master Jobs</a>
                                </div>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-lg font-weight-bolder ps-2">No.</th>
                                            <th class="text-uppercase text-secondary text-lg font-weight-bolder ps-2">Jobs Name</th>
                                            <!-- <th class="text-uppercase text-secondary text-lg font-weight-bolder ps-2">Token</th> -->
                                            <th class="text-uppercase text-secondary text-lg font-weight-bolder ps-2">Description</th>
                                            <th colspan="2" class="text-uppercase text-secondary text-lg font-weight-bolder ps-2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($masterjobs as $masterjobss)
                                        <tr>
                                            <td><p class="text-md font-weight-normal mb-0">{{ $masterjobs->firstItem()+$loop->index }}</p></th>
                                            <td><p class="text-md font-weight-normal mb-0">{{ $masterjobss->jobs_name }}</p></td>
                                            <!-- <td><p class="text-md font-weight-normal mb-0">{{ $masterjobss->jobs_token }}</p></td> -->
                                            <td><p class="text-md font-weight-normal mb-0">{{ $masterjobss->desc }}</p></td>
                                            <td class="align-middle text-center">
                                                <div class="d-flex align-items-center">
                                                    <a href="masterjobs/{{$masterjobss->id}}/show" class="m-1 btn bg-gradient-info"><i class="material-icons text-sm me-2">visibility</i>View</a>
                                                    <a href="masterjobs/{{$masterjobss->id}}/edit" class="m-1 btn bg-gradient-warning"><i class="material-icons text-sm me-2">edit</i>Edit</a>
                                                    <a href="masterjobs/{{$masterjobss->id}}/delete" class="m-1 btn bg-gradient-danger" onclick="return confirm('Apa yakin ingin menghapus data ini?')"><i class="material-icons text-sm me-2">delete</i>Delete</a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $masterjobs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
