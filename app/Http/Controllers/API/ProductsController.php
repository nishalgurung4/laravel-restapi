<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\Http\Resources\ProductsCollection;
use App\Http\Resources\ProductsResource;
use Validator;
class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return response([
            'success' => true,
            'data' => new ProductsCollection($products),
            'message' => 'Successfully Created'], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'detail' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['Validation Error', $validator->errors()], 422);
        }

        $product = Product::create($request->all());
        return response([
            'success' => true,
            // 'data' => new ProductsCollection($product),
            'message' => "Successfully Created"
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        if (is_null($product)) {
            return response()->json(['message' => "No Product Found"], 404);
        }
        return response([
            'success' => true,
            'data' => new ProductsResource($product),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'detail' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['message' => $validator->errors()], 422);       
        }
        $product->name = $input['name'];
        $product->detail = $input['detail'];
        $product->save();
        return response([
            'success' => true,
            'data' => new ProductsResource($product), 
            'message' => 'Product updated successfully.'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => "Successfully Deleted"], 200);
    }
}
