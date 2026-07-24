<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Profil Pengguna</h1>
            <p class="text-xs text-slate-500 mt-0.5 font-medium">Kelola informasi akun, email, dan kata sandi Anda.</p>
        </div>
    </x-slot>

    <div class="max-w-4xl space-y-6">
        <div class="page-card p-6">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="page-card p-6">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="page-card p-6 border-rose-100">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
