<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCartRequest;
use App\Models\Cart;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productsInCart = Cart::where("ownerID", Auth::user()->id)->get();

        if($productsInCart->count() == 0) {
            return $this->success("Cart is empty", null);
        }

        return $this->success("Products in your cart", [
            "products" => $productsInCart
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
    public function store(CreateCartRequest $request)
    {
        $request->validated($request->all());

        // check if product already in cart
        $productInCart = Cart::where("productID", $request->productID)->where("ownerID", Auth::user()->id)->first();

        if ($productInCart) {
            return $this->error("Product already in your cart",null, 403);
        }

        // if product is not in cart
        $product = Product::where("id", $request->productID)->first();

        if(!$product) {
            return $this->error("Product not found!", null, 404);
        }

        Cart::create([
            "productID" => $product->id,
            "ownerID" => Auth::user()->id,
        ]);

        return $this->success("Product added to cart", [
            "productID" => $product->id,
            "productName" => $product->name,
            "productPrice" => $product->price
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
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
