<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WhatsApp Bulk SMS SaaS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
            flex: 1;
        }

        #sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #343a40;
            color: #fff;
            transition: all 0.3s;
        }

        #sidebar.active {
            margin-left: -250px;
        }

        #sidebar .sidebar-header {
            padding: 20px;
            background: #343a40;
        }

        #sidebar ul.components {
            padding: 20px 0;
            border-bottom: 1px solid #4b545c;
        }

        #sidebar ul p {
            color: #fff;
            padding: 10px;
        }

        #sidebar ul li a {
            padding: 10px;
            font-size: 1.1em;
            display: block;
            color: #fff;
            text-decoration: none;
        }

        #sidebar ul li a:hover {
            color: #343a40;
            background: #fff;
        }

        #sidebar ul li.active>a {
            color: #fff;
            background: #6d7fcc;
        }

        #content {
            width: 100%;
            padding: 20px;
            min-height: 100vh;
            transition: all 0.3s;
        }
    </style>
</head>

<body>

    <div class="wrapper">
        <!-- Sidebar -->
        @auth
            <nav id="sidebar">
                <div class="sidebar-header">
                    <h3>WhatsApp SaaS</h3>
                </div>

                <ul class="list-unstyled components">
                    <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    </li>
                    <li class="{{ request()->routeIs('templates.*') ? 'active' : '' }}">
                        <a href="{{ route('templates.index') }}"><i class="bi bi-file-text me-2"></i> Templates</a>
                    </li>
                    <li class="{{ request()->routeIs('messages.*') ? 'active' : '' }}">
                        <a href="{{ route('messages.index') }}"><i class="bi bi-chat-dots me-2"></i> Messages</a>
                    </li>
                    <li class="{{ request()->routeIs('configs.*') ? 'active' : '' }}">
                        <a href="{{ route('configs.index') }}"><i class="bi bi-gear me-2"></i> Configuration</a>
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <a href="#" onclick="this.closest('form').submit()"><i class="bi bi-box-arrow-right me-2"></i>
                                Logout</a>
                        </form>
                    </li>
                </ul>
            </nav>
        @endauth

        <!-- Page Content -->
        <div id="content">
            @if(!Auth::check())
                <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="#">WhatsApp SaaS</a>
                        <div class="collapse navbar-collapse">
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                            </ul>
                        </div>
                    </div>
                </nav>
            @endif

            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>