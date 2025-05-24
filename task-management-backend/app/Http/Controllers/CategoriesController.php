<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoriesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     tags={"Categories"},
     *     summary="Categories list",
     *     security={{"bearerAuth": {}}},     
     *     @OA\Response(
     *         response=200,
     *         description="Categories list",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string")
     *             )
     *         )
     *     )     
     * )
     */
    public function index()
    {
        $categories = Category::all();

        return response()->json($categories);
    }
}
