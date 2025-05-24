<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Validation\Rule;

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
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(property="access_token", type="string"),
     *             @OA\Property(property="token_type", type="string")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Invalid credentials"),
     *     @OA\Response(response=500, description="Could not create token")
     * )
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

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
            'user' => [
                'name' => $user->name,
                'email' => $user->email
            ],
            'access_token' => $token,
            'token_type' => 'bearer'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/users/logout",
     *     summary="Logout",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
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
     *     path="/api/users",
     *     summary="Register",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="password_confirmation", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(property="access_token", type="string"),
     *             @OA\Property(property="token_type", type="string")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Validation error")
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
            'user' => [
                'name' => $user->name,
                'email' => $user->email
            ],
            'access_token' => $token,
            'token_type' => 'bearer'
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/users",
     *     summary="Update",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="new_password", type="string"),
     *             @OA\Property(property="new_password_confirmation", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Validation error"),
     *     @OA\Response(response=422, description="Password incorrect")
     * )
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        if ($result = $this->validateUser($request, $user->id)) {
            return $result;
        }

        if (!Hash::check($request->input('password'), $user->password)) {
            return response()->json(['password' => ['Password incorrect']], 422);
        }

        $user->name = $request->get('name');
        $user->email = $request->get('email');

        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->get('new_password'));
        }

        $user->save();

        return response()->json([
            'message' => 'User updated successful',
            'user' => [
                'name' => $user->name,
                'email' => $user->email
            ]
        ]);
    }

    protected function validateUser($request, $userId = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId)
            ],
            'password' => $userId
                ? 'required|string|min:6|max:255'
                : 'required|string|min:6|max:255|confirmed',
        ];

        if ($userId) {
            $rules['new_password'] = 'nullable|string|min:6|max:255|confirmed';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
    }
}
