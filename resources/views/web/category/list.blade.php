@extends('layout')
  
@section('content')
<div class="container">
    <div class="datatables-header">
        <h1>Car Categories</h1>
        <a  class="edit btn btn-primary btn-sm" href="{{route('add.category')}}"> Add New</a>
    </div>
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th width="100px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

@endsection