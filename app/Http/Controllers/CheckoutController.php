<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCheckout;
use App\Models\Cart;
use App\Models\Checkout;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $checkouts = Checkout::where("ownerID", Auth::user()->id)->get();

        if($checkouts->count() == 0) {
            return $this->error("Checkout is empty!", null, 404);
        }

        return $this->success("Checkout products", $checkouts);
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
    public function store(CreateCheckout $request)
    {
        $request->validated($request->all());

        // check if product already in checkout
        $productInCheckout = Checkout::where("ownerID", Auth::user()->id)->where("productIdInCart", $request->productIdInCart)->first();

        if ($productInCheckout) {
            return $this->error("You already checkout this product",null, 403);
        }

        // if product is not in checkout
        $productsInCart = Cart::where("ownerID", Auth::user()->id)->get();

        if(!$productsInCart) {
            return $this->error("Cart is empty", null, 404);
        }

        $specifiedProduct = $productsInCart->where("productID", $request->productIdInCart)->first();

        if(!$specifiedProduct) {
            return $this->error("Product is not in your cart", null, 403);
        }

        Checkout::create([
            "ownerID" => Auth::user()->id,
            "productIdInCart" => $specifiedProduct->productID,
            "ownerName" => Auth::user()->name
        ]);

        return $this->success("Product checkout success", [
            "productID" => $specifiedProduct->productID
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
