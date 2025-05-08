<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - รายละเอียดสินค้า</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
            <div class="md:flex">
                <div class="md:w-1/2">
                    <div class="flex items-center justify-center h-full p-4">
                        <img src="{{ $product->image_path }}" alt="{{ $product->name }}" class="max-w-full max-h-80 object-contain">
                    </div>
                </div>
                <div class="md:w-1/2 p-6">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $product->name }}</h1>
                    <div class="prose max-w-none mb-6">
                        <p class="text-gray-600">{{ $product->description }}</p>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('home') }}" class="bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded inline-block">
                            กลับไปหน้าหลัก
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">รีวิวสินค้า</h2>

                @auth
                    <div class="mb-8 bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-700 mb-4">เพิ่มรีวิวของคุณ</h3>
                        <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <textarea 
                                    name="content" 
                                    rows="4" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="เขียนรีวิวของคุณที่นี่..."
                                    required
                                >{{ old('content') }}</textarea>
                                @error('content')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
                                ส่งรีวิว
                            </button>
                        </form>
                    </div>
                @else
                    <div class="mb-8 bg-gray-50 p-4 rounded-lg text-center">
                        <p class="text-gray-600">กรุณา <a href="{{ route('login') }}" class="text-blue-600 hover:underline">เข้าสู่ระบบ</a> เพื่อเขียนรีวิว</p>
                    </div>
                @endauth

                <div class="space-y-6">
                    @forelse ($reviews as $review)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between mb-2">
                                <div class="font-medium text-gray-700">
                                    {{ $review->user->name }} 
                                    <span class="text-sm text-gray-500">({{ $review->user->username }})</span>
                                </div>
                                <div class="text-sm text-gray-500">{{ $review->created_at->timezone('Asia/Bangkok')->format('d/m/Y H:i') }}</div>
                            </div>
                            <p class="text-gray-600 mb-2">{{ $review->content }}</p>
                            
                            @auth
                                @if (Auth::id() == $review->user_id)
                                    <div class="flex justify-end">
                                        <form action="{{ route('reviews.destroy', $review->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm" onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบรีวิวนี้?')">
                                                ลบรีวิว
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <p class="text-gray-500">ยังไม่มีรีวิวสำหรับสินค้านี้</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $reviews->links() }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>