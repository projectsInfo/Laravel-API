<?php

namespace App\Http\Controllers;

use App\cart;
use Illuminate\Http\Request;

class cartController extends Controller
{
    public function index()
    {
        $cart = auth()->user()->cart;

        return response()->json($cart);
    }

    public function show($id)
    {
        $cart = auth()->user()->cart()->find($id);

        if (!$cart) {
            return response()->json('sorry', 400);
        }

        return response()->json([$cart->toArray()], 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'details' => 'required',
            'price' => 'required|integer',
            'image' => 'required',
            'tmp_name' => 'required',
            'rate' => ''
        ]);

        $cart = new cart();


        // Store Images
//        $file = $request->file('image')->getClientOriginalName();
//        $filename = pathinfo($file, PATHINFO_FILENAME);
//        $extention = $request->file('image')->getClientOriginalExtension();
//        $filenametostore = $filename . '_' . time() . '.' . $extention;
//        $request->file('image')->storeAs('public/images', $filenametostore);
//
//        $cart->name = $request->name;
//        $cart->details = $request->details;
//        $cart->price = $request->price;
//        $cart->image = $request->file('image')->storeAs('', $filenametostore);

        // Store Images
        $data = $_POST;
        $imageName = $_POST['image'];
        $nameBase64 = $_POST['tmp_name'];
        $realimage = base64_decode($nameBase64);

//        $nameBase64Split = $nameBase64[10];

        // Upload Image
        file_put_contents("uploads/" . $imageName,$realimage);
        $cart->image = $imageName;

        $cart->name = $request->name;
        $cart->details = $request->details;
        $cart->price = $request->price;
        $cart->tmp_name = $nameBase64;

        if($cart->rate = $request->rate == ''){
            $cart->rate = 1;
        }else{
            $cart->rate = $request->rate;
        }

        if (auth()->user()->cart()->save($cart))
            return response()->json('done'
            //     [
            //     'success' => true,
            //     'data' => $product->toArray()
            // ]
            );
        else
            return response()->json('sorry'
                //     [
                //     'success' => false,
                //     'message' => 'Product could not be added'
                // ]
                , 500);
    }


    public function update(Request $request, $id)
    {
        $cart = auth()->user()->cart()->find($id);

        if (!$cart) {
            return response()->json('sorry', 400);
        }

        $updated = $cart->fill($request->all())->save();

        if ($updated)
            return response()->json('done'
            //     [
            //     'success' => true
            // ]
            );
        else
            return response()->json('sorry'
                //     [
                //     'success' => false,
                //     'message' => 'Product could not be updated'
                // ]
                , 500);
    }

    public function destroy($id)
    {
        $cart = auth()->user()->cart()->find($id);

        if (!$cart) {
            return response()->json('sorry'
                //     [
                //     'success' => false,
                //     'message' => 'Product with id ' . $id . ' not found'
                // ]
                , 400);
        }

        if ($cart->delete()) {
            return response()->json('done'
            //     [
            //     'success' => true
            // ]
            );
        } else {
            return response()->json('sorry'
                //     [
                //     'success' => false,
                //     'message' => 'Product could not be deleted'
                // ]
                , 500);
        }
    }
}
