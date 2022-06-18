@extends('layout')
  
@section('content')
<main class="login-form">
  <div class="cotainer">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header">@if(isset($car))Edit @else Add @endif Car</div>
                  <div class="card-body">
  
                      <form action="@if(isset($car)) {{ route('edit.car.post') }} @else {{ route('add.car.post') }} @endif" method="POST">
                          @csrf
                          <div class="form-group row">
                              <label for="email_address" class="col-md-4 col-form-label text-md-right">Color</label>
                              <div class="col-md-6">
                              <input type="hidden"  name="id" value="@if(isset($car)) {{$car['id']}} @endif">
                                  <input type="text" id="email_address" value="@if(isset($car)){{$car['color']}}@else{{ old('color') }}@endif" class="form-control" name="color" required autofocus>
                                  @if ($errors->has('color'))
                                      <span class="text-danger">{{ $errors->first('color') }}</span>
                                  @endif
                              </div>
                          </div>
                          <div class="form-group row">
                              <label for="email_address" class="col-md-4 col-form-label text-md-right">Make</label>
                              <div class="col-md-6">
                                  <input type="text" id="email_address" value="@if(isset($car)){{$car['make']}}@else{{ old('make') }}@endif" class="form-control" name="make" required autofocus>
                                  @if ($errors->has('make'))
                                      <span class="text-danger">{{ $errors->first('make') }}</span>
                                  @endif
                              </div>
                          </div>
                          <div class="form-group row">
                              <label for="email_address" class="col-md-4 col-form-label text-md-right">Model</label>
                              <div class="col-md-6">
                                  <input type="text" id="email_address" value="@if(isset($car)){{$car['model']}}@else{{ old('model') }}@endif" class="form-control" name="model" required autofocus>
                                  @if ($errors->has('model'))
                                      <span class="text-danger">{{ $errors->first('model') }}</span>
                                  @endif
                              </div>
                          </div>
                          <div class="form-group row">
                              <label for="email_address" class="col-md-4 col-form-label text-md-right">Registration No</label>
                              <div class="col-md-6">
                                  <input type="text" id="email_address" value="@if(isset($car)){{$car['registration_no']}}@else{{ old('registration_no') }}@endif" class="form-control" name="registration_no" required autofocus>
                                  @if ($errors->has('registration_no'))
                                      <span class="text-danger">{{ $errors->first('registration_no') }}</span>
                                  @endif
                              </div>
                          </div>
                          <div class="form-group row">
                              <label for="email_address" class="col-md-4 col-form-label text-md-right">Category</label>
                              <div class="col-md-6">
                                <select class="form-control" aria-label="Default select example" name="category_id">
                                    <option >Select Category</option>
                                    @foreach($car_categories as $car_category)
                                        
                                        <option @if(isset($car) && $car['category_id'] == $car_category['id']) selected @elseif(old('category_id') == $car_category['id']) selected @endif value="{{$car_category['id']}}">{{$car_category['name']}}</option>
                                    @endforeach
                                </select>
                                  @if ($errors->has('category_id'))
                                      <span class="text-danger">{{ $errors->first('category_id') }}</span>
                                  @endif
                              </div>
                          </div>

  
                          <div class="col-md-6 offset-md-4">
                              <button type="submit" class="btn btn-primary">
                                @if(isset($car))Update @else Add @endif
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