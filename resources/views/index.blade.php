<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เว็บไซต์ของฉัน</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between">
                <div class="flex space-x-7">
                    <!-- Logo -->
                    <div>
                        <a href="/" class="flex items-center py-4 px-2">
                            <span class="font-semibold text-gray-500 text-lg">LOGO</span>
                        </a>
                    </div>
                    <!-- Primary Nav -->
                    <div class="hidden md:flex items-center space-x-1">
                        <a href="/" class="py-4 px-2 text-blue-500 border-b-4 border-blue-500 font-semibold">หน้าแรก</a>
                        <a href="/products" class="py-4 px-2 text-gray-500 font-semibold hover:text-blue-500 transition duration-300">สินค้า</a>
                    </div>
                </div>
                <!-- Secondary Nav -->
                <div class="hidden md:flex items-center space-x-3">
                    @auth
                        <!-- แสดงเมื่อล็อกอินแล้ว -->
                        <a href="" class="py-2 px-2 font-medium text-gray-500 rounded hover:bg-gray-200 transition duration-300">
                            โปรไฟล์
                        </a>
                        <form method="POST" action="">
                            @csrf
                            <button type="submit" class="py-2 px-2 font-medium text-gray-500 rounded hover:bg-gray-200 transition duration-300">
                                ออกจากระบบ
                            </button>
                        </form>
                    @else
                        <!-- แสดงเมื่อยังไม่ล็อกอิน -->
                        <a href="" class="py-2 px-2 font-medium text-gray-500 rounded hover:bg-gray-200 transition duration-300">
                            เข้าสู่ระบบ
                        </a>
                        <a href="{{ route('register') }}" class="py-2 px-2 font-medium text-white bg-blue-500 rounded hover:bg-blue-400 transition duration-300">
                            สมัครสมาชิก
                        </a>
                    @endauth
                </div>
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button class="outline-none mobile-menu-button">
                        <svg class=" w-6 h-6 text-gray-500 hover:text-blue-500 "
                            x-show="!showMenu"
                            fill="none"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div class="hidden mobile-menu">
            <ul class="">
                <li class="active"><a href="/" class="block text-sm px-2 py-4 text-white bg-blue-500 font-semibold">หน้าแรก</a></li>
                <li><a href="/products" class="block text-sm px-2 py-4 hover:bg-gray-200 transition duration-300">สินค้า</a></li>
                @auth
                    <li><a href="/profile" class="block text-sm px-2 py-4 hover:bg-gray-200 transition duration-300">โปรไฟล์</a></li>
                    <li>
                        <form method="POST" action="">
                            @csrf
                            <button type="submit" class="block w-full text-left text-sm px-2 py-4 hover:bg-gray-200 transition duration-300">
                                ออกจากระบบ
                            </button>
                        </form>
                    </li>
                @else
                    <li><a href="" class="block text-sm px-2 py-4 hover:bg-gray-200 transition duration-300">เข้าสู่ระบบ</a></li>
                    <li><a href="{{ route('register') }}" class="block text-sm px-2 py-4 hover:bg-gray-200 transition duration-300">สมัครสมาชิก</a></li>
                @endauth
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="py-20 bg-gradient-to-r from-blue-500 to-blue-700">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold mb-2 text-white">
                ยินดีต้อนรับสู่เว็บไซต์ของเรา
            </h2>
            <h3 class="text-2xl mb-8 text-gray-200">
                พบกับสินค้าคุณภาพมากมาย
            </h3>
            <a href="/products" class="bg-white font-bold rounded-full py-4 px-8 shadow-lg uppercase tracking-wider hover:bg-gray-100 transition duration-300">
                ดูสินค้าทั้งหมด
            </a>
        </div>
    </div>

    <!-- JavaScript สำหรับ Mobile Menu -->
    <script>
        const btn = document.querySelector("button.mobile-menu-button");
        const menu = document.querySelector(".mobile-menu");

        btn.addEventListener("click", () => {
            menu.classList.toggle("hidden");
        });
    </script>
</body>
</html>