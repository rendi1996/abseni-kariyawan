<x-guest-layout>
<style>
    .login-card {
        width: 100%;
        max-width: 420px;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.12), 0 3px 10px rgba(15, 23, 42, 0.05);
        padding: 30px 28px 26px;
        border: 1px solid rgba(209, 230, 255, 0.6);
        position: relative;
        overflow: hidden;
    }

    .login-card::before {
        content: '';
        position: absolute;
        width: 210px;
        height: 210px;
        right: -55px;
        top: -55px;
        background-image: url('https://tse4.mm.bing.net/th/id/OIP.wwmH8GIuBlBf1jFM--5S6gHaHa?rs=1&pid=ImgDetMain&o=7&rm=3');
        background-size: cover;
        background-repeat: no-repeat;
        opacity: 0.08;
        filter: grayscale(0.2);
        border-radius: 50%;
        pointer-events: none;
    }

    .login-card > * {
        position: relative;
        z-index: 1;
    }

    .login-brand {
        text-align: center;
        margin-bottom: 22px;
    }

    .login-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #0f766e, #14b8a6);
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        box-shadow: 0 8px 24px rgba(20, 184, 166, 0.35);
    }

    .login-icon svg { width: 32px; height: 32px; color: #fff; }

    .login-brand h1 {
        font-size: 1.4rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.3px;
        margin-bottom: 3px;
    }

    .login-brand p {
        color: #64748b;
        font-size: 0.86rem;
    }

    .alert-error {
        background: #fff1f2;
        border: 1px solid #fecdd3;
        color: #9f1239;
        border-radius: 12px;
        padding: 12px 14px;
        font-size: 0.88rem;
        font-weight: 500;
        margin-bottom: 20px;
    }

    .alert-status {
        background: #f0fdf4;
        border: 1px solid #86efac;
        color: #166534;
        border-radius: 12px;
        padding: 12px 14px;
        font-size: 0.88rem;
        font-weight: 500;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 6px;
    }

    .form-group input[type="email"],
    .form-group input[type="password"] {
        width: 100%;
        padding: 11px 14px;
        border: 1.5px solid #cbd5e1;
        border-radius: 12px;
        font-size: 0.95rem;
        font-family: inherit;
        color: #0f172a;
        background: #f8fafc;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    }

    .form-group input:focus {
        border-color: #0f766e;
        box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.18);
        background: #ffffff;
    }

    .password-wrapper {
        position: relative;
    }

    .password-wrapper input { padding-right: 44px; }

    .toggle-pw {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: #94a3b8;
        padding: 4px;
        display: flex;
        align-items: center;
    }

    .toggle-pw:hover { color: #0f766e; }
    .toggle-pw svg { width: 20px; height: 20px; }

    .form-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 22px;
    }

    .remember-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.875rem;
        color: #475569;
        cursor: pointer;
        font-weight: 500;
    }

    .remember-label input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: #0f766e;
        cursor: pointer;
    }

    .forgot-link {
        font-size: 0.875rem;
        color: #0f766e;
        font-weight: 600;
        text-decoration: none;
    }

    .forgot-link:hover { text-decoration: underline; }

    .btn-login {
        width: 100%;
        padding: 13px;
        background: linear-gradient(135deg, #0f766e, #14b8a6);
        color: #ffffff;
        border: none;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 700;
        font-family: inherit;
        cursor: pointer;
        box-shadow: 0 8px 24px rgba(20, 184, 166, 0.35);
        transition: transform 0.15s, filter 0.2s, box-shadow 0.2s;
        letter-spacing: 0.2px;
    }

    .btn-login:hover { transform: translateY(-1px); filter: brightness(1.06); }
    .btn-login:active { transform: translateY(0); }

    .login-divider {
        display: none;
    }

    .register-link {
        text-align: center;
        font-size: 0.88rem;
        color: #64748b;
        margin-top: 12px;
        line-height: 1.45;
    }

    .register-link a {
        color: #0f766e;
        font-weight: 700;
        text-decoration: none;
    }

    .register-link a:hover { text-decoration: underline; }

    .login-footer-note {
        margin-top: 28px;
        text-align: center;
        font-size: 0.78rem;
        color: #94a3b8;
    }

    @media (max-width: 640px) {
        .login-card {
            max-width: 100%;
            border-radius: 16px;
            padding: 18px 14px 14px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.1);
        }

        .login-card::before {
            width: 145px;
            height: 145px;
            right: -38px;
            top: -38px;
            opacity: 0.06;
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
            font-size: 1.18rem;
            margin-bottom: 2px;
        }

        .login-brand p {
            font-size: 0.8rem;
        }

        .form-group {
            margin-bottom: 12px;
        }

        .form-group input[type="email"],
        .form-group input[type="password"] {
            padding: 10px 12px;
            font-size: 0.9rem;
            border-radius: 10px;
        }

        .form-footer {
            gap: 8px;
            flex-direction: column;
            align-items: flex-start;
            margin-bottom: 14px;
        }

        .remember-label,
        .forgot-link {
            font-size: 0.82rem;
        }

        .btn-login {
            padding: 11px;
            border-radius: 10px;
            font-size: 0.92rem;
        }

        .login-divider {
            display: none;
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
            padding: 14px 12px 12px;
        }

        .login-brand h1 {
            font-size: 1.06rem;
        }

        .toggle-pw {
            right: 8px;
        }
    }
</style>

<div class="login-card">

    {{-- Brand --}}
    <div class="login-brand">
        <h1>Selamat Datang</h1>
        <p>Masuk untuk mencatat kehadiran Anda</p>
    </div>

    {{-- Status / Error --}}
    @if(session('status'))
        <div class="alert-status">{{ session('status') }}</div>
    @endif

    @if($errors->any())
        <div class="alert-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="email">Alamat Email</label>
            <input id="email" type="email" name="email"
                   value="{{ old('email') }}"
                   placeholder="nama@email.com"
                   required autofocus>
        </div>

        <div class="form-group">
            <label for="password">Kata Sandi</label>
            <div class="password-wrapper">
                <input id="password" type="password" name="password"
                       placeholder="••••••••"
                       required autocomplete="current-password">
                <button type="button" class="toggle-pw" onclick="togglePassword('password', this)" title="Tampilkan/Sembunyikan">
                    <svg id="eye-password" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="form-footer">
            <label class="remember-label">
                <input type="checkbox" name="remember">
                Ingat saya
            </label>
            @if(Route::has('password.request'))
                <a class="forgot-link" href="{{ route('password.request') }}">Lupa kata sandi?</a>
            @endif
        </div>

        <button type="submit" class="btn-login">Masuk Sekarang</button>
    </form>

    <div class="login-divider">info</div>
    <div class="register-link">
        Akun karyawan dibuat oleh admin. Hubungi admin untuk username/email dan password.
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
