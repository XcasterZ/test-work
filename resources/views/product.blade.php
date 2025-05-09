<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - รายละเอียดสินค้า</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        .swal2-confirm {
            background-color: #3b82f6 !important;
            border-color: #3b82f6 !important;
        }

        .swal2-confirm:hover {
            background-color: #2563eb !important;
        }

        .swal2-cancel {
            background-color: #ef4444 !important;
            border-color: #ef4444 !important;
            margin-right: 10px;
        }

        .swal2-cancel:hover {
            background-color: #dc2626 !important;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                role="alert">
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
                        <img src="{{ $product->image_path }}" alt="{{ $product->name }}"
                            class="max-w-full max-h-80 object-contain">
                    </div>
                </div>
                <div class="md:w-1/2 p-6">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $product->name }}</h1>
                    <div class="prose max-w-none mb-6">
                        <p class="text-gray-600">{{ $product->description }}</p>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('home') }}"
                            class="bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded inline-block">
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
                        <form id="reviewForm" action="{{ route('reviews.store', $product->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <textarea name="content" id="reviewContent" rows="4"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="เขียนรีวิวของคุณที่นี่..." required></textarea>
                                <div id="contentError" class="text-red-500 text-sm mt-1"></div>
                            </div>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
                                ส่งรีวิว
                            </button>
                        </form>
                    </div>
                @else
                    <div class="mb-8 bg-gray-50 p-4 rounded-lg text-center">
                        <p class="text-gray-600">กรุณา <a href="{{ route('login') }}"
                                class="text-blue-600 hover:underline">เข้าสู่ระบบ</a> เพื่อเขียนรีวิว</p>
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
                                <div class="text-sm text-gray-500">
                                    {{ $review->created_at->timezone('Asia/Bangkok')->format('d/m/Y H:i') }}</div>
                            </div>
                            <p class="text-gray-600 mb-2">{{ $review->content }}</p>

                            @auth
                                @if (Auth::id() == $review->user_id)
                                    <div class="flex justify-end">
                                        <form action="{{ route('reviews.destroy', $review->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
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

    <script>
        document.getElementById('reviewForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
    
            const form = e.target;
            const submitButton = form.querySelector('button[type="submit"]');
            const contentError = document.getElementById('contentError');
    
            submitButton.disabled = true;
            contentError.textContent = '';
    
            const loadingSwal = Swal.fire({
                title: 'กำลังดำเนินการ...',
                text: 'กรุณารอสักครู่',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
    
            const formData = new FormData(form);
    
            const config = {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            };
    
            axios.post(form.action, formData, config)
                .then(response => {
                    loadingSwal.close(); 
                    if (response.data.success) {
                        Swal.fire({
                            title: 'สำเร็จ!',
                            text: response.data.message,
                            icon: 'success',
                            confirmButtonText: 'ตกลง',
                            confirmButtonColor: '#3b82f6',
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                })
                .catch(error => {
                    loadingSwal.close(); 
                    let errorMessage = 'เกิดข้อผิดพลาดในการส่งรีวิว';
    
                    if (error.response) {
                        if (error.response.data.errors) {
                            if (error.response.data.errors.content) {
                                contentError.textContent = error.response.data.errors.content[0];
                            }
                            errorMessage = 'กรุณาตรวจสอบข้อมูลให้ถูกต้อง';
                        } else if (error.response.data.message) {
                            errorMessage = error.response.data.message;
                        }
                    }
    
                    Swal.fire({
                        title: 'เกิดข้อผิดพลาด!',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#3b82f6',
                    });
                })
                .finally(() => {
                    submitButton.disabled = false;
                });
        });
    
        document.querySelectorAll('form[action*="reviews"]').forEach(form => {
            if (form.getAttribute('method') === 'POST' &&
                form.querySelector('input[name="_method"][value="DELETE"]')) {
    
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
    
                    const actionUrl = form.getAttribute('action');
    
                    Swal.fire({
                        title: 'คุณแน่ใจหรือไม่?',
                        text: "คุณจะไม่สามารถกู้คืนรีวิวนี้ได้!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3b82f6',
                        cancelButtonColor: '#ef4444',
                        confirmButtonText: 'ใช่, ลบเลย!',
                        cancelButtonText: 'ยกเลิก',
                        customClass: {
                            cancelButton: 'hover:bg-red-100'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const deleteLoading = Swal.fire({
                                title: 'กำลังลบ...',
                                text: 'กรุณารอสักครู่',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
    
                            const formData = new FormData(form);
    
                            axios.post(actionUrl, formData)
                                .then(response => {
                                    deleteLoading.close(); 
                                    Swal.fire({
                                        title: 'ลบแล้ว!',
                                        text: response.data.message,
                                        icon: 'success',
                                        confirmButtonText: 'ตกลง',
                                        confirmButtonColor: '#3b82f6',
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                })
                                .catch(error => {
                                    deleteLoading.close(); 
                                    Swal.fire({
                                        title: 'เกิดข้อผิดพลาด!',
                                        text: error.response?.data?.message ||
                                            'ไม่สามารถลบรีวิวได้',
                                        icon: 'error',
                                        confirmButtonText: 'ตกลง',
                                        confirmButtonColor: '#3b82f6',
                                    });
                                });
                        }
                    });
                });
            }
        });
    </script>
</body>

</html>
