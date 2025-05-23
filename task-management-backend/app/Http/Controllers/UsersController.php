<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

/**  
 * @OA\Info(  
 *     title="Task Management API",  
 *     version="1.0"  
 * )  
 */
class UsersController extends Controller
{
    /**  
     * @OA\Post(  
     *     path="/api/users/login",  
     *     summary="Login",  
     *     tags={"Users"},  
     *     @OA\RequestBody(  
     *         required=true,  
     *         @OA\JsonContent(  
     *             required={"email","password"},  
     *             @OA\Property(property="email", type="string"),  
     *             @OA\Property(property="password", type="string")  
     *         )  
     *     ),  
     *     @OA\Response(  
     *         response=200,  
     *         description="Login successful",  
     *         @OA\JsonContent(  
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(property="access_token", type="string"),
     *             @OA\Property(property="token_type", type="string"),
     *             @OA\Property(property="expires_in", type="integer")  
     *         )  
     *     ),  
     *     @OA\Response(response=401, description="Invalid credentials")  
     * )  
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        $user = Auth::user();

        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer'
        ]);
    }

    /**  
     * @OA\Post(  
     *     path="/api/users/logout",  
     *     summary="Logout",  
     *     tags={"Users"},      
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(  
     *         response=200,  
     *         description="Logged out successful",  
     *         @OA\JsonContent(  
     *             @OA\Property(property="message", type="string")  
     *         )  
     *     )  
     * )  
     */
    public function logout(Request $request)
    {

        try {
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to logout'], 500);
        }

        return response()->json(['message' => 'Logged out successful']);
    }

    /**  
     * @OA\Post(  
     *     path="/api/users/register",  
     *     summary="Register",  
     *     tags={"Users"},  
     *     @OA\RequestBody(  
     *         required=true,  
     *         @OA\JsonContent(  
     *             required={"name","email","password"},  
     *             @OA\Property(property="name", type="string"),  
     *             @OA\Property(property="email", type="string"),  
     *             @OA\Property(property="password", type="string")  
     *         )  
     *     ),  
     *     @OA\Response(  
     *         response=200,  
     *         description="User registered successful",  
     *         @OA\JsonContent(  
     *             @OA\Property(property="message", type="string"),  
     *             @OA\Property(property="user", type="object")  
     *         )  
     *     )  
     * )  
     */
    public function register(Request $request)
    {
        if ($result = $this->validateUser($request)) {
            return $result;
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'User registered successful',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer'
        ]);
    }

    /**  
     * @OA\Put(  
     *     path="/api/users/update",  
     *     summary="Update",  
     *     tags={"Users"},       
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(  
     *         required=true,  
     *         @OA\JsonContent(  
     *             required={"name","email"},  
     *             @OA\Property(property="name", type="string"),  
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string")    
     *         )  
     *     ),  
     *     @OA\Response(  
     *         response=200,  
     *         description="User updated successful"  
     *     )  
     * )  
     */
    public function update(Request $request)
    {
        if ($result = $this->validateUser($request)) {
            return $result;
        }

        $user = User::update([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $user = Auth::user();

        return response()->json(['message' => 'User updated successful', 'user' => $user]);
    }

    protected function validateUser($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
    }
}
