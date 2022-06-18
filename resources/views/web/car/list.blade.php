@extends('layout')
  
@section('content')
<div class="container">
    <div class="datatables-header">
        <h1>Cars</h1>
        <a style="float:left" class="edit btn btn-primary btn-sm" href="{{route('add.car')}}"> Add New</a>
    </div>
    <table class="table table-bordered data-table-cars">
        <thead>
            <tr>
                <th>No</th>
                <th>Color</th>
                <th>Make</th>
                <th>Model</th>
                <th>Registration No</th>
                <th>Category</th>

                <th width="100px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

@endsection