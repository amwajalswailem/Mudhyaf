<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Mudhyaf</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@700&family=Noto+Sans+Arabic:wght@400;700&display=swap"
        rel="stylesheet">
    <link href="{{asset('css/auth.css')}}" rel="stylesheet">
</head>

<body class="min-h-screen flex items-center justify-center p-4 lg:p-8">

<div class="max-w-6xl w-full bg-white rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col md:flex-row">

    <!-- Left Side: Branding/Visual -->
    <div class="md:w-5/12 register-split p-10 lg:p-14 text-white flex flex-col justify-between">

        <div>
            <a href="/" class="text-3xl flex items-center justify-center gap-3 mb-5">
                <img src="{{asset('logo.png')}}" alt="Mudhyaf Logo" class="h-20 w-auto">
            </a>
            <h3 class="text-3xl font-bold mb-6 font-serif leading-tight text-center">Start Your Journey in Mudhyaf</h3>
            <p class="text-white/80 leading-relaxed text-center">Create an account to unlock personalized
                recommendations, save
                your favorite spots, or list your local business on the region's premier smart platform.</p>
        </div>
    </div>

    <!-- Right Side: Registration Form -->
    <div class="md:w-7/12 p-8 md:p-12 lg:p-16 overflow-y-auto max-h-[90vh]">
        <div class="max-w-md mx-auto">
            <div class="mb-8 text-center md:text-left">
                <h3 class="text-3xl font-bold text-[--deep-green] mb-2">Create Account</h3>
                <p class="text-gray-500">Join the Mudhyaf community today</p>
            </div>

            <!-- Role Selector Tabs -->
            <div class="flex bg-gray-100 p-1.5 rounded-2xl mb-8">
                <button id="tourist-tab"
                        type="button"
                        class="flex-1 py-2.5 px-4 rounded-xl text-sm font-bold transition-all bg-white shadow-sm text-[--deep-green]">
                    As
                    Tourist
                </button>
                <button id="owner-tab"
                        type="button"
                        class="flex-1 py-2.5 px-4 rounded-xl text-sm font-bold transition-all text-gray-500 hover:text-[--deep-green]">
                    As
                    Business Owner
                </button>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf
                <!-- Common Fields -->
                <input type="hidden" name="role" id="role-input" value="user">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                        <input placeholder="Enter Your FullName" type="text" name="full_name" value="{{ old('full_name') }}"
                               class="w-full px-4 py-3 rounded-xl border">
                        @error('full_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                    <input name="email" value="{{ old('email') }}" type="email" placeholder="name@example.com"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[--primary-gold] focus:ring-2 focus:ring-[--primary-gold]/20 outline-none transition-all">
                    @error('email')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Business Specific Fields (Hidden by default) -->
                <div id="business-fields" class="hidden-form space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Business Name</label>
                        <input name="business_name" value="{{ old('business_name') }}" type="text" placeholder="e.g., Al-Qishlah Café"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[--primary-gold] focus:ring-2 focus:ring-[--primary-gold]/20 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Business Category</label>
                        <select name="category_id"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white">

                            <option value="">Select Category</option>

                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach

                        </select>

                        @error('category_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" placeholder="Min. 8 characters"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[--primary-gold] focus:ring-2 focus:ring-[--primary-gold]/20 outline-none transition-all">
                    @error('password')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Password Confirmation</label>
                    <input type="password" name="password_confirmation" placeholder="Min. 8 characters"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[--primary-gold] focus:ring-2 focus:ring-[--primary-gold]/20 outline-none transition-all">
                    @error('password_confirmation')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-start gap-3 py-2">
                    <input required type="checkbox" id="terms"
                           class="mt-1 w-4 h-4 rounded text-[--primary-gold] focus:ring-[--primary-gold]">
                    <label for="terms" class="text-sm text-gray-600">I agree to the <a href="#"
                                                                                       class="text-[--primary-gold] font-bold hover:underline">Terms
                            of Service</a> and <a href="#"
                                                  class="text-[--primary-gold] font-bold hover:underline">Privacy
                            Policy</a>.</label>
                </div>

                <button type="submit"
                        class="w-full py-4 btn-primary rounded-2xl font-bold text-lg shadow-xl shadow-[--deep-green]/20">
                    Create Account
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-gray-500">Already have an account?
                    <a href="{{route('login')}}" class="text-[--primary-gold] font-bold hover:underline">Sign In</a></p>
            </div>


        </div>
    </div>
</div>

<script>
    const touristTab = document.getElementById('tourist-tab');
    const ownerTab = document.getElementById('owner-tab');
    const businessFields = document.getElementById('business-fields');
    const roleInput = document.getElementById('role-input');

    touristTab.addEventListener('click', () => {
        touristTab.classList.add('bg-white', 'shadow-sm', 'text-[--deep-green]');
        touristTab.classList.remove('text-gray-500');
        ownerTab.classList.remove('bg-white', 'shadow-sm', 'text-[--deep-green]');
        ownerTab.classList.add('text-gray-500');
        businessFields.classList.add('hidden-form');
        roleInput.value = 'user';
    });

    ownerTab.addEventListener('click', () => {
        ownerTab.classList.add('bg-white', 'shadow-sm', 'text-[--deep-green]');
        ownerTab.classList.remove('text-gray-500');
        touristTab.classList.remove('bg-white', 'shadow-sm', 'text-[--deep-green]');
        touristTab.classList.add('text-gray-500');
        businessFields.classList.remove('hidden-form');
        roleInput.value = 'business_owner';
    });


</script>
</body>

</html>
