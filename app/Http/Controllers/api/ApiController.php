<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\ApiHelper;
use App\Http\Resources\CutomerCollection;
use App\Http\Resources\CutomerResource;
use App\Customer;
class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allCustomer = Customer::latest()->paginate(10);
        $response = ApiHelper::createApiResponse(false, 200, '', $allCustomer);
        // return response()->json($response, 200);
        return new CutomerCollection($allCustomer);
    }

    public function search($query)
    {
        $search = Customer::where('name','LIKE',"%$query%")
                ->orWhere('address','LIKE',"%$query%")->latest()->paginate(10);
        return new CutomerCollection($search);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'address' => 'required',
            'number' => 'required',
            'postcode' => 'required'
        ]);
        $customer = Customer::create($request->all());
         return response()->json(['message'=>'Customer Created Successfully', 'status'=>200]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $customer = Customer::findOrFail($id);
       return response()->json($customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
          $this->validate($request,[
            'name' => 'required',
            'address' => 'required',
            'number' => 'required',
            'postcode' => 'required'
        ]);
        $customer = Customer::findOrFail($id)->update($request->all());
         return response()->json(['message'=>'Customer Updated Successfully', 'status'=>200]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
       return response()->json(['message'=>'Customer Deleted Successfully', 200]);
    }
}
