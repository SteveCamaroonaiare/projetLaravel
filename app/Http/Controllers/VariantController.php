<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'color_id' => 'required|exists:colors,id',
            'sizes' => 'array',
            'sizes.*' => 'exists:sizes,id',
            'images' => 'array',
            'images.*' => 'string',
        ]);

        $variant = ProductVariant::create([
            'product_id' => $validated['product_id'],
            'color_id' => $validated['color_id'],
        ]);

        if (isset($validated['sizes'])) {
            $variant->sizes()->attach($validated['sizes']);
        }

        if (isset($validated['images'])) {
            foreach ($validated['images'] as $index => $imagePath) {
                $variant->images()->create([
                    'path' => $imagePath,
                    'is_main' => $index === 0,
                ]);
            }
        }

        return response()->json($variant->load('color', 'sizes', 'images'), 201);
    }

    public function update(Request $request, $id)
    {
        $variant = ProductVariant::findOrFail($id);
        $validated = $request->validate([
            'color_id' => 'exists:colors,id',
            'sizes' => 'array',
            'sizes.*' => 'exists:sizes,id',
        ]);

        if (isset($validated['color_id'])) {
            $variant->update(['color_id' => $validated['color_id']]);
        }

        if (isset($validated['sizes'])) {
            $variant->sizes()->sync($validated['sizes']);
        }

        return response()->json($variant->load('color', 'sizes', 'images'));
    }

    public function destroy($id)
    {
        ProductVariant::destroy($id);
        return response()->json(null, 204);
    }
}