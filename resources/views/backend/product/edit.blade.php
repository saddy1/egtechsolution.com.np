@extends('backend.layout.app')

@section('content')
    <div class="card">
        <h5 class="card-header">Edit Product</h5>

        <div class="card-body">
            <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-4">
                        <label for="name">Name</label>
                        <input type="none" name="name" id="name" class="form-control" value="{{ $product->name }}"
                            disabled>
                        <span class="text-danger">
                            @error('name')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>
                    <div class="col-md-2 padding">
                        <label for="rate">Rate</label>
                        <input name="rate" id="rate" class="form-control" value="{{ $product->rate }}" required>
                        <span class="text-danger">
                            @error('rate')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>
                    <div class="col-md-2 padding">
                        <label for="discount">Discount</label>
                        <input name="discount" id="discount" value="{{ $product->discount }}" class="form-control"
                            required>
                        <span class="text-danger">
                            @error('discount')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>
                    <div class="col-md-2 padding">
                        <label for="quantity">Quantity</label>
                        <input name="quantity" id="quantity"value="{{ $product->quantity }}" class="form-control" required>
                        <span class="text-danger">
                            @error('quantity')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>
                    {{-- <div class="col-md-2 ">
                        <label for="brand">Brand</label>
                        <input name="brand" id="brand" class="form-control " value="{{ $product->brand }}" required>
                        <span class="text-danger">
                            @error('brand')
                                {{ $message }}
                            @enderror
                        </span>

                    </div> --}}
                    <div class="col-md-2">
                        <label for="brand">Brand</label>
                        <select name="brand" id="brand" class="form-control">
                            <option value="{{ $product->brand }}" selected>{{ $product->brand }}</option>
                            @foreach ($brands as $brand)
                                @if ($brand->name != $product->brand)
                                    <option value="{{ $brand->name }}">{{ $brand->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>


                    <div class="col-md-2 mt-3">
                        <label for="category">Category</label>
                        <select name="category" id="category" class="form-control">
                            <option value="{{ $product->category }}" selected>{{ $product->category }}</option>
                            @foreach ($category as $cat)
                                @if ($cat->name != $product->category)
                                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>


                    <div class="col-md-10 mt-3">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" style="height: 100px" required>{{ $product->description }}</textarea>

                        {{-- <input name="description" id="description" value={{ $product->description }} class="form-control" required> --}}
                        <span class="text-danger">
                            @error('description')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>
                    {{-- <div class="col-md-4 mt-4">
                        <label for="photo">Photo:</label>
                        <input type="file" name="photo" id="photo" required>
                        <span class="text-danger">
                            @error('photo')
                                {{ $message }}
                            @enderror
                        </span>
                    </div> --}}
                    <div class="col-md-12 mt-3">
                        <h6>Specifications:</h6>
                        <textarea  name="specifications" class="form-control" rows="8">
                    @foreach ($product->specifications as $specification)
                    {{ trim($specification->name) }}:  {{ $specification->value }}
                    @endforeach
                    </textarea>
                        <span class="text-danger">
                            @error('specifications')
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
