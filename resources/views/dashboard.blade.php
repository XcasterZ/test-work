<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - เพิ่มสินค้า</title>
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
    @php
        if(!auth()->user() || auth()->user()->role !== 'admin') {
            header('Location: ' . route('home'));
            exit;
        }
    @endphp

    <nav class="bg-white shadow-lg py-4 md:py-0">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between">
                <div class="flex md:hidden items-center">
                    <button type="button" class="mobile-menu-button outline-none" aria-controls="mobile-menu"
                        aria-expanded="false">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <div class="flex space-x-7">
                    <div class="hidden md:flex items-center">
                        <a href="/"
                            class="flex items-center py-4 px-2 text-gray-700 hover:text-blue-500 transition duration-300">
                            <span class="font-semibold">หน้าแรก</span>
                        </a>

                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="py-2 px-2 font-medium text-gray-500 rounded hover:bg-gray-200 transition duration-300">
                            ออกจากระบบ
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="hidden mobile-menu md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 py-20">
                <a href="/"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-500 hover:bg-gray-50">หน้าแรก</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-500 hover:bg-gray-50">
                        ออกจากระบบ
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">จัดการสินค้า</h1>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">เพิ่มสินค้าใหม่</h2>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="productForm" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">ชื่อสินค้า</label>
                    <input type="text" id="name" name="name" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">รายละเอียด</label>
                    <textarea id="description" name="description" rows="3" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-gray-700 text-sm font-bold mb-2">รูปภาพสินค้า (ไม่เกิน 1MB)</label>
                    <input type="file" id="image" name="image" required accept="image/*"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-sm text-gray-500 mt-1">ขนาดไฟล์สูงสุด: 1MB</p>
                </div>

                <button type="submit"
                    class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    เพิ่มสินค้า
                </button>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">รายการสินค้าทั้งหมด</h2>

            @if ($products->isEmpty())
                <p class="text-gray-500">ยังไม่มีสินค้า</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th
                                    class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    ID</th>
                                <th
                                    class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    ชื่อสินค้า</th>
                                <th
                                    class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    รายละเอียด</th>
                                <th
                                    class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    รูปภาพ</th>
                                <th
                                    class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    การกระทำ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td class="py-2 px-4 border-b border-gray-200">{{ $product->id }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200">{{ $product->name }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200">
                                        {{ Str::limit($product->description, 50) }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200">
                                        <img src="{{ $product->image_path }}" alt="{{ $product->name }}"
                                            class="h-16 w-16 object-cover">
                                    </td>
                                    <td class="py-2 px-4 border-b border-gray-200">
                                        <button type="button" data-product-id="{{ $product->id }}" class="deleteProductBtn text-red-600 hover:text-red-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.getElementById('productForm')?.addEventListener('submit', function(e) {
            const fileInput = document.getElementById('image');
            const maxSize = 1 * 1024 * 1024; // 1MB

            if (fileInput.files.length > 0) {
                const fileSize = fileInput.files[0].size;
                
                if (fileSize > maxSize) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'ขนาดไฟล์เกินกำหนด!',
                        text: 'กรุณาอัพโหลดไฟล์ขนาดไม่เกิน 1MB',
                        icon: 'error',
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#3b82f6',
                    });
                    return;
                }
            }

            e.preventDefault();

            const form = e.target;
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.disabled = true;

            const loadingSwal = Swal.fire({
                title: 'กำลังดำเนินการ...',
                text: 'กรุณารอสักครู่',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const formData = new FormData(form);

            axios.post(form.action, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => {
                    loadingSwal.close();
                    Swal.fire({
                        title: 'สำเร็จ!',
                        text: 'เพิ่มสินค้าเรียบร้อยแล้ว',
                        icon: 'success',
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#3b82f6',
                    }).then(() => {
                        window.location.reload();
                    });
                })
                .catch(error => {
                    loadingSwal.close();
                    let errorMessage = 'เกิดข้อผิดพลาดในการเพิ่มสินค้า';

                    if (error.response) {
                        if (error.response.data.errors) {
                            errorMessage = Object.values(error.response.data.errors).join('<br>');
                        } else if (error.response.data.message) {
                            errorMessage = error.response.data.message;
                        }
                    }

                    Swal.fire({
                        title: 'เกิดข้อผิดพลาด!',
                        html: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#3b82f6',
                    });
                })
                .finally(() => {
                    submitButton.disabled = false;
                });
        });

        document.querySelectorAll('.deleteProductBtn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const productId = this.getAttribute('data-product-id');
                const deleteUrl = "{{ route('products.destroy', ':id') }}".replace(':id', productId);

                Swal.fire({
                    title: 'คุณแน่ใจหรือไม่?',
                    text: "คุณจะไม่สามารถกู้คืนสินค้านี้ได้!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3b82f6',
                    cancelButtonColor: '#ef4444',
                    confirmButtonText: 'ใช่, ลบเลย!',
                    cancelButtonText: 'ยกเลิก'
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

                        const formData = new FormData();
                        formData.append('_method', 'DELETE');
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                        
                        axios.post(deleteUrl, formData, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(response => {
                            deleteLoading.close();
                            Swal.fire({
                                title: 'ลบแล้ว!',
                                text: 'ลบสินค้าเรียบร้อยแล้ว',
                                icon: 'success',
                                confirmButtonText: 'ตกลง',
                                confirmButtonColor: '#3b82f6',
                            }).then(() => {
                                window.location.reload();
                            });
                        })
                        .catch(error => {
                            deleteLoading.close();
                            console.error('Delete error:', error);
                            
                            let errorMessage = 'ไม่สามารถลบสินค้าได้';
                            if (error.response && error.response.data && error.response.data.message) {
                                errorMessage = error.response.data.message;
                            }
                            
                            Swal.fire({
                                title: 'เกิดข้อผิดพลาด!',
                                text: errorMessage,
                                icon: 'error',
                                confirmButtonText: 'ตกลง',
                                confirmButtonColor: '#3b82f6',
                            });
                        });
                    }
                });
            });
        });

        const btn = document.querySelector('.mobile-menu-button');
        const menu = document.querySelector('.mobile-menu');

        btn?.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>
</body>

</html>