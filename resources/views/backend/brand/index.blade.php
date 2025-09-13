@extends('backend.layout.app')

@section('content')


<div class="card">
    <div class="card-header">
 <div class="row">
    <div class="col-6">
        <h6>Brand</h6>
    </div>
    <div class="col-6">
        <a href="{{route('brand.create')}}" class="btn btn-sm btn-danger float-end"> Create </a>

    </div>
 </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>Name</th>
                    <th>Is Active</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 0;
                @endphp
                @foreach ($brands as $row)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->is_active ? "Active" : "Incative" }}</td>
                        </tr>

                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
