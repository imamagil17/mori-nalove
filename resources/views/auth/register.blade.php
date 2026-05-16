<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-slate-800 mb-2">Buat Akun Baru</h2>
        <p class="text-sm text-slate-500">Bergabunglah dengan sistem mitigasi Flood-Vision.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">{{ __('Nama Lengkap') }}</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                   class="w-full rounded-xl bg-white/50 border border-slate-300 text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all px-4 py-3 outline-none shadow-sm" 
                   placeholder="Masukkan nama lengkap">
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-rose-500 text-sm" />
        </div>

        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">{{ __('Email Address') }}</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" 
                   class="w-full rounded-xl bg-white/50 border border-slate-300 text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all px-4 py-3 outline-none shadow-sm" 
                   placeholder="email@contoh.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-rose-500 text-sm" />
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">{{ __('Password') }}</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" 
                   class="w-full rounded-xl bg-white/50 border border-slate-300 text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all px-4 py-3 outline-none shadow-sm"
                   placeholder="Minimal 8 karakter">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-500 text-sm" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1">{{ __('Konfirmasi Password') }}</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                   class="w-full rounded-xl bg-white/50 border border-slate-300 text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all px-4 py-3 outline-none shadow-sm"
                   placeholder="Ulangi password">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-rose-500 text-sm" />
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white rounded-xl font-bold text-sm shadow-[0_4px_14px_0_rgba(79,70,229,0.39)] transition-all transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ __('Daftar Sekarang') }}
            </button>
        </div>
        
        <p class="text-center text-sm text-slate-500 mt-6">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-bold transition-colors">Masuk di sini</a>
        </p>
    </form>
</x-guest-layout>