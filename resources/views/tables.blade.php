@extends('inc.app')
@section('body')
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

          @include('inc.toolbar')

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-2 text-gray-800">Tables</h1>
                    <a type="button" id="create" class="has_action btn btn-primary px-5" data-type="edit"
                        href="{{ route('products.create') }}">
                        Create new
                    </a>
                </div>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>price</th>
                                        <th>quantity</th>
                                        <th>Category</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Name</th>
                                        <th>price</th>
                                        <th>quantity</th>
                                        <th>Category</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($products as $product)
                                        
                                    
                                    <tr>
                                        <td>{{@$product->name}}</td>
                                        <td>{{@$product->price}}</td>
                                        <td>{{@$product->quantity}}</td>
                                        <td>{{@$product->category->name}}</td>
                                    </tr>
                                    @endforeach

                                </tbody>
                              
                            </table>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $products->links() }}
                    </div>
                </div>
            
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->
@endsection