@extends('backend.layout.app')

@section('content')

<div class="card">
    <h5 class="card-header">Create Product</h5>
    <div class="card-body">
        <form action="{{route('banner.store')}}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-4">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{old('name')}}" required>
                    <span class="text-danger">
                        @error('name')
                            {{ $message }}
                        @enderror
                    </span>

                 <div class="col-md-4 mt-4">
                    <label for="photo">Photo:</label>
                    <input type="file" name="photo" id="photo" required>
                    <span class="text-danger">
                        @error('photo')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-danger mt-3 float-end">Create</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
