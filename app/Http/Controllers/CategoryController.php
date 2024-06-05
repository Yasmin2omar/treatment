<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Traits\UploadFile;

class CategoryController extends Controller
{
    use UploadFile;

    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $filename = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = $this->UploadImage($image, 'market/images/category');
        }

        $validatedData['image'] = $filename;

        $category = Category::create($validatedData);

        return response()->json($category, 201);
    }

    public function show($category)
    {
        $category = Category::with('products')->findOrFail($category);
        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($category->image) {
                $this->DeleteImage($category->image, 'market/images/category/');
            }

            $image = $request->file('image');
            $filename = $this->UploadImage($image, 'market/images/category');
            $validatedData['image'] = $filename;
        }

        $category->update($validatedData);

        return response()->json($category);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->image) {
            $this->DeleteImage($category->image, 'market/images/category/');
        }

        $category->delete();

        return response()->json(null, 204);
    }
}
