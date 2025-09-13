@extends('backend.layout.app')

@section('content')

<div class="card">
    <h5 class="card-header">Create Brand</h5>
    <div class="card-body">

        <form action="{{route('category.store')}}" method="POST">
@csrf
        <div class="row">
            <div class="col-md-12">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="col-md-12">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" cols="30" rows="10" required></textarea>
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-danger mt-3 float-end">Create</button>
            </div>
        </div></form>
    </div>
</div>

@endsection
