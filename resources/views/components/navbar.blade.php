<nav class="navbar fixed-top shadow navbar-expand-lg navbar-light bg-light mt-lg-4 rounded">
    <div class="container">
        <a class="navbar-brand" href="/">{{ $appname }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
    <li class="nav-item">
        <a class="nav-link" aria-current="page" href="/">Home</a>
    </li>

    @foreach($menu as $mn)
    <li class="nav-item">
        <a class="nav-link" href="/page/{{ $mn->id }}">{{ $mn->name }}</a>
    </li>
    @endforeach

    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Pages
        </a>
        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            @foreach($submenu as $sm)
            <li><a class="dropdown-item" href="/page/{{ $sm->id }}">{{ $sm->name }}</a></li>
            @endforeach
        </ul>
            </li>

            @auth
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle me-1"></i>
                    {{ auth()->user()->name }} ({{ auth()->user()->role }})
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdown">
                    {{-- Tambahkan link profil jika ada --}}
                    <li><a class="dropdown-item" href="#">Profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger" type="submit">Logout</button>
                        </form>
                    </li>
                </ul>
            </li>
            @else
            <li class="nav-item">
                <a class="nav-link btn btn-primary rounded text-white" href="{{ route('login') }}">Login</a>
            </li>
            @endauth
        </ul>
    </div>
  </nav>
  {{-- Navbar expanded --}}
  <div class="py-4 mt-3 py-lg-0 mt-lg-0"></div>