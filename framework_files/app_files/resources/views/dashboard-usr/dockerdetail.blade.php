@extends('layouts.admin')
@section('title', 'Docker')
@section('page1', 'Docker')
@section('page2', 'Docker Detail')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table">
                            <div class="row">
                                <div class="col-6 align-items-center">
                                    <h2 class="mb-0">Detail Docker</h2>
                                </div>
                                <div class="col-6 text-end align-items-center">
                                    <a class="btn bg-gradient-warning mb-0" href="/docker"><i class="material-icons text-sm">arrow_back</i>&nbsp;&nbsp;Back</a>
                                </div>
                            </div>
                            <br>  

                            <div class="row">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Username</label>
                                            <input type="text" id="" class="form-control" value="{{ $docker->username }}" disabled readonly>
                                        </div>
                                    </div>
                                    <!-- <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Password</label>
                                            <input type="text" id="" class="form-control" value="{{ $docker->password }}" disabled readonly>
                                        </div>
                                    </div> -->
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label">Password</label>
                                            <div class="input-group">
                                                <input type="password" id="passwordField" class="form-control" value="{{ $docker->password }}" disabled readonly>
                                                <button type="button" id="togglePassword" class="btn btn-outline-secondary">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </button>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const passwordField = document.getElementById('passwordField');
        const togglePasswordButton = document.getElementById('togglePassword');
        const toggleIcon = togglePasswordButton.querySelector('i');

        togglePasswordButton.addEventListener('click', () => {
            // Toggle the type attribute
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);

            // Toggle the icon
            toggleIcon.classList.toggle('fa-eye');
            toggleIcon.classList.toggle('fa-eye-slash');
        });
    });
</script>
@endpush