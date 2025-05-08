<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function store(Request $request, $productId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        try {
            $product = Product::findOrFail($productId);
            
            $review = new Review();
            $review->content = $request->content;
            $review->user_id = Auth::id();
            $review->product_id = $product->id;
            $review->save();

            return response()->json([
                'message' => 'เพิ่มรีวิวสำเร็จ',
                'review' => $review
            ], 201);
        } catch (\Exception $e) {
            Log::error('❌ เพิ่มรีวิวล้มเหลว', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'เกิดข้อผิดพลาดในการเพิ่มรีวิว',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function destroy($id)
    {
        try {
            $review = Review::findOrFail($id);
            
            if ($review->user_id !== Auth::id()) {
                return response()->json([
                    'message' => 'คุณไม่มีสิทธิ์ลบรีวิวนี้'
                ], 403);
            }
            
            $review->delete();
            
            return response()->json([
                'message' => 'ลบรีวิวเรียบร้อยแล้ว'
            ], 200);
        } catch (\Exception $e) {
            Log::error('❌ ลบรีวิวล้มเหลว', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'เกิดข้อผิดพลาดในการลบรีวิว',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}