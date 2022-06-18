<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\CarCategory;
use App\Models\Car;
use DataTables;
use App\Common\Utility;
class WebController extends Controller
{

    /**
     * Display a listing of the cars in data-table.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     */
    
    public function index(Request $request)
    {
        Utility::stripXSS();
        if ($request->ajax()) {
            $data = Car::select('cars.*','car_categories.name as category_name')
            ->join('car_categories','car_categories.id', '=','cars.category_id')->latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
   
                           $btn = '<a href="cars/edit/'.$row->id.'" class="edit btn btn-primary btn-sm mr-2">Edit</a>';
                           $btn .= '<a href="cars/delete/'.$row->id.'" class="edit btn btn-danger btn-sm">Delete</a>';
     
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('web.car.list');
    }

    /**
     * Display the web page for login
     *
    */

    public function login()
    {
        return view('web.auth.login');
    }

    /**
     * Display the web page for Registration
     *
    */

    public function register()
    {
        return view('web.auth.register');
    }

    /**
     * Display the web page for adding a category
     *
    */

    public function addCategory()
    {
        return view('web.category.add');
    }

    /**
     * Display the web page for Editing a category
     *
    */

    public function editCategory($id)
    {
        //validate id's exsistance in categorires
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:car_categories,id'
        ]);

        // Display Error Message 
        if ($validator->fails()) {
            return redirect()->back()->withInput()
                ->withErrors($validator->messages());
        }

        $car_category = CarCategory::find($id);
        return view('web.category.add', compact("car_category"));
    }
    
    /**
     * Display the web page for adding a Car
     *
    */

    public function addCar()
    {
        $car_categories = CarCategory::all();
        return view('web.car.add',compact("car_categories"));
    }

    /**
     * Display the web page for editing a category
     *
    */

    public function editCar($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:car_categories,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()
                ->withErrors($validator->messages());
        }

        $car = Car::find($id);
        $car_categories = CarCategory::all();
        return view('web.car.add', compact("car","car_categories"));
    }
}
