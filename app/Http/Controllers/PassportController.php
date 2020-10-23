<?php


namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class PassportController extends Controller
{
    /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'image' => 'required',
            'tmp_name' => 'required',
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone' => 'required|integer|min:11'
        ]);

        // Store Images
        $data = $_POST;
        $imageName = $_POST['image'];
        $nameBase64 = $_POST['tmp_name'];
        $realimage = base64_decode($nameBase64);

        // Upload Image
        file_put_contents("uploads/" . $imageName,$realimage);
//        $product->image = $imageName;

        $user = User::create([
            'image' => $imageName,
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone
        ]);

        $token = $user->createToken('MySecret')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    /**
     * Handles Login Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('MySecret')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'UnAuthorised'], 401);
        }
    }

    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function details()
    {
//        return response()->json(['user' => auth()->user()], 200);
        return response()->json([auth()->user()], 200);
    }
}
