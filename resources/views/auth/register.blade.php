<x-guest-layout>
<style>
    .login-card {
        width: 100%;
        max-width: 420px;
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 24px 64px rgba(15, 23, 42, 0.13), 0 4px 16px rgba(15, 23, 42, 0.06);
        padding: 40px 36px 36px;
        border: 1px solid rgba(209, 230, 255, 0.6);
    }
    .login-brand { text-align: center; margin-bottom: 28px; }
    .login-icon {
        width: 64px; height: 64px;
        background: linear-gradient(135deg, #0f766e, #14b8a6);
        border-radius: 18px;
        display: inline-flex; align-items: center; justify-content: center;
        margin-bottom: 16px;
        box-shadow: 0 8px 24px rgba(20,184,166,0.35);
    }
    .login-icon svg { width: 32px; height: 32px; color: #fff; }
    .login-brand h1 { font-size: 1.5rem; font-weight: 800; color: #0f172a; letter-spacing: -0.3px; margin-bottom: 4px; }
    .login-brand p { color: #64748b; font-size: 0.9rem; }
    .alert-error {
        background: #fff1f2; border: 1px solid #fecdd3; color: #9f1239;
        border-radius: 12px; padding: 12px 14px; font-size: 0.88rem;
        font-weight: 500; margin-bottom: 20px;
    }
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; font-size: 0.85rem; font-weight: 600; color: #1e293b; margin-bottom: 6px; }
    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="password"] {
        width: 100%; padding: 11px 14px;
        border: 1.5px solid #cbd5e1; border-radius: 12px;
        font-size: 0.95rem; font-family: inherit; color: #0f172a;
        background: #f8fafc; outline: none;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    }
    .form-group input:focus { border-color: #0f766e; box-shadow: 0 0 0 3px rgba(20,184,166,0.18); background: #fff; }
    .password-wrapper { position: relative; }
    .password-wrapper input { padding-right: 44px; }
    .toggle-pw {
        position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
        background: none; border: none; cursor: pointer; color: #94a3b8;
        padding: 4px; display: flex; align-items: center;
    }
    .toggle-pw:hover { color: #0f766e; }
    .toggle-pw svg { width: 20px; height: 20px; }
    .btn-login {
        width: 100%; padding: 13px;
        background: linear-gradient(135deg, #0f766e, #14b8a6);
        color: #fff; border: none; border-radius: 12px;
        font-size: 1rem; font-weight: 700; font-family: inherit; cursor: pointer;
        box-shadow: 0 8px 24px rgba(20,184,166,0.35);
        transition: transform 0.15s, filter 0.2s; letter-spacing: 0.2px;
        margin-top: 6px;
    }
    .btn-login:hover { transform: translateY(-1px); filter: brightness(1.06); }
    .login-divider {
        display: flex; align-items: center; gap: 12px;
        margin: 20px 0; color: #cbd5e1; font-size: 0.82rem;
    }
    .login-divider::before, .login-divider::after {
        content: ''; flex: 1; height: 1px; background: #e2e8f0;
    }
    .register-link { text-align: center; font-size: 0.88rem; color: #64748b; }
    .register-link a { color: #0f766e; font-weight: 700; text-decoration: none; }
    .register-link a:hover { text-decoration: underline; }
    .login-footer-note { margin-top: 28px; text-align: center; font-size: 0.78rem; color: #94a3b8; }

    @media (max-width: 640px) {
        .login-card {
            max-width: 100%;
            border-radius: 16px;
            padding: 20px 16px 16px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.1);
        }

        .login-brand {
            margin-bottom: 18px;
        }

        .login-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            margin-bottom: 10px;
        }

        .login-icon svg {
            width: 24px;
            height: 24px;
        }

        .login-brand h1 {
            font-size: 1.14rem;
            margin-bottom: 2px;
        }

        .login-brand p {
            font-size: 0.8rem;
        }

        .form-group {
            margin-bottom: 12px;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"] {
            padding: 10px 12px;
            font-size: 0.9rem;
            border-radius: 10px;
        }

        .btn-login {
            padding: 11px;
            border-radius: 10px;
            font-size: 0.92rem;
        }

        .login-divider {
            margin: 14px 0 10px;
            font-size: 0.72rem;
        }

        .register-link {
            font-size: 0.79rem;
            line-height: 1.45;
        }

        .login-footer-note {
            margin-top: 14px;
            font-size: 0.7rem;
        }
    }

    @media (max-width: 380px) {
        .login-card {
            padding: 16px 12px 12px;
        }

        .login-brand h1 {
            font-size: 1.02rem;
        }

        .toggle-pw {
            right: 8px;
        }
    }
</style>

<div class="login-card">

    <div class="login-brand">
        <div class="login-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
        </div>
        <h1>Buat Akun Baru</h1>
        <p>Daftarkan diri Anda untuk mulai absensi</p>
    </div>

    @if($errors->any())
        <div class="alert-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <input id="name" type="text" name="name"
                   value="{{ old('name') }}"
                   placeholder="Masukkan nama lengkap"
                   required autofocus>
        </div>

        <div class="form-group">
            <label for="email">Alamat Email</label>
            <input id="email" type="email" name="email"
                   value="{{ old('email') }}"
                   placeholder="nama@email.com"
                   required>
        </div>

        <div class="form-group">
            <label for="password">Kata Sandi</label>
            <div class="password-wrapper">
                <input id="password" type="password" name="password"
                       placeholder="Minimal 8 karakter"
                       required autocomplete="new-password">
                <button type="button" class="toggle-pw" onclick="togglePassword('password', this)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Kata Sandi</label>
            <div class="password-wrapper">
                <input id="password_confirmation" type="password" name="password_confirmation"
                       placeholder="Ulangi kata sandi"
                       required>
                <button type="button" class="toggle-pw" onclick="togglePassword('password_confirmation', this)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-login">Daftar Sekarang</button>
    </form>

    <div class="login-divider">atau</div>
    <div class="register-link">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
    </div>

    <div class="login-footer-note">
        &copy; {{ date('Y') }} Sistem Absensi BKK Banten
    </div>
</div>

<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    btn.querySelector('svg').innerHTML = isHidden
        ? '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>'
        : '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
}
</script>
</x-guest-layout>
