<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GenZehat - Ultimate Fitness</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* EFEK UNTUK HARI INI */
        .today-card {
        border: 2px solid #2ecc71 !important; /* Garis Hijau */
        background-color: #f0fff4 !important; /* Background agak hijau muda */
        transform: scale(1.03); /* Sedikit lebih besar */
        box-shadow: 0 0 15px rgba(46, 204, 113, 0.5) !important; /* Efek Glowing */
        position: relative;
        z-index: 10;
        }

     .today-badge {
        background: #2ecc71;
        color: white;
        font-size: 0.7rem;
        font-weight: bold;
        padding: 3px 8px;
        border-radius: 20px;
        margin-left: 8px;
        text-transform: uppercase;
        animation: pulse 2s infinite;
      }

        /* Animasi denyut halus */
         @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
        }
    </style>
</head>
<body>

{{-- LOGIKA TAMPILAN: Jika Belum Login (Guest) vs Sudah Login (Auth) --}}

@guest
    <div class="auth-overlay">
        <div class="auth-box">
            <h2 style="text-align:center; color:var(--primary); margin-bottom:20px;">
                <i class="fas fa-heartbeat"></i> GenZehat
            </h2>
            
            {{-- Pesan Error/Sukses --}}
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Form Login --}}
            <form id="formLogin" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group"><label>Username</label><input type="text" name="username" required></div>
                <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
                <button type="submit" class="btn-auth">Masuk</button>
                <div class="toggle-link" onclick="toggleForm('register')">Belum punya akun? Daftar</div>
            </form>

            {{-- Form Register --}}
            <form id="formRegister" action="{{ route('register') }}" method="POST" style="display:none;">
                @csrf
                <div class="form-group"><label>Buat Username</label><input type="text" name="reg_username" required></div>
                <div class="form-group"><label>Buat Password</label><input type="password" name="reg_password" required></div>
                <button type="submit" class="btn-auth" style="background:var(--secondary)">Daftar</button>
                <div class="toggle-link" onclick="toggleForm('login')">Sudah punya akun? Login</div>
            </form>
        </div>
    </div>
    
    <script>
        function toggleForm(show){
            document.getElementById('formLogin').style.display = show==='login'?'block':'none';
            document.getElementById('formRegister').style.display = show==='register'?'block':'none';
        }
    </script>

@else
    <header>
        <div class="container header-container">
            <div class="logo"><i class="fas fa-heartbeat"></i> GenZehat</div>
            <div class="user-info">
                <span>Halo, <b>{{ Auth::user()->username }}</b></span>
                <button class="settings-btn" onclick="openSettings()" title="Pengaturan">
                    <i class="fas fa-cog"></i>
                </button>
            </div>
        </div>
    </header>

    <div class="container main-content">
        <div class="sidebar">
            <h3 style="margin-bottom:15px; color:var(--primary)">Level Aktif</h3>
            <div class="level-info-box">
                <h4 id="displayLevelName">Memuat...</h4>
                <p id="displayLevelDesc">...</p>
            </div>
            <div style="margin-top: 20px; background: white; padding: 10px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                 <h4 style="font-size: 0.9rem; color: var(--primary); margin-bottom: 10px;">
                     <i class="fas fa-chart-line"></i> Grafik Progress
                 </h4>
                 <canvas id="historyChart" width="100%" height="80"></canvas>
    
                 <p id="noDataMsg" style="font-size: 0.8rem; color: #999; text-align: center; display: none; margin-top:10px;">
                      Selesaikan 1 minggu untuk melihat grafik.
               </p>
            </div>
            <div style="font-size:0.9rem; color:var(--gray); margin-bottom:10px;">Jadwal Rutin:</div>
            <div id="levelDetails"></div>
            <button class="btn-activity" onclick="openHistory()"><i class="fas fa-history"></i> Pelacakan Aktivitas</button>
            <div style="margin-top: 20px; font-size: 0.8rem; color: #999; text-align: center;">Ganti level di menu <i class="fas fa-cog"></i> Pengaturan</div>
        </div>

        <div class="jadwal-area">
            <div class="card">
                <h2 style="color:var(--primary); margin-bottom:20px;"><i class="fas fa-chart-bar"></i> Status</h2>
                <div class="progress-container">
                    <div class="progress-box completed"><h3 id="completedCount">0</h3><p>Selesai</p></div>
                    <div class="progress-box missed"><h3 id="missedCount">0</h3><p>Terlewat</p></div>
                </div>
                
                <h4 style="margin-bottom: 10px; color: var(--gray);">Progress Mingguan</h4>
                <div class="bar-container">
                    <div class="bar-wrapper"><div class="visual-bar green" id="barGreen"></div><div class="bar-label">Selesai</div></div>
                    <div class="bar-wrapper"><div class="visual-bar red" id="barRed"></div><div class="bar-label">Terlewat</div></div>
                </div>

                <div id="feedbackContainer" class="feedback-card" style="display:none;">
                    <i id="feedbackIcon" class="feedback-icon fas fa-trophy"></i>
                    <div id="feedbackTitle" class="feedback-title">Title</div>
                    <div id="feedbackText">Message</div>
                </div>
            </div>

            <div class="card">
                <h2 style="color:var(--primary); margin-bottom:20px;"><i class="fas fa-calendar-alt"></i> Jadwal Latihan</h2>
                <div class="days-container" id="daysContainer"></div>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="settingsModal">
        <div class="modal-card">
            <button class="modal-close" onclick="closeSettings()">&times;</button>
            <h2 style="margin-bottom:20px; color:var(--primary)"><i class="fas fa-cog"></i> Pengaturan</h2>
            <div class="setting-option">
                <span style="display:block;margin-bottom:10px;font-weight:bold">Ganti Level Latihan</span>
                <div class="level-radio-group">
                    <div class="level-radio" onclick="changeLevel('pemula')" id="opt-pemula"><i class="fas fa-seedling"></i><div><div style="font-weight:bold">Pemula</div><small>3x Seminggu</small></div></div>
                    <div class="level-radio" onclick="changeLevel('menengah')" id="opt-menengah"><i class="fas fa-running"></i><div><div style="font-weight:bold">Menengah</div><small>4x Seminggu</small></div></div>
                    <div class="level-radio" onclick="changeLevel('profesional')" id="opt-profesional"><i class="fas fa-dumbbell"></i><div><div style="font-weight:bold">Profesional</div><small>6x Seminggu</small></div></div>
                </div>
            </div>
            <a href="{{ route('logout') }}" class="btn-logout-full"><i class="fas fa-sign-out-alt"></i> Keluar Akun</a>
        </div>
    </div>

    <div class="modal-overlay" id="historyModal">
        <div class="modal-card">
            <button class="modal-close" onclick="closeHistory()">&times;</button>
            <h2 style="margin-bottom:20px; color:var(--primary)"><i class="fas fa-history"></i> Pelacakan Aktivitas</h2>
            <div id="historyListContainer" style="max-height: 400px; overflow-y: auto;"></div>
            <button class="btn-clear-log" onclick="clearHistory()"><i class="fas fa-trash"></i> Reset Log Aktivitas (Permanen)</button>
        </div>
    </div>

    <div class="modal-overlay" id="detailModal">
        <div class="modal-card">
            <button class="modal-close" onclick="closeModal()">&times;</button>
            <h2 id="modalTitle" style="color:var(--primary); margin-bottom:15px;">Detail</h2>
            <div style="background:#f8f9fa; padding:20px; text-align:center; border-radius:10px; margin-bottom:20px;"><i id="modalIcon" class="fas fa-running" style="font-size:3rem; color:var(--gray)"></i></div>
            <ul class="exercise-list" id="modalList" style="list-style:none; padding:0;"></ul>
        </div>
    </div>

    <div class="floating-controls">
        <div class="db-status"><div class="status-dot" id="dbDot"></div><span id="dbText">Tersinkronisasi</span></div>
        <button id="resetSaveBtn" class="reset-save-btn" onclick="archiveWeek()"><i class="fas fa-save"></i> Reset & Save</button>
    </div>

    <script>
        // Mengirim data dari Laravel (PHP) ke Javascript
        const currentUser = @json(Auth::user()->username);
        let userProgress = @json(Auth::user()->progress_data) || {};
        let userHistory = @json(Auth::user()->history_data ?? []);
        let currentLevel = localStorage.getItem('user_level_pref') || 'pemula';
        if (Array.isArray(userProgress) && userProgress.length === 0) {
            userProgress = {};
            }
    </script>
    
    <script src="{{ asset('js/script.js') }}"></script>

@endguest

</body>
</html>