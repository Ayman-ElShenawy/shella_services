<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $order = Order::with('product')->with('service')->get();
        if($order->count()>0){
            return response()->json([
                'status'=>200,
                'order'=>$order,
            ],200);
        }
        else{
            return response()->json([
                'status'=>404,
                'message'=>'No orders Found',
            ],404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(),[
            'service_id'=>'required|exists:services,id',
            'product_id'=>'required|exists:products,id',
            'payment_method'=>'required|in:cash_on_delivery,card,wallet',
           
        ]);
        $total = 0;
        if($validator->fails()){
            return response()->json([
                'status'=>422,
                'message'=>$validator->messages(),
            ],422);
        }
        else{
            if($request->product_id){
                $product = Product::findOrFail($request->product_id);
                $total += $product->price;
            }
          $order=  Order::create([
                'user_id'=>Auth::user()->id,
                'service_id'=>$request->service_id,
                'product_id'=>$request->product_id,
                'total'=>$total,
                'payment_method'=>$request->payment_method,
            ]);
            if($order){
                return response()->json([
                    'status'=>200,
                    'message'=>"order Created Successfully",
                ],200);
            }
            else{
                return response()->json([
                    'status'=>500,
                    'message'=>"Something Went Wrong!",
                ],500); 
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $order = Order::find($id);
        if($order){
            return response()->json([
                'status'=>200,
                'category'=>$order,
            ],200);
        }
        else{
            return response()->json([
                'status'=>404,
                'message'=>'No Such order Found!',
            ],404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(),[
            'service_id'=>'required|exists:services,id',
            'product_id'=>'required|exists:products,id',
            'payment_method'=>'required|in:cash_on_delivery,card,wallet',
           
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>422,
                'message'=>$validator->messages(),
            ],422);
        }
        else{
            $order = Order::find($id);
            if($order){
                $order->update([
                    'service_id'=>$request->service_id,
                'product_id'=>$request->product_id,
                'payment_method'=>$request->payment_method,
                'payment_status'=>$request->payment_status,
                'status'=>$request->status,
                  ]);
                  return response()->json([
                    'status'=>200,
                    'message'=>"order Updated Successfully",
                ],200);
            }
            else{
                return response()->json([
                    'status'=>404,
                    'message'=>"No Such order Found!",
                ],404); 
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $order = Order::find($id);
        if ($order){
          $order->delete();
          return response()->json([
              'status'=>200,
              'message'=>"order Deleted Successfully",
          ],200);
        }
         else{
          return response()->json([
              'status'=>404,
              'message'=>"No Such order Found",
          ],404);
         };
    }
}
