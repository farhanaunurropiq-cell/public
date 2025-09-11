<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIKEPO - MAS PPIQ</title>
    <link rel="icon" id="favicon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>ðŸ“š</text></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <!-- Leaflet for Geofencing Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <style>
        body { font-family: 'Inter', sans-serif; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
        .sidebar-link.active { background-color: #3b82f6; color: white; }
        .sidebar-link.active svg { color: white; }
        .content-section { display: none; }
        .content-section.active { display: block; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #64748b; }
        [x-cloak] { display: none !important; }
        .dashboard-shortcut { cursor: pointer; }
        #logo-preview, #sidebar-logo, #login-logo, #favicon-preview { object-fit: cover; }
        .modal { display: none; }
        .modal.flex { display: flex; }
        #login-container-wrapper {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1593083753443-a2a8a305a4a5?q=80&w=1932&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }
        .skeleton-loader {
            background-color: #e2e8f0;
            border-radius: 0.25rem;
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse {
            50% { opacity: .5; }
        }
        
        #sidebar { transition: width 300ms ease-in-out; }
        .sidebar-collapsed #sidebar { width: 5rem; }
        .sidebar-collapsed .sidebar-link-text,
        .sidebar-collapsed .sidebar-dropdown-text,
        .sidebar-collapsed #sidebar-logo-text { display: none; }
        .sidebar-collapsed .sidebar-link,
        .sidebar-collapsed .sidebar-dropdown-button { justify-content: center; }
        .sidebar-collapsed .sidebar-dropdown-button svg:last-child { display: none; }

        @media print {
            body * { visibility: hidden; }
            #print-area, #print-area * { visibility: visible; }
            #print-area { position: absolute; left: 0; top: 0; width: 100%; padding: 1rem; }
            .no-print { display: none !important; }
            @page { size: auto; margin: 0.5in; }
            .page-break-before { page-break-before: always; }
        }
    </style>
</head>
<body class="bg-slate-100">

    <div id="app-wrapper">
        <!-- Login Container -->
        <div id="login-container-wrapper" class="flex items-center justify-center min-h-screen">
            <div id="login-container" class="w-full max-w-md p-6 sm:p-8 space-y-6 bg-white rounded-lg shadow-xl">
                <div class="text-center">
                    <img id="login-logo" src="https://placehold.co/80x80/3b82f6/ffffff?text=MAS" alt="Logo Sekolah" class="w-20 h-20 mx-auto mb-4 rounded-full border-4 border-white shadow-md">
                    <h2 class="text-2xl font-bold text-gray-900">SIKEPO</h2>
                    <h2 class="text-sm text-gray-600">Sistem Kehadiran dan Poin</h2>
                    <p class="text-2xl font-bold text-gray-900">MAS PPIQ</p>
                </div>
                <form id="login-form" class="space-y-4">
                    <div>
                        <label for="username" class="text-sm font-medium text-gray-700">Username</label>
                        <input id="username" name="username" type="text" autocomplete="username" required class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan username">
                    </div>
                    <div>
                        <label for="password" class="text-sm font-medium text-gray-700">Password</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="••••••••">
                    </div>
                    <div id="login-error" class="text-sm text-center text-red-600 hidden">Username atau password salah.</div>
                    <div>
                        <button type="submit" id="login-submit-btn" class="w-full px-4 py-2 font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:bg-slate-400 disabled:cursor-wait" disabled>
                            Memuat Aplikasi...
                        </button>
                    </div>
                </form>
                 <div class="text-center pt-2">
                    <button id="goto-attendance-btn" class="w-full px-4 py-2 font-semibold text-white bg-teal-600 rounded-md hover:bg-teal-700">
                        Ke Halaman Absensi Guru
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Attendance Container (Public, no login) -->
        <div id="attendance-container-wrapper" class="hidden min-h-screen bg-slate-100 flex items-center justify-center p-4">
            <div class="w-full max-w-lg bg-white p-6 rounded-lg shadow-xl">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-slate-800">Absensi Guru Online</h2>
                    <p class="text-slate-600" id="current-time-display"></p>
                </div>
                <div class="space-y-4">
                    <div>
                        <label for="teacher-select" class="block text-sm font-medium text-slate-700">Pilih Nama Anda</label>
                        <select id="teacher-select" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"></select>
                    </div>
                    
                    <div id="attendance-status" class="text-center p-3 rounded-md text-sm"></div>

                    <div class="grid grid-cols-2 gap-4">
                        <button id="check-in-btn" class="w-full px-4 py-3 font-semibold text-white bg-green-600 rounded-md hover:bg-green-700 disabled:bg-slate-400 flex items-center justify-center gap-2">
                            <i data-lucide="log-in" class="w-5 h-5"></i> Absen Masuk
                        </button>
                        <button id="check-out-btn" class="w-full px-4 py-3 font-semibold text-white bg-red-600 rounded-md hover:bg-red-700 disabled:bg-slate-400 flex items-center justify-center gap-2">
                            <i data-lucide="log-out" class="w-5 h-5"></i> Absen Pulang
                        </button>
                    </div>
                </div>
                <div class="text-center mt-4">
                     <button id="back-to-login-btn" class="text-sm text-blue-600 hover:underline">Kembali ke Login Admin</button>
                </div>
            </div>
        </div>

        <!-- App Container (Hidden by default) -->
        <div id="app-container" class="hidden h-screen">
            <div id="app-body" class="flex h-full bg-slate-100 relative">
                <!-- Sidebar Overlay -->
                <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-10 hidden md:hidden"></div>
                <!-- Sidebar -->
                <aside id="sidebar" class="w-64 h-full flex-shrink-0 bg-slate-800 text-slate-200 flex flex-col transition-transform duration-300 ease-in-out absolute md:relative z-20 md:translate-x-0 -translate-x-full">
                    <div class="h-16 flex items-center justify-center px-4 border-b border-slate-700">
                        <div class="flex items-center">
                           <img src="https://placehold.co/40x40/3b82f6/ffffff?text=E-P" alt="Logo Sekolah" class="w-10 h-10 rounded-full mr-3" id="sidebar-logo">
                           <span class="text-lg font-bold" id="sidebar-logo-text">SIKEPO</span>
                        </div>
                        <button id="sidebar-close-btn" class="p-2 rounded-md hover:bg-slate-700 md:hidden absolute right-2 top-4">
                            <i data-lucide="x" class="w-6 h-6 text-slate-400"></i>
                        </button>
                    </div>
                    <nav id="sidebar-nav" class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
                        <a href="#" class="sidebar-link active flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors" data-target="dashboard" data-roles="Admin,Guru,Siswa"> <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i> <span class="sidebar-link-text">Dashboard</span> </a>
                        
                        <!-- Menu Baru: Kalender Akademik & Aplikasi Pendidikan -->
                        <a href="#" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors" data-target="kalender-akademik" data-roles="Admin,Guru"> <i data-lucide="calendar-days" class="w-5 h-5 mr-3"></i> <span class="sidebar-link-text">Kalender Akademik</span> </a>
                        <a href="#" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors" data-target="aplikasi-pendidikan" data-roles="Admin,Guru,Siswa"> <i data-lucide="app-window" class="w-5 h-5 mr-3"></i> <span class="sidebar-link-text">Aplikasi Pendidikan</span> </a>

                        <a href="#" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors" data-target="laporan-semester" data-roles="Admin,Guru"> <i data-lucide="file-text" class="w-5 h-5 mr-3"></i> <span class="sidebar-link-text">Laporan Semester</span> </a>
                        <a href="#" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors" data-target="rekap-poin" data-roles="Admin,Guru"> <i data-lucide="trending-up" class="w-5 h-5 mr-3"></i> <span class="sidebar-link-text">Rekap Poin Siswa</span> </a>
                        <div x-data="{ open: true }" data-roles="Admin,Guru">
                            <button @click="open = !open" class="sidebar-dropdown-button w-full flex items-center justify-between px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors">
                                <span class="flex items-center"><i data-lucide="edit" class="w-5 h-5 mr-3"></i> <span class="sidebar-dropdown-text">Transaksi Poin</span></span>
                                <i data-lucide="chevron-down" class="w-5 h-5 transition-transform" :class="{'rotate-180': open}"></i>
                            </button>
                            <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
                                <a href="#" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors text-sm" data-target="input-pelanggaran" data-roles="Admin,Guru"> <i data-lucide="user-x" class="w-4 h-4 mr-3"></i> <span class="sidebar-link-text">Input Pelanggaran</span> </a>
                                <a href="#" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors text-sm" data-target="input-prestasi" data-roles="Admin,Guru"> <i data-lucide="user-check" class="w-4 h-4 mr-3"></i> <span class="sidebar-link-text">Input Prestasi</span> </a>
                            </div>
                        </div>
                        <div x-data="{ open: true }" data-roles="Admin,Guru">
                            <button @click="open = !open" class="sidebar-dropdown-button w-full flex items-center justify-between px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors">
                                <span class="flex items-center"><i data-lucide="calendar-check" class="w-5 h-5 mr-3"></i> <span class="sidebar-dropdown-text">Absensi Guru</span></span>
                                <i data-lucide="chevron-down" class="w-5 h-5 transition-transform" :class="{'rotate-180': open}"></i>
                            </button>
                            <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
                                <a href="#" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors text-sm" data-target="absensi-laporan" data-roles="Admin,Guru"> <i data-lucide="file-clock" class="w-4 h-4 mr-3"></i> <span class="sidebar-link-text">Laporan Absensi</span> </a>
                                <a href="#" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors text-sm" data-target="absensi-jadwal" data-roles="Admin"> <i data-lucide="calendar-plus" class="w-4 h-4 mr-3"></i> <span class="sidebar-link-text">Jadwal Mengajar</span> </a>
                            </div>
                        </div>
                        <div x-data="{ open: true }" data-roles="Admin">
                             <button @click="open = !open" class="sidebar-dropdown-button w-full flex items-center justify-between px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors">
                                <span class="flex items-center"><i data-lucide="database" class="w-5 h-5 mr-3"></i> <span class="sidebar-dropdown-text">Manajemen Data</span></span>
                                <i data-lucide="chevron-down" class="w-5 h-5 transition-transform" :class="{'rotate-180': open}"></i>
                            </button>
                            <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
                                <a href="#" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors text-sm" data-target="data-academic-year" data-roles="Admin"> <i data-lucide="calendar" class="w-4 h-4 mr-3"></i> <span class="sidebar-link-text">Data Tahun Ajaran</span> </a>
                                <a href="#" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors text-sm" data-target="data-major" data-roles="Admin"> <i data-lucide="book-marked" class="w-4 h-4 mr-3"></i> <span class="sidebar-link-text">Data Jurusan</span> </a>
                                <a href="#" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors text-sm" data-target="data-student" data-roles="Admin"> <i data-lucide="users" class="w-4 h-4 mr-3"></i> <span class="sidebar-link-text">Data Siswa</span> </a>
                                <a href="#" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors text-sm" data-target="data-teacher" data-roles="Admin"> <i data-lucide="contact" class="w-4 h-4 mr-3"></i> <span class="sidebar-link-text">Data Guru</span> </a>
                                <a href="#" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors text-sm" data-target="data-kelas" data-roles="Admin"> <i data-lucide="school" class="w-4 h-4 mr-3"></i> <span class="sidebar-link-text">Data Kelas</span> </a>
                                <a href="#" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors text-sm" data-target="data-violation" data-roles="Admin"> <i data-lucide="shield-alert" class="w-4 h-4 mr-3"></i> <span class="sidebar-link-text">Jenis Pelanggaran</span> </a>
                                <a href="#" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors text-sm" data-target="data-achievement" data-roles="Admin"> <i data-lucide="award" class="w-4 h-4 mr-3"></i> <span class="sidebar-link-text">Jenis Prestasi</span> </a>
                                <a href="#" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors text-sm" data-target="data-sanction" data-roles="Admin"> <i data-lucide="gavel" class="w-4 h-4 mr-3"></i> <span class="sidebar-link-text">Manajemen Sanksi</span> </a>
                                <!-- Menu Manajemen Baru -->
                                <a href="#" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors text-sm" data-target="manajemen-kalender" data-roles="Admin"> <i data-lucide="calendar-edit" class="w-4 h-4 mr-3"></i> <span class="sidebar-link-text">Kelola Kalender</span> </a>
                                <a href="#" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors text-sm" data-target="manajemen-aplikasi" data-roles="Admin"> <i data-lucide="layout-grid" class="w-4 h-4 mr-3"></i> <span class="sidebar-link-text">Kelola Aplikasi</span> </a>
                            </div>
                        </div>
                        <div x-data="{ open: true }" data-roles="Admin">
                            <button @click="open = !open" class="sidebar-dropdown-button w-full flex items-center justify-between px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors">
                                <span class="flex items-center"><i data-lucide="settings-2" class="w-5 h-5 mr-3"></i> <span class="sidebar-dropdown-text">Pengaturan Sistem</span></span>
                                <i data-lucide="chevron-down" class="w-5 h-5 transition-transform" :class="{'rotate-180': open}"></i>
                            </button>
                            <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
                                <!-- Menu Manajemen Pengumuman -->
                                <a href="#" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors text-sm" data-target="manajemen-pengumuman" data-roles="Admin"> <i data-lucide="megaphone" class="w-4 h-4 mr-3"></i> <span class="sidebar-link-text">Kelola Pengumuman</span> </a>
                                <a href="#" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors text-sm" data-target="setting-display" data-roles="Admin"> <i data-lucide="image" class="w-4 h-4 mr-3"></i> <span class="sidebar-link-text">Tampilan & Logo</span> </a>
                                <a href="#" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors text-sm" data-target="setting-favicon" data-roles="Admin"> <i data-lucide="sparkles" class="w-4 h-4 mr-3"></i> <span class="sidebar-link-text">Favicon</span> </a>
                                <a href="#" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors text-sm" data-target="absensi-lokasi" data-roles="Admin"> <i data-lucide="map-pin" class="w-4 h-4 mr-3"></i> <span class="sidebar-link-text">Lokasi Absensi</span> </a>
                                <a href="#" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors text-sm" data-target="setting-whatsapp" data-roles="Admin"> <i data-lucide="send" class="w-4 h-4 mr-3"></i> <span class="sidebar-link-text">Pengingat WhatsApp</span> </a>
                                <a href="#" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors text-sm" data-target="kelola-akun" data-roles="Admin"> <i data-lucide="users-cog" class="w-4 h-4 mr-3"></i> <span class="sidebar-link-text">Kelola Akun User</span> </a>
                            </div>
                        </div>
                    </nav>
                    <div class="p-4 border-t border-slate-700">
                        <button id="logout-btn" class="flex items-center w-full px-4 py-2.5 text-left rounded-lg bg-red-600/20 text-red-300 hover:bg-red-500 hover:text-white transition-colors">
                            <i data-lucide="log-out" class="w-5 h-5 mr-3"></i> <span class="sidebar-link-text">Logout</span>
                        </button>
                    </div>
                </aside>
    
                <!-- Main content -->
                <div id="main-panel" class="flex-1 flex flex-col overflow-hidden transition-all duration-300 ease-in-out">
                    <header class="h-16 flex items-center justify-between px-4 sm:px-6 bg-white border-b border-slate-200 flex-shrink-0 no-print">
                        <div class="flex items-center">
                            <button id="sidebar-toggle-btn" class="p-2 mr-2 rounded-md hover:bg-slate-100">
                                <i data-lucide="menu" class="w-6 h-6 text-slate-600"></i>
                            </button>
                            <h1 class="text-xl font-semibold text-slate-800" id="page-title">Dashboard</h1>
                        </div>
                        <div class="flex items-center space-x-2 sm:space-x-4">
                            <div id="header-academic-year-selector-container" class="items-center space-x-2 hidden">
                               <label class="hidden sm:inline text-sm text-slate-600">T.A Aktif:</label>
                               <select id="header-academic-year-select" class="text-sm font-semibold text-blue-600 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"></select>
                            </div>
                             <span id="header-academic-year-display" class="text-sm text-slate-600 hidden"><span class="hidden sm:inline">T.A Aktif: </span><span class="font-semibold text-blue-600"></span></span>
                            <div class="flex items-center space-x-1 sm:space-x-3">
                                <img src="https://placehold.co/40x40/7e22ce/ffffff?text=A" alt="Admin Avatar" class="w-10 h-10 rounded-full" id="user-avatar">
                                <div>
                                    <p class="font-semibold text-slate-700 text-sm" id="user-name">Nama Pengguna</p>
                                    <p class="text-xs text-slate-500 capitalize" id="user-role">Role</p>
                                </div>
                            </div>
                        </div>
                    </header>
    
                    <main id="main-content" class="flex-1 overflow-x-hidden overflow-y-auto p-4 sm:p-6">
                        <div id="dashboard" class="content-section active"></div>
                        <div id="laporan-semester" class="content-section"></div>
                        <div id="rekap-poin" class="content-section"></div>
                        <div id="setting-display" class="content-section"></div>
                        <div id="setting-favicon" class="content-section"></div>
                        <div id="data-student" class="content-section data-table-container"></div>
                        <div id="data-teacher" class="content-section data-table-container"></div>
                        <div id="data-kelas" class="content-section data-table-container"></div>
                        <div id="data-violation" class="content-section data-table-container"></div>
                        <div id="data-achievement" class="content-section data-table-container"></div>
                        <div id="data-sanction" class="content-section data-table-container"></div>
                        <div id="input-pelanggaran" class="content-section data-table-container"></div>
                        <div id="input-prestasi" class="content-section data-table-container"></div>
                        <div id="kelola-akun" class="content-section data-table-container"></div>
                        <div id="data-academic-year" class="content-section data-table-container"></div>
                        <div id="data-major" class="content-section data-table-container"></div>
                        <!-- Attendance Sections -->
                        <div id="absensi-laporan" class="content-section"></div>
                        <div id="absensi-jadwal" class="content-section data-table-container"></div>
                        <div id="absensi-lokasi" class="content-section"></div>
                        <div id="setting-whatsapp" class="content-section"></div>
                        <!-- New Content Sections -->
                        <div id="kalender-akademik" class="content-section"></div>
                        <div id="aplikasi-pendidikan" class="content-section"></div>
                        <div id="manajemen-pengumuman" class="content-section data-table-container"></div>
                        <div id="manajemen-aplikasi" class="content-section data-table-container"></div>
                        <div id="manajemen-kalender" class="content-section data-table-container"></div>
                    </main>
                </div>
            </div>
        </div>
        
        <!-- Modals -->
        <div id="form-modal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 no-print">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] flex flex-col">
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-medium text-gray-900" id="form-modal-title">Formulir Data</h3>
                    <button id="form-modal-close-btn" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
                <form id="modal-form" class="p-6 space-y-4 overflow-y-auto">
                    <div id="form-modal-body"></div>
                    <div class="flex justify-end pt-4 space-x-2 border-t mt-4">
                        <button type="button" id="form-modal-cancel-btn" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Batal</button>
                        <button type="submit" id="form-modal-save-btn" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div id="confirm-modal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 no-print"> <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4"> <div class="flex items-start"> <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10"> <i data-lucide="alert-triangle" class="h-6 w-6 text-red-600"></i> </div> <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left"> <h3 class="text-lg leading-6 font-medium text-gray-900" id="confirm-modal-title">Konfirmasi</h3> <div class="mt-2"><p class="text-sm text-gray-500" id="confirm-modal-body"></p></div> </div> </div> <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse"> <button type="button" id="confirm-modal-confirm-btn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">Ya</button> <button type="button" id="confirm-modal-cancel-btn" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Batal</button> </div> </div> </div>
        
        <div id="ai-modal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 no-print">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4">
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2" id="ai-modal-title"><i data-lucide="brain-circuit" class="w-6 h-6 text-purple-600"></i> Analisis & Tindak Lanjut AI</h3>
                    <button id="ai-modal-close-btn" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4 max-h-[80vh] overflow-y-auto">
                    <div id="ai-modal-body"></div>
                </div>
            </div>
        </div>
    
        <div id="toast" class="fixed top-5 right-5 bg-green-500 text-white py-2 px-5 rounded-lg shadow-lg opacity-0 -translate-y-12 transition-all duration-300 z-[100] no-print"></div>
    </div>
    
    <div id="print-area"></div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
        import { getAuth, signInAnonymously, signInWithCustomToken } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-auth.js";
        import { getFirestore, collection, doc, getDoc, getDocs, setDoc, addDoc, updateDoc, deleteDoc, onSnapshot, writeBatch, query, where, orderBy, limit, serverTimestamp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-firestore.js";
        import { getStorage, ref, uploadString, getDownloadURL } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-storage.js";

        // --- GLOBAL VARIABLES & CONFIG ---
        const LOGGED_IN_USER_KEY = 'ePoinSiswaUser_v14_final';
        const LOCAL_LOGO_KEY = 'ePoinSiswaLogo_v14';
        const LOCAL_FAVICON_KEY = 'ePoinSiswaFavicon_v14';


        let appData = {};
        let currentUser = null; 
        let currentEditId = null; 
        let currentModuleKey = null;
        let db, auth, storage;
        let currentView = 'dashboard';
        const collectionListeners = {};
        let isDataSeeding = false;
        let map, mapMarker, mapCircle;

        // --- DEFAULT DATA FOR SEEDING ---
        const getDefaultData = () => ({
            students: [ { id: '1011', name: 'Budi Santoso', classId: 'XII-IPA-1', parentWhatsappNumber: '6281298765432'}, { id: '1012', name: 'Citra Lestari', classId: 'XII-IPS-2', parentWhatsappNumber: ''} ],
            teachers: [ { id: '19850101', name: 'Dr. Suryo Prakoso', title: 'S.Pd, M.Pd', photoUrl: '', whatsappNumber: '6281234567890' }, { id: '19900315', name: 'Indah Permatasari', title: 'S.Kom', photoUrl: '', whatsappNumber: '6289876543210' } ],
            classes: [ { id: 'XII-IPA-1', name: 'XII IPA 1', homeroomTeacherId: '19850101', majorId: 'IPA'}, { id: 'XII-IPS-2', name: 'XII IPS 2', homeroomTeacherId: '19900315', majorId: 'IPS'} ],
            violationTypes: [ { id: 'vt_1', name: 'Terlambat', points: 5 }, { id: 'vt_2', name: 'Seragam tidak lengkap', points: 10 } ],
            achievementTypes: [ { id: 'at_1', name: 'Juara Cerdas Cermat', points: 50 }, { id: 'at_2', name: 'Juara OSN Provinsi', points: 75 } ],
            violationRecords: [ { id: 'vr_1', studentId: '1012', violationTypeId: 'vt_1', date: '2024-09-01T07:30' } ],
            achievementRecords: [ { id: 'ar_1', studentId: '1011', achievementTypeId: 'at_1', date: '2024-09-05T10:00' } ],
            users: [ { id: 'us_1', name: 'Admin Utama', username: 'admin', role: 'Admin', password: 'password123' }, { id: 'us_2', name: 'Dr. Suryo Prakoso', username: 'suryo.p', role: 'Guru', password: 'password123', teacherId: '19850101' }, { id: 'us_3', name: 'Budi Santoso', username: 'budi.s', role: 'Siswa', password: 'password123', studentId: '1011' }, ],
            academicYears: [ {id: '2024-2025', name: '2024/2025'}, {id: '2023-2024', name: '2023/2024'}],
            majors: [{id: 'IPA', name: 'Ilmu Pengetahuan Alam'}, {id: 'IPS', name: 'Ilmu Pengetahuan Sosial'}],
            schedules: [ { id: 'sch_1', teacherId: '19850101', dayOfWeek: '1', startTime: '07:30', endTime: '09:00'}],
            attendanceRecords: [],
            sanctions: [
                { id: 'sanc_1', category: 'Ringan', minPoints: 20, maxPoints: 50, description: '1. Siswa diperingatkan\n2. Sanksi dalam bentuk olahraga' },
                { id: 'sanc_2', category: 'Sedang', minPoints: 55, maxPoints: 100, description: '1. Membuat Tugas Laporan\n2. Orang tua dipanggil' },
                { id: 'sanc_3', category: 'Berat', minPoints: 110, maxPoints: 200, description: 'Membuat pernyataan bermaterai di depan orang tua dan kepala madrasah' },
                { id: 'sanc_4', category: 'Sangat Berat', minPoints: 210, maxPoints: 300, description: 'Siswa dikembalikan ke orang tua' }
            ],
            settings: [ { id: 'main', activeAcademicYear: '2024-2025', logo: 'https://placehold.co/80x80/3b82f6/ffffff?text=MAS', schoolLat: -6.5950, schoolLon: 106.7523, schoolRadius: 100, adminWhatsapp: '6281122334455' } ], // Default: Bogor
            // New data collections
            announcements: [{ id: 'anno_1', title: 'Selamat Datang di SIKEPO', content: 'Ini adalah sistem informasi kehadiran dan poin siswa. Gunakan dengan bijak.', createdAt: new Date('2024-01-01T08:00:00Z').getTime() }],
            educationalApps: [
                { id: 'app_1', name: 'Rumah Belajar', url: 'https://belajar.kemdikbud.go.id/', description: 'Portal pembelajaran resmi dari Kemdikbud.' },
                { id: 'app_2', name: 'Kamus Besar Bahasa Indonesia (KBBI)', url: 'https://kbbi.kemdikbud.go.id/', description: 'Akses kamus resmi Bahasa Indonesia.' }
            ],
            academicCalendar: [
                { id: 'cal_1', date: '2024-07-15', title: 'Hari Pertama Sekolah', description: 'Awal tahun ajaran baru 2024/2025.' },
                { id: 'cal_2', date: '2024-08-17', title: 'Upacara HUT Kemerdekaan RI', description: 'Seluruh siswa dan guru wajib mengikuti.' }
            ]
        });

        // ----- FIRESTORE API -----
        let API;
        
        // ----- UI HELPERS -----
        const showToast = (message, isError = false) => { const t = document.getElementById('toast'); t.textContent = message; t.className = `fixed top-5 right-5 text-white py-2 px-5 rounded-lg shadow-lg transition-all duration-300 z-[100] no-print ${isError ? 'bg-red-500' : 'bg-green-500'}`; t.classList.remove('opacity-0','-translate-y-12'); setTimeout(()=>t.classList.add('opacity-0','-translate-y-12'), 3000); };
        const showConfirmModal = (title, body, onConfirm) => { document.getElementById('confirm-modal-title').textContent = title; document.getElementById('confirm-modal-body').textContent = body; document.getElementById('confirm-modal').classList.add('flex'); document.getElementById('confirm-modal-confirm-btn').onclick = () => {onConfirm(); hideConfirmModal();}; };
        const hideConfirmModal = () => document.getElementById('confirm-modal').classList.remove('flex');

        const initializeSearchableSelects = () => {
            document.querySelectorAll('.searchable-select-container').forEach(container => {
                const searchInput = container.querySelector('.search-input');
                const hiddenInput = container.querySelector('input[type="hidden"]');
                const list = container.querySelector(`#${searchInput.dataset.listId}`);
                const options = list.querySelectorAll('div[data-id]');

                const closeAllLists = () => {
                    document.querySelectorAll('.searchable-select-container .absolute').forEach(l => l.classList.add('hidden'));
                }

                searchInput.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const isHidden = list.classList.contains('hidden');
                    closeAllLists();
                    if (isHidden) {
                        list.classList.remove('hidden');
                    } else {
                        list.classList.add('hidden');
                    }
                });
                
                searchInput.addEventListener('input', () => {
                    const filter = searchInput.value.toLowerCase();
                    options.forEach(option => {
                        const text = option.textContent.toLowerCase();
                        option.style.display = text.includes(filter) ? '' : 'none';
                    });
                });

                options.forEach(option => {
                    option.addEventListener('click', () => {
                        searchInput.value = option.dataset.name; 
                        hiddenInput.value = option.dataset.id;
                        list.classList.add('hidden');
                        hiddenInput.dispatchEvent(new Event('change'));
                    });
                });
            });
        };
        
        const showFormModal = (moduleKey, editId = null) => {
            currentModuleKey = moduleKey;
            currentEditId = editId;
            const config = tableConfigs[moduleKey];
            const data = editId ? API.getById(config.module, editId) : {};
            document.getElementById('form-modal-title').textContent = `${editId ? 'Edit' : 'Tambah'} ${config.title.replace('Manajemen ','').replace('Kelola ', '')}`;
            document.getElementById('form-modal-body').innerHTML = config.formFields(data);
            document.getElementById('form-modal').classList.add('flex');
            
            if (['input-pelanggaran', 'input-prestasi'].includes(moduleKey)) {
                initializeSearchableSelects();
            }

            lucide.createIcons();
        };

        const hideFormModal = () => document.getElementById('form-modal').classList.remove('flex');
        const showAiModal = () => document.getElementById('ai-modal').classList.add('flex');
        const hideAiModal = () => document.getElementById('ai-modal').classList.remove('flex');

        // ----- RENDERERS & CORE LOGIC -----
        const contentRenderer = {};
        const tableConfigs = {};

        const formatDateTime = (isoString) => {
            if (!isoString) return '';
            if (typeof isoString === 'object' && isoString.seconds) { // Firestore Timestamp object
                isoString = new Date(isoString.seconds * 1000).toISOString();
            } else if (typeof isoString === 'number') { // Milliseconds
                 isoString = new Date(isoString).toISOString();
            }

            if (!isoString.includes('T')) return isoString; // Handle plain dates
            const date = new Date(isoString);
            return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) + ', ' + date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        };
        
        const formatDate = (dateString) => {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        };

        const calculateStudentPoints = (studentId, records) => {
            const allRecords = records || [...(appData.achievementRecords || []), ...(appData.violationRecords || [])];
            const studentRecords = allRecords.filter(r => r.studentId === studentId);
            const achievementPoints = studentRecords
                .filter(r => r.achievementTypeId)
                .reduce((sum, r) => sum + (Number(API.getById('achievementTypes', r.achievementTypeId)?.points) || 0), 0);
            const violationPoints = studentRecords
                .filter(r => r.violationTypeId)
                .reduce((sum, r) => sum + (Number(API.getById('violationTypes', r.violationTypeId)?.points) || 0), 0);
            return { achievement: achievementPoints, violation: violationPoints, net: achievementPoints - violationPoints };
        };

        const renderCurrentView = () => {
            if (isDataSeeding) return;
            const section = document.getElementById(currentView);
            if (!section) return;
            const rendererName = `render${currentView.split('-').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join('')}`;
            if (contentRenderer[rendererName]) {
                section.innerHTML = contentRenderer[rendererName]();
                // Special handler for map initialization
                if(currentView === 'absensi-lokasi') {
                    setTimeout(initializeMap, 100);
                }
            } else if (tableConfigs[currentView]) {
                section.innerHTML = contentRenderer.renderDataTablePage(tableConfigs[currentView]);
            }
            lucide.createIcons();
        };
        
        Object.assign(contentRenderer, {
            renderDashboard: () => {
                if (!currentUser) return '<div class="text-center text-slate-500">Memuat sesi pengguna...</div>';

                // Render Announcement
                const announcements = API.getAll('announcements').sort((a, b) => (b.createdAt || 0) - (a.createdAt || 0));
                let announcementHtml = '';
                if (announcements.length > 0) {
                    const latestAnn = announcements[0];
                    announcementHtml = `<div class="mb-6 p-4 bg-blue-100 border-l-4 border-blue-500 text-blue-800 rounded-r-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0"><i data-lucide="megaphone" class="w-5 h-5 mt-0.5"></i></div>
                            <div class="ml-3">
                                <h3 class="font-bold">${latestAnn.title}</h3>
                                <p class="text-sm mt-1">${latestAnn.content}</p>
                            </div>
                        </div>
                    </div>`;
                }

                if(currentUser.role==='Siswa'){ 
                    const s=API.getById('students',currentUser.studentId); 
                    if (!s) return '<div class="text-center text-slate-500">Memuat data siswa...</div>';
                    const p=calculateStudentPoints(currentUser.studentId); 
                    return `${announcementHtml}<div class="bg-white p-8 rounded-lg shadow-sm max-w-2xl mx-auto"><h2 class="text-2xl font-bold text-slate-800">Halo, ${s.name}</h2><p class="text-slate-600 mb-6">Berikut rekapitulasi poin Anda.</p><div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center"><div class="p-4 bg-green-100 rounded-lg"><p class="text-sm text-green-700">Poin Prestasi</p><p class="text-3xl font-bold text-green-800">${p.achievement}</p></div><div class="p-4 bg-red-100 rounded-lg"><p class="text-sm text-red-700">Poin Pelanggaran</p><p class="text-3xl font-bold text-red-800">${p.violation}</p></div><div class="p-4 bg-blue-100 rounded-lg"><p class="text-sm text-blue-700">Poin Bersih</p><p class="text-3xl font-bold text-blue-800">${p.net}</p></div></div></div>`; 
                }
                const stats=[ {t:'Jumlah Siswa',c:API.getAll('students').length,i:'users',cl:'blue',tr:'data-student'}, {t:'Jumlah Guru',c:API.getAll('teachers').length,i:'contact',cl:'indigo',tr:'data-teacher'}, {t:'Guru Hadir Hari Ini',c:API.getAll('attendanceRecords').filter(r => r.checkInTime?.startsWith(new Date().toISOString().slice(0,10))).length,i:'user-check',cl:'green',tr:'absensi-laporan'}, {t:'Total Pelanggaran',c:API.getAll('violationRecords').length,i:'user-x',cl:'red',tr:'input-pelanggaran'}]; 
                return `${announcementHtml}<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">${stats.map(s=>`<div class="dashboard-shortcut bg-white p-6 rounded-lg shadow-sm flex items-center space-x-4 transition hover:shadow-md" data-target="${s.tr}"><div class="p-3 rounded-full bg-${s.cl}-100 text-${s.cl}-600"><i data-lucide="${s.i}" class="w-6 h-6"></i></div><div><p class="text-sm text-slate-500">${s.t}</p><p class="text-2xl font-bold text-slate-800">${s.c}</p></div></div>`).join('')}</div>`;
            },
            renderKalenderAkademik: () => {
                const events = API.getAll('academicCalendar').sort((a, b) => new Date(a.date) - new Date(b.date));
                if (events.length === 0) {
                    return `<div class="bg-white p-8 rounded-lg shadow-sm text-center text-slate-500">Belum ada jadwal di kalender akademik.</div>`;
                }
                const eventsHtml = events.map(event => `
                    <div class="flex items-start space-x-4 p-4 border-b">
                        <div class="text-center flex-shrink-0 w-20">
                            <div class="text-sm font-semibold text-red-600">${new Date(event.date).toLocaleDateString('id-ID', { month: 'short' }).toUpperCase()}</div>
                            <div class="text-2xl font-bold text-slate-800">${new Date(event.date).getDate()}</div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-800">${event.title}</h3>
                            <p class="text-sm text-slate-600">${event.description || ''}</p>
                        </div>
                    </div>
                `).join('');
                return `<div class="bg-white rounded-lg shadow-sm overflow-hidden"><h2 class="text-xl font-semibold text-slate-800 p-6">Kalender Akademik</h2>${eventsHtml}</div>`;
            },
            renderAplikasiPendidikan: () => {
                const apps = API.getAll('educationalApps');
                if (apps.length === 0) {
                    return `<div class="bg-white p-8 rounded-lg shadow-sm text-center text-slate-500">Belum ada aplikasi pendidikan yang ditambahkan.</div>`;
                }
                const appsHtml = apps.map(app => `
                    <a href="${app.url}" target="_blank" rel="noopener noreferrer" class="block bg-white p-6 rounded-lg shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all group">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-slate-100 rounded-full group-hover:bg-blue-100">
                                <i data-lucide="arrow-up-right-square" class="w-6 h-6 text-slate-600 group-hover:text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-800">${app.name}</h3>
                                <p class="text-sm text-slate-500">${app.description || 'Klik untuk membuka'}</p>
                            </div>
                        </div>
                    </a>
                `).join('');
                return `<div class="space-y-6">
                    <h2 class="text-2xl font-bold text-slate-800">Aplikasi Pendidikan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">${appsHtml}</div>
                </div>`;
            },
            renderRekapPoin: () => {
                 return `
                 <div class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="md:flex md:items-center md:justify-between mb-6 no-print">
                        <div class="flex items-center gap-4">
                            <h2 class="text-xl font-semibold text-slate-800" id="rekap-title">Peringkat Poin Siswa</h2>
                            <select id="rekap-view-type" class="text-sm rounded-md border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="peringkat">Peringkat Poin</option>
                                <option value="prestasi">Daftar Prestasi</option>
                                <option value="pelanggaran">Daftar Pelanggaran</option>
                            </select>
                        </div>
                        <div class="mt-4 md:mt-0 flex items-center gap-2">
                             <button id="rekap-export-excel" class="inline-flex items-center gap-2 px-3 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50" title="Ekspor ke Excel"><i data-lucide="file-spreadsheet" class="w-4 h-4"></i> Unduh Excel</button>
                             <button id="rekap-export-pdf" class="inline-flex items-center gap-2 px-3 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50" title="Cetak ke PDF"><i data-lucide="printer" class="w-4 h-4"></i> Unduh PDF</button>
                        </div>
                    </div>
                    <div id="rekap-content-container">
                        ${contentRenderer.renderRekapView('peringkat')}
                    </div>
                 </div>`;
            },
            renderRekapView: (viewType) => {
                if (viewType === 'peringkat') {
                    let sWP = API.getAll('students').map(s => ({...s, pointsData: calculateStudentPoints(s.id) })).sort((a,b) => b.pointsData.net - a.pointsData.net); 
                    if(sWP.length === 0){return `<div class="text-center text-slate-500 py-8">Belum ada data siswa.</div>`;} 
                    
                    const sanctions = API.getAll('sanctions').sort((a,b) => a.minPoints - b.minPoints);
                    const findSanction = (violationPoints) => {
                        return sanctions.find(s => violationPoints >= s.minPoints && violationPoints <= s.maxPoints);
                    };

                    const highest = sWP[0]; const lowest = sWP[sWP.length-1];
                    const summaryHTML = `<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6"><div class="bg-white p-5 rounded-lg shadow-sm border-l-4 border-green-500"><h3 class="text-sm font-medium text-slate-500">POIN TERTINGGI</h3><div class="flex items-center justify-between mt-2"><span class="text-lg font-semibold text-slate-800">${highest.name}</span><span class="text-2xl font-bold text-green-600">${highest.pointsData.net}</span></div></div><div class="bg-white p-5 rounded-lg shadow-sm border-l-4 border-red-500"><h3 class="text-sm font-medium text-slate-500">POIN TERENDAH</h3><div class="flex items-center justify-between mt-2"><span class="text-lg font-semibold text-slate-800">${lowest.name}</span><span class="text-2xl font-bold text-red-600">${lowest.pointsData.net}</span></div></div></div>`;
                    const tableRows = sWP.map(s => {
                        const points = s.pointsData.net;
                        const sanction = findSanction(s.pointsData.violation);
                        let bg = '';
                        if (s.id === highest.id && highest.pointsData.net > 0) bg = 'bg-green-50';
                        if (s.id === lowest.id && lowest.pointsData.net < highest.pointsData.net) bg = 'bg-red-50';
                        return `<tr class="${bg}">
                                    <td class="px-6 py-4">${s.id}</td>
                                    <td class="px-6 py-4 font-medium">${s.name}</td>
                                    <td class="px-6 py-4">${API.getById('classes', s.classId)?.name || 'N/A'}</td>
                                    <td class="px-6 py-4 font-bold text-lg ${points > 0 ? 'text-green-600' : points < 0 ? 'text-red-600' : 'text-slate-600'}">${points}</td>
                                    <td class="px-6 py-4 text-red-600">${s.pointsData.violation}</td>
                                    <td class="px-6 py-4 text-sm whitespace-pre-wrap">${sanction ? `(${sanction.category}) ${sanction.description}` : '-'}</td>
                                    <td class="px-6 py-4 text-center no-print"><button class="ai-analysis-btn inline-flex items-center gap-2 px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700" data-student-id="${s.id}"><i data-lucide="brain-circuit" class="w-4 h-4"></i> Analisis AI</button></td>
                                </tr>`;
                    }).join(''); 
                    const table = `<div id="rekap-table-container" class="overflow-x-auto"><table class="min-w-full divide-y divide-slate-200"><thead><tr class="bg-slate-50"><th class="px-6 py-3 text-left">NIS</th><th class="px-6 py-3 text-left">Nama</th><th class="px-6 py-3 text-left">Kelas</th><th class="px-6 py-3 text-left">Poin Bersih</th><th class="px-6 py-3 text-left">Poin Pelanggaran</th><th class="px-6 py-3 text-left">Sanksi Berlaku</th><th class="px-6 py-3 text-center no-print">Aksi</th></tr></thead><tbody class="divide-y divide-slate-200">${tableRows}</tbody></table></div>`;
                    return summaryHTML + table;
                }
                if (viewType === 'prestasi') {
                    const records = API.getAll('achievementRecords').sort((a, b) => new Date(b.date) - new Date(a.date));
                    if (records.length === 0) return `<div class="text-center text-slate-500 py-8">Belum ada data prestasi.</div>`;
                    const headers = ['Waktu', 'Siswa', 'Kelas', 'Jenis Prestasi', 'Poin'];
                    const rows = records.map(r => {
                        const student = API.getById('students', r.studentId) || {};
                        const type = API.getById('achievementTypes', r.achievementTypeId) || {};
                        return `<tr><td class="px-6 py-4">${formatDateTime(r.date)}</td><td class="px-6 py-4 font-medium">${student.name || 'N/A'}</td><td class="px-6 py-4">${API.getById('classes', student.classId)?.name || 'N/A'}</td><td class="px-6 py-4">${type.name || 'N/A'}</td><td class="px-6 py-4 font-bold text-green-600">+${type.points || 0}</td></tr>`;
                    }).join('');
                    return `<div id="rekap-table-container" class="overflow-x-auto"><table class="min-w-full divide-y divide-slate-200"><thead class="bg-slate-50"><tr>${headers.map(h => `<th class="px-6 py-3 text-left text-sm font-medium text-slate-500 uppercase">${h}</th>`).join('')}</tr></thead><tbody class="divide-y divide-slate-200">${rows}</tbody></table></div>`;
                }
                 if (viewType === 'pelanggaran') {
                    const records = API.getAll('violationRecords').sort((a, b) => new Date(b.date) - new Date(a.date));
                    if (records.length === 0) return `<div class="text-center text-slate-500 py-8">Belum ada data pelanggaran.</div>`;
                    const headers = ['Waktu', 'Siswa', 'Kelas', 'Jenis Pelanggaran', 'Poin'];
                    const rows = records.map(r => {
                        const student = API.getById('students', r.studentId) || {};
                        const type = API.getById('violationTypes', r.violationTypeId) || {};
                        return `<tr><td class="px-6 py-4">${formatDateTime(r.date)}</td><td class="px-6 py-4 font-medium">${student.name || 'N/A'}</td><td class="px-6 py-4">${API.getById('classes', student.classId)?.name || 'N/A'}</td><td class="px-6 py-4">${type.name || 'N/A'}</td><td class="px-6 py-4 font-bold text-red-600">-${type.points || 0}</td></tr>`;
                    }).join('');
                    return `<div id="rekap-table-container" class="overflow-x-auto"><table class="min-w-full divide-y divide-slate-200"><thead class="bg-slate-50"><tr>${headers.map(h => `<th class="px-6 py-3 text-left text-sm font-medium text-slate-500 uppercase">${h}</th>`).join('')}</tr></thead><tbody class="divide-y divide-slate-200">${rows}</tbody></table></div>`;
                }
                return '';
            },
            renderLaporanSemester: () => { 
                const classOptions = API.getAll('classes').map(c => `<option value="${c.id}">${c.name}</option>`).join('');
                const activeYear = API.getById('settings', 'main')?.activeAcademicYear || 'N/A';
                return `<div class="bg-white p-6 rounded-lg shadow-sm">
                    <h2 class="text-xl font-semibold text-slate-800 mb-2">Laporan Poin Semester</h2>
                    <p class="text-sm text-slate-500 mb-6">Tahun Ajaran Aktif: <span class="font-semibold text-blue-600">${activeYear.replace(/-/g, '/')}</span></p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end mb-6 border-b pb-6 no-print">
                        <div class="lg:col-span-1"><label class="block text-sm font-medium text-slate-700">Pilih Kelas</label><select id="report-class" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">${classOptions}</select></div>
                        <div class="lg:col-span-1"><label class="block text-sm font-medium text-slate-700">Pilih Semester</label><select id="report-semester" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm"><option value="Ganjil">Ganjil (Juni - Des)</option><option value="Genap">Genap (Jan - Jul)</option></select></div>
                        <div class="lg:col-span-1"><label class="block text-sm font-medium text-slate-700">Tipe Laporan</label>
                            <select id="report-type" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
                                <option value="per-siswa">Per Siswa</option>
                                <option value="per-jenis">Per Jenis</option>
                            </select>
                        </div>
                        <div class="lg:col-span-1"><label class="block text-sm font-medium text-slate-700">Jenis Catatan</label>
                            <select id="report-record-type" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
                                <option value="semua">Semua Jenis</option>
                                <option value="prestasi">Hanya Prestasi</option>
                                <option value="pelanggaran">Hanya Pelanggaran</option>
                            </select>
                        </div>
                        <button id="generate-report-btn" class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-700 h-10 w-full lg:w-auto">Tampilkan</button>
                    </div>
                    <div id="report-results-container">
                        <div id="report-actions" class="hidden text-right mb-4 no-print">
                            <button id="report-export-excel" class="inline-flex items-center gap-2 px-3 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50" title="Ekspor ke Excel"><i data-lucide="file-spreadsheet" class="w-4 h-4"></i> Unduh Excel</button>
                            <button id="report-export-pdf" class="inline-flex items-center gap-2 px-3 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50" title="Cetak ke PDF"><i data-lucide="printer" class="w-4 h-4"></i> Unduh PDF</button>
                        </div>
                        <div id="report-results" class="text-center text-slate-500 p-4">Pilih opsi di atas dan klik "Tampilkan" untuk melihat hasilnya.</div>
                    </div>
                </div>`;
            },
            renderSettingDisplay: () => { 
                const settings = API.getById('settings', 'main') || {}; 
                const localLogo = localStorage.getItem(LOCAL_LOGO_KEY) || settings.logo;
                return `<div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow-sm"><h2 class="text-xl font-semibold text-slate-800 mb-6">Pengaturan Tampilan & Logo</h2><div class="flex items-center space-x-6"><img src="${localLogo}" alt="Logo Preview" id="logo-preview" class="w-24 h-24 rounded-lg bg-slate-100"><div> <p class="text-sm text-slate-600 mb-2">Unggah logo baru (JPG, PNG, BMP).</p><input type="file" id="logo-upload" class="hidden" accept=".jpg,.jpeg,.png,.bmp"><button id="select-logo-btn" class="bg-slate-100 text-slate-700 font-semibold py-2 px-4 rounded-md hover:bg-slate-200 text-sm">Pilih File</button><p id="file-name" class="text-xs text-slate-500 mt-2"></p></div></div><button id="save-logo-btn" class="mt-6 w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-700">Simpan Logo</button></div>`; 
            },
            renderSettingFavicon: () => {
                const currentFavicon = localStorage.getItem(LOCAL_FAVICON_KEY) || document.getElementById('favicon').href;
                return `<div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow-sm">
                    <h2 class="text-xl font-semibold text-slate-800 mb-6">Pengaturan Favicon</h2>
                    <div class="flex items-center space-x-6">
                        <img src="${currentFavicon}" alt="Favicon Preview" id="favicon-preview" class="w-16 h-16 rounded-lg bg-slate-100 p-1">
                        <div>
                            <p class="text-sm text-slate-600 mb-2">Unggah ikon baru (JPG, PNG, ICO).<br>Akan diubah ukurannya menjadi 32x32 piksel.</p>
                            <input type="file" id="favicon-upload" class="hidden" accept=".jpg,.jpeg,.png,.ico">
                            <button id="select-favicon-btn" class="bg-slate-100 text-slate-700 font-semibold py-2 px-4 rounded-md hover:bg-slate-200 text-sm">Pilih File</button>
                            <p id="favicon-file-name" class="text-xs text-slate-500 mt-2"></p>
                        </div>
                    </div>
                    <button id="save-favicon-btn" class="mt-6 w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-700">Simpan Favicon</button>
                </div>`;
            },
            renderAbsensiLokasi: () => {
                 const settings = API.getById('settings', 'main') || {};
                 return `<div class="bg-white p-6 rounded-lg shadow-sm">
                    <h2 class="text-xl font-semibold text-slate-800 mb-4">Pengaturan Lokasi Absensi (Geofencing)</h2>
                    <p class="text-sm text-slate-500 mb-6">Tentukan titik pusat lokasi sekolah dan radius yang diizinkan untuk absensi.</p>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2">
                             <div id="map" class="w-full h-96 rounded-lg z-0"></div>
                        </div>
                        <div class="space-y-4">
                             <div>
                                <label class="block text-sm font-medium text-slate-700">Latitude</label>
                                <input type="number" id="school-lat" value="${settings.schoolLat || ''}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" readonly>
                             </div>
                             <div>
                                <label class="block text-sm font-medium text-slate-700">Longitude</label>
                                <input type="number" id="school-lon" value="${settings.schoolLon || ''}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" readonly>
                             </div>
                              <div>
                                <label class="block text-sm font-medium text-slate-700">Radius (meter)</label>
                                <input type="number" id="school-radius" value="${settings.schoolRadius || 100}" min="20" max="500" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                             </div>
                             <p class="text-xs text-slate-500">Klik pada peta untuk menentukan titik lokasi.</p>
                             <button id="save-location-btn" class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-700">Simpan Pengaturan Lokasi</button>
                        </div>
                    </div>
                 </div>`;
            },
            renderSettingWhatsapp: () => {
                const settings = API.getById('settings', 'main') || {};
                return `<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-sm">
                    <h2 class="text-xl font-semibold text-slate-800 mb-4">Pengingat Absensi via WhatsApp</h2>
                    <p class="text-sm text-slate-500 mb-6">Atur nomor WhatsApp admin dan kirim pengingat manual kepada guru.</p>
                    
                    <div class="space-y-4 mb-6 pb-6 border-b">
                        <label for="admin-whatsapp-number" class="block text-sm font-medium text-slate-700">Nomor WhatsApp Admin</label>
                        <input type="text" id="admin-whatsapp-number" value="${settings.adminWhatsapp || ''}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Contoh: 6281122334455">
                        <button id="save-whatsapp-admin-btn" class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-700">Simpan Nomor Admin</button>
                    </div>

                    <div class="p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 rounded-md">
                        <h4 class="font-bold">Fitur dalam Pengembangan</h4>
                        <p>Pengiriman pesan WhatsApp otomatis adalah fitur server-side dan tidak dapat diimplementasikan sepenuhnya di sisi klien. Tombol di bawah ini adalah simulasi untuk menunjukkan alur kerja.</p>
                    </div>
                    <div class="mt-6">
                        <h3 class="font-semibold text-slate-700">Kirim Pengingat Manual</h3>
                        <p class="text-sm text-slate-500 mb-4">Pilih guru untuk dikirimkan pengingat absensi.</p>
                        <select id="whatsapp-teacher-select" class="block w-full rounded-md border-slate-300 shadow-sm mb-4">
                            <option value="">Pilih Guru...</option>
                            ${API.getAll('teachers').map(t => `<option value="${t.id}">${t.name}</option>`).join('')}
                        </select>
                        <button id="send-wa-reminder-btn" class="w-full bg-green-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-green-700 flex items-center justify-center gap-2">
                            <i data-lucide="send" class="w-4 h-4"></i> Kirim Pengingat (Simulasi)
                        </button>
                    </div>
                </div>`;
            },
            renderAbsensiLaporan: () => {
                const teacherOptions = API.getAll('teachers').map(t => `<option value="${t.id}">${t.name}</option>`).join('');
                return `<div class="bg-white p-6 rounded-lg shadow-sm">
                    <h2 class="text-xl font-semibold text-slate-800 mb-6">Laporan Absensi Guru</h2>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end mb-6 border-b pb-6 no-print">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tipe Laporan</label>
                            <select id="attendance-report-type" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
                                <option value="harian">Harian</option>
                                <option value="bulanan">Bulanan</option>
                            </select>
                        </div>
                        <div id="attendance-date-filter-container">
                            <label class="block text-sm font-medium text-slate-700">Tanggal</label>
                            <input type="date" id="report-date-filter" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm" value="${new Date().toISOString().slice(0,10)}">
                        </div>
                        <div id="attendance-month-filter-container" class="hidden">
                            <label class="block text-sm font-medium text-slate-700">Bulan</label>
                            <input type="month" id="report-month-filter" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm" value="${new Date().toISOString().slice(0,7)}">
                        </div>
                        <div>
                             <label class="block text-sm font-medium text-slate-700">Guru</label>
                             <select id="report-teacher-filter" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
                                <option value="all">Semua Guru</option>
                                ${teacherOptions}
                             </select>
                        </div>
                        <button id="generate-attendance-report-btn" class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-700 h-10">Tampilkan</button>
                    </div>
                     <div id="report-actions-attendance" class="hidden text-right mb-4 no-print space-x-2">
                        <button id="generate-attendance-summary-btn" class="inline-flex items-center gap-2 px-3 py-2 border border-purple-300 text-sm font-medium rounded-md text-purple-700 bg-purple-50 hover:bg-purple-100" title="Buat Ringkasan AI"><i data-lucide="brain-circuit" class="w-4 h-4"></i> ✨ Buat Ringkasan AI</button>
                        <button id="attendance-report-export-excel" class="inline-flex items-center gap-2 px-3 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50" title="Ekspor ke Excel"><i data-lucide="file-spreadsheet" class="w-4 h-4"></i> Unduh Excel</button>
                        <button id="attendance-report-export-pdf" class="inline-flex items-center gap-2 px-3 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50" title="Cetak ke PDF"><i data-lucide="printer" class="w-4 h-4"></i> Unduh PDF</button>
                    </div>
                    <div id="attendance-report-summary-container" class="hidden mb-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-2 flex items-center gap-2"><i data-lucide="brain-circuit" class="w-5 h-5 text-purple-600"></i> Ringkasan AI</h3>
                        <div id="attendance-report-summary" class="p-4 border rounded-lg text-sm text-slate-700 bg-slate-50 min-h-[80px]"></div>
                    </div>
                    <div id="attendance-report-results">
                         <div class="text-center text-slate-500 p-4">Pilih filter di atas untuk melihat laporan.</div>
                    </div>
                </div>`;
            },
            renderDataTablePage: (config) => {
                const data = API.getAll(config.module);
                const headers = config.columns.map(c => `<th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">${c.header}</th>`).join('');
                const rows = data.map(item => `<tr class="hover:bg-slate-50">${config.columns.map(c => `<td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">${c.cell(item)}</td>`).join('')}<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium no-print"><button class="text-blue-600 hover:text-blue-900 mr-4 edit-btn" data-module="${config.id}" data-id="${item.id}"><i data-lucide="edit" class="w-4 h-4 pointer-events-none"></i></button><button class="text-red-600 hover:text-red-900 delete-btn" data-module="${config.id}" data-id="${item.id}"><i data-lucide="trash-2" class="w-4 h-4 pointer-events-none"></i></button></td></tr>`).join('');

                const isSearchable = ['input-pelanggaran', 'input-prestasi'].includes(config.id);
                const searchInputHTML = isSearchable
                    ? `<div class="relative w-full sm:w-auto">
                            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"></i>
                            <input type="text" placeholder="Cari siswa atau jenis..." onkeyup="filterTable(this, '${config.id}')" class="w-full sm:w-64 rounded-md border-slate-300 pl-9 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2">
                       </div>`
                    : '';

                return `<div class="bg-white p-6 rounded-lg shadow-sm" id="datatable-${config.id}">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 no-print">
                                <h2 class="text-xl font-semibold text-slate-800">${config.title}</h2>
                                <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full sm:w-auto">
                                    ${searchInputHTML}
                                    <div class="flex items-center gap-2 ${isSearchable ? 'sm:ml-auto' : ''}">
                                        <input type="file" id="import-file-${config.id}" class="hidden" accept=".xlsx, .xls">
                                        <label for="import-file-${config.id}" class="inline-flex items-center justify-center h-9 w-9 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 cursor-pointer" title="Impor dari Excel"><i data-lucide="upload" class="w-4 h-4"></i></label>
                                        <button class="export-btn inline-flex items-center justify-center h-9 w-9 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50" data-module="${config.id}" title="Ekspor ke Excel"><i data-lucide="download" class="w-4 h-4"></i></button>
                                        <button class="add-new-btn inline-flex items-center gap-2 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 h-9" data-module="${config.id}"><i data-lucide="plus" class="w-4 h-4"></i><span class="hidden sm:inline">Tambah Baru</span></button>
                                    </div>
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-200">
                                    <thead class="bg-slate-50"><tr>${headers}<th class="w-24 no-print"></th></tr></thead>
                                    <tbody class="bg-white divide-y divide-slate-200">${rows.length > 0 ? rows : `<tr><td colspan="${config.columns.length + 1}" class="text-center py-10 text-slate-500">Tidak ada data.</td></tr>`}</tbody>
                                </table>
                            </div>
                        </div>`;
            }
        });
        
        // --- TABLE & FORM CONFIGS ---
        Object.assign(tableConfigs, {
            'data-student': { module: 'students', title: 'Manajemen Data Siswa', columns: [{header:'NIS', field: 'id', cell:i=>i.id},{header:'Nama', field: 'name', cell:i=>i.name},{header:'Kelas', field: 'classId', cell:i=>API.getById('classes', i.classId)?.name || 'N/A'}, {header: 'No. WA Ortu', cell: i => i.parentWhatsappNumber || '-'}] },
            'data-teacher': { module: 'teachers', title: 'Manajemen Data Guru', columns: [{header:'NIP', field: 'id', cell:i=>i.id},{header:'Nama', field: 'name', cell:i=>i.name},{header:'Gelar', field: 'title', cell:i=>i.title}, {header: 'No. WhatsApp', cell: i => i.whatsappNumber || '-'}] },
            'data-kelas': { module: 'classes', title: 'Manajemen Data Kelas', columns: [{header:'ID Kelas', field: 'id', cell:i=>i.id},{header:'Nama Kelas', field: 'name', cell:i=>i.name},{header:'Jurusan', field: 'majorId', cell:i=>API.getById('majors', i.majorId)?.name || 'N/A'}, {header:'Wali Kelas', field: 'homeroomTeacherId', cell:i=>API.getById('teachers', i.homeroomTeacherId)?.name || 'N/A'}] },
            'data-violation': { module: 'violationTypes', title: 'Manajemen Jenis Pelanggaran', columns: [{header:'ID', field: 'id', cell:i=>i.id},{header:'Nama', field: 'name', cell:i=>i.name},{header:'Poin', field: 'points', cell:i=>`<span class="font-bold text-red-600">${i.points}</span>`}] },
            'data-achievement': { module: 'achievementTypes', title: 'Manajemen Jenis Prestasi', columns: [{header:'ID', field: 'id', cell:i=>i.id},{header:'Nama', field: 'name', cell:i=>i.name},{header:'Poin', field: 'points', cell:i=>`<span class="font-bold text-green-600">${i.points}</span>`}] },
            'input-pelanggaran': { module: 'violationRecords', title: 'Manajemen Input Pelanggaran', columns: [{header:'Siswa', field: 'studentId', cell:i=>API.getById('students', i.studentId)?.name},{header:'Pelanggaran', field: 'violationTypeId', cell:i=>API.getById('violationTypes', i.violationTypeId)?.name},{header:'Poin', cell: i => `<span class="font-bold text-red-600">${API.getById('violationTypes', i.violationTypeId)?.points || 0}</span>`},{header:'Waktu Kejadian', field: 'date', cell:i=>formatDateTime(i.date)}] },
            'input-prestasi': { module: 'achievementRecords', title: 'Manajemen Input Prestasi', columns: [{header:'Siswa', field: 'studentId', cell:i=>API.getById('students', i.studentId)?.name},{header:'Prestasi', field: 'achievementTypeId', cell:i=>API.getById('achievementTypes', i.achievementTypeId)?.name},{header:'Poin', cell: i => `<span class="font-bold text-green-600">${API.getById('achievementTypes', i.achievementTypeId)?.points || 0}</span>`},{header:'Waktu Kejadian', field: 'date', cell:i=>formatDateTime(i.date)}] },
            'kelola-akun': { module: 'users', title: 'Manajemen Akun User', columns: [{header:'Nama', field: 'name', cell:i=>i.name},{header:'Username', field: 'username', cell:i=>i.username},{header:'Role', field: 'role', cell:i=>i.role}] },
            'data-academic-year': { module: 'academicYears', title: 'Manajemen Tahun Ajaran', columns: [{header:'Tahun Ajaran', field: 'name', cell:i=>i.name}] },
            'data-major': { module: 'majors', title: 'Manajemen Jurusan', columns: [{header:'ID Jurusan', field: 'id', cell:i=>i.id},{header:'Nama Jurusan', field: 'name', cell:i=>i.name}] },
            'absensi-jadwal': { module: 'schedules', title: 'Manajemen Jadwal Mengajar', columns: [{header:'Guru', cell:i=>API.getById('teachers', i.teacherId)?.name}, {header:'Hari', cell:i=>['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'][i.dayOfWeek]}, {header:'Jam Mulai', cell:i=>i.startTime}, {header:'Jam Selesai', cell:i=>i.endTime}] },
            'data-sanction': { 
                module: 'sanctions', 
                title: 'Manajemen Sanksi Pelanggaran', 
                columns: [
                    {header:'Kategori', cell:i=>i.category},
                    {header:'Rentang Poin', cell:i=> `${i.minPoints} - ${i.maxPoints}`},
                    {header:'Deskripsi Sanksi', cell:i=>`<div class="whitespace-pre-wrap">${i.description}</div>`}
                ]
            },
            // New Table Configs
            'manajemen-pengumuman': { module: 'announcements', title: 'Kelola Pengumuman', columns: [{header:'Judul', cell:i=>i.title}, {header:'Isi', cell:i=>i.content.substring(0, 50) + '...'}, {header:'Dibuat', cell:i=>formatDateTime(i.createdAt)}] },
            'manajemen-aplikasi': { module: 'educationalApps', title: 'Kelola Aplikasi Pendidikan', columns: [{header:'Nama Aplikasi', cell:i=>i.name}, {header:'URL', cell:i=>`<a href="${i.url}" target="_blank" class="text-blue-600 hover:underline">${i.url}</a>`}, {header:'Deskripsi', cell:i=>i.description}] },
            'manajemen-kalender': { module: 'academicCalendar', title: 'Kelola Kalender Akademik', columns: [{header:'Tanggal', cell:i=>formatDate(i.date)}, {header:'Judul Kegiatan', cell:i=>i.title}, {header:'Deskripsi', cell:i=>i.description}] },
        });
        Object.keys(tableConfigs).forEach(key => { tableConfigs[key].id = key; });
        
        const commonStyles = "mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm";
        
        tableConfigs['data-student'].formFields = d => `<div><label>NIS</label><input type="text" name="id" value="${d.id||''}" class="${commonStyles}" ${d.id?'readonly':''} required></div><div><label>Nama</label><input type="text" name="name" value="${d.name||''}" class="${commonStyles}" required></div><div><label>Kelas</label><select name="classId" class="${commonStyles}" required>${API.getAll('classes').map(c=>`<option value="${c.id}" ${d.classId===c.id?'selected':''}>${c.name}</option>`).join('')}</select></div><div><label>No. WhatsApp Orang Tua</label><input type="text" name="parentWhatsappNumber" value="${d.parentWhatsappNumber||''}" class="${commonStyles}" placeholder="Contoh: 6281234567890"></div>`;
        tableConfigs['data-teacher'].formFields = d => `<div><label>NIP</label><input type="text" name="id" value="${d.id||''}" class="${commonStyles}" ${d.id?'readonly':''} required></div><div><label>Nama</label><input type="text" name="name" value="${d.name||''}" class="${commonStyles}" required></div><div><label>Gelar</label><input type="text" name="title" value="${d.title||''}" class="${commonStyles}"></div><div><label>No. WhatsApp</label><input type="text" name="whatsappNumber" value="${d.whatsappNumber||''}" class="${commonStyles}" placeholder="Contoh: 6281234567890"></div>`;
        tableConfigs['data-kelas'].formFields = d => `<div><label>ID Kelas</label><input type="text" name="id" value="${d.id||''}" class="${commonStyles}" ${d.id?'readonly':''} required></div><div><label>Nama Kelas</label><input type="text" name="name" value="${d.name||''}" class="${commonStyles}" required></div><div><label>Jurusan</label><select name="majorId" class="${commonStyles}" required>${API.getAll('majors').map(m=>`<option value="${m.id}" ${d.majorId===m.id?'selected':''}>${m.name}</option>`).join('')}</select></div><div><label>Wali Kelas</label><select name="homeroomTeacherId" class="${commonStyles}" required>${API.getAll('teachers').map(t=>`<option value="${t.id}" ${d.homeroomTeacherId===t.id?'selected':''}>${t.name}</option>`).join('')}</select></div>`;
        tableConfigs['data-violation'].formFields = d => `<div><label>Nama Pelanggaran</label><input type="text" name="name" value="${d.name||''}" class="${commonStyles}" required></div><div><label>Poin (Negatif)</label><input type="number" name="points" min="1" value="${d.points||''}" class="${commonStyles}" required></div>`;
        tableConfigs['data-achievement'].formFields = d => `<div><label>Nama Prestasi</label><input type="text" name="name" value="${d.name||''}" class="${commonStyles}" required></div><div><label>Poin (Positif)</label><input type="number" name="points" min="1" value="${d.points||''}" class="${commonStyles}" required></div>`;
        
        const createSearchableSelectHTML = (label, name, placeholder, listId, data, selectedId) => {
            const selectedItem = data.find(item => item.id === selectedId);
            const selectedName = selectedItem ? selectedItem.name : '';
            const optionsHTML = data.map(item => `<div class="p-3 text-sm hover:bg-slate-100 cursor-pointer" data-id="${item.id}" data-name="${item.name}">${item.name}</div>`).join('');

            return `
            <div class="space-y-1">
                <label class="block text-sm font-medium text-slate-700">${label}</label>
                <div class="searchable-select-container relative">
                    <input type="text" 
                           class="search-input ${commonStyles} pr-8" 
                           placeholder="${placeholder}" 
                           value="${selectedName}"
                           data-list-id="${listId}" 
                           autocomplete="off">
                    <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"></i>
                    <input type="hidden" name="${name}" value="${selectedId || ''}" required>
                    <div id="${listId}" class="absolute z-10 w-full bg-white border rounded-md mt-1 hidden max-h-60 overflow-y-auto shadow-lg">
                        ${optionsHTML}
                    </div>
                </div>
            </div>`;
        };

        const recordFormFields = (d, type) => {
            const now = new Date();
            const today = now.toISOString().slice(0, 10);
            const currentTime = now.toTimeString().slice(0, 5);
            let dateVal = today;
            let timeVal = currentTime;
            if (d.date && d.date.includes('T')) {
                [dateVal, timeVal] = d.date.split('T');
            }

            const recordTypesRaw = type === 'pelanggaran' ? API.getAll('violationTypes') : API.getAll('achievementTypes');
            const typeKey = type === 'pelanggaran' ? 'violationTypeId' : 'achievementTypeId';
            const typeLabel = type === 'pelanggaran' ? 'Jenis Pelanggaran' : 'Jenis Prestasi';
            const pointSign = type === 'pelanggaran' ? '-' : '+';
            const students = API.getAll('students').map(s => ({ id: s.id, name: `${s.name} (${API.getById('classes', s.classId)?.name || 'N/A'})` }));
            
            const recordTypes = recordTypesRaw.map(v => ({ id: v.id, name: `${v.name} (${pointSign}${v.points} Poin)` }));

            return `
                ${createSearchableSelectHTML('Siswa', 'studentId', 'Cari & pilih siswa...', 'student-list', students, d.studentId)}
                ${createSearchableSelectHTML(typeLabel, typeKey, `Cari & pilih ${typeLabel.toLowerCase()}...`, 'type-list', recordTypes, d[typeKey])}
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-slate-700">Tanggal & Waktu</label>
                    <div class="flex gap-2">
                        <input type="date" name="date" value="${dateVal}" class="${commonStyles} w-1/2" required>
                        <input type="time" name="time" value="${timeVal}" class="${commonStyles} w-1/2" required>
                    </div>
                </div>
            `;
        };

        tableConfigs['input-pelanggaran'].formFields = d => recordFormFields(d, 'pelanggaran');
        tableConfigs['input-prestasi'].formFields = d => recordFormFields(d, 'prestasi');

        tableConfigs['kelola-akun'].formFields = d => `<div><label>Nama</label><input type="text" name="name" value="${d.name||''}" class="${commonStyles}" required></div><div><label>Username</label><input type="text" name="username" value="${d.username||''}" class="${commonStyles}" required></div><div><label>Password</label><input type="password" name="password" class="${commonStyles}" placeholder="${d.id?'Kosongkan jika tidak diubah':''}" ${d.id?'':'required'}></div><div><label>Role</label><select name="role" class="${commonStyles}" required><option value="Admin" ${d.role==='Admin'?'selected':''}>Admin</option><option value="Guru" ${d.role==='Guru'?'selected':''}>Guru</option><option value="Siswa" ${d.role==='Siswa'?'selected':''}>Siswa</option></select></div>`;
        tableConfigs['data-academic-year'].formFields = d => `<div><label>Tahun Ajaran (Contoh: 2025/2026)</label><input type="text" name="name" value="${d.name||''}" class="${commonStyles}" required pattern="\\d{4}/\\d{4}" title="Format harus TTTT/TTTT, contoh: 2025/2026"></div>`;
        tableConfigs['data-major'].formFields = d => `<div><label>ID Jurusan (Contoh: IPA)</label><input type="text" name="id" value="${d.id||''}" class="${commonStyles}" ${d.id?'readonly':''} required></div><div><label>Nama Jurusan</label><input type="text" name="name" value="${d.name||''}" class="${commonStyles}" required></div>`;
        tableConfigs['absensi-jadwal'].formFields = d => {
            const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
            return `<div><label>Guru</label><select name="teacherId" class="${commonStyles}" required>${API.getAll('teachers').map(t=>`<option value="${t.id}" ${d.teacherId===t.id?'selected':''}>${t.name}</option>`).join('')}</select></div>
            <div><label>Hari</label><select name="dayOfWeek" class="${commonStyles}" required>${days.map((day, i)=>`<option value="${i}" ${d.dayOfWeek==i?'selected':''}>${day}</option>`).join('')}</select></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label>Jam Mulai</label><input type="time" name="startTime" value="${d.startTime||''}" class="${commonStyles}" required></div>
                <div><label>Jam Selesai</label><input type="time" name="endTime" value="${d.endTime||''}" class="${commonStyles}" required></div>
            </div>`;
        };
        tableConfigs['data-sanction'].formFields = d => {
            const categories = ['Ringan', 'Sedang', 'Berat', 'Sangat Berat'];
            return `<div><label>Kategori</label><select name="category" class="${commonStyles}" required>${categories.map(c => `<option value="${c}" ${d.category === c ? 'selected' : ''}>${c}</option>`).join('')}</select></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label>Poin Minimal</label><input type="number" name="minPoints" value="${d.minPoints || ''}" class="${commonStyles}" required></div>
                <div><label>Poin Maksimal</label><input type="number" name="maxPoints" value="${d.maxPoints || ''}" class="${commonStyles}" required></div>
            </div>
            <div>
                 <div class="flex justify-between items-center mb-1">
                    <label>Deskripsi Sanksi</label>
                    <button type="button" id="generate-sanction-desc-btn" class="text-xs text-purple-600 hover:text-purple-800 font-semibold flex items-center gap-1"><i data-lucide="sparkles" class="w-3 h-3"></i> Buat Otomatis</button>
                </div>
                <textarea name="description" class="${commonStyles}" rows="4" required>${d.description || ''}</textarea>
            </div>`;
        };
        // New Form Fields
        tableConfigs['manajemen-pengumuman'].formFields = d => `<div><label>Judul Pengumuman</label><input type="text" name="title" value="${d.title||''}" class="${commonStyles}" required></div><div><label>Isi Pengumuman</label><textarea name="content" class="${commonStyles}" rows="5" required>${d.content||''}</textarea></div>`;
        tableConfigs['manajemen-aplikasi'].formFields = d => `<div><label>Nama Aplikasi</label><input type="text" name="name" value="${d.name||''}" class="${commonStyles}" required></div><div><label>URL (Tautan)</label><input type="url" name="url" value="${d.url||''}" class="${commonStyles}" placeholder="https://contoh.com" required></div><div><label>Deskripsi Singkat</label><input type="text" name="description" value="${d.description||''}" class="${commonStyles}"></div>`;
        tableConfigs['manajemen-kalender'].formFields = d => `<div><label>Tanggal</label><input type="date" name="date" value="${d.date||''}" class="${commonStyles}" required></div><div><label>Nama Kegiatan</label><input type="text" name="title" value="${d.title||''}" class="${commonStyles}" required></div><div><label>Deskripsi</label><textarea name="description" class="${commonStyles}" rows="3">${d.description||''}</textarea></div>`;

        // --- GEMINI API ---
        async function callGeminiAPI(prompt) {
            const apiKey = ""; 
            const apiUrl = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-05-20:generateContent?key=${apiKey}`;
            const payload = { contents: [{ parts: [{ text: prompt }] }] };

            try {
                const response = await fetch(apiUrl, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) });
                if (!response.ok) { 
                    let errorText = `HTTP error! status: ${response.status}`;
                    try { const errorJson = await response.json(); errorText = errorJson.error?.message || JSON.stringify(errorJson); } catch (e) { errorText = await response.text(); }
                    console.error("Gemini API Error:", errorText); 
                    return `Maaf, terjadi kesalahan saat menghubungi AI: ${response.status}`; 
                }
                const result = await response.json();
                return result.candidates?.[0]?.content?.parts?.[0]?.text || "Tidak ada respons dari AI.";
            } catch (error) {
                console.error("Fetch/Network Error:", error);
                return "Gagal terhubung ke layanan AI. Periksa koneksi internet Anda.";
            }
        }
        
        // --- NAVIGATION & EVENT LISTENERS ---
        const navigateTo = (targetId) => {
            currentView = targetId;
            document.querySelectorAll('.sidebar-link').forEach(l => l.classList.toggle('active', l.dataset.target === targetId));
            document.querySelectorAll('.content-section').forEach(s => s.classList.toggle('active', s.id === targetId));
            const link = document.querySelector(`.sidebar-link[data-target="${targetId}"]`);
            document.getElementById('page-title').textContent = link.querySelector('.sidebar-link-text')?.textContent.trim() || 'Dashboard';
            renderCurrentView();
        };

        window.filterTable = (input, tableId) => {
            const filter = input.value.toLowerCase();
            const table = document.getElementById(`datatable-${tableId}`);
            if (!table) return;
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        };

        const setupEventListeners = () => {
            document.getElementById('login-form')?.addEventListener('submit', (e) => {
                e.preventDefault();
                const { username, password } = Object.fromEntries(new FormData(e.target));
                const user = (appData.users || []).find(u => u.username === username && u.password === password);
                if (user) {
                    localStorage.setItem(LOGGED_IN_USER_KEY, JSON.stringify(user));
                    currentUser = user;
                    setupUiForUser();
                } else {
                    document.getElementById('login-error').classList.remove('hidden');
                }
            });
            
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const appBody = document.getElementById('app-body');
            
            const toggleSidebar = () => {
                 if (window.innerWidth < 768) { // Mobile toggle
                    sidebar.classList.toggle('-translate-x-full');
                    overlay.classList.toggle('hidden');
                } else { // Desktop collapse/expand
                    const isCollapsed = appBody.classList.toggle('sidebar-collapsed');
                    localStorage.setItem('sidebarCollapsed', isCollapsed);
                }
            }

            document.getElementById('sidebar-toggle-btn').addEventListener('click', toggleSidebar);
            overlay.addEventListener('click', toggleSidebar);
            document.getElementById('sidebar-close-btn').addEventListener('click', toggleSidebar);


            document.getElementById('logout-btn').addEventListener('click', () => { localStorage.removeItem(LOGGED_IN_USER_KEY); window.location.reload(); });
            document.getElementById('confirm-modal-cancel-btn').addEventListener('click', hideConfirmModal);
            document.getElementById('form-modal-close-btn').addEventListener('click', hideFormModal);
            document.getElementById('form-modal-cancel-btn').addEventListener('click', hideFormModal);
            document.getElementById('ai-modal-close-btn').addEventListener('click', hideAiModal);

            document.getElementById('goto-attendance-btn').addEventListener('click', showAttendancePage);
            document.getElementById('back-to-login-btn').addEventListener('click', showLoginPage);
            document.getElementById('check-in-btn').addEventListener('click', () => handleAttendance('in'));
            document.getElementById('check-out-btn').addEventListener('click', () => handleAttendance('out'));
            
            document.getElementById('modal-form').addEventListener('submit', async (e) => {
                e.preventDefault();
                const config = tableConfigs[currentModuleKey];
                const data = Object.fromEntries(new FormData(e.target).entries());
                
                if (data.points) data.points = Number(data.points);
                if (data.minPoints) data.minPoints = Number(data.minPoints);
                if (data.maxPoints) data.maxPoints = Number(data.maxPoints);
                if (config.module === 'academicYears') { data.id = data.name.replace(/\//g, '-'); }
                if (config.module === 'announcements') { data.createdAt = serverTimestamp(); }
                
                // Combine date and time for record modules
                if (['input-pelanggaran', 'input-prestasi'].includes(currentModuleKey)) {
                    if (data.date && data.time) {
                        data.date = `${data.date}T${data.time}`;
                        delete data.time;
                    }
                }

                let result;
                if (currentEditId) {
                    result = await API.update(config.module, currentEditId, data);
                    if(result) showToast('Data berhasil diperbarui.');
                } else {
                    result = await API.create(config.module, data);
                    if(result) showToast('Data berhasil ditambahkan.');
                }
                if (result) { hideFormModal(); }
            });
            
            document.body.addEventListener('click', async e => {
                const navLink = e.target.closest('.sidebar-link, .dashboard-shortcut');
                if (navLink?.dataset.target) { 
                    e.preventDefault(); 
                    if(!e.target.closest('[x-data] > button')) {
                        navigateTo(navLink.dataset.target);
                        if (window.innerWidth < 768) { 
                           sidebar.classList.add('-translate-x-full');
                           overlay.classList.add('hidden');
                        }
                    }
                }

                const btn = e.target.closest('button');
                if (!btn) return;
                
                const { module, id, studentId, action } = btn.dataset;

                if (btn.classList.contains('add-new-btn')) if (module) showFormModal(module);
                if (btn.classList.contains('edit-btn')) if (module && id) showFormModal(module, id);
                if (btn.classList.contains('delete-btn')) {
                    if (module && id) {
                        showConfirmModal('Konfirmasi Hapus', `Anda yakin ingin menghapus data dengan ID: ${id}?`, async () => { 
                           await API.delete(tableConfigs[module].module, id);
                           showToast('Data berhasil dihapus.');
                        });
                    }
                }
                 if (btn.classList.contains('export-btn')) {
                    if (module) handleExportExcel(module);
                }

                if (btn.id === 'select-logo-btn') document.getElementById('logo-upload').click();
                if (btn.id === 'save-logo-btn') {
                    const previewSrc = document.getElementById('logo-preview').src;
                    if (previewSrc && !previewSrc.startsWith('https://placehold.co')) {
                        localStorage.setItem(LOCAL_LOGO_KEY, previewSrc);
                        await API.update('settings', 'main', { logo: previewSrc });
                        showToast('Logo berhasil disimpan.');
                    } else { showToast('Silakan pilih file logo baru terlebih dahulu.', true); }
                }

                if (btn.id === 'select-favicon-btn') document.getElementById('favicon-upload').click();
                if (btn.id === 'save-favicon-btn') saveFavicon();
                
                if(btn.id === 'generate-report-btn') generateSemesterReport();
                if(btn.id === 'report-export-excel') handleReportExportExcel();
                if(btn.id === 'report-export-pdf') printElement('report-results', 'Laporan Semester');
                if(btn.id === 'rekap-export-excel') handleRekapExportExcel();
                if(btn.id === 'rekap-export-pdf') {
                    const viewType = document.getElementById('rekap-view-type')?.value || 'peringkat';
                    const titles = { peringkat: 'Peringkat Poin Siswa', prestasi: 'Daftar Prestasi Siswa', pelanggaran: 'Daftar Pelanggaran Siswa' };
                    printElement('rekap-content-container', titles[viewType]);
                }
                if(btn.id === 'attendance-report-export-excel') handleAttendanceReportExportExcel();
                if(btn.id === 'attendance-report-export-pdf') printElement('attendance-report-results', 'Laporan Absensi Guru');
                if(btn.id === 'save-location-btn') saveLocationSettings();
                if(btn.id === 'generate-attendance-report-btn') generateAttendanceReport();
                if(btn.id === 'generate-attendance-summary-btn') handleAiGenerateAttendanceSummary();
                if(btn.id === 'generate-sanction-desc-btn') handleAiGenerateSanctionDescription(e);
                if(btn.id === 'save-whatsapp-admin-btn') {
                    const adminWa = document.getElementById('admin-whatsapp-number').value;
                    if (adminWa) {
                        await API.update('settings', 'main', { adminWhatsapp: adminWa });
                        showToast('Nomor WhatsApp Admin berhasil disimpan.');
                    } else {
                        showToast('Nomor tidak boleh kosong.', true);
                    }
                }
                if(btn.id === 'send-wa-reminder-btn') {
                    const teacherId = document.getElementById('whatsapp-teacher-select').value;
                    if(teacherId) {
                        const teacher = API.getById('teachers', teacherId);
                        const settings = API.getById('settings', 'main');
                        if (!teacher?.whatsappNumber) {
                             showToast(`Guru ${teacher.name} tidak memiliki nomor WhatsApp.`, true);
                             return;
                        }
                        if (!settings?.adminWhatsapp) {
                             showToast(`Nomor WhatsApp admin belum diatur.`, true);
                             return;
                        }
                        showToast(`(Simulasi) Mengirim pengingat dari ${settings.adminWhatsapp} ke ${teacher.name} (${teacher.whatsappNumber})...`);
                    } else {
                        showToast('Silakan pilih guru terlebih dahulu.', true);
                    }
                }

                try {
                    if (btn.classList.contains('ai-analysis-btn')) {
                        if (studentId) await handleAiAnalysis(studentId);
                    }
                    if (action) {
                        const studentIdForAction = document.getElementById('ai-modal-body').dataset.studentId;
                        if(studentIdForAction) await handleAiAction(studentIdForAction, action);
                    }
                } catch (error) {
                    console.error("AI Action Failed:", error);
                    showToast("Terjadi kesalahan saat menjalankan fungsi AI.", true);
                }
            });

            document.body.addEventListener('change', e => {
                if (e.target.id.startsWith('import-file-')) { handleImport(e.target.id.replace('import-file-', ''), e.target.files[0]); e.target.value = ''; }
                if (e.target.id === 'logo-upload') { const file = e.target.files[0]; if (!file || !['image/jpeg', 'image/png', 'image/bmp'].includes(file.type)) { showToast('Format file tidak valid.', true); return; } const reader = new FileReader(); reader.onload = (ev) => { document.getElementById('logo-preview').src = ev.target.result; document.getElementById('file-name').textContent = file.name; }; reader.readAsDataURL(file); }
                if (e.target.id === 'favicon-upload') { const file = e.target.files[0]; if (!file) return; const reader = new FileReader(); reader.onload = (ev) => { document.getElementById('favicon-preview').src = ev.target.result; document.getElementById('favicon-file-name').textContent = file.name; }; reader.readAsDataURL(file); }
                if (e.target.id === 'header-academic-year-select') { API.update('settings', 'main', { activeAcademicYear: e.target.value }); showToast(`Tahun ajaran aktif diubah.`); }
                if (e.target.id === 'rekap-view-type') {
                    const viewType = e.target.value;
                    document.getElementById('rekap-content-container').innerHTML = contentRenderer.renderRekapView(viewType);
                    const titles = { peringkat: 'Peringkat Poin Siswa', prestasi: 'Daftar Prestasi Siswa', pelanggaran: 'Daftar Pelanggaran Siswa' };
                    document.getElementById('rekap-title').textContent = titles[viewType];
                    lucide.createIcons();
                }
                if (e.target.id === 'attendance-report-type') {
                    const type = e.target.value;
                    document.getElementById('attendance-date-filter-container').classList.toggle('hidden', type !== 'harian');
                    document.getElementById('attendance-month-filter-container').classList.toggle('hidden', type !== 'bulanan');
                }
                if (e.target.id === 'school-radius') {
                    if (mapCircle) mapCircle.setRadius(Number(e.target.value));
                }
            });

            document.addEventListener('click', e => {
                if (!e.target.closest('.searchable-select-container')) {
                    document.querySelectorAll('.searchable-select-container .absolute').forEach(list => {
                        list.classList.add('hidden');
                    });
                }
            });
        };

        // --- FAVICON FUNCTIONS ---
        function loadFaviconFromStorage() {
            const faviconDataUrl = localStorage.getItem(LOCAL_FAVICON_KEY);
            if (faviconDataUrl) {
                document.getElementById('favicon').href = faviconDataUrl;
            }
        }
        
        function saveFavicon() {
            const fileInput = document.getElementById('favicon-upload');
            const file = fileInput.files[0];
            if (!file) {
                showToast('Silakan pilih file gambar terlebih dahulu.', true);
                return;
            }

            const reader = new FileReader();
            reader.onload = e => {
                const img = new Image();
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    canvas.width = 32;
                    canvas.height = 32;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, 32, 32);
                    const dataUrl = canvas.toDataURL('image/png');
                    
                    document.getElementById('favicon').href = dataUrl;
                    localStorage.setItem(LOCAL_FAVICON_KEY, dataUrl);
                    showToast('Favicon berhasil disimpan.');
                    document.getElementById('favicon-preview').src = dataUrl;
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        // --- EXPORT, PRINT & IMPORT ---
        function exportToExcel(data, fileName) {
            try {
                const ws = XLSX.utils.json_to_sheet(data);
                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'Data');
                XLSX.writeFile(wb, `${fileName}.xlsx`);
                showToast('Ekspor Excel berhasil.');
            } catch (error) {
                console.error("Excel Export Error:", error);
                showToast('Gagal mengekspor data ke Excel.', true);
            }
        }

        function printElement(elementId, reportTitle) {
            const el = document.getElementById(elementId);
            if (!el) return;
            const printArea = document.getElementById('print-area');
            const titleHtml = `<div class="mb-4 text-center">
                <h1 class="text-xl font-bold">${reportTitle}</h1>
                <p class="text-sm">MAS PPIQ Ciomas</p>
                <p class="text-xs">Tanggal Cetak: ${new Date().toLocaleDateString('id-ID')}</p>
            </div>`;
            printArea.innerHTML = titleHtml + el.innerHTML;
            window.print();
            printArea.innerHTML = ''; // Clear after printing
        }
        
        function handleExportExcel(moduleKey) {
            const config = tableConfigs[moduleKey];
            if (!config) return;
            const rawData = API.getAll(config.module);
            
            const stripHtml = (html) => {
                if(typeof html !== 'string') return html;
                const doc = new DOMParser().parseFromString(html, 'text/html');
                return doc.body.textContent || "";
            }

            const dataForExport = rawData.map(item => {
                const row = {};
                config.columns.forEach(col => {
                    if (col.header === 'Poin' && (moduleKey === 'input-pelanggaran' || moduleKey === 'input-prestasi')) return;
                    
                    let cellValue = col.cell(item);
                    if (col.field === 'date') { // Special handling for datetime
                        cellValue = formatDateTime(item.date);
                    }
                    row[col.header] = stripHtml(cellValue);
                });
                return row;
            });
            
            exportToExcel(dataForExport, config.title.replace(/\s+/g, '_'));
        }
        
        async function handleImport(moduleKey, file) {
            if (!file) { showToast('Tidak ada file yang dipilih.', true); return; }
            if (!file.name.endsWith('.xlsx') && !file.name.endsWith('.xls')) { showToast('Format file tidak valid. Harap unggah file Excel (.xlsx atau .xls).', true); return; }

            const config = tableConfigs[moduleKey];
            if (!config) { showToast('Konfigurasi modul tidak ditemukan.', true); return; }

            const reader = new FileReader();
            reader.onload = async (event) => {
                try {
                    const data = new Uint8Array(event.target.result);
                    const workbook = XLSX.read(data, { type: 'array' });
                    const sheetName = workbook.SheetNames[0];
                    const worksheet = workbook.Sheets[sheetName];
                    const jsonData = XLSX.utils.sheet_to_json(worksheet, {raw: false});
                    if (jsonData.length === 0) { showToast('File Excel kosong atau format tidak sesuai.', true); return; }

                    showToast(`Memproses ${jsonData.length} baris data...`);

                    const batch = writeBatch(db);
                    let importedCount = 0;
                    
                    const headerMap = {};
                    config.columns.forEach(col => { if(col.field) headerMap[col.header] = col.field; });
                    
                    for (const row of jsonData) {
                        const newItem = {};
                        
                        for (const header in headerMap) {
                            const field = headerMap[header];
                            const value = row[header];

                            if (value === undefined) continue;
                            
                            // Map relational data from name to ID
                            if (field === 'classId') { newItem[field] = API.getAll('classes').find(c => c.name === value)?.id || null; } 
                            else if (field === 'homeroomTeacherId') { newItem[field] = API.getAll('teachers').find(t => t.name === value)?.id || null; } 
                            else if (field === 'majorId') { newItem[field] = API.getAll('majors').find(m => m.name === value)?.id || null; }
                            else if (field === 'studentId') { newItem[field] = API.getAll('students').find(s => s.name === value)?.id || null; }
                            else if (field === 'violationTypeId') { newItem[field] = API.getAll('violationTypes').find(v => v.name === value)?.id || null; }
                            else if (field === 'achievementTypeId') { newItem[field] = API.getAll('achievementTypes').find(a => a.name === value)?.id || null; }
                            else { newItem[field] = value; }
                        }
                        
                        let docId = newItem.id;
                        const idRequiredModules = ['students', 'teachers', 'classes', 'majors', 'violationTypes', 'achievementTypes', 'academicYears', 'users'];

                        if (config.module === 'academicYears' && newItem.name) { docId = newItem.name.replace(/\//g, '-'); }
                        if (!docId && idRequiredModules.includes(config.module)) { console.warn('ID wajib tidak ada, baris dilewati:', row); continue; }
                        if (!docId) { docId = doc(collection(db, "dummy")).id; }
                        
                        newItem.id = String(docId);

                        const docRef = doc(db, "e-poin-data/v1", config.module, newItem.id);
                        const docSnap = await getDoc(docRef);

                        if (docSnap.exists()) {
                            console.warn(`Data dengan ID ${newItem.id} sudah ada, dilewati.`);
                            continue;
                        }
                        
                        batch.set(docRef, newItem);
                        importedCount++;
                    }

                    if (importedCount > 0) {
                        await batch.commit();
                        showToast(`${importedCount} data berhasil diimpor.`);
                    } else {
                        showToast('Tidak ada data baru untuk diimpor. Data mungkin sudah ada.', true);
                    }

                } catch (error) {
                    console.error("Import Error:", error);
                    showToast('Gagal memproses file Excel. Periksa format.', true);
                }
            };
            reader.onerror = () => { showToast('Gagal membaca file.', true); };
            reader.readAsArrayBuffer(file);
        }


        function handleRekapExportExcel() {
            const viewType = document.getElementById('rekap-view-type')?.value || 'peringkat';
            let dataForExport = [];
            let fileName = 'Rekap_Poin';

            if (viewType === 'peringkat') {
                const sWP = API.getAll('students').map(s => ({...s, pointsData: calculateStudentPoints(s.id) })).sort((a,b) => b.pointsData.net - a.pointsData.net);
                const sanctions = API.getAll('sanctions');
                const findSanction = (violationPoints) => sanctions.find(s => violationPoints >= s.minPoints && violationPoints <= s.maxPoints);
                dataForExport = sWP.map(s => {
                    const sanction = findSanction(s.pointsData.violation);
                    return {
                        'NIS': s.id,
                        'Nama': s.name,
                        'Kelas': API.getById('classes', s.classId)?.name || 'N/A',
                        'Poin Bersih': s.pointsData.net,
                        'Poin Pelanggaran': s.pointsData.violation,
                        'Sanksi Berlaku': sanction ? `(${sanction.category}) ${sanction.description.replace(/\n/g, ' ')}` : '-'
                    }
                });
                fileName = 'Rekap_Poin_Siswa_Peringkat';
            } else if (viewType === 'prestasi') {
                const records = API.getAll('achievementRecords').sort((a, b) => new Date(b.date) - new Date(a.date));
                dataForExport = records.map(r => {
                    const student = API.getById('students', r.studentId) || {};
                    const type = API.getById('achievementTypes', r.achievementTypeId) || {};
                    return {
                        'Waktu': formatDateTime(r.date),
                        'Siswa': student.name || 'N/A',
                        'Kelas': API.getById('classes', student.classId)?.name || 'N/A',
                        'Jenis Prestasi': type.name || 'N/A',
                        'Poin': `+${type.points || 0}`
                    };
                });
                fileName = 'Rekap_Daftar_Prestasi';
            } else if (viewType === 'pelanggaran') {
                const records = API.getAll('violationRecords').sort((a, b) => new Date(b.date) - new Date(a.date));
                dataForExport = records.map(r => {
                    const student = API.getById('students', r.studentId) || {};
                    const type = API.getById('violationTypes', r.violationTypeId) || {};
                    return {
                        'Waktu': formatDateTime(r.date),
                        'Siswa': student.name || 'N/A',
                        'Kelas': API.getById('classes', student.classId)?.name || 'N/A',
                        'Jenis Pelanggaran': type.name || 'N/A',
                        'Poin': `-${type.points || 0}`
                    };
                });
                fileName = 'Rekap_Daftar_Pelanggaran';
            }
            
            exportToExcel(dataForExport, fileName);
        }

        function handleReportExportExcel() {
            const tableEl = document.getElementById('report-results').querySelector('table');
            if(!tableEl) {
                showToast('Tidak ada data untuk diekspor.', true);
                return;
            }
             try {
                const wb = XLSX.utils.table_to_book(tableEl, {sheet:"Laporan"});
                XLSX.writeFile(wb, 'Laporan_Semester.xlsx');
                showToast('Ekspor Laporan berhasil.');
            } catch (error) {
                console.error("Report Export Error:", error);
                showToast('Gagal mengekspor laporan.', true);
            }
        }
        
        function handleAttendanceReportExportExcel() {
            const tableEl = document.getElementById('attendance-report-results').querySelector('table');
            if(!tableEl) {
                showToast('Tidak ada data untuk diekspor.', true);
                return;
            }
             try {
                const wb = XLSX.utils.table_to_book(tableEl, {sheet:"Laporan Absensi"});
                XLSX.writeFile(wb, 'Laporan_Absensi_Guru.xlsx');
                showToast('Ekspor Laporan Absensi berhasil.');
            } catch (error) {
                console.error("Report Export Error:", error);
                showToast('Gagal mengekspor laporan.', true);
            }
        }


        // --- REPORTING LOGIC ---
        function generateSemesterReport() {
            const classId = document.getElementById('report-class').value;
            const semester = document.getElementById('report-semester').value;
            const reportType = document.getElementById('report-type').value;
            const recordType = document.getElementById('report-record-type').value;
            const resultsDiv = document.getElementById('report-results');
            const actionsDiv = document.getElementById('report-actions');
            
            const activeYearSetting = API.getById('settings', 'main')?.activeAcademicYear;
            if (!activeYearSetting) {
                resultsDiv.innerHTML = `<div class="text-red-600 p-4">Tahun ajaran aktif belum diatur.</div>`;
                actionsDiv.classList.add('hidden');
                return;
            }

            const [startYear, endYear] = activeYearSetting.split('-').map(Number);
            let startDate, endDate;
            if (semester === 'Ganjil') { // Juni - Desember
                startDate = new Date(`${startYear}-06-01`);
                endDate = new Date(`${startYear}-12-31`);
            } else { // Januari - Juli
                startDate = new Date(`${endYear}-01-01`);
                endDate = new Date(`${endYear}-07-31`);
            }
            
            const studentsInClass = API.getAll('students').filter(s => s.classId === classId);
            const studentIdsInClass = studentsInClass.map(s => s.id);
            
            let allRecords;
            if (recordType === 'prestasi') {
                allRecords = [...API.getAll('achievementRecords')];
            } else if (recordType === 'pelanggaran') {
                allRecords = [...API.getAll('violationRecords')];
            } else {
                allRecords = [...API.getAll('violationRecords'), ...API.getAll('achievementRecords')];
            }
            
            const filteredRecords = allRecords.filter(r => {
                const recordDate = new Date(r.date.split('T')[0]); // Compare date part only
                return studentIdsInClass.includes(r.studentId) && recordDate >= startDate && recordDate <= endDate;
            });

            if (filteredRecords.length === 0) {
                 resultsDiv.innerHTML = `<div class="text-slate-500 p-4">Tidak ada data pada periode dan jenis yang dipilih untuk kelas ini.</div>`;
                 actionsDiv.classList.add('hidden');
                 return;
            }
            
            let reportHtml = '';
            if (reportType === 'per-siswa') {
                reportHtml = generateReportByStudent(studentsInClass, filteredRecords, recordType);
            } else {
                reportHtml = generateReportByType(filteredRecords);
            }
            resultsDiv.innerHTML = reportHtml;
            actionsDiv.classList.remove('hidden');
            lucide.createIcons();
        }

        function generateReportByStudent(students, records, recordType) {
            let html = '';
            students.forEach((student, index) => {
                const studentRecords = records.filter(r => r.studentId === student.id);
                if (studentRecords.length === 0) return;

                const points = calculateStudentPoints(student.id, records);
                const achievements = studentRecords.filter(r => r.achievementTypeId).map(r => `<tr><td class="px-2 py-1">${formatDateTime(r.date)}</td><td class="px-2 py-1">${API.getById('achievementTypes', r.achievementTypeId)?.name || 'N/A'}</td><td class="px-2 py-1 text-green-600">+${API.getById('achievementTypes', r.achievementTypeId)?.points || 0}</td></tr>`).join('');
                const violations = studentRecords.filter(r => r.violationTypeId).map(r => `<tr><td class="px-2 py-1">${formatDateTime(r.date)}</td><td class="px-2 py-1">${API.getById('violationTypes', r.violationTypeId)?.name || 'N/A'}</td><td class="px-2 py-1 text-red-600">-${API.getById('violationTypes', r.violationTypeId)?.points || 0}</td></tr>`).join('');
                
                let achievementsHtml = '';
                if (recordType === 'semua' || recordType === 'prestasi') {
                    achievementsHtml = `<div>
                        <h4 class="font-semibold text-green-700 mb-2">Prestasi (${points.achievement} Poin)</h4>
                        <table class="w-full text-sm"><tbody>${achievements || '<tr><td colspan="3" class="text-slate-400 py-2">Tidak ada prestasi</td></tr>'}</tbody></table>
                    </div>`;
                }

                let violationsHtml = '';
                if (recordType === 'semua' || recordType === 'pelanggaran') {
                    violationsHtml = `<div>
                        <h4 class="font-semibold text-red-700 mb-2">Pelanggaran (${points.violation} Poin)</h4>
                        <table class="w-full text-sm"><tbody>${violations || '<tr><td colspan="3" class="text-slate-400 py-2">Tidak ada pelanggaran</td></tr>'}</tbody></table>
                    </div>`;
                }


                html += `
                    <div class="mb-8 p-4 border rounded-lg ${index > 0 ? 'page-break-before' : ''}">
                        <h3 class="text-lg font-semibold text-slate-800">${student.name} <span class="text-sm font-normal text-slate-500">(${student.id})</span></h3>
                        <div class="grid grid-cols-1 ${ (recordType === 'semua') ? 'md:grid-cols-2' : ''} gap-6 mt-4">
                           ${achievementsHtml}
                           ${violationsHtml}
                        </div>
                        <div class="mt-4 text-right font-bold border-t pt-2">Total Poin Semester: <span class="${points.net >= 0 ? 'text-blue-600' : 'text-red-600'}">${points.net}</span></div>
                    </div>
                `;
            });
            return html || `<div class="text-slate-500 p-4">Tidak ada data untuk ditampilkan.</div>`;
        }
        
        function generateReportByType(records) {
            const grouped = {};
            records.forEach(r => {
                let type, typeInfo;
                if(r.achievementTypeId) {
                    type = `ach_${r.achievementTypeId}`;
                    typeInfo = API.getById('achievementTypes', r.achievementTypeId);
                } else {
                    type = `vio_${r.violationTypeId}`;
                    typeInfo = API.getById('violationTypes', r.violationTypeId);
                }
                if(!typeInfo) return;
                if(!grouped[type]) {
                    grouped[type] = {
                        name: typeInfo.name,
                        points: typeInfo.points,
                        isAchievement: !!r.achievementTypeId,
                        students: []
                    };
                }
                const student = API.getById('students', r.studentId);
                grouped[type].students.push({name: student?.name || 'N/A', date: r.date});
            });

            let html = '<table class="min-w-full divide-y divide-slate-200 text-left"><thead class="bg-slate-50"><tr><th class="px-4 py-2">Jenis</th><th class="px-4 py-2">Poin</th><th class="px-4 py-2">Jumlah</th><th class="px-4 py-2">Siswa Terlibat</th></tr></thead><tbody class="divide-y divide-slate-200">';
            for(const key in grouped) {
                const item = grouped[key];
                const studentList = item.students.map(s => `${s.name} (${formatDateTime(s.date)})`).join(', ');
                html += `<tr>
                    <td class="px-4 py-2 font-medium">${item.name}</td>
                    <td class="px-4 py-2 font-bold ${item.isAchievement ? 'text-green-600' : 'text-red-600'}">${item.isAchievement ? '+' : '-'}${item.points}</td>
                    <td class="px-4 py-2">${item.students.length}</td>
                    <td class="px-4 py-2 text-sm text-slate-600">${studentList}</td>
                </tr>`;
            }
            html += '</tbody></table>';
            return html;
        }

        // --- AI-POWERED FUNCTIONS ---
        async function handleAiGenerateSanctionDescription(event) {
            const form = event.target.closest('form');
            if (!form) return;

            const category = form.querySelector('[name="category"]').value;
            const minPoints = form.querySelector('[name="minPoints"]').value;
            const maxPoints = form.querySelector('[name="maxPoints"]').value;
            const descriptionTextarea = form.querySelector('[name="description"]');

            if (!category || !minPoints || !maxPoints) {
                showToast('Harap isi Kategori dan Rentang Poin terlebih dahulu.', true);
                return;
            }

            descriptionTextarea.value = "Menghasilkan deskripsi sanksi dengan AI...";
            
            const prompt = `Anda adalah seorang ahli tata tertib sekolah di madrasah aliyah (setingkat SMA). Buatkan daftar sanksi yang mendidik dan sesuai untuk kategori pelanggaran "${category}" dengan rentang poin pelanggaran dari ${minPoints} sampai ${maxPoints} poin. Berikan dalam format daftar bernomor (1., 2., dst.).

            Contoh untuk kategori Ringan:
            1. Peringatan lisan dari guru piket.
            2. Melakukan tugas kebersihan ringan.

            Buat sanksi yang relevan untuk kategori "${category}" dalam Bahasa Indonesia.`;

            const description = await callGeminiAPI(prompt);
            descriptionTextarea.value = description;
        }

        async function handleAiGenerateAttendanceSummary() {
            const reportType = document.getElementById('attendance-report-type').value;
            const tableEl = document.getElementById('attendance-report-results').querySelector('table');
            const summaryContainer = document.getElementById('attendance-report-summary-container');
            const summaryDiv = document.getElementById('attendance-report-summary');

            if (!tableEl) {
                showToast('Tampilkan laporan terlebih dahulu untuk membuat ringkasan.', true);
                return;
            }

            summaryContainer.classList.remove('hidden');
            summaryDiv.innerHTML = `<div class="space-y-2"><div class="h-4 w-full skeleton-loader"></div><div class="h-4 w-3/4 skeleton-loader"></div></div>`;

            let reportDataText = '';
            const headers = [...tableEl.querySelectorAll('thead th')].map(th => th.textContent.trim());
            const rows = [...tableEl.querySelectorAll('tbody tr')];
            
            rows.forEach(row => {
                const cells = [...row.querySelectorAll('td')].map(td => td.textContent.trim());
                let rowText = '';
                headers.forEach((header, index) => {
                    rowText += `${header}: ${cells[index]}; `;
                });
                reportDataText += rowText.trim() + '\n';
            });

            if (reportDataText.trim() === '') {
                summaryDiv.textContent = 'Tidak ada data yang cukup untuk dianalisis.';
                return;
            }

            let prompt = `Anda adalah seorang kepala sekolah yang analitis. Berdasarkan data laporan absensi guru berikut, buatkan ringkasan singkat (2-4 kalimat) yang menyoroti tren utama, seperti tingkat kehadiran secara umum, guru yang paling disiplin, atau jika ada masalah keterlambatan yang perlu diperhatikan.

            Tipe Laporan: ${reportType}
            Data Absensi:
            ${reportDataText}

            Berikan kesimpulan dalam Bahasa Indonesia yang formal dan jelas.`

            const summary = await callGeminiAPI(prompt);
            summaryDiv.textContent = summary;
        }

        async function handleAiAnalysis(studentId) {
            const student = API.getById('students', studentId);
            if (!student) return;

            const aiModalBody = document.getElementById('ai-modal-body');
            aiModalBody.dataset.studentId = studentId;
            aiModalBody.innerHTML = `<div class="p-4 border rounded-lg bg-slate-50 mb-4"><h4 class="font-semibold text-lg text-slate-800">${student.name}</h4><p class="text-sm text-slate-600">${API.getById('classes', student.classId)?.name || ''}</p></div><h5 class="font-semibold text-slate-700 mb-2">Analisis Perilaku Awal</h5><div id="ai-analysis-result" class="p-4 border rounded-lg text-sm text-slate-700 bg-slate-100 min-h-[100px] whitespace-pre-wrap"><div class="space-y-2"><div class="h-4 w-full skeleton-loader"></div><div class="h-4 w-3/4 skeleton-loader"></div><div class="h-4 w-1/2 skeleton-loader"></div></div></div><div id="ai-action-panel" class="mt-4"></div>`;
            showAiModal();
            lucide.createIcons();
            
            const points = calculateStudentPoints(studentId);
            const violations = API.getAll('violationRecords').filter(r => r.studentId === studentId).map(r => `- ${API.getById('violationTypes', r.violationTypeId)?.name || 'N/A'}`).join('\n');
            const achievements = API.getAll('achievementRecords').filter(r => r.studentId === studentId).map(r => `- ${API.getById('achievementTypes', r.achievementTypeId)?.name || 'N/A'}`).join('\n');
            const prompt = `Anda adalah seorang konselor sekolah yang bijaksana di MAS PPIQ Ciomas. Berikan analisis singkat dan padat (maksimal 3 kalimat) mengenai tren perilaku siswa berikut. Identifikasi kemungkinan akar masalah dan berikan 2-3 saran intervensi yang konkret dan membangun. Nama Siswa: ${student.name}, Kelas: ${API.getById('classes', student.classId)?.name}, Total Poin Bersih: ${points.net}, Riwayat Prestasi:\n${achievements || 'Tidak ada'}\nRiwayat Pelanggaran:\n${violations || 'Tidak ada'}\nGunakan bahasa Indonesia yang formal namun empatik. Format jawaban dalam poin-poin: Analisis, Kemungkinan Akar Masalah, dan Saran Intervensi.`;
            
            const analysis = await callGeminiAPI(prompt);
            document.getElementById('ai-analysis-result').innerHTML = analysis.replace(/\n/g, '<br>');

            const actionPanel = document.getElementById('ai-action-panel');
            let buttonsHTML = '<h5 class="font-semibold text-slate-700 mb-2 mt-6">Tindak Lanjut Otomatis</h5><div class="flex flex-wrap gap-4">';
            
            if (points.achievement > 0) { 
                buttonsHTML += `<button data-action="apresiasi" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">✨ Buat Draf Apresiasi</button>`; 
            }
            if (points.violation > 0) { 
                buttonsHTML += `<button data-action="peringatan" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">✨ Buat Draf Surat Peringatan</button>`; 
            }
            
            const studentData = API.getById('students', studentId);
            if (studentData && studentData.parentWhatsappNumber) {
                 buttonsHTML += `<button data-action="whatsapp" class="w-full mt-2 inline-flex items-center justify-center gap-2 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700"><i data-lucide="send" class="w-4 h-4"></i> Kirim Analisis via WA ke Orang Tua</button>`;
            }

            buttonsHTML += '</div><div id="ai-action-result" class="mt-4"></div>';
            actionPanel.innerHTML = buttonsHTML;
            lucide.createIcons();
        }

        async function handleAiAction(studentId, action) {
            const student = API.getById('students', studentId);
            if (!student) return;

            const resultContainer = document.getElementById('ai-action-result');
            resultContainer.innerHTML = `<div class="p-4 border rounded-lg bg-slate-100 min-h-[200px]"><div class="h-4 w-full skeleton-loader"></div></div>`;
            
            const points = calculateStudentPoints(studentId);
            let prompt = '';

            if (action === 'peringatan') {
                const violations = API.getAll('violationRecords').filter(r => r.studentId === studentId).map(r => `- ${API.getById('violationTypes', r.violationTypeId)?.name || 'N/A'} (pada ${formatDateTime(r.date)})`).join('\n');
                const sanctions = API.getAll('sanctions').sort((a,b) => a.minPoints - b.minPoints);
                const applicableSanction = sanctions.find(s => points.violation >= s.minPoints && points.violation <= s.maxPoints);
                const sanctionText = applicableSanction ? `Berdasarkan peraturan sekolah, total poin pelanggaran tersebut masuk ke dalam kategori sanksi "${applicableSanction.category}", yang meliputi:\n${applicableSanction.description}` : "Sanksi akan ditentukan berdasarkan diskusi lebih lanjut.";

                prompt = `Anda adalah Kepala Sekolah MAS PPIQ Ciomas. Buatkan draf Surat Peringatan yang formal, tegas, namun mendidik untuk orang tua/wali dari siswa berikut. Sebutkan total poin pelanggaran dan daftar pelanggarannya. Jelaskan juga sanksi yang berlaku sesuai kebijakan sekolah. Akhiri dengan ajakan untuk bertemu dan berdiskusi demi kebaikan siswa.

                Nama Siswa: ${student.name}
                Kelas: ${API.getById('classes', student.classId)?.name}
                Total Poin Pelanggaran: ${points.violation}
                Detail Pelanggaran:
                ${violations}

                Sanksi yang Berlaku:
                ${sanctionText}

                Gunakan format surat resmi sederhana dalam bahasa Indonesia.`;
            } else if (action === 'apresiasi') {
                 const achievements = API.getAll('achievementRecords').filter(r => r.studentId === studentId).map(r => `- ${API.getById('achievementTypes', r.achievementTypeId)?.name || 'N/A'} (pada ${formatDateTime(r.date)})`).join('\n');
                 prompt = `Anda adalah Wali Kelas di MAS PPIQ Ciomas. Buatkan sebuah pesan apresiasi yang hangat dan memotivasi untuk orang tua/wali dari siswa berprestasi berikut. Sebutkan total poin prestasinya dan pencapaian yang telah diraih. Akhiri dengan harapan agar prestasi ini dapat terus dipertahankan. Nama Siswa: ${student.name}, Kelas: ${API.getById('classes', student.classId)?.name}, Total Poin Prestasi: ${points.achievement}. Detail Prestasi:\n${achievements}\nGunakan format pesan singkat yang bisa dikirim melalui WhatsApp atau surat, dalam bahasa Indonesia.`;
            } else if (action === 'whatsapp') {
                const parentWaNumber = student?.parentWhatsappNumber;
                if (!parentWaNumber) {
                    showToast('Nomor WhatsApp orang tua tidak ditemukan untuk siswa ini.', true);
                    resultContainer.innerHTML = '';
                    return;
                }

                const studentClass = API.getById('classes', student.classId);
                const homeroomTeacher = studentClass ? API.getById('teachers', studentClass.homeroomTeacherId) : null;
                const teacherName = homeroomTeacher ? homeroomTeacher.name : 'Pihak Sekolah';

                const analysisText = document.getElementById('ai-analysis-result').innerText;
                if (!analysisText || analysisText.includes('Memuat...')) {
                    showToast('Tunggu analisis AI selesai dibuat sebelum mengirim.', true);
                    resultContainer.innerHTML = '';
                    return;
                }
                
                const message = `Yth. Bapak/Ibu Wali Murid dari ${student.name},

Berikut kami sampaikan hasil analisis dan observasi perilaku dari sekolah:

${analysisText}

Terima kasih atas perhatian dan kerja sama Bapak/Ibu.

Hormat kami,
${teacherName}
Wali Kelas`;

                const encodedMessage = encodeURIComponent(message);
                const whatsappUrl = `https://wa.me/${parentWaNumber}?text=${encodedMessage}`;
                
                window.open(whatsappUrl, '_blank');
                resultContainer.innerHTML = ''; // Clear loading indicator
                return; // Exit function, no need to call Gemini again
            }
            
            const result = await callGeminiAPI(prompt);
            resultContainer.innerHTML = `<textarea readonly class="w-full h-64 p-2 border rounded-md bg-slate-50 text-sm font-mono">${result}</textarea><button onclick="this.previousElementSibling.select(); document.execCommand('copy');" class="mt-2 text-sm text-blue-600 hover:underline">Salin Teks</button>`;
        }
        
        // --- ATTENDANCE FEATURE FUNCTIONS ---

        function showLoginPage() {
            document.getElementById('attendance-container-wrapper').classList.add('hidden');
            document.getElementById('login-container-wrapper').classList.remove('hidden');
        }

        async function showAttendancePage() {
            document.getElementById('login-container-wrapper').classList.add('hidden');
            document.getElementById('attendance-container-wrapper').classList.remove('hidden');
            
            const teacherSelect = document.getElementById('teacher-select');
            const teachers = API.getAll('teachers');
            teacherSelect.innerHTML = `<option value="">-- Pilih Nama --</option>` + teachers.map(t => `<option value="${t.id}">${t.name}</option>`).join('');

            updateTimeDisplay();
            setInterval(updateTimeDisplay, 1000);
            lucide.createIcons();
        }

        function updateTimeDisplay() {
            const timeEl = document.getElementById('current-time-display');
            if(timeEl) {
                const now = new Date();
                timeEl.textContent = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'}) + ' - ' + now.toLocaleTimeString('id-ID');
            }
        }
        
        function getDistance(lat1, lon1, lat2, lon2) {
            const R = 6371e3; // metres
            const φ1 = lat1 * Math.PI/180;
            const φ2 = lat2 * Math.PI/180;
            const Δφ = (lat2-lat1) * Math.PI/180;
            const Δλ = (lon2-lon1) * Math.PI/180;

            const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) + Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ/2) * Math.sin(Δλ/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

            return R * c; // in metres
        }
        
        function setAttendanceStatus(message, type = 'info') {
            const statusEl = document.getElementById('attendance-status');
            statusEl.textContent = message;
            const colors = { info: 'bg-blue-100 text-blue-800', success: 'bg-green-100 text-green-800', error: 'bg-red-100 text-red-800', warning: 'bg-yellow-100 text-yellow-800' };
            statusEl.className = `text-center p-3 rounded-md text-sm ${colors[type]}`;
        }

        async function handleAttendance(type) { // 'in' or 'out'
            const teacherId = document.getElementById('teacher-select').value;
            if (!teacherId) {
                setAttendanceStatus('Silakan pilih nama Anda terlebih dahulu.', 'error');
                return;
            }

            const buttons = [document.getElementById('check-in-btn'), document.getElementById('check-out-btn')];
            buttons.forEach(b => b.disabled = true);
            setAttendanceStatus('Memproses absensi...', 'info');

            const now = new Date();
            const today = now.getDay();
            
            const schedule = API.getAll('schedules').find(s => s.teacherId === teacherId && parseInt(s.dayOfWeek) === today);

            if (!schedule) {
                setAttendanceStatus('Tidak ada jadwal mengajar untuk Anda hari ini.', 'error');
                buttons.forEach(b => b.disabled = false);
                return;
            }

            setAttendanceStatus('Mendapatkan lokasi Anda...', 'info');
            let userPosition;
            try {
                userPosition = await new Promise((resolve, reject) => navigator.geolocation.getCurrentPosition(resolve, reject, { 
                    timeout: 20000, 
                    enableHighAccuracy: true,
                    maximumAge: 0 
                }));
            } catch (err) {
                let message = 'Gagal mendapatkan lokasi. Pastikan izin lokasi diberikan.';
                if (err.code === 1) message = 'Akses lokasi ditolak. Harap izinkan akses lokasi di pengaturan browser Anda.';
                if (err.code === 2) message = 'Lokasi tidak tersedia. Pastikan GPS atau layanan lokasi Anda aktif.';
                if (err.code === 3) message = 'Waktu permintaan lokasi habis. Coba lagi di tempat dengan sinyal lebih baik.';
                setAttendanceStatus(message, 'error');
                buttons.forEach(b => b.disabled = false);
                return;
            }
            
            const { latitude, longitude } = userPosition.coords;
            const schoolSettings = API.getById('settings', 'main');
            const distance = getDistance(latitude, longitude, schoolSettings.schoolLat, schoolSettings.schoolLon);

            if (distance > schoolSettings.schoolRadius) {
                 setAttendanceStatus(`Anda berada ${Math.round(distance)}m dari sekolah. Anda harus berada dalam radius ${schoolSettings.schoolRadius}m.`, 'error');
                 buttons.forEach(b => b.disabled = false);
                 return;
            }
            setAttendanceStatus('Lokasi terverifikasi. Menyimpan data...', 'info');
            
            const todayISO = now.toISOString().slice(0, 10);
            const q = query(collection(db, "e-poin-data/v1/attendanceRecords"), where("teacherId", "==", teacherId), where("date", "==", todayISO));
            const querySnapshot = await getDocs(q);
            const existingRecords = querySnapshot.docs.map(d => ({...d.data(), id: d.id}));
            
            let status = 'Hadir Tepat Waktu';
            const nowISO = now.toISOString();

            if (type === 'in') {
                if (existingRecords.length > 0) {
                     setAttendanceStatus('Anda sudah melakukan absen masuk hari ini.', 'warning');
                     buttons.forEach(b => b.disabled = false);
                     return;
                }
                
                const currentTime = now.toTimeString().slice(0, 5);
                if (currentTime > schedule.startTime) status = 'Terlambat';
                
                await API.create('attendanceRecords', { 
                    teacherId, 
                    date: todayISO,
                    scheduleId: schedule.id,
                    checkInTime: nowISO,
                    checkInLat: latitude,
                    checkInLon: longitude,
                    status: status
                });
                setAttendanceStatus('Absen Masuk Berhasil!', 'success');
            } else { // type 'out'
                if (existingRecords.length === 0 || !existingRecords[0].checkInTime) {
                    setAttendanceStatus('Anda belum melakukan absen masuk hari ini.', 'error');
                    buttons.forEach(b => b.disabled = false);
                    return;
                }
                if (existingRecords[0].checkOutTime) {
                    setAttendanceStatus('Anda sudah melakukan absen pulang hari ini.', 'warning');
                    buttons.forEach(b => b.disabled = false);
                    return;
                }
                status = existingRecords[0].status;
                const currentTime = now.toTimeString().slice(0, 5);
                if(currentTime < schedule.endTime) status = 'Pulang Lebih Awal';

                await API.update('attendanceRecords', existingRecords[0].id, {
                    checkOutTime: nowISO,
                    checkOutLat: latitude,
                    checkOutLon: longitude,
                    status: status
                });
                setAttendanceStatus('Absen Pulang Berhasil!', 'success');
            }

            setTimeout(() => {
                buttons.forEach(b => b.disabled = false);
                setAttendanceStatus('', 'info');
                document.getElementById('teacher-select').value = '';
            }, 3000);
        }

        function generateAttendanceReport() {
            const reportType = document.getElementById('attendance-report-type').value;
            document.getElementById('attendance-report-summary-container').classList.add('hidden'); // Hide summary on new report
            
            if (reportType === 'harian') {
                generateDailyAttendanceReport();
            } else if (reportType === 'bulanan') {
                generateMonthlyAttendanceReport();
            }
        }

        function generateDailyAttendanceReport() {
            const date = document.getElementById('report-date-filter').value;
            const teacherId = document.getElementById('report-teacher-filter').value;
            const resultsDiv = document.getElementById('attendance-report-results');
            const actionsDiv = document.getElementById('report-actions-attendance');

            if (!date) {
                resultsDiv.innerHTML = `<div class="text-center text-red-500 p-4">Silakan pilih tanggal.</div>`;
                actionsDiv.classList.add('hidden');
                return;
            }
            actionsDiv.classList.remove('hidden');

            let records = API.getAll('attendanceRecords').filter(r => r.date === date);
            if (teacherId !== 'all') {
                records = records.filter(r => r.teacherId === teacherId);
            }
            
            const totalTeachersWithSchedule = API.getAll('teachers').filter(t => {
                const dayOfWeek = new Date(date).getDay();
                return API.getAll('schedules').some(s => s.teacherId === t.id && Number(s.dayOfWeek) === dayOfWeek);
            }).length;

            const presentCount = new Set(records.map(r => r.teacherId)).size;
            const absentCount = totalTeachersWithSchedule - presentCount;
            const attendancePercentage = totalTeachersWithSchedule > 0 ? ((presentCount / totalTeachersWithSchedule) * 100).toFixed(1) : 0;
            
            const summaryHTML = `<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="p-4 bg-blue-100 rounded-lg text-center"><p class="text-sm text-blue-700">Total Jadwal</p><p class="text-2xl font-bold text-blue-800">${totalTeachersWithSchedule}</p></div>
                <div class="p-4 bg-green-100 rounded-lg text-center"><p class="text-sm text-green-700">Hadir</p><p class="text-2xl font-bold text-green-800">${presentCount}</p></div>
                <div class="p-4 bg-red-100 rounded-lg text-center"><p class="text-sm text-red-700">Tidak Hadir</p><p class="text-2xl font-bold text-red-800">${absentCount}</p></div>
                <div class="p-4 bg-indigo-100 rounded-lg text-center"><p class="text-sm text-indigo-700">Persentase</p><p class="text-2xl font-bold text-indigo-800">${attendancePercentage}%</p></div>
            </div>`;

            if (records.length === 0) {
                 resultsDiv.innerHTML = summaryHTML + `<div class="text-center text-slate-500 p-4">Tidak ada data absensi pada tanggal ini.</div>`;
                 return;
            }

            const statusColors = { 'Hadir Tepat Waktu': 'bg-green-100 text-green-800', 'Terlambat': 'bg-yellow-100 text-yellow-800', 'Pulang Lebih Awal': 'bg-orange-100 text-orange-800' };

            const tableRows = records.map(r => {
                const teacher = API.getById('teachers', r.teacherId);
                return `<tr>
                    <td class="px-6 py-4">${teacher?.name || 'N/A'}</td>
                    <td class="px-6 py-4">${r.checkInTime ? new Date(r.checkInTime).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) : '-'}</td>
                    <td class="px-6 py-4">${r.checkOutTime ? new Date(r.checkOutTime).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) : '-'}</td>
                    <td class="px-6 py-4"><span class="px-2 py-1 text-xs font-medium rounded-full ${statusColors[r.status] || 'bg-slate-100 text-slate-800'}">${r.status || 'Hadir'}</span></td>
                </tr>`;
            }).join('');
            
            resultsDiv.innerHTML = summaryHTML + `<div class="overflow-x-auto"><table class="min-w-full divide-y divide-slate-200"><thead class="bg-slate-50"><tr>
                <th class="px-6 py-3 text-left">Nama Guru</th>
                <th class="px-6 py-3 text-left">Jam Masuk</th>
                <th class="px-6 py-3 text-left">Jam Pulang</th>
                <th class="px-6 py-3 text-left">Status</th>
            </tr></thead><tbody class="divide-y divide-slate-200">${tableRows}</tbody></table></div>`;
        }
        
        function generateMonthlyAttendanceReport() {
            const monthInput = document.getElementById('report-month-filter').value;
            const teacherIdFilter = document.getElementById('report-teacher-filter').value;
            const resultsDiv = document.getElementById('attendance-report-results');
            const actionsDiv = document.getElementById('report-actions-attendance');

            if (!monthInput) {
                resultsDiv.innerHTML = `<div class="text-center text-red-500 p-4">Silakan pilih bulan dan tahun.</div>`;
                actionsDiv.classList.add('hidden');
                return;
            }
            actionsDiv.classList.remove('hidden');
            
            const [year, month] = monthInput.split('-');
            const startDate = new Date(year, month - 1, 1);
            const endDate = new Date(year, month, 0);

            let teachers = API.getAll('teachers');
            if (teacherIdFilter !== 'all') {
                teachers = teachers.filter(t => t.id === teacherIdFilter);
            }
            
            const recordsInMonth = API.getAll('attendanceRecords').filter(r => r.date.startsWith(monthInput));

            const stats = teachers.map(teacher => {
                let totalScheduleDays = 0;
                for (let d = new Date(startDate); d <= endDate; d.setDate(d.getDate() + 1)) {
                    const dayOfWeek = d.getDay();
                    const hasSchedule = API.getAll('schedules').some(s => s.teacherId === teacher.id && Number(s.dayOfWeek) === dayOfWeek);
                    if (hasSchedule) {
                        totalScheduleDays++;
                    }
                }
                
                const presentDays = new Set(recordsInMonth.filter(r => r.teacherId === teacher.id).map(r => r.date)).size;
                const absentDays = totalScheduleDays - presentDays;
                const percentage = totalScheduleDays > 0 ? ((presentDays / totalScheduleDays) * 100).toFixed(1) : 0;

                return {
                    name: teacher.name,
                    totalScheduleDays,
                    presentDays,
                    absentDays,
                    percentage
                };
            }).filter(s => s.totalScheduleDays > 0);
            
            if (stats.length === 0) {
                resultsDiv.innerHTML = `<div class="text-center text-slate-500 p-4">Tidak ada data jadwal atau absensi pada bulan ini.</div>`;
                return;
            }

            const tableRows = stats.map(s => {
                return `<tr>
                    <td class="px-6 py-4 font-medium">${s.name}</td>
                    <td class="px-6 py-4 text-center">${s.totalScheduleDays}</td>
                    <td class="px-6 py-4 text-center">${s.presentDays}</td>
                    <td class="px-6 py-4 text-center">${s.absentDays}</td>
                    <td class="px-6 py-4 text-center font-bold">${s.percentage}%</td>
                </tr>`;
            }).join('');
            
            resultsDiv.innerHTML = `<div class="overflow-x-auto"><table class="min-w-full divide-y divide-slate-200"><thead class="bg-slate-50"><tr>
                <th class="px-6 py-3 text-left">Nama Guru</th>
                <th class="px-6 py-3 text-center">Total Hari Mengajar</th>
                <th class="px-6 py-3 text-center">Total Hadir</th>
                <th class="px-6 py-3 text-center">Total Tidak Hadir</th>
                <th class="px-6 py-3 text-center">Persentase Kehadiran</th>
            </tr></thead><tbody class="divide-y divide-slate-200">${tableRows}</tbody></table></div>`;
        }

        // --- MAP & LOCATION FUNCTIONS ---
        function initializeMap() {
            const settings = API.getById('settings', 'main') || {};
            const lat = settings.schoolLat || -6.5950;
            const lon = settings.schoolLon || 106.7523;
            const radius = settings.schoolRadius || 100;

            if (document.getElementById('map')._leaflet_id) { return; } // Already initialized

            map = L.map('map').setView([lat, lon], 17);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            mapMarker = L.marker([lat, lon], { draggable: true }).addTo(map);
            mapCircle = L.circle([lat, lon], { radius }).addTo(map);

            mapMarker.on('dragend', function(e) {
                const newPos = e.target.getLatLng();
                document.getElementById('school-lat').value = newPos.lat.toFixed(6);
                document.getElementById('school-lon').value = newPos.lng.toFixed(6);
                mapCircle.setLatLng(newPos);
            });
            
            map.on('click', function(e) {
                const newPos = e.latlng;
                 document.getElementById('school-lat').value = newPos.lat.toFixed(6);
                 document.getElementById('school-lon').value = newPos.lng.toFixed(6);
                 mapMarker.setLatLng(newPos);
                 mapCircle.setLatLng(newPos);
            });
        }
        
        async function saveLocationSettings() {
            const lat = document.getElementById('school-lat').value;
            const lon = document.getElementById('school-lon').value;
            const radius = document.getElementById('school-radius').value;

            if(!lat || !lon || !radius) {
                showToast('Data lokasi tidak lengkap.', true);
                return;
            }

            await API.update('settings', 'main', { 
                schoolLat: Number(lat), 
                schoolLon: Number(lon), 
                schoolRadius: Number(radius) 
            });
            showToast('Pengaturan lokasi berhasil disimpan.');
        }

        // --- MAIN APP INITIALIZATION ---
        async function main() {
            const firebaseConfig = {
                apiKey: "AIzaSyAKrvD0AFxAgS_WzmNNJTrm9o8BqDpvKB8",
                authDomain: "epoint-4c094.firebaseapp.com",
                databaseURL: "https://epoint-4c094-default-rtdb.asia-southeast1.firebasedatabase.app",
                projectId: "epoint-4c094",
                storageBucket: "epoint-4c094.appspot.com",
                messagingSenderId: "180782645250",
                appId: "1:180782645250:web:f88ce641fd53893a37671b",
                measurementId: "G-XVFYP6V22V"
            };

            const app = initializeApp(firebaseConfig);
            db = getFirestore(app);
            auth = getAuth(app);
            storage = getStorage(app);
            
            const dataRootPath = "e-poin-data/v1";

            API = {
                getAll: (c) => appData[c] || [],
                getById: (c, id) => (appData[c] || []).find(item => item.id === id),
                create: async (c, data) => { 
                    try {
                        const isIdProvided = !!data.id;
                        const newId = isIdProvided ? data.id : doc(collection(db, "dummy")).id;
                        
                        if(isIdProvided && (await getDoc(doc(db, dataRootPath, c, data.id))).exists()) {
                            showToast(`Error: ID '${data.id}' sudah ada.`, true); return null;
                        }
                        
                        const docRef = doc(db, dataRootPath, c, newId);
                        await setDoc(docRef, {...data, id: newId });
                        return { ...data, id: newId };
                    } catch (e) { console.error("Create Error:", e); showToast("Gagal membuat data.", true); return null; }
                },
                update: async (c, id, data) => { try { const docRef = doc(db, dataRootPath, c, id); await updateDoc(docRef, data); return { id, ...data }; } catch (e) { console.error("Update Error:", e); showToast("Gagal memperbarui data.", true); return null; } },
                delete: async (c, id) => { try { await deleteDoc(doc(db, dataRootPath, c, id)); return true; } catch (e) { console.error("Delete Error:", e); showToast("Gagal menghapus data.", true); return false; } },
            };
            
            try {
                await signInAnonymously(auth);
            } catch (e) { 
                console.error("Authentication failed:", e); 
                const loginBtn = document.getElementById('login-submit-btn');
                loginBtn.textContent = "Kesalahan Konfigurasi";
                if (e.code === 'auth/configuration-not-found') {
                    showToast("Error: Login anonim belum diaktifkan di Firebase.", true);
                }
                return;
            }

            try {
                const usersSnap = await getDocs(collection(db, dataRootPath, 'users'));
                if (usersSnap.empty) {
                    isDataSeeding = true;
                    const loginBtn = document.getElementById('login-submit-btn');
                    loginBtn.textContent = "Menyiapkan Database Awal...";
                    showToast("Database kosong, mengisi data awal...");
                    const defaultData = getDefaultData();
                    const batch = writeBatch(db);
                    for (const colName in defaultData) {
                        defaultData[colName].forEach(item => {
                            const docRef = doc(db, dataRootPath, colName, item.id);
                            batch.set(docRef, item);
                        });
                    }
                    await batch.commit();
                    isDataSeeding = false;
                    showToast("Database siap.");
                }
            } catch (e) {
                console.error("Firestore access error:", e);
                const loginBtn = document.getElementById('login-submit-btn');
                loginBtn.textContent = "Kesalahan Izin Akses";
                if (e.code === 'permission-denied') {
                    showToast("Error: Aturan keamanan Firestore tidak mengizinkan akses.", true);
                }
                return;
            }

            // Await for collections to be ready before enabling login
            const collectionsToPreload = ['users', 'settings', 'teachers'];
            for(const colName of collectionsToPreload) {
                await new Promise(resolve => {
                    const q = query(collection(db, dataRootPath, colName));
                    const unsubscribe = onSnapshot(q, (querySnapshot) => {
                        appData[colName] = querySnapshot.docs.map(d => ({...d.data(), id: d.id}));
                        if (!querySnapshot.empty || isDataSeeding === false) {
                            resolve();
                            unsubscribe();
                        }
                    });
                });
            }
            
            const loginBtn = document.getElementById('login-submit-btn');
            loginBtn.disabled = false;
            loginBtn.textContent = "Login";

            const collectionsToSync = Object.keys(getDefaultData()).filter(c => !collectionsToPreload.includes(c));
            collectionsToSync.forEach(colName => {
                const q = query(collection(db, dataRootPath, colName));
                collectionListeners[colName] = onSnapshot(q, (querySnapshot) => {
                    appData[colName] = querySnapshot.docs.map(d => ({...d.data(), id: d.id}));
                    if(currentUser){
                        if(colName === 'academicYears' && currentUser.role === 'Admin') updateHeaderAcademicYearSelector();
                        renderCurrentView();
                    }
                });
            });
            
            loadFaviconFromStorage();

            const userJson = localStorage.getItem(LOGGED_IN_USER_KEY);
            if (userJson) {
                currentUser = JSON.parse(userJson);
                setupUiForUser();
            } else {
                updateHeaderAndLogo(); // Update logo on login page
                document.getElementById('login-container-wrapper').classList.remove('hidden');
            }
            setupEventListeners();
        }

        function setupUiForUser() {
            document.getElementById('login-container-wrapper').classList.add('hidden');
            document.getElementById('attendance-container-wrapper').classList.add('hidden');
            document.getElementById('app-container').classList.remove('hidden');
            
            const userRole = currentUser.role;
            document.getElementById('user-name').textContent = currentUser.name;
            document.getElementById('user-role').textContent = userRole;
            document.getElementById('user-avatar').src = `https://placehold.co/40x40/7e22ce/ffffff?text=${currentUser.name.charAt(0).toUpperCase()}`;
            document.querySelectorAll('#sidebar-nav [data-roles]').forEach(el => { el.style.display = el.dataset.roles.includes(userRole) ? '' : 'none'; });
            
            updateHeaderAndLogo();
            if(userRole === 'Admin') updateHeaderAcademicYearSelector();

            if (window.innerWidth >= 768) {
                const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                document.getElementById('app-body').classList.toggle('sidebar-collapsed', isCollapsed);
            }

            // Set Alpine.js open state based on screen size
            if (window.innerWidth < 768) {
                document.querySelectorAll('#sidebar-nav [x-data]').forEach(el => {
                   // This is a bit of a hack since we're mixing vanilla JS and Alpine
                   // A better approach would be a global Alpine store if the app grew
                   const button = el.querySelector('button');
                   if (button) {
                       const alpineInstance = button.__x;
                       if (alpineInstance) {
                           // This is not standard Alpine API, might break.
                           // A more robust way is to set x-data="{ open: window.innerWidth >= 768 }"
                           // But for this case, let's just make them all open on mobile load.
                           // The x-data="{ open: true }" already handles this.
                       }
                   }
                });
            }


            navigateTo('dashboard');
        }

        function updateHeaderAndLogo(){
            const settings = API.getById('settings', 'main');
            const localLogo = localStorage.getItem(LOCAL_LOGO_KEY);
            const logoToUse = localLogo || (settings ? settings.logo : 'https://placehold.co/80x80/3b82f6/ffffff?text=MAS');
            
            document.querySelectorAll('#sidebar-logo, #login-logo').forEach(el => el.src = logoToUse);
            
            if(!settings) return;
            const activeYearDoc = API.getById('academicYears', settings.activeAcademicYear);
            const activeYearName = activeYearDoc ? activeYearDoc.name : (settings.activeAcademicYear || '').replace(/-/g, '/');
            const displaySpan = document.getElementById('header-academic-year-display').querySelector('span');
            if(displaySpan) displaySpan.textContent = activeYearName;
        }

        function updateHeaderAcademicYearSelector() {
            const selector = document.getElementById('header-academic-year-select');
            const settings = API.getById('settings', 'main');
            if(selector && settings) {
                selector.innerHTML = (appData.academicYears || []).map(y => `<option value="${y.id}" ${settings.activeAcademicYear === y.id ? 'selected' : ''}>${y.name}</option>`).join('');
                document.getElementById('header-academic-year-selector-container').style.display = 'flex';
                document.getElementById('header-academic-year-display').style.display = 'none';
            }
        }

        main();
    </script>
</body>
</html>

