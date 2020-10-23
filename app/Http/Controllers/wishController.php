<?php

namespace App\Http\Controllers;

use App\wish;
use Illuminate\Http\Request;

class wishController extends Controller
{
    public function index()
    {
        $wish = auth()->user()->wish;

        return response()->json($wish);
    }

    public function show($id)
    {
        $wish = auth()->user()->wish()->find($id);

        if (!$wish) {
            return response()->json('sorry', 400);
        }

        return response()->json([$wish->toArray()], 200);
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

        $wish = new wish();


        // Store Images
//        $file = $request->file('image')->getClientOriginalName();
//        $filename = pathinfo($file, PATHINFO_FILENAME);
//        $extention = $request->file('image')->getClientOriginalExtension();
//        $filenametostore = $filename . '_' . time() . '.' . $extention;
//        $request->file('image')->storeAs('public/images', $filenametostore);
//
//        $wish->name = $request->name;
//        $wish->details = $request->details;
//        $wish->price = $request->price;
//        $wish->image = $request->file('image')->storeAs('', $filenametostore);


        // Store Images
        $data = $_POST;
        $imageName = $_POST['image'];
        $nameBase64 = $_POST['tmp_name'];
        $realimage = base64_decode($nameBase64);

//        $nameBase64Split = $nameBase64[10];

        // Upload Image
        file_put_contents("uploads/" . $imageName,$realimage);
        $wish->image = $imageName;

        $wish->name = $request->name;
        $wish->details = $request->details;
        $wish->price = $request->price;
        $wish->tmp_name = $nameBase64;


        if (auth()->user()->wish()->save($wish))
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
        $wish = auth()->user()->wish()->find($id);

        if (!$wish) {
            return response()->json('sorry', 400);
        }

        $updated = $wish->fill($request->all())->save();

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
        $wish = auth()->user()->wish()->find($id);

        if (!$wish) {
            return response()->json('sorry'
                //     [
                //     'success' => false,
                //     'message' => 'Product with id ' . $id . ' not found'
                // ]
                , 400);
        }

        if ($wish->delete()) {
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
