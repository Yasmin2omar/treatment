<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Traits\UploadFile;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    use UploadFile;

    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'type' => 'nullable|string',
            ]);
            
            $filename = 'image';
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = $this->UploadImage($image, 'market/images/product');
            }
            // return response()->json( 201);

            $product = Product::create([
                'name' => $request->name,
                'image' => $filename,
                'category_id' => $request->category_id,
                'price' => $request->price,
                'type' => $request->type,
                'description' => $request->description,
            ]);

            return response()->json($product, 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'type' => 'nullable|string',
            ]);

            if ($request->hasFile('image')) {
                if ($product->image) {
                    $this->DeleteImage($product->image, 'market/images/product/');
                }

                $image = $request->file('image');
                $filename = $this->UploadImage($image, 'market/images/product');
                $validatedData['image'] = $filename;
            }

            $product->update($validatedData);

            return response()->json($product);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image) {
            $this->DeleteImage($product->image, 'market/images/product/');
        }

        $product->delete();

        return response()->json(null, 204);
    }
}
