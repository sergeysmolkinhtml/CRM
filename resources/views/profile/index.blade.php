@extends('layouts.app')

@section('content')
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">Contact information</div>

        <div class="card-body">
            <div class="float-left">
                <img class="rounded mx-auto ms-10"
                     src="https://img.mercedes-benz-kiev.com/data/purchase/amg-gt-coupe/0952611142/mercedes-amg-gt-r-1.jpg"
                     alt="..." height="290" width="260">
            </div>
            <div class="mx-auto text-center">
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Title</h5>
                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                        <a href="#" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
            <hr>
            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="required" for="first_name">First name</label>
                        <input class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}"
                               type="text" name="first_name" id="first_name"
                               value="{{ old('first_name', auth()->user()->first_name) }}" required>

                        @if($errors->has('first_name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('first_name') }}
                            </div>
                        @endif
                        <span class="help-block"> </span>
                    </div>

                    <div class="form-group">
                        <label class="required" for="last_name">Last name</label>
                        <input class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}" type="text" name="last_name" id="last_name" value="{{ old('last_name', auth()->user()->last_name) }}" required>
                    @if($errors->has('last_name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('last_name') }}
                        </div>
                    @endif
                    <span class="help-block"> </span>
                </div>

                <div class="form-group">
                    <label class="required" for="address">Address</label>
                    <input class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" type="text" name="address" id="address" value="{{ old('address', auth()->user()->address) }}" required>
                    @if($errors->has('address'))
                        <div class="invalid-feedback">
                            {{ $errors->first('address') }}
                        </div>
                    @endif
                    <span class="help-block"> </span>
                </div>
                    <div class="form-group">
                        <label class="required" for="workExperience">Work Experience</label>
                        <input class="form-control {{ $errors->has('workExperience') ? 'is-invalid' : '' }}" type="text" name="workExperience" id="workExperience" value="{{ old('workExperience', auth()->user()->workExperience ) }}" required>
                        @if($errors->has('workExperience'))
                            <div class="invalid-feedback">
                                {{ $errors->first('workExperience') }}
                            </div>
                        @endif
                        <span class="help-block"> </span>
                    </div>

                <div class="form-group">
                    <label class="required" for="phone_number">Phone number</label>
                    <input class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', auth()->user()->phone_number) }}" required>
                    @if($errors->has('phone_number'))
                        <div class="invalid-feedback">
                            {{ $errors->first('phone_number') }}
                        </div>
                    @endif
                    <span class="help-block"> </span>
                </div>
                    @include('profile.partials.details-bar')
                </div>

                <button class="btn btn-primary" type="submit">
                    Save
                </button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Change password</div>

        <div class="card-body">
            <form action="{{ route('admin.profile.changePassword') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="required" for="old_password">Old password</label>
                    <input class="form-control {{ $errors->has('old_password') ? 'is-invalid' : '' }}" type="password" name="old_password" id="old_password" required>
                    @if($errors->has('old_password'))
                        <div class="invalid-feedback">
                            {{ $errors->first('old_password') }}
                        </div>
                    @endif
                    <span class="help-block"> </span>
                </div>

                <div class="form-group">
                    <label class="required" for="new_password">New password</label>
                    <input class="form-control {{ $errors->has('new_password') ? 'is-invalid' : '' }}" type="password" name="new_password" id="new_password" required>
                    @if($errors->has('new_password'))
                        <div class="invalid-feedback">
                            {{ $errors->first('new_password') }}
                        </div>
                    @endif
                    <span class="help-block"> </span>
                </div>

                <div class="form-group">
                    <label class="required" for="new_password_confirmation">Confirm new password</label>
                    <input class="form-control {{ $errors->has('new_password_confirmation') ? 'is-invalid' : '' }}" type="password" name="new_password_confirmation" id="new_password_confirmation" required>
                    @if($errors->has('new_password_confirmation'))
                        <div class="invalid-feedback">
                            {{ $errors->first('new_password_confirmation') }}
                        </div>
                    @endif
                    <span class="help-block"> </span>
                </div>

                <button class="btn btn-primary" type="submit">
                    Save
                </button>
            </form>
        </div>
    </div>
@endsection
