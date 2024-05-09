@extends('layouts.admin')
@section('title', 'Jobs')
@section('page1', 'Jobs')
@section('page2', 'Jobs Console')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="table">
                        <div class="row">
                            <div class="col-6 align-items-center">
                                <h2 class="mb-0">Jobs Console</h2>
                            </div>
                            <div class="col-6 text-end align-items-center">
                                <a class="btn bg-gradient-warning mb-0" href="/project/{{$project_id}}/show"><i class="material-icons text-sm">arrow_back</i>&nbsp;&nbsp;Back</a>
                            </div>
                        </div>
                        <br>  
                        <div class="row">
                            <div class="col-lg">
                                <div class="form-group">
                                    <label class="form-control-label">Console Log for {{ $job_name }} ({{ $build_number}})</label>
                                    <!-- Render consoleText directly -->
                                    <div class="console-text" readonly>
                                        @php
                                            $lines = explode("\n", $consoleText);
                                        @endphp
                                        @foreach ($lines as $line)
                                            <div>{!! preg_replace('/\b(https?:\/\/\S+)/i', '<a href="$1" target="_blank">$1</a>', e($line)) !!}</div>
                                        @endforeach
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
</div>
@endsection

