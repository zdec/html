<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Product::class);

        $products = Product::with(['category', 'images'])->orderBy('name')->paginate(10);
        $products->setPath(route('admin.products.index'));
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $this->authorize('create', Product::class);

        $categories = Category::orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();

        $product = Product::create([
            'name' => $validated['name'],
            'slug' => $this->uniqueSlug(Str::slug($validated['name'])),
            'category_id' => $validated['category_id'] ?? null,
            'sku' => $validated['sku'] ?? Str::upper(Str::substr(Str::slug($validated['name']), 0, 20)),
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'old_price' => $validated['old_price'] ?? null,
            'stock' => (int) $validated['stock'],
            'active' => $request->boolean('active'),
        ]);

        $this->storeProductImages($product, $request->file('image_main'), $request->file('image_gallery'));

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'redirect' => route('admin.products.index'),
                'message' => 'Producto creado correctamente.',
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Producto creado correctamente.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'images']);

        return redirect()->route('admin.products.index');
    }

    public function modal(Product $product)
    {
        $this->authorize('view', $product);

        $product->load(['category', 'images']);

        return view('admin.products.partials.modal-content', compact('product'));
    }

    public function editForm(Product $product)
    {
        $this->authorize('update', $product);

        $product->load('images');
        $categories = Category::orderBy('name')->get();

        return view('admin.products.partials.form-edit', compact('product', 'categories'));
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);

        $product->load('images');
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        $remainingAfterRemove = $product->images()->count() - count($validated['remove_image_ids'] ?? []);
        $newUploads = ($request->hasFile('image_main') ? 1 : 0) + count($request->file('image_gallery') ?? []);
        $totalAfterUpdate = $remainingAfterRemove + $newUploads;
        if ($totalAfterUpdate < 5) {
            return back()->withErrors(['image_main' => 'El producto debe tener al menos 5 imágenes en total (1 principal + 4 de galería). Actualmente quedarían ' . $totalAfterUpdate . '.'])->withInput();
        }

        $product->update([
            'name' => $validated['name'],
            'slug' => $this->uniqueSlug(Str::slug($validated['name']), $product->id),
            'category_id' => $validated['category_id'] ?? null,
            'sku' => $validated['sku'] ?? $product->sku,
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'old_price' => $validated['old_price'] ?? null,
            'stock' => (int) $validated['stock'],
            'active' => $request->boolean('active'),
        ]);

        if (! empty($validated['remove_image_ids'])) {
            foreach ($validated['remove_image_ids'] as $id) {
                $img = $product->images()->find($id);
                if ($img) {
                    $this->deleteProductImageFile($img->path);
                    $img->delete();
                }
            }
        }

        $mainFile = $request->file('image_main');
        $galleryFiles = $request->file('image_gallery');
        if ($mainFile || ($galleryFiles && count($galleryFiles) > 0)) {
            $this->storeProductImages($product, $mainFile, $galleryFiles, $product->images()->count());
        }

        if ($product->images()->count() < 5) {
            $msg = 'El producto debe tener al menos 5 imágenes (1 principal + 4 de galería).';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->withErrors(['image_main' => $msg])->withInput();
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'redirect' => route('admin.products.index'),
                'message' => 'Producto actualizado correctamente.',
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Request $request, Product $product)
    {
        $this->authorize('delete', $product);

        if ((int) $product->stock !== 0) {
            $msg = 'Solo se puede eliminar un producto cuando su stock es 0.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->route('admin.products.index')->with('error', $msg);
        }

        foreach ($product->images as $img) {
            $this->deleteProductImageFile($img->path);
        }
        Storage::disk('public')->deleteDirectory('products/' . $product->id);
        $product->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'redirect' => route('admin.products.index'),
                'message' => 'Producto eliminado correctamente.',
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Producto eliminado correctamente.');
    }

    public function updateStock(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $request->validate([
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        $product->update(['stock' => $request->stock]);

        if ($request->expectsJson() || $request->ajax()) {
            $redirect = $request->header('Referer') ?? route('admin.products.index');
            return response()->json([
                'success' => true,
                'redirect' => $redirect,
                'message' => 'Stock actualizado correctamente.',
            ]);
        }
        return back()->with('success', 'Stock actualizado correctamente.');
    }

    private function storeProductImages(Product $product, $mainFile, $galleryFiles = [], $existingCount = 0): void
    {
        $storagePath = 'products/' . $product->id;
        $order = $existingCount;

        if ($mainFile && $mainFile->isValid()) {
            $filename = $this->uniqueImageNameInStorage($product->id, $mainFile->getClientOriginalExtension());
            Storage::disk('public')->putFileAs($storagePath, $mainFile, $filename);
            $product->images()->create(['path' => 'storage/' . $storagePath . '/' . $filename, 'order' => $order]);
            $order++;
        }

        if ($galleryFiles && is_array($galleryFiles)) {
            foreach ($galleryFiles as $file) {
                if (! $file || ! $file->isValid()) {
                    continue;
                }
                $filename = $this->uniqueImageNameInStorage($product->id, $file->getClientOriginalExtension());
                Storage::disk('public')->putFileAs($storagePath, $file, $filename);
                $product->images()->create(['path' => 'storage/' . $storagePath . '/' . $filename, 'order' => $order]);
                $order++;
            }
        }
    }

    /**
     * Elimina el archivo físico de una imagen (path en public o en storage).
     */
    private function deleteProductImageFile(string $path): void
    {
        if (str_starts_with($path, 'storage/')) {
            Storage::disk('public')->delete(Str::after($path, 'storage/'));
        } elseif (file_exists(public_path($path))) {
            @unlink(public_path($path));
        }
    }

    private function uniqueSlug(string $base, ?int $excludeId = null): string
    {
        $slug = $base;
        $n = 0;
        while (true) {
            $query = Product::where('slug', $slug);
            if ($excludeId !== null) {
                $query->where('id', '!=', $excludeId);
            }
            if (! $query->exists()) {
                break;
            }
            $n++;
            $slug = $base . '-' . $n;
        }

        return $slug;
    }

    private function uniqueImageNameInStorage(int $productId, string $ext): string
    {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $ext = strtolower(preg_replace('/^\./', '', $ext) ?: 'jpg');
        if (! in_array($ext, $allowed, true)) {
            $ext = 'jpg';
        }
        $prefix = 'products/' . $productId . '/';
        do {
            $name = uniqid('img_', true) . '.' . $ext;
        } while (Storage::disk('public')->exists($prefix . $name));

        return $name;
    }
}
