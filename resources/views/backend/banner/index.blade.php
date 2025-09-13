@extends('backend.layout.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    <h3>Banner</h3>
                </div>
                <div class="col-6">
                    <a href="{{ route('banner.create') }}" class="btn btn-sm btn-danger float-end"> Add New Product </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    @php
                        $i = 1;
                    @endphp
                    <tr>
                        <th>SN</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($banner as $row)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->is_active ? "Active" : "Incative" }}</td>
                            <td>
                                <div class="text-center " >
                                    <form action="{{ route('banner.destroy', $row->id) }}" method="POST"
                                        style="display: inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                                    </form>

                            </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
