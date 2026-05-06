<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><i class="fa-solid fa-map"></i>{{$title}}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route('home') }}"><i class="fa-solid fa-house"></i>Home</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="{{ route('map') }}"><i class="fa-solid fa-map-pin"></i>Map</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="{{route('tabel') }}"><i class="fa-solid fa-table"></i>Table</a>
            </li>
            <li class="nav-item">
            <a class="nav-link disabled" aria-disabled="true"><i class="fa-solid fa-circle-info"></i>Tentang</a>
            </li>
        </ul>
        </div>
    </div>
    </nav>
