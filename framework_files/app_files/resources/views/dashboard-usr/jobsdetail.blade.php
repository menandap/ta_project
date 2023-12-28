@extends('layouts.admin')
@section('title', 'Event')
@section('page1', 'Event')
@section('page2', 'Event Detail')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table">
                            <div class="row">
                                <div class="col-6 align-items-center">
                                    <h2 class="mb-0">Detail Event</h2>
                                </div>
                                <div class="col-6 text-end align-items-center">
                                    <a class="btn bg-gradient-warning mb-0" href="/myevent"><i class="material-icons text-sm">arrow_back</i>&nbsp;&nbsp;Back</a>
                                </div>
                            </div>
                            <br>  

                            <div class="row">
                            <div class="row">
                                    @php
                                        $undangans = App\Models\Undangan::where('id', '=', $events->id_undangan)->first();
                                    @endphp
                                    <div class="col-lg">
                                        <div class="form-group">
                                            <label class="form-control-label">Undangan</label>
                                            <input type="text" id="" class="form-control" value="{{ $undangans->title }}" disabled readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Nama Event</label>
                                            <input type="text" id="" class="form-control" value="{{ $events->title }}" disabled readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Lokasi</label>
                                            <input type="text" id="" class="form-control" value="{{ $events->location }}" disabled readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label">Tanggal</label>
                                            <input type="text" id="" class="form-control" value="{{ $events->date }}" disabled readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label">Mulai</label>
                                            <input type="text" id="" class="form-control" value="{{ $events->date_start }}" disabled readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label">Berakhir</label>
                                            <input type="text" id="" class="form-control" value="{{ $events->date_end }}" disabled readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg">
                                        <div class="form-group">
                                            <label class="form-control-label">Deskripsi</label>
                                            <textarea class="form-control" id="" rows="5" disabled readonly>{{$events->desc}}</textarea>
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