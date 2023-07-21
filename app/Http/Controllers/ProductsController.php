<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();

        return $this->success("Products fetched", [
            "products" => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProductRequest $request)
    {
        $request->validated($request->all());

        $product = Product::create([
            "name" => $request->name,
            "price" => $request->price
        ]);

        return $this->success("Product added!", [
            "detail" => $product
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::where("id", $id)->first();

        if(!$product) {
            return $this->error("Product not found!", null, 404);
        }

        return $this->success("Product fetched", [
            "product" => $product
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::where("id", $id)->first();

        if(!$product) {
            return $this->error("Product not found", null, 404);
        }

        $product->update([
            "name" => $request->name,
            "price" => $request->price
        ]);

        return $this->success("Product updated", [
            "product" => $product
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::where("id", $id)->first();

        if(!$product) {
            return $this->error("Product not found", null, 404);
        }

        Product::destroy($id);

        return $this->success("Product deleted", [
            "deleted_product" => $product
        ]);
    }
}
