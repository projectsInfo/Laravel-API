<?php

namespace App\Http\Controllers;

use App\Product;
use http\Env\Response;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = auth()->user()->products;

        return response()->json($products);
    }

    public function show($id)
    {
        $product = auth()->user()->products()->find($id);

        if (!$product) {
            return response()->json('sorry', 400);
        }

        return response()->json([$product->toArray()], 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'details' => 'required',
            'price' => 'required|integer',
            'image' => 'required',
            'tmp_name' => 'required'
        ]);

        $product = new Product();

        // Store Images
         $data = $_POST;
        $imageName = $_POST['image'];
        $nameBase64 = $_POST['tmp_name'];
        $realimage = base64_decode($nameBase64);

        $nameBase64Split = explode(' ', $nameBase64, 100);
//        $nameBase64Split = explode(' ', trim($nameBase64))[0];
//        $nameBase64Split = $nameBase64[10];

        // Upload Image
         file_put_contents("uploads/" . $imageName,$realimage);
         $product->image = $imageName;


        $product->name = $request->name;
        $product->details = $request->details;
        $product->price = $request->price;
        $product->tmp_name = $nameBase64;



        if (auth()->user()->products()->save($product))
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
        $product = auth()->user()->products()->find($id);

        if (!$product) {
            return response()->json('sorry', 400);
        }

        $updated = $product->fill($request->all())->save();

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
        $product = auth()->user()->products()->find($id);

        if (!$product) {
            return response()->json('sorry'
                //     [
                //     'success' => false,
                //     'message' => 'Product with id ' . $id . ' not found'
                // ]
                , 400);
        }

        if ($product->delete()) {
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


    public function search(Request $request , $data)
    {
        $product = Product::where('name', 'like', "%{$data}%")
//                 ->orWhere('last_name', 'like', "%{$data}%"))
                 ->get();

            return Response()->json([
                'data' => $product
            ]);
    }
}




