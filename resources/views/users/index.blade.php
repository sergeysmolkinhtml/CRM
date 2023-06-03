@extends('layouts.app')

@section('content')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.users.create') }}">
                Create user
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Users list</div>
        <div class="input-group rounded p-2">
            <form class="form-inline my-2 my-lg-0" action="{{route('user.search')}}" method="get" type="get">
                <input type="search"
                       class="form-control rounded"
                       placeholder="Search"
                       name="userSearch"
                       aria-label="Search"
                       aria-describedby="search-addon"/>
                <button class="input-group-text border-0" id="search-addon">
                    <i class="fas fa-search"></i>
                </button>
            </form>

            <div class="d-flex justify-content-end p-2">
                <form action="{{ route('admin.users.index') }}" method="GET">
                    <div class="form-group">
                        <label for="deleted" class="col-form-label">Show deleted:</label>
                        <select class="form-control" name="deleted" id="deleted" onchange="this.form.submit()">
                            <option value="false" {{ request('deleted') == 'false' ? 'selected' : '' }}>No</option>
                            <option value="true" {{ request('deleted') == 'true' ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                </form>
        </div>

            <table class="table table-responsive-sm table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>Email</th>
                    <th>Role</th>
                    @if ($withDeleted)
                        <th>Deleted at</th>
                    @endif
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->first_name }}</td>
                        <td>{{ $user->last_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @foreach($user->roles as $role)
                                {{ $role->name }}
                            @endforeach
                        </td>
                        @if ($withDeleted)
                            <td>{{ $user->deleted_at ?? 'Not deleted' }}</td>
                        @endif
                        <td>
                            <a class="btn btn-sm btn-info" href="{{ route('admin.users.edit', $user) }}">
                                Edit
                            </a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are your sure?');" style="display: inline-block;">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="submit" class="btn btn-sm btn-danger" value="Delete">
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{ $users->withQueryString()->links() }}
        </div>
    </div>

@endsection
