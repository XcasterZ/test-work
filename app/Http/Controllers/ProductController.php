<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

// class ProductController extends Controller
// {
//     public function index()
//     {
//         $products = Product::latest()->get();

//         return response()->json([
//             'data' => $products
//         ]);
//     }


//     public function store(Request $request)
//     {
//         Log::info('📥 Store function called');
        
//         $request->validate([
//             'name' => 'required|string|max:255',
//             'description' => 'required|string',
//             'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
//         ]);

//         try {
//             DB::beginTransaction();

//             $filename = Str::random(40) . '.' . $request->file('image')->getClientOriginalExtension();
//             Log::info('📝 Generated filename: ' . $filename);

//             $s3Path = 'test/' . $filename;
//             Log::info('📂 S3 path (folder + filename): ' . $s3Path);

//             $uploaded = Storage::disk('s3')->putFileAs('test', $request->file('image'), $filename);

//             if (!$uploaded) {
//                 throw new \Exception('❌ Failed to upload file to S3');
//             }

//             $url = Storage::disk('s3')->url($s3Path);
//             Log::info('✅ File uploaded to S3 successfully', [
//                 's3_path' => $s3Path,
//                 'public_url' => $url,
//             ]);

//             $product = Product::create([
//                 'name' => $request->name,
//                 'description' => $request->description,
//                 'image_path' => $url
//             ]);

//             DB::commit();

//             return response()->json([
//                 'message' => 'Product created successfully',
//                 'product' => [
//                     'id' => $product->id,
//                     'name' => $product->name,
//                     'description' => $product->description,
//                     'image_path' => $product->image_path,
//                 ]
//             ], 201);  

//         } catch (\Illuminate\Validation\ValidationException $e) {
//             DB::rollBack();
//             Log::error('⚠️ Validation error', [
//                 'error' => $e->getMessage(),
//                 'errors' => $e->errors()
//             ]);

//             return response()->json([
//                 'message' => 'Validation failed',
//                 'errors' => $e->errors()
//             ], 422); 

//         } catch (\Exception $e) {
//             DB::rollBack();
//             Log::error('❌ Error creating product', [
//                 'error' => $e->getMessage(),
//                 'trace' => $e->getTraceAsString()
//             ]);

//             return response()->json([
//                 'message' => 'Error occurred while creating product',
//                 'error' => $e->getMessage(),
//             ], 500); 
//         }
//     }


    
//     public function destroy($id)
//     {
//         try {
//             DB::beginTransaction();
    
//             $product = Product::findOrFail($id);
    
//             $imagePath = parse_url($product->image_path, PHP_URL_PATH);
//             $imagePath = ltrim($imagePath, '/');
    
//             Log::info('🔍 กำลังตรวจสอบและพยายามลบรูปภาพจาก S3', ['path' => $imagePath]);
    
//             if (Storage::disk('s3')->exists($imagePath)) {
//                 Storage::disk('s3')->delete($imagePath);
//                 Log::info('✅ ลบรูปภาพจาก S3 สำเร็จ', ['path' => $imagePath]);
//             } else {
//                 Log::warning('⚠️ ไม่พบรูปภาพบน S3 จึงไม่สามารถลบได้', ['path' => $imagePath]);
//             }
    
//             $product->delete();
//             Log::info('🗑️ ลบข้อมูลสินค้าในฐานข้อมูลสำเร็จ', ['product_id' => $product->id]);
    
//             DB::commit();
    
      
//             return response()->json([
//                 'message' => 'Product deleted successfully',
//                 'product_id' => $product->id
//             ], 200); 
    
//         } catch (\Exception $e) {
//             DB::rollBack();
//             Log::error('❌ เกิดข้อผิดพลาดในการลบสินค้า', [
//                 'error' => $e->getMessage(),
//                 'trace' => $e->getTraceAsString()
//             ]);
    
//             return response()->json([
//                 'message' => 'Error deleting product',
//                 'error' => $e->getMessage()
//             ], 500); 
//         }
//     }
    

//     public function show($id)
//     {
//         $product = Product::with(['reviews.user'])->findOrFail($id);

//         return response()->json([
//             'product' => $product,
//             'reviews' => $product->reviews
//         ], 200);
//     }

// }

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('dashboard', compact('products'));
    }

    public function create()
    {
        return view('dashboard');
    }

    public function store(Request $request)
    {
        Log::info('📥 Store function called');
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            DB::beginTransaction();

            $filename = Str::random(40) . '.' . $request->file('image')->getClientOriginalExtension();
            Log::info('📝 Generated filename: ' . $filename);

            $s3Path = 'test/' . $filename;
            $uploaded = Storage::disk('s3')->putFileAs('test', $request->file('image'), $filename);

            if (!$uploaded) {
                throw new \Exception('Failed to upload file to S3');
            }

            $url = Storage::disk('s3')->url($s3Path);
            
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'image_path' => $url
            ]);

            DB::commit();

            return redirect()->route('dashboard')
                            ->with('success', 'Product created successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating product: ' . $e->getMessage());
            return back()->with('error', 'Error occurred while creating product');
        }
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        
        $reviews = Review::with('user')
                    ->where('product_id', $id)
                    ->latest()
                    ->paginate(10); 

        return view('product', [
            'product' => $product,
            'reviews' => $reviews
        ]);
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
    
            $product = Product::findOrFail($id);
            $imagePath = parse_url($product->image_path, PHP_URL_PATH);
            $imagePath = ltrim($imagePath, '/');
    
            if (Storage::disk('s3')->exists($imagePath)) {
                Storage::disk('s3')->delete($imagePath);
            }
    
            $product->delete();
            DB::commit();
    
            return redirect()->route('dashboard')
                            ->with('success', 'Product deleted successfully!');
    
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting product: ' . $e->getMessage());
            return back()->with('error', 'Error deleting product');
        }
    }
}