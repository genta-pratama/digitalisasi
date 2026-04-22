<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Laboratorium Kimia</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(160deg, #1565c0 0%, #1976d2 30%, #e3f2fd 60%, #ffffff 100%);
            background-attachment: fixed;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: -120px; left: -120px;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
            pointer-events: none;
        }
        body::after {
            content: '';
            position: fixed;
            bottom: -100px; right: -100px;
            width: 350px; height: 350px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            pointer-events: none;
        }

        .particle {
            position: fixed;
            border-radius: 50%;
            background: rgba(255,255,255,0.12);
            animation: float linear infinite;
            pointer-events: none;
        }
        @keyframes float {
            0%   { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 1; }
            100% { transform: translateY(-100px) rotate(720deg); opacity: 0; }
        }

        .login-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow:
                0 25px 60px rgba(21, 101, 192, 0.25),
                0 8px 20px rgba(0,0,0,0.08),
                inset 0 1px 0 rgba(255,255,255,0.9);
            width: 100%;
            max-width: 420px;
            padding: 48px 40px;
            position: relative;
            z-index: 10;
            animation: cardIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) both;
        }
        @keyframes cardIn {
            from { opacity: 0; transform: translateY(30px) scale(0.95); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .logo-wrapper {
            width: 72px; height: 72px;
            border-radius: 20px;
            background: linear-gradient(135deg, #1565c0, #42a5f5);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px;
            box-shadow: 0 8px 24px rgba(21,101,192,0.35);
            animation: iconPulse 3s ease-in-out infinite;
        }
        @keyframes iconPulse {
            0%, 100% { box-shadow: 0 8px 24px rgba(21,101,192,0.35); }
            50%       { box-shadow: 0 8px 32px rgba(21,101,192,0.55); }
        }

        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 14px 20px;
            border-radius: 12px;
            border: 2px solid #e8eaf6;
            background: white;
            color: #1f2937;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.25s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            position: relative;
            overflow: hidden;
        }
        .btn-google::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #f0f4ff, #e8f4fd);
            opacity: 0;
            transition: opacity 0.25s;
        }
        .btn-google:hover {
            border-color: #1565c0;
            box-shadow: 0 4px 20px rgba(21,101,192,0.18);
            transform: translateY(-1px);
        }
        .btn-google:hover::before { opacity: 1; }
        .btn-google:active { transform: translateY(0); }
        .btn-google span { position: relative; z-index: 1; }
        .btn-google svg  { position: relative; z-index: 1; flex-shrink: 0; }

        .divider {
            display: flex; align-items: center; gap: 12px;
            margin: 24px 0;
            color: #9ca3af; font-size: 13px;
        }
        .divider::before, .divider::after {
            content: ''; flex: 1;
            height: 1px; background: #e5e7eb;
        }

        .info-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 12px;
            padding: 14px 16px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-top: 24px;
        }

        /* Tombol admin */
        .btn-admin {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 11px 20px;
            border-radius: 12px;
            border: 1.5px dashed #cbd5e1;
            background: transparent;
            color: #64748b;
            font-weight: 500;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-top: 16px;
        }
        .btn-admin:hover {
            border-color: #94a3b8;
            background: #f8fafc;
            color: #334155;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 12px 14px;
            display: flex; align-items: center; gap: 8px;
            color: #dc2626; font-size: 13px;
            margin-bottom: 20px;
            animation: shake 0.4s ease;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25%       { transform: translateX(-6px); }
            75%       { transform: translateX(6px); }
        }
    </style>
</head>
<body>

    {{-- Floating particles --}}
    <div class="particle" style="width:8px;height:8px;left:10%;animation-duration:12s;animation-delay:0s;"></div>
    <div class="particle" style="width:5px;height:5px;left:25%;animation-duration:16s;animation-delay:3s;"></div>
    <div class="particle" style="width:10px;height:10px;left:50%;animation-duration:14s;animation-delay:1s;"></div>
    <div class="particle" style="width:6px;height:6px;left:70%;animation-duration:18s;animation-delay:5s;"></div>
    <div class="particle" style="width:4px;height:4px;left:85%;animation-duration:11s;animation-delay:2s;"></div>

    <div style="width:100%;max-width:480px;padding:16px;display:flex;flex-direction:column;align-items:center;">

        <div class="login-card">

            {{-- Logo --}}
            <div class="logo-wrapper">
                <i class="fa-solid fa-flask text-white text-2xl"></i>
            </div>

            {{-- Judul --}}
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900 leading-tight">
                    Selamat Datang
                </h1>
                <p class="text-gray-500 text-sm mt-1">
                    Sistem Peminjaman Laboratorium
                </p>
                <p class="text-blue-700 font-semibold text-sm mt-0.5">
                    Prodi Kimia — UIN Ar-Raniry
                </p>
            </div>

            {{-- Flash error --}}
            @if(session('error'))
                <div class="alert-error">
                    <i class="fa-solid fa-circle-exclamation flex-shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Tombol Login Google (Mahasiswa) --}}
            <a href="{{ route('google.redirect') }}" class="btn-google">
                <svg width="20" height="20" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M43.611 20.083H42V20H24v8h11.303C33.654 32.657 29.332 35 24 35c-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z" fill="#FFC107"/>
                    <path d="M6.306 14.691l6.571 4.819C14.655 15.108 19.000 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 16.318 4 9.656 8.337 6.306 14.691z" fill="#FF3D00"/>
                    <path d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238C29.211 35.091 26.715 36 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z" fill="#4CAF50"/>
                    <path d="M43.611 20.083H42V20H24v8h11.303c-.792 2.237-2.231 4.166-4.087 5.571l6.19 5.238C42.021 35.595 44 30.138 44 24c0-1.341-.138-2.65-.389-3.917z" fill="#1976D2"/>
                </svg>
                <span>Masuk sebagai Pengguna</span>
            </a>

            <div class="divider">atau</div>

            {{-- Tombol Login Admin --}}
            <a href="/admin" class="btn-admin">
                <i class="fa-solid fa-shield-halved text-slate-400"></i>
                <span>Masuk sebagai Admin / Petugas Lab</span>
                <i class="fa-solid fa-arrow-right text-xs text-slate-300 ml-auto"></i>
            </a>

            {{-- Info box --}}
            <div class="info-box">
                <i class="fa-solid fa-circle-info text-blue-500 mt-0.5 flex-shrink-0"></i>
                <div>
                    <p class="text-blue-800 text-sm font-semibold mb-0.5">Khusus Mahasiswa & Civitas Akademika UIN Ar-Raniry</p>
                    <p class="text-blue-600 text-xs leading-relaxed">
                        Menggunakan akun Google institusi UIN Ar-Raniry
                        (<strong>@uin.ar-raniry.ac.id</strong> / <strong>@ar-raniry.ac.id</strong> / <strong>@student.ar-raniry.ac.id</strong>).
                    </p>
                </div>
            </div>

        </div>

        {{-- Footer kecil --}}
        <p class="text-white/60 text-xs mt-4 text-center">
            © {{ date('Y') }} Laboratorium Kimia — UIN Ar-Raniry Banda Aceh
        </p>

    </div>

</body>
</html>