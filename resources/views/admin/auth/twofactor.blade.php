
<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş təsdiqi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="bg-white rounded-lg shadow-md w-full max-w-md p-8">
    <h1 class="text-2xl font-semibold text-center text-gray-800 mb-2">Giriş təsdiqi</h1>
    <p class="text-sm text-gray-600 text-center mb-6">
        E-poçtunuza göndərilən 6 rəqəmli kodu daxil edin.
    </p>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.twofactor.verify') }}">
        @csrf

        <div class="mb-6">
            <input type="text" name="code" maxlength="6" required autofocus
                   class="w-full text-center text-2xl tracking-widest border border-gray-300 rounded px-3 py-3 focus:outline-none focus:border-blue-500">
        </div>

        <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
            Təsdiqlə
        </button>
    </form>
</div>

</body>
</html>
