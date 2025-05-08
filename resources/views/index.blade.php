<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เว็บไซต์ของฉัน</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between">
                <div class="flex space-x-7">
                    <div>
                        <a href="/" class="flex items-center py-4 px-2">
                            <span class="font-semibold text-gray-500 text-lg">LOGO</span>
                        </a>
                    </div>                
                </div>

                <div class="hidden md:flex items-center space-x-3">
                    @auth
                    <div class="relative">
                        <button id="profileButton" class="py-2 px-2 font-medium text-gray-500 rounded hover:bg-gray-200 transition duration-300">
                            โปรไฟล์
                        </button>
                        <div id="profileMenu" class="absolute right-0 mt-2 w-48 bg-white border rounded-md shadow-lg hidden z-50">
                            @if(auth()->user()->role === 'admin')
                                <a href="{{route('dashboard')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">แดชบอร์ด</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">ออกจากระบบ</button>
                            </form>
                        </div>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="py-2 px-2 font-medium text-gray-500 rounded hover:bg-gray-200 transition duration-300">
                        เข้าสู่ระบบ
                    </a>
                    <a href="{{ route('register') }}" class="py-2 px-2 font-medium text-white bg-blue-500 rounded hover:bg-blue-400 transition duration-300">
                        สมัครสมาชิก
                    </a>
                    @endauth
                </div>

                <div class="md:hidden flex items-center">
                    <button class="outline-none mobile-menu-button">
                        <svg class="w-6 h-6 text-gray-500 hover:text-blue-500"
                            fill="none"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="hidden mobile-menu">
            <ul>
                @auth
                    <li><a href="/profile" class="block text-sm px-2 py-4 hover:bg-gray-200 transition duration-300">โปรไฟล์</a></li>
                    @if(auth()->user()->role === 'admin')
                        <li><a href="{{route('dashboard')}}" class="block text-sm px-2 py-4 hover:bg-gray-200 transition duration-300">แดชบอร์ด</a></li>
                    @endif
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left text-sm px-2 py-4 hover:bg-gray-200 transition duration-300">
                                ออกจากระบบ
                            </button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}" class="block text-sm px-2 py-4 hover:bg-gray-200 transition duration-300">เข้าสู่ระบบ</a></li>
                    <li><a href="{{ route('register') }}" class="block text-sm px-2 py-4 hover:bg-gray-200 transition duration-300">สมัครสมาชิก</a></li>
                @endauth
            </ul>
        </div>
    </nav>

    <div class="py-20 bg-gradient-to-r from-blue-500 to-blue-700">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold mb-2 text-white">ยินดีต้อนรับสู่เว็บไซต์ของเรา</h2>
            <h3 class="text-2xl mb-8 text-gray-200">พบกับสินค้าคุณภาพมากมาย</h3>
        </div>
    </div>

    <div class="container mx-auto px-6 py-12">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">สินค้าของเรา</h2>
        
        @if($products->isEmpty())
            <div class="text-center text-gray-600 py-12">
                <p>ไม่มีสินค้าในขณะนี้</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($products as $product)
                <a href="{{ route('products.show', $product->id) }}" class="block">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform transform hover:scale-105 hover:shadow-lg">
                        <img src="{{ $product->image_path }}" alt="{{ $product->name }}" class="w-full h-64 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $product->name }}</h3>
                            <p class="text-gray-600">{{ Str::limit($product->description, 100) }}</p>
                            <div class="mt-4 flex justify-end">
                                <span class="inline-block bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-sm font-semibold">
                                    ดูรายละเอียด
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        const btn = document.querySelector("button.mobile-menu-button");
        const menu = document.querySelector(".mobile-menu");
        btn.addEventListener("click", () => {
            menu.classList.toggle("hidden");
        });

        const profileButton = document.getElementById("profileButton");
        const profileMenu = document.getElementById("profileMenu");

        if (profileButton && profileMenu) {
            profileButton.addEventListener("click", (e) => {
                e.stopPropagation();
                profileMenu.classList.toggle("hidden");
            });

            document.addEventListener("click", (e) => {
                if (!profileMenu.contains(e.target) && !profileButton.contains(e.target)) {
                    profileMenu.classList.add("hidden");
                }
            });
        }
    </script>
</body>
</html>