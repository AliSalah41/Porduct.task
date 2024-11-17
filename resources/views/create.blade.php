@extends('inc.app')

@section('body')
<div class="container-fluid">
    {{-- @include('inc.toolbar') --}}
    <div class="row">
        <div class="col-xl-9 mx-auto mt-5">
            <div class="card border-top border-0 border-4 border-primary border-left-primary" style="bottom: 5%">
                <div class="card-body p-5" style="background-color: ">
                    <div class="card-title d-flex align-items-center">
                        <h5 class="mb-0 text-primary">Create Service</h5>
                    </div>
                    <hr>
                    <form class="row g-3" action='' method="POST"
                        enctype="multipart/form-data" id="createForm">
                        @csrf

                        <label class="block font-medium text-sm text-gray-700" for="">Name</label>
                        <input class="form-control" id="name" name="name" required>

                        <label class="block font-medium text-sm text-gray-700" for="">quantity</label>
                        <input class="form-control" type="number" name="quantity" id="quantity">

                        <label class="block font-medium text-sm text-gray-700" for="">price</label>
                        <input class="form-control" type="number" name="price" id="price">
                        
                        <label class="block font-medium text-sm text-gray-700" for="">Category</label>
                        {{-- <input class="form-control" type="number" name="price"> --}}
                        <select class="form-control" name="category_id" id="category_id">
                            @foreach ($categories as $category )
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>

                        <button class="form-control btn btn-primary" type="submit">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#createForm').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            // Collect form data
            let formData = $(this).serialize();

            // AJAX request
            $.ajax({
                url: "{{ route('products.store') }}", // Adjust to your route
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        // Optionally, clear the form or redirect
                        $('#createForm')[0].reset();
                    } else {
                        alert('Failed to create product: ' + response.message);
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = '';
                    for (let field in errors) {
                        errorMessage += errors[field][0] + '\n';
                    }
                    alert(errorMessage);
                }
            });
        });
    });
</script>

@endsection