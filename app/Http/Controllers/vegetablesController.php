<?php

namespace App\Http\Controllers;

use App\Product;
use App\vegetables;
use http\Env\Response;
use Illuminate\Http\Request;

class vegetablesController extends Controller
{
    public function index()
    {
        $vegetables = auth()->user()->vegetables;

        return response()->json($vegetables);
    }

    public function show($id)
    {
        $vegetables = auth()->user()->vegetables()->find($id);

        if (!$vegetables) {
            return response()->json('sorry', 400);
        }

        return response()->json([$vegetables->toArray()], 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'details' => 'required',
            'price' => 'required|integer',
            'image' => 'required'
        ]);

        $vegetable = new vegetables();


        // Store Images
        $file = $request->file('image')->getClientOriginalName();
        $filename = pathinfo($file, PATHINFO_FILENAME);
        $extention = $request->file('image')->getClientOriginalExtension();
        $filenametostore = $filename . '_' . time() . '.' . $extention;
        $request->file('image')->storeAs('public/images', $filenametostore);

        $vegetable->name = $request->name;
        $vegetable->details = $request->details;
        $vegetable->price = $request->price;
        $vegetable->image = $request->file('image')->storeAs('', $filenametostore);

        $products = new Product();

        // Store Images
        $file = $request->file('image')->getClientOriginalName();
        $filename = pathinfo($file, PATHINFO_FILENAME);
        $extention = $request->file('image')->getClientOriginalExtension();
        $filenametostore = $filename . '_' . time() . '.' . $extention;
        $request->file('image')->storeAs('public/images', $filenametostore);

        $products->name = $request->name;
        $products->details = $request->details;
        $products->price = $request->price;
        $products->image = $request->file('image')->storeAs('', $filenametostore);


        if (auth()->user()->vegetables()->save($vegetable)&&auth()->user()->products()->save($products))
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
        $vegetables = auth()->user()->vegetables()->find($id);

        if (!$vegetables) {
            return response()->json('sorry', 400);
        }

        $updated = $vegetables->fill($request->all())->save();

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
        $vegetables = auth()->user()->vegetables()->find($id);

        if (!$vegetables) {
            return response()->json('sorry'
                //     [
                //     'success' => false,
                //     'message' => 'Product with id ' . $id . ' not found'
                // ]
                , 400);
        }

        if ($vegetables->delete()) {
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
