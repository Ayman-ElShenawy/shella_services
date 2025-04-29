<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $product = Product::with('category')->get();
        if($product->count()>0){
            return response()->json([
                'status'=>200,
                'product'=>$product,
            ],200);
        }
        else{
            return response()->json([
                'status'=>404,
                'message'=>'No products Found',
            ],404);
        }
        
    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required|string|max:266|unique:products,name',
            'description'=>'required|string|max:266',
            'price'=>'required|numeric',
            'category_id'=>'required|exists:categories,id',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>422,
                'message'=>$validator->messages(),
            ],422);
        }
        else{
          $product=  Product::create([
                'user_id'=>Auth::user()->id,
                'name'=>$request->name,
                'description'=>$request->description,
                'price'=>$request->price,
                'category_id'=>$request->category_id,
            ]);
            if($product){
                return response()->json([
                    'status'=>200,
                    'message'=>"Product Created Successfully",
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
        $product = Product::find($id);
        if($product){
            return response()->json([
                'status'=>200,
                'category'=>$product,
            ],200);
        }
        else{
            return response()->json([
                'status'=>404,
                'message'=>'No Such product Found!',
            ],404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required|string|max:266|unique:products,name',
            'description'=>'required|string|max:266',
            'price'=>'required|numeric',
            'category_id'=>'required|exists:categories,id',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>422,
                'message'=>$validator->messages(),
            ],422);
        }
        else{
            $product = Product::find($id);
            if($product){
                $product->update([
                      'name'=>$request->name,
                      'description'=>$request->description,
                      'price'=>$request->price,
                      'category_id'=>$request->category_id,
                  ]);
                  return response()->json([
                    'status'=>200,
                    'message'=>"Product Updated Successfully",
                ],200);
            }
            else{
                return response()->json([
                    'status'=>404,
                    'message'=>"No Such Product Found!",
                ],404); 
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $product = Product::find($id);
        if ($product){
          $product->delete();
          return response()->json([
              'status'=>200,
              'message'=>"product Deleted Successfully",
          ],200);
        }
         else{
          return response()->json([
              'status'=>404,
              'message'=>"No Such product Found",
          ],404);
         };
    }
}
