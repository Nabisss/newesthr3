<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup 2FA - CaliCrane</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-blue-950">Setup Two-Factor Authentication</h2>
            <p class="text-gray-600 mt-2">Secure your account with 2FA</p>
        </div>

        <div class="text-center mb-6">
            <p class="text-gray-600 mb-4">Scan the QR code with your authenticator app:</p>
            <div class="flex justify-center mb-4">
                <!-- QR Code will be generated here -->
                <div class="bg-white p-4 rounded border">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($qrCodeUrl) }}" alt="QR Code">
                </div>
            </div>
            <p class="text-sm text-gray-500 mb-2">Or enter this secret key manually:</p>
            <code class="bg-gray-100 p-2 rounded text-sm font-mono">{{ $user->two_factor_secret }}</code>
        </div>

        <form method="POST" action="{{ route('2fa.enable') }}">
            @csrf
            <div class="mb-4">
                <label for="code" class="block text-gray-700 text-sm font-bold mb-2">Enter 6-digit code</label>
                <input type="text" name="code" id="code" maxlength="6"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-center tracking-widest"
                       required autofocus>
                @error('code')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit"
                        class="bg-gray-900 hover:bg-gray-950 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Enable 2FA
                </button>
                <a href="{{ route('dashboard') }}"
                   class="text-gray-500 hover:text-gray-700 text-sm">Skip for now</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>
</html>
