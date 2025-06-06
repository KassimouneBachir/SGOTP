<!DOCTYPE html>
<html>
<head>
    @include('layouts.head')
</head>
<body class="bg-gray-100">
    <!-- Sidebar -->
    <div class="flex">
        @include('admin.partials.sidebar')
        
        <!-- Main Content -->
        <div class="flex-1 p-8">
            @yield('content')
        </div>
    </div>
</body>
</html>