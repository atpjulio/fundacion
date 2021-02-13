<header class="header">

  <div class="header-block header-block-collapse d-lg-none d-xl-none">
    <button class="collapse-btn" id="sidebar-collapse-btn">
      <i class="fa fa-bars"></i>
    </button>
  </div>
  <div class="header-block header-block-search">
    <form method="POST" action="{{ route('authorization.global') }}">
      <div class="input-container">
        <i class="fa fa-search"></i>
        <input type="search" name="authorization_code" placeholder="Autorización">
        <div class="underline"></div>
      </div>
    </form>
  </div>
  <div class="header-block header-block-search">
    <form method="POST" action="{{ route('patient.global') }}">
      <div class="input-container">
        <i class="fa fa-search"></i>
        <input type="search" name="patient" placeholder="Usuario">
        <div class="underline"></div>
      </div>
    </form>
  </div>
  {{-- <div class="header-block header-block-buttons" >
        <a href="https://github.com/modularcode/modular-admin-html"
            class="btn btn-sm header-btn">
            <i class="fa fa-github-alt"></i>
            <span>View on GitHub</span>
        </a>

        <a href="https://github.com/modularcode/modular-admin-html/stargazers"
            class="btn btn-sm header-btn">
            <i class="fa fa-star"></i>
            <span>Star Us</span>
        </a>

        <a href="https://github.com/modularcode/modular-admin-html/releases"
            class="btn btn-sm header-btn">
            <i class="fa fa-cloud-download"></i>
            <span>Download .zip</span>
        </a>
        <button type="button" class="btn btn-default btn-sm rounded-s buttons header-btn">
            <i class="fa fa-share-alt"></i>
            Share
        </button>
    </div> --}}
  <div class="header-block header-block-nav">
    <ul class="nav-profile">
      <li class="profile dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
          aria-expanded="false">
          @if (is_null(auth()->user()->picture))
            <div class="img"
              style="{{ 'background-image: url(' . env('APP_URL') . config('constants.usersImages') . 'default.png)' }}">
            </div>
          @else
            <div class="img"
              style="{{ 'background-image: url(' . env('APP_URL') . config('constants.usersImages') . auth()->user()->picture . ')' }}">
            </div>
          @endif
          {{-- <div class="img" style="background-image: url('https://avatars3.githubusercontent.com/u/3959008?v=3&s=40')">
                    </div> --}}
          <span class="name">
            {!! auth()->user()->full_name !!}
          </span>
        </a>
        <div class="dropdown-menu profile-dropdown-menu" aria-labelledby="dropdownMenu1">
          <a class="dropdown-item" href="{{ route('user.profile', auth()->user()->id) }}">
            <i class="fa fa-user icon"></i>
            Mi cuenta
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
            <i class="fa fa-power-off icon"></i>
            Salir
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </div>
      </li>
    </ul>
  </div>
</header>
