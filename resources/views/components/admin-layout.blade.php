<!-- resources/views/components/admin-layout.blade.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    {{ $head ?? '' }}
    <title>{{ $title ?? 'Admin' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        {{ $sidebar ?? '' }}
        
        <div class="flex-1 p-8">
            {{ $slot }} <!-- Contenu principal -->
        </div>
    </div>
</body>
</html>