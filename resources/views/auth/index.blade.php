<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Register Form</title>

    <!--Boxicons CDN-->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!--Custom CSS-->
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">

</head>

<body>
    <div class="wrapper">
        <span class="rotate-bg"></span>
        <span class="rotate-bg2"></span>

        <!-- Form Login -->
        <div class="form-box login">
            <h2 class="title animation" style="--i:0; --j:21">Login</h2>
            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="input-box animation" style="--i:1; --j:22">
                    <input type="text" name="username" value="{{ old('username') }}" required>
                    <label for="">Username</label>
                    <i class='bx bxs-user'></i>
                    @error('username')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                </div>

                <div class="input-box animation" style="--i:2; --j:23">
                    <input type="password" name="password" required>
                    <label for="">Password</label>
                    <i class='bx bxs-lock-alt'></i>
                    @error('password')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Checkbox hanya di login -->
                <div>
                    <label>
                        <input type="checkbox" name="remember"> Remember Me
                    </label>
                </div>

                <button type="submit" class="btn animation" style="--i:3; --j:24">Login</button>

                <div class="linkTxt animation" style="--i:5; --j:25">
                    <p>Don't have an account? <a href="#" class="register-link">Sign Up</a></p>
                </div>
            </form>
        </div>

        <!-- Form Register -->
        <div class="form-box register">
            <h2 class="title animation" style="--i:17; --j:0">Sign Up</h2>
            <form method="POST" action="{{ route('register.post') }}">
                @csrf
                <div class="input-box animation" style="--i:18; --j:1">
                    <input type="text" name="username" value="{{ old('username') }}" required>
                    <label for="">Username</label>
                    <i class='bx bxs-user'></i>
                    @error('username')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                </div>

                <div class="input-box animation" style="--i:19; --j:2">
                    <input type="email" name="email" value="{{ old('email') }}" required>
                    <label for="">Email</label>
                    <i class='bx bxs-envelope'></i>
                    @error('email')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                </div>

                <div class="input-box animation" style="--i:20; --j:3">
                    <input type="password" name="password" required>
                    <label for="">Password</label>
                    <i class='bx bxs-lock-alt'></i>
                    @error('password')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                </div>

                <div class="input-box animation">
                    <input type="password" name="password_confirmation" required>
                    <label for="">Confirm Password</label>
                </div>

                <button type="submit" class="btn animation" style="--i:21;--j:4">Sign Up</button>

                <div class="linkTxt animation" style="--i:22; --j:5">
                    <p>Already have an account? <a href="#" class="login-link">Login</a></p>
                </div>
            </form>
        </div>


        <!-- Info Section -->
        <div class="info-text login">
            <h2 class="animation" style="--i:0; --j:20">Welcome Back!</h2>
            <p class="animation" style="--i:1; --j:21">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
        </div>

        <div class="info-text register">
            <h2 class="animation" style="--i:17; --j:0;">Welcome Back!</h2>
            <p class="animation" style="--i:18; --j:1;">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
        </div>
    </div>

    <!-- Custom JavaScript -->
    <script src="{{ asset('assets/js/auth.js') }}"></script>
</body>

</html>
