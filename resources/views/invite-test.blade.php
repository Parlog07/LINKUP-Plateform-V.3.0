<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invite Test</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center bg-white">

    <div class="p-8 rounded-xl shadow-lg border w-full max-w-md text-center">
        <h1 class="text-2xl font-bold mb-6 text-black">Invite Test</h1>

        <form method="POST" action="{{ route('friends.invite.generate') }}">
            @csrf
            <button type="submit"
                class="w-full py-3 text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold">
                Generate Invite Link
            </button>
        </form>

        @if(session('invite_link'))
            <div class="mt-6 p-4 bg-gray-100 rounded text-left">
                <p class="font-semibold text-black">Your invite link:</p>
                <a class="text-blue-700 break-all" href="{{ session('invite_link') }}">
                    {{ session('invite_link') }}
                </a>
            </div>
        @endif
        @if(session('invite_link'))
    <div class="mt-6 text-center">
        <h3 class="font-semibold mb-2">Scan QR Code</h3>
        {!! QrCode::size(200)->generate(session('invite_link')) !!}
    </div>
@endif

    </div>

</body>
</html>
