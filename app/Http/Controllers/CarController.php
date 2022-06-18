<?php
namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Common\Utility;

class CarController extends Controller
{
    public function __construct()
    {
        
    }

    /**
     * Returns list of all cars
     *
     * @return response/json
    */

    public function index()
    {
        return Car::all();
    }
    
    /**
     * Create a newly created car in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */

    public function create(Request $request)
    {
        //Validate data
        Utility::stripXSS();
        $data = $request->all();
        $validator = Validator::make($data, [
            'color' => 'required|string',
            'make' => 'required|string',
            'model' => 'required|string',
            'registration_no' => 'required|string|unique:cars',
            'category_id' => 'required|exists:car_categories,id',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['error' => $validator->messages()], 200);
            } else {
                return redirect()->back()->withInput()
                ->withErrors($validator->messages());
            }
        }

        //Request is valid, create new Car
        $car = new Car();
        $car = $car->create($request->only($car->getFillable()));

        //Car created, return success response
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Car created successfully',
                'data' => $car_category
            ], Response::HTTP_OK);
        } else {
            return redirect()->intended('/')
            ->withSuccess('Car Added Successfully');
        }
    }

    /**
     * return the specified car.
     *
     * @param  car->id
    */
    public function find($id)
    {
        $car = Car::find($id);
    
        if (!$car) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, cars not found.'
            ], 400);
        }
    
        return $car;
    }

    /**
     * Show the form for editing the specified Car.
     *
     * @param  \Illuminate\Http\Request  $request
     */

    public function update(Request $request)
    {
        Utility::stripXSS();
        //Validate data
        $data = $request->all();
        $validator = Validator::make($data, [
            'id' => 'required|exists:cars,id',
            'color' => 'required|string',
            'make' => 'required|string',
            'model' => 'required|string',
            'registration_no' => 'required|string',
            'category_id' => 'required|exists:car_categories,id',

        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['error' => $validator->messages()], 200);
            } else {
                return redirect()->back()
                ->withErrors($validator->messages());
            }
        }
        $car = Car::find($data['id']);
        $car->fill($request->only($car->getFillable()));
        $car->save();

        //Car created, return success response
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Car Updated successfully',
                'data' => $car
            ], Response::HTTP_OK);
        } else {
            return redirect()->intended('/')
            ->withSuccess('Car Added Successfully');
        }
    }

    /**
     * Remove the specified Car from storage.
     *
     * @param  \App\Models\Car  $Car
     */
    
    public function destroy($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:car_categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $car = Car::find($id);
        $car->delete();
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Car Deleted successfully',
            ], Response::HTTP_OK);
        } else {
            return redirect()->intended('/')
            ->withSuccess('Car Delted Successfully');
        }
    }
}