<x-guest-layout>
    <div class="login-card">
        <img src="{{ asset('build/assets/images/Logo_PLN.png') }}" alt="Logo PLN">
        <h5>Sistem Informasi Inventori Material SAR</h5>
        <p>PT. PLN (Persero) ULP Lumajang</p>

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            <div class="input-group">
                <i class="fas fa-user input-icon"></i>
                <input id="email" type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       name="email" placeholder="Email"
                       value="{{ old('email') }}" required autofocus>
            </div>
            @error('email')
                <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
            @enderror

            <div class="input-group">
                <i class="fas fa-lock input-icon"></i>
                <input id="password" type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       name="password" placeholder="Password" required>
                <span class="password-toggle" id="togglePassword">
                    <i class="fas fa-eye-slash"></i>
                </span>
            </div>
            @error('password')
                <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
            @enderror

            @if (Route::has('password.request'))
                <div style="text-align:right; margin:5px 0 15px 0;">
                    <a class="forgot-link" href="{{ route('password.request') }}">
                        Lupa Password?
                    </a>
                </div>
            @endif

            <button type="submit" class="btn-login" id="loginButton">Login</button>
        </form>

        <!-- Pesan error global -->
        @if ($errors->any())
            <div class="alert alert-danger mt-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</x-guest-layout>