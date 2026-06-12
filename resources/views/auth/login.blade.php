<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-slate-800 mb-2">Selamat Datang! </h2>
        <p class="text-sm font-semibold text-rose-500">Khusus Hak Akses Admin Command Center.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email"
                class="block text-sm font-semibold text-slate-700 mb-1">{{ __('Email Address') }}</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                autocomplete="username"
                class="w-full rounded-xl bg-white/50 border border-slate-300 text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all px-4 py-3 outline-none shadow-sm"
                placeholder="Masukkan email Anda">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-rose-500 text-sm" />
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">{{ __('Password') }}</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="w-full rounded-xl bg-white/50 border border-slate-300 text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all px-4 py-3 outline-none shadow-sm"
                placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-500 text-sm" />
        </div>

        <div class="flex items-center justify-between mt-2">
            <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember"
                    class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500">
                <span
                    class="ms-2 text-sm text-slate-600 group-hover:text-slate-800 transition-colors">{{ __('Ingat saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors"
                    href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <!-- PERBAIKAN: Menambahkan space-y-3 untuk jarak antar tombol -->
        <div class="pt-4 space-y-3">
            <button type="submit"
                class="w-full py-3.5 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white rounded-xl font-bold text-sm shadow-[0_4px_14px_0_rgba(79,70,229,0.39)] transition-all transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ __('Masuk ke Sistem') }}
            </button>

            <!-- TOMBOL KEMBALI KE HALAMAN UTAMA -->
            <a href="{{ route('user.dashboard') }}"
                class="w-full flex items-center justify-center py-3.5 px-4 bg-white/60 hover:bg-white text-slate-700 border border-slate-300 rounded-xl font-bold text-sm shadow-sm transition-all transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-300">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                    class="mr-2">
                    <path d="m15 18-6-6 6-6" />
                </svg>
                Kembali ke Halaman Utama
            </a>
        </div>


    </form>
</x-guest-layout>
