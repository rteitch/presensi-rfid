<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-extrabold text-slate-900">Masuk ke Akun Anda</h2>
        <p class="text-xs text-slate-500 mt-1">Silakan masukkan email dan kata sandi Anda untuk melanjutkan.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email <span class="text-rose-500">*</span></label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="w-full rounded-xl border-slate-300 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/15 text-sm p-3 font-medium text-slate-900"
                   placeholder="admin@sekolah.sch.id">
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-600" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Kata Sandi <span class="text-rose-500">*</span></label>
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="w-full rounded-xl border-slate-300 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/15 text-sm p-3 font-medium text-slate-900"
                   placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-rose-600" />
        </div>

        <!-- Math Captcha Security Check -->
        <div class="p-3 bg-indigo-50/60 border border-indigo-100 rounded-xl space-y-2">
            <div class="flex items-center justify-between">
                <label for="captcha_answer" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                    Keamanan Bot Captcha <span class="text-rose-500">*</span>
                </label>
                <span class="text-xs font-mono font-black px-2.5 py-1 bg-indigo-600 text-white rounded-lg shadow-sm">
                    {{ $mathCaptcha ?? 'Verifikasi Keamanan' }}
                </span>
            </div>
            <input id="captcha_answer" type="number" name="captcha_answer" required
                   class="w-full rounded-xl border-slate-300 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/15 text-sm p-2.5 font-mono font-bold text-slate-900"
                   placeholder="Ketik jawaban angka...">
            <x-input-error :messages="$errors->get('captcha_answer')" class="mt-1 text-xs text-rose-600 font-medium" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-4 h-4">
                <span class="ms-2 text-xs font-medium text-slate-600">Ingat saya</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm transition shadow-lg flex items-center justify-center gap-2">
                <span>Masuk Sekarang</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </div>
    </form>
</x-guest-layout>
