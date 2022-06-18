<?php
namespace App\Http\Controllers;

use App\Models\CarCategory;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use DataTables;
use App\Common\Utility;
class CarCategoryController extends Controller
{
 
    public function __construct()
    {}

    /**
     * Display a listing of the Category.
     *
     */

    public function index(Request $request)
    {
        Utility::stripXSS();
        if (request()->wantsJson()) {
            if ($request->ajax()) {
                $data = CarCategory::latest()->get();
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function($row){
       
                               $btn = '<a href="edit/'.$row->id.'" class="edit btn btn-primary btn-sm mr-2">Edit</a>';
                               $btn .= '<a href="delete/'.$row->id.'" class="edit btn btn-danger btn-sm">Delete</a>';
         
                                return $btn;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
            }
            return CarCategory::all();
        } else {
            if ($request->ajax()) {
                $data = CarCategory::latest()->get();
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function($row){
       
                               $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';
         
                                return $btn;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
            }
            return view('web.category.list');
        }
        
    }


    /**
     * Store a newly created Category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function create(Request $request)
    {
        Utility::stripXSS();
        $data = $request->all('name');
        $validator = Validator::make($data, [
            'name' => 'required|string|unique:car_categories',
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

        //Request is valid, create new CarCategory
        $car_category = CarCategory::create([
            'name' => $request->name
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'CarCategory created successfully',
                'data' => $car_category
            ], Response::HTTP_OK);
        } else {
            return redirect()->intended('/categories/get-list')
            ->withSuccess('Category Added Successfully');
        }
        //CarCategory created, return success response
        
    }

    /**
     * Display the specified Category.
     *
     * @param  \App\Models\CarCategory  $CarCategory
     */
    public function find($id)
    {
        $car_category = CarCategory::find($id);
    
        if (!$car_category) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, category not found.'
            ], 400);
        }
    
        return $car_category;
    }

    /**
     * Show the form for editing the specified Category.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function update(Request $request)
    {
        //Validate data
        Utility::stripXSS();
        $data = $request->all();
        $validator = Validator::make($data, [
            'id' => 'required|exists:car_categories,id',
            'name' => 'required|string',

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
        $car_category = CarCategory::find($data['id']);
        $car_category->fill($request->only($car_category->getFillable()));
        $car_category->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'CarCategory created successfully',
                'data' => $car_category
            ], Response::HTTP_OK);
        } else {
            return redirect()->intended('/categories/get-list')
            ->withSuccess('Category Updated Successfully');
        }
    }

    /**
     * Remove the specified Category from storage.
     *
     * @param  \App\Models\CarCategory  $CarCategory
     */

    public function destroy($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:car_categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $car_category = CarCategory::find($id);
        $car_category->delete();
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'CarCategory deleted successfully'
            ], Response::HTTP_OK);
        } else {
            return redirect()->intended('/categories/get-list')
            ->withSuccess('Category Deleted Successfully');
        }
       
    }
}