<?php

namespace App\Http\Controllers;

use App\fruits;
use App\Product;
use http\Env\Response;
use Illuminate\Http\Request;

class fruitsController extends Controller
{
    public function index()
    {
        $fruits = auth()->user()->fruits;

        return response()->json($fruits);
    }

    public function show($id)
    {
        $fruits = auth()->user()->vegetables()->find($id);

        if (!$fruits) {
            return response()->json('sorry', 400);
        }

        return response()->json([$fruits->toArray()], 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'details' => 'required',
            'price' => 'required|integer',
            'image' => 'required'
        ]);

        $fruits = new fruits();

        // Store Images
        $file = $request->file('image')->getClientOriginalName();
        $filename = pathinfo($file, PATHINFO_FILENAME);
        $extention = $request->file('image')->getClientOriginalExtension();
        $filenametostore = $filename . '_' . time() . '.' . $extention;
        $request->file('image')->storeAs('public/images', $filenametostore);

        $fruits->name = $request->name;
        $fruits->details = $request->details;
        $fruits->price = $request->price;
        $fruits->image = $request->file('image')->storeAs('', $filenametostore);

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

        if (auth()->user()->fruits()->save($fruits) && auth()->user()->products()->save($products))
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
        $fruits = auth()->user()->fruits()->find($id);

        if (!$fruits) {
            return response()->json('sorry', 400);
        }

        $updated = $fruits->fill($request->all())->save();

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
        $fruits = auth()->user()->fruits()->find($id);

        if (!$fruits) {
            return response()->json('sorry'
                //     [
                //     'success' => false,
                //     'message' => 'Product with id ' . $id . ' not found'
                // ]
                , 400);
        }

        if ($fruits->delete()) {
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
