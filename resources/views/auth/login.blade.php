<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Mudhyaf</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@700&family=Noto+Sans+Arabic:wght@400;700&display=swap"
        rel="stylesheet">
    <link href="{{asset('css/auth1.css')}}" rel="stylesheet">
</head>

<body class="min-h-screen flex items-center justify-center p-4">
<div class="max-w-5xl w-full bg-white rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col md:flex-row">
    <!-- Left Side: Branding/Visual -->
    <div class="md:w-5/12 login-split p-12 text-white flex flex-col justify-between">
        <div>
            <a href="/" class="text-3xl flex items-center justify-center gap-3 mb-5">
                <img src="{{asset('logo.png')}}" alt="Mudhyaf Logo" class="h-20 w-auto">
            </a>
            <h3 class="text-3xl font-bold mb-6 font-serif leading-tight text-center">Welcome Back to Mudhyaf</h3>
            <p class="text-white/80 leading-relaxed text-center">Login to access your personalized itineraries, favorite
                landmarks, and
                exclusive local offers.</p>
        </div>


    </div>

    <!-- Right Side: Login Form -->
    <div class="md:w-7/12 p-8 md:p-16">
        <div class="max-w-md mx-auto">
            <div class="mb-10 text-center md:text-left">
                <h3 class="text-3xl font-bold text-[--deep-green] mb-2">Sign In</h3>
                <p class="text-gray-500">Please select your account type to continue</p>
            </div>

            <!-- Role Selector Tabs -->
            <div class="flex bg-gray-100 p-1.5 rounded-2xl mb-8">
                <button id="user-tab"
                        class="flex-1 py-2.5 px-4 rounded-xl text-sm font-bold transition-all bg-white shadow-sm text-[--deep-green]">
                    Tourist
                </button>
                <button id="business-tab"
                        class="flex-1 py-2.5 px-4 rounded-xl text-sm font-bold transition-all text-gray-500 hover:text-[--deep-green]">
                    Business
                </button>
                <button id="admin-tab"
                        class="flex-1 py-2.5 px-4 rounded-xl text-sm font-bold transition-all text-gray-500 hover:text-[--deep-green]">
                    Admin
                </button>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                @error('email') <p class="text-red-500">{{ $message }}</p> @enderror
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                    <input required name="email" value="{{ old('email') }}" type="email" placeholder="name@example.com"
                           class="w-full px-5 py-4 rounded-2xl border border-gray-200 focus:border-[--primary-gold] focus:ring-2 focus:ring-[--primary-gold]/20 outline-none transition-all">

                </div>

                <div>
                    <div class="flex justify-between mb-2">
                        <label class="text-sm font-bold text-gray-700">Password</label>
                        <a href="#" class="text-xs font-bold text-[--primary-gold] hover:underline">Forgot?</a>
                    </div>
                    <input required name="password" type="password" placeholder="••••••••"
                           class="w-full px-5 py-4 rounded-2xl border border-gray-200 focus:border-[--primary-gold] focus:ring-2 focus:ring-[--primary-gold]/20 outline-none transition-all">
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" id="remember"
                           class="w-4 h-4 rounded text-[--primary-gold] focus:ring-[--primary-gold]">
                    <label for="remember" class="text-sm text-gray-600">Remember me for 30 days</label>
                </div>

                <button type="submit"
                        class="w-full py-4 btn-primary rounded-2xl font-bold text-lg shadow-xl shadow-[--primary-gold]/20">
                    Sign In
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-gray-500">Don't have an account? <a href="{{route('register')}}"
                                                                   class="text-[--deep-green] font-bold hover:underline">Create
                        Account</a></p>
            </div>


        </div>
    </div>
</div>

<script>
    // Simple tab switching logic
    const tabs = ['user-tab', 'business-tab', 'admin-tab'];
    tabs.forEach(tabId => {
        document.getElementById(tabId).addEventListener('click', function () {
            tabs.forEach(t => {
                const el = document.getElementById(t);
                el.classList.remove('bg-white', 'shadow-sm', 'text-[--deep-green]');
                el.classList.add('text-gray-500');
            });
            this.classList.add('bg-white', 'shadow-sm', 'text-[--deep-green]');
            this.classList.remove('text-gray-500');
        });
    });
</script>
</body>

</html>
