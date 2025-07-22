<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/login.css'])
    <title>Document</title>
</head>
<body class="relative">
    @if (session()->has('success'))
        <div id="top-popup" class="flex flex-row justify-center text-white w-screen absolute bg-green-500 z-10 transition-all" style="height: 30px">
            {{ session('success') }}
        </div>
    @endif  

     @if (session()->has('fail'))
        <div id="top-popup" class="flex flex-row justify-center text-white w-screen absolute bg-red-500 z-10 transition-all" style="height: 30px">
            {{ session('fail') }}
        </div>
    @endif
    <div class="h-screen container flex flex-col bg-(--main-color) gap-5" style="justify-content: center; align-items: center;">
        <img width="200px" src="{{ asset('images/logo.png') }}" alt="logo.png">
        <form class="px-5 py-10 gap-5" action={{ route('auth') }} method="post">
            @csrf
            <div class="mb-3 w-100 relative">
                <input type="email" name="email" id="email" value={{old('email')}}>
                <label class="form-label" for="email">Email</label>
                @error('email')
                    <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 w-100 relative">
                <input type="password" name="password" id="password">
                <label class="form-label" for="password">Password</label>
                <i class="bi bi-eye-fill"></i>
                @error('password')
                    <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" name="submit">Login</button>
        </form>
    </div>
@vite(['resources/js/login.js'])
</body>
</html>