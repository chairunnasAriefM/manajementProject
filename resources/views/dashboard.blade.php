@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')

    @if (Auth::user()->role === 'admin')
        <div class="page-heading">
            <h3>Dashboard Admin</h3>
        </div>
        <div class="page-content">
            <section class="row">
                <div class="col-12 col-lg-9">
                    <div class="row">
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-4 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                            <div class="stats-icon purple mb-2">
                                                <i class="iconly-boldFolder"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                            <h6 class="text-muted font-semibold">Total Proyek</h6>
                                            <h6 class="font-extrabold mb-0">{{ $totalProjects }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-4 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                            <div class="stats-icon blue mb-2">
                                                <i class="iconly-boldPaper"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                            <h6 class="text-muted font-semibold">Total Tugas</h6>
                                            <h6 class="font-extrabold mb-0">{{ $totalTasks }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-4 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                            <div class="stats-icon green mb-2">
                                                <i class="iconly-boldUser"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                            <h6 class="text-muted font-semibold">Pengguna Aktif</h6>
                                            <h6 class="font-extrabold mb-0">{{ $activeUsers }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-4 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                            <div class="stats-icon red mb-2">
                                                <i class="iconly-boldNotification"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                            <h6 class="text-muted font-semibold">Notifikasi Baru</h6>
                                            <h6 class="font-extrabold mb-0">{{ $newNotifications }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4>Daftar Pengguna Aktif</h4>
                                    <input type="text" id="searchUser" class="form-control w-25"
                                        placeholder="Cari pengguna...">
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nama</th>
                                                    <th>Email</th>
                                                    <th>Peran</th>
                                                    <th>Terakhir Login</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="userTable">
                                                @foreach ($activeUsersData as $key => $user)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $user->name }}</td>
                                                        <td>{{ $user->email }}</td>
                                                        <td>
                                                            <span
                                                                class="badge
                                                                {{ $user->role === 'admin' ? 'bg-primary' : ($user->role === 'leader' ? 'bg-success' : 'bg-secondary') }}">
                                                                {{ ucfirst($user->role) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $user->email_verified_at ? $user->email_verified_at->format('d-m-Y H:i') : '-' }}
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-info btn-sm text-white">
                                                                <i class="bi bi-eye"></i> Detail
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3">
                    <div class="card">
                        <div class="card-body py-4 px-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-xl">
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Face 1">
                                </div>
                                <div class="ms-3 name">
                                    <h5 class="font-bold">{{ Auth::user()->name }}</h5>
                                    <h6 class="text-muted mb-0">{{ Auth::user()->email }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Profil Proyek</h4>
                        </div>
                        <div class="card-body">
                            <div id="chart-visitors-profile"></div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @elseif (Auth::user()->role === 'leader')
        <div class="page-heading">
            <h3>Dashboard Leader</h3>
        </div>
        <div class="page-content">
            <section class="row">
                <div class="col-12 col-lg-9">
                    <div class="row">
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-4 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                            <div class="stats-icon purple mb-2">
                                                <i class="iconly-boldFolder"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                            <h6 class="text-muted font-semibold">Total Proyek</h6>
                                            <h6 class="font-extrabold mb-0">{{ $projectTotal }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-4 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                            <div class="stats-icon blue mb-2">
                                                <i class="fa fa-folder-open"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                            <h6 class="text-muted font-semibold">Proyek Aktif</h6>
                                            <h6 class="font-extrabold mb-0">{{ $projectAktifData }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-4 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                            <div class="stats-icon green mb-2">
                                                <i class="fa fa-folder"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                            <h6 class="text-muted font-semibold">Proyek Ditunda</h6>
                                            <h6 class="font-extrabold mb-0">{{ $projectHold }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-4 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                            <div class="stats-icon red mb-2">
                                                <i class="iconly-boldNotification"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                            <h6 class="text-muted font-semibold">Notifikasi Baru</h6>
                                            <h6 class="font-extrabold mb-0">{{ $newNotifications }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <!-- Card Header -->
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4>Daftar Proyek Aktif</h4>
                                    <input type="text" id="searchUser" class="form-control w-25"
                                        placeholder="Cari Proyek...">
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <!-- Table -->
                                        <table class="table table-hover table-striped">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Judul</th>
                                                    <th>Tanggal Mulai</th>
                                                    <th>Tanggal Berakhir</th>
                                                    <th>Progress</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="userTable">
                                                @foreach ($projectAktif as $key => $project)
                                                    @php
                                                        $totalTasks = $project->tasks->count();
                                                        $completedTasks = $project->tasks
                                                            ->where('status', 'completed')
                                                            ->count();
                                                        $progress =
                                                            $totalTasks > 0
                                                                ? round(($completedTasks / $totalTasks) * 100)
                                                                : 0;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $project->title }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($project->start_date)->translatedFormat('d F Y') }}
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($project->end_date)->translatedFormat('d F Y') }}
                                                        </td>
                                                        <td>
                                                            <!-- Progress Chart -->
                                                            <div id="chart-{{ $project->id }}"
                                                                style="width: 200px; height: 50px;"></div>
                                                            <script>
                                                                document.addEventListener('DOMContentLoaded', function() {
                                                                    const options = {
                                                                        series: [{
                                                                            name: 'Progress',
                                                                            data: [{{ $progress }}]
                                                                        }],
                                                                        chart: {
                                                                            type: 'bar',
                                                                            height: 70,
                                                                            width: 200,
                                                                            toolbar: {
                                                                                show: false
                                                                            }
                                                                        },
                                                                        plotOptions: {
                                                                            bar: {
                                                                                horizontal: true,
                                                                                barHeight: '60%' // Memperbesar bar menjadi lebih tebal
                                                                            }
                                                                        },
                                                                        xaxis: {
                                                                            max: 100,
                                                                            labels: {
                                                                                show: false
                                                                            }
                                                                        },
                                                                        yaxis: {
                                                                            show: false
                                                                        },
                                                                        dataLabels: {
                                                                            enabled: true,
                                                                            formatter: function(val) {
                                                                                return val + '%';
                                                                            },
                                                                            style: {
                                                                                fontSize: '12px',
                                                                                colors: ['#000']
                                                                            }
                                                                        },
                                                                        colors: ['#00E396'],
                                                                        grid: {
                                                                            show: false
                                                                        }
                                                                    };

                                                                    const chart = new ApexCharts(
                                                                        document.querySelector("#chart-{{ $project->id }}"),
                                                                        options
                                                                    );
                                                                    chart.render();
                                                                });
                                                            </script>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-info btn-sm text-white"
                                                                onclick="location.href='{{ route('projects.show', $project->id) }}'">
                                                                <i class="bi bi-eye"></i> Detail
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <!-- Pagination -->
                                        <div class="pagination-container">
                                            {{ $projectAktif->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                </div>
                <div class="col-12 col-lg-3">
                    <div class="card">
                        <div class="card-body py-4 px-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-xl">
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Face 1">
                                </div>
                                <div class="ms-3 name">
                                    <h5 class="font-bold">{{ Auth::user()->name }}</h5>
                                    <h6 class="text-muted mb-0">{{ Auth::user()->email }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Profil Proyek</h4>
                        </div>
                        <div class="card-body">
                            <div id="chart-visitors-profile"></div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @else
        @if (Auth::user()->role === 'admin')
            <div class="page-heading">
                <h3>Dashboard Admin</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12 col-lg-9">
                        <div class="row">
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-4 py-4-5">
                                        <div class="row">
                                            <div
                                                class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                                <div class="stats-icon purple mb-2">
                                                    <i class="iconly-boldFolder"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                <h6 class="text-muted font-semibold">Total Proyek</h6>
                                                <h6 class="font-extrabold mb-0">{{ $totalProjects }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-4 py-4-5">
                                        <div class="row">
                                            <div
                                                class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                                <div class="stats-icon blue mb-2">
                                                    <i class="iconly-boldPaper"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                <h6 class="text-muted font-semibold">Total Tugas</h6>
                                                <h6 class="font-extrabold mb-0">{{ $totalTasks }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-4 py-4-5">
                                        <div class="row">
                                            <div
                                                class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                                <div class="stats-icon green mb-2">
                                                    <i class="iconly-boldUser"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                <h6 class="text-muted font-semibold">Pengguna Aktif</h6>
                                                <h6 class="font-extrabold mb-0">{{ $activeUsers }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-4 py-4-5">
                                        <div class="row">
                                            <div
                                                class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                                <div class="stats-icon red mb-2">
                                                    <i class="iconly-boldNotification"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                <h6 class="text-muted font-semibold">Notifikasi Baru</h6>
                                                <h6 class="font-extrabold mb-0">{{ $newNotifications }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4>Daftar Pengguna Aktif</h4>
                                        <input type="text" id="searchUser" class="form-control w-25"
                                            placeholder="Cari pengguna...">
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Nama</th>
                                                        <th>Email</th>
                                                        <th>Peran</th>
                                                        <th>Terakhir Login</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="userTable">
                                                    @foreach ($activeUsersData as $key => $user)
                                                        <tr>
                                                            <td>{{ $key + 1 }}</td>
                                                            <td>{{ $user->name }}</td>
                                                            <td>{{ $user->email }}</td>
                                                            <td>
                                                                <span
                                                                    class="badge
                                                            {{ $user->role === 'admin' ? 'bg-primary' : ($user->role === 'leader' ? 'bg-success' : 'bg-secondary') }}">
                                                                    {{ ucfirst($user->role) }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $user->email_verified_at ? $user->email_verified_at->format('d-m-Y H:i') : '-' }}
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-info btn-sm text-white">
                                                                    <i class="bi bi-eye"></i> Detail
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-3">
                        <div class="card">
                            <div class="card-body py-4 px-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-xl">
                                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Face 1">
                                    </div>
                                    <div class="ms-3 name">
                                        <h5 class="font-bold">{{ Auth::user()->name }}</h5>
                                        <h6 class="text-muted mb-0">{{ Auth::user()->email }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4>Profil Proyek</h4>
                            </div>
                            <div class="card-body">
                                <div id="chart-visitors-profile"></div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        @endif
    @endif

@endsection

@section('scripts')
    {{-- apex chart --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const totalProjects = @json($totalProjects);
            const totalTasks = @json($totalTasks); // Total semua tugas

            // Data untuk chart
            const inProgress = @json(App\Models\Project::where('status', 'in_progress')->count());
            const completed = @json(App\Models\Project::where('status', 'completed')->count());
            const onHold = @json(App\Models\Project::where('status', 'on_hold')->count());

            const options = {
                series: [inProgress, completed, onHold],
                chart: {
                    type: 'pie',
                    height: 350
                },
                labels: ['Dalam Proses', 'Selesai', 'Ditunda'],
                colors: ['#4CAF50', '#2196F3', '#FFC107'],
                legend: {
                    position: 'bottom'
                }
            };

            const chart = new ApexCharts(document.querySelector("#chart-visitors-profile"), options);
            chart.render();
        });
    </script>
    {{-- tabel --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchUser');
            const userTable = document.getElementById('userTable');

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = userTable.querySelectorAll('tr');

                rows.forEach(row => {
                    const name = row.cells[1].textContent.toLowerCase();
                    const email = row.cells[2].textContent.toLowerCase();

                    if (name.includes(searchTerm) || email.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>




@endsection


{{-- <div class="container-fluid">
    @php
        $role = Auth::user()->role;
    @endphp

    <!-- Kondisi berdasarkan role -->
    @if ($role === 'admin')
        <h1 class="mb-4">Dashboard Admin</h1>
        <div class="row mb-4">
            <!-- Statistik Kunci untuk Admin -->
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Proyek</h5>
                        <h3>{{ $totalProjects }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Total Tugas</h5>
                        <h3>{{ $totalTasks }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Pengguna Aktif</h5>
                        <h3>{{ $activeUsers }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Notifikasi Baru</h5>
                        <h3>{{ $newNotifications }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik dan Tabel -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">Distribusi Status Proyek</div>
                    <div class="card-body">
                        <canvas id="projectChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">Manajemen Pengguna</div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ ucfirst($user->role) }}</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm">Edit</button>
                                            <button class="btn btn-danger btn-sm">Hapus</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($role === 'leader')
        <h1 class="mb-4">Dashboard Leader</h1>
        <div class="row mb-4">
            <!-- Statistik untuk Leader -->
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Proyek yang Dipimpin</h5>
                        <h3>{{ $projectsLed }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Tugas Tim</h5>
                        <h3>{{ $teamTasks }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Kemajuan Tim</h5>
                        <h3>{{ $teamProgress }}%</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Proyek -->
        <div class="card">
            <div class="card-header bg-primary text-white">Proyek yang Dipimpin</div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leaderProjects as $project)
                            <tr>
                                <td>{{ $project->title }}</td>
                                <td>{{ $project->end_date }}</td>
                                <td>{{ ucfirst($project->status) }}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm">Detail</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <h1 class="mb-4">Dashboard Anggota</h1>
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Tugas Saya</h5>
                        <h3>{{ $myTasks }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Notifikasi Baru</h5>
                        <h3>{{ $newNotifications }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tugas Pribadi -->
        <div class="card">
            <div class="card-header bg-primary text-white">Tugas Saya</div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Deadline</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($myTasksList as $task)
                            <tr>
                                <td>{{ $task->title }}</td>
                                <td>{{ $task->due_date }}</td>
                                <td>{{ ucfirst($task->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<!-- Grafik -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@if ($role === 'admin')
    <script>
        const ctx = document.getElementById('projectChart').getContext('2d');
        const projectChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['In Progress', 'Completed', 'On Hold'],
                datasets: [{
                    data: [{{ $inProgress }}, {{ $completed }}, {{ $onHold }}],
                    backgroundColor: ['#007bff', '#28a745', '#ffc107']
                }]
            }
        });
    </script>
@endif --}}
