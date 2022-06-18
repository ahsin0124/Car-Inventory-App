@extends('layout')
  
@section('content')
<main class="login-form">
  <div class="cotainer">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header"@if(isset($car_category)) Edit @else Add @endif Category</div>
                  <div class="card-body">
  
                      <form action="@if(isset($car_category)) {{ route('edit.category.post') }} @else {{ route('add.category.post') }} @endif" method="POST">
                          @csrf
                          <div class="form-group row">
                              <label for="email_address" class="col-md-4 col-form-label text-md-right">Category Name</label>
                              <div class="col-md-6">
                              <input type="hidden"  name="id" value="@if(isset($car_category)) {{$car_category['id']}} @else @endif">
                                  <input type="text" id="email_address" value="@if(isset($car_category)){{$car_category['name']}}@else{{ old('name') }}@endif" class="form-control" name="name" required autofocus>
                                  @if ($errors->has('name'))
                                      <span class="text-danger">{{ $errors->first('name') }}</span>
                                  @endif
                              </div>
                          </div>

  
                          <div class="col-md-6 offset-md-4">
                              <button type="submit" class="btn btn-primary">
                                @if(isset($car_category))Update @else Add @endif
                              </button>
                          </div>
                      </form>
                        
                  </div>
              </div>
          </div>
      </div>
  </div>
</main>
@endsection