<?php
session_start();

// --- 1. KONEKSI DATABASE ---
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'fitness_app';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi Gagal: " . $conn->connect_error);
}

// --- 2. LOGIKA BACKEND ---
$message = '';
$msg_type = '';

// Handle Register
if (isset($_POST['register'])) {
    $username = $conn->real_escape_string($_POST['reg_username']);
    $password = $_POST['reg_password'];
    
    $check = $conn->query("SELECT id FROM users WHERE username = '$username'");
    if ($check->num_rows > 0) {
        $message = "Username sudah terpakai!";
        $msg_type = "error";
    } else {
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password, progress_data, history_data) VALUES ('$username', '$hashed_pass', '{}', '[]')";
        if ($conn->query($sql)) {
            $message = "Registrasi sukses! Silakan login.";
            $msg_type = "success";
        } else {
            $message = "Error: " . $conn->error;
            $msg_type = "error";
        }
    }
}

// Handle Login
if (isset($_POST['login'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    
    $result = $conn->query("SELECT * FROM users WHERE username = '$username'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['progress_data'] = $row['progress_data'];
            $_SESSION['history_data'] = $row['history_data']; 
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $message = "Password salah!";
            $msg_type = "error";
        }
    } else {
        $message = "Username tidak ditemukan.";
        $msg_type = "error";
    }
}

// Handle AJAX Save Progress
if (isset($_POST['action']) && $_POST['action'] == 'save_progress' && isset($_SESSION['user_id'])) {
    $progress_json = $conn->real_escape_string($_POST['data']);
    $uid = $_SESSION['user_id'];
    $conn->query("UPDATE users SET progress_data = '$progress_json' WHERE id = $uid");
    echo "Saved";
    exit;
}

// Handle AJAX Archive Week
if (isset($_POST['action']) && $_POST['action'] == 'archive_week' && isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $completedCount = isset($_POST['completed_count']) ? (int)$_POST['completed_count'] : 0;
    
    $res = $conn->query("SELECT history_data FROM users WHERE id = $uid");
    $row = $res->fetch_assoc();
    $historyArr = json_decode($row['history_data'] ?: '[]', true);
    
    $weekNum = count($historyArr) + 1;
    $date = date("d M Y");
    
    $newLog = [
        "title" => "Minggu ke-$weekNum",
        "desc" => "$completedCount Selesai dari 7 Latihan",
        "count" => $completedCount, 
        "date" => $date
    ];
    $historyArr[] = $newLog;
    
    $newHistoryJson = $conn->real_escape_string(json_encode($historyArr));
    
    $conn->query("UPDATE users SET history_data = '$newHistoryJson', progress_data = '{}' WHERE id = $uid");
    $_SESSION['history_data'] = json_encode($historyArr);
    
    echo json_encode($historyArr); 
    exit;
}

// Handle AJAX Reset History
if (isset($_POST['action']) && $_POST['action'] == 'reset_history' && isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $conn->query("UPDATE users SET history_data = '[]' WHERE id = $uid");
    $_SESSION['history_data'] = '[]';
    echo "Cleared";
    exit;
}

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$is_logged_in = isset($_SESSION['user_id']);
$current_user = $is_logged_in ? $_SESSION['username'] : '';
$db_progress = $is_logged_in ? ($_SESSION['progress_data'] ?: '{}') : '{}';
$db_history = $is_logged_in ? ($_SESSION['history_data'] ?: '[]') : '[]';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GenZehat - Ultimate Fitness</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* --- CSS UTAMA --- */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        :root { --primary: #4a6fa5; --secondary: #166088; --accent: #20c997; --warning: #ffc107; --danger: #dc3545; --light: #f8f9fa; --dark: #343a40; --gray: #6c757d; --light-gray: #e9ecef; }
        body { background-color: #f5f7fa; color: var(--dark); line-height: 1.6; padding-bottom: 80px; }
        .container { width: 100%; max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        
        /* AUTH & HEADER */
        .auth-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: #f5f7fa; z-index: 2000; display: flex; align-items: center; justify-content: center; }
        .auth-box { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid var(--light-gray); border-radius: 5px; }
        .btn-auth { width: 100%; padding: 12px; background: var(--primary); color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; }
        .toggle-link { text-align: center; margin-top: 15px; color: var(--primary); cursor: pointer; text-decoration: underline; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 5px; text-align: center; }
        .alert-error { background: #f8d7da; color: #721c24; }
        .alert-success { background: #d4edda; color: #155724; }

        header { background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 100; }
        .header-container { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; }
        .logo { display: flex; align-items: center; gap: 10px; color: var(--primary); font-size: 1.5rem; font-weight: bold; }
        .user-info { display: flex; align-items: center; gap: 15px; }
        .settings-btn { background: none; border: none; font-size: 1.5rem; color: var(--dark); cursor: pointer; transition: 0.3s; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .settings-btn:hover { background: var(--light-gray); color: var(--primary); transform: rotate(90deg); }

        /* LAYOUT & SIDEBAR */
        .main-content { display: flex; gap: 30px; margin-top: 30px; }
        .sidebar { flex: 0 0 250px; background: white; border-radius: 10px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); height: fit-content; }
        .jadwal-area { flex: 1; }
        .card { background: white; border-radius: 10px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-bottom: 30px; }
        
        .level-info-box { background: rgba(74, 111, 165, 0.1); padding: 15px; border-radius: 8px; border-left: 4px solid var(--primary); margin-bottom: 20px; }
        #levelDetails { white-space: normal; word-wrap: break-word; line-height: 1.6; color: var(--dark); }
        .btn-activity { display: block; width: 100%; padding: 12px; margin-top: 20px; background: white; border: 2px solid var(--primary); color: var(--primary); border-radius: 8px; font-weight: bold; cursor: pointer; transition: 0.3s; text-align: center; }
        .btn-activity:hover { background: var(--primary); color: white; }

        /* PROGRESS BARS (NEW) */
        .bar-container { display: flex; height: 150px; align-items: flex-end; gap: 20px; padding-top: 20px; border-bottom: 2px solid #eee; margin-top: 20px; }
        .bar-wrapper { flex: 1; height: 100%; display: flex; flex-direction: column; justify-content: flex-end; background: rgba(0,0,0,0.02); border-radius: 5px 5px 0 0; position: relative; }
        .visual-bar { width: 100%; height: 0%; transition: height 0.8s cubic-bezier(0.4, 0, 0.2, 1); border-radius: 5px 5px 0 0; }
        .visual-bar.green { background: var(--accent); }
        .visual-bar.red { background: var(--danger); }
        .bar-label { text-align: center; margin-top: 10px; font-weight: bold; font-size: 0.9rem; color: var(--gray); }

        /* LOG COLORS */
        .log-item { padding: 15px; border-radius: 8px; margin-bottom: 10px; border-left: 5px solid #ccc; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .log-item.red { background: #f8d7da; border-left-color: #dc3545; color: #842029; }
        .log-item.yellow { background: #fff3cd; border-left-color: #ffc107; color: #664d03; }
        .log-item.green { background: #d1e7dd; border-left-color: #198754; color: #0f5132; }
        .log-title { font-weight: bold; font-size: 1rem; }
        .log-desc { font-size: 0.9rem; margin-top: 2px; }
        .log-date { font-size: 0.75rem; opacity: 0.8; font-weight: 600;}
        .empty-log { text-align: center; color: var(--gray); padding: 20px; font-style: italic; }
        .btn-clear-log { margin-top: 20px; background: none; border: none; color: var(--danger); font-size: 0.85rem; cursor: pointer; text-decoration: underline; display: flex; align-items: center; gap: 5px; }

        /* GRID & BUTTONS */
        .days-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 15px; }
        .day-box { background: var(--light); border-radius: 8px; padding: 15px; text-align: center; transition: 0.3s; cursor: pointer; border: 2px solid transparent; }
        .day-box:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .day-box.today { border-color: var(--accent); }
        .day-status { font-size: 2rem; margin: 10px 0; min-height: 40px; }
        .day-status.completed { color: var(--accent); }
        .day-status.missed { color: var(--danger); }
        .day-status.pending { color: var(--warning); }
        
        .action-buttons { display: flex; gap: 5px; justify-content: center; min-height: 32px; }
        .action-btn { padding: 6px 10px; border: none; border-radius: 5px; cursor: pointer; color: white; font-size: 0.9rem; transition: 0.2s; }
        .complete-btn { background: var(--accent); }
        .miss-btn { background: var(--danger); }
        .reset-day-btn { background: var(--warning); color: var(--dark); display: none; }

        /* OTHER STYLES */
        .progress-container { display: flex; gap: 20px; margin-bottom: 20px; }
        .progress-box { flex: 1; padding: 20px; border-radius: 10px; text-align: center; color: white; position: relative; overflow: hidden; }
        .progress-box.completed { background: linear-gradient(to bottom right, var(--accent), #17a589); }
        .progress-box.missed { background: linear-gradient(to bottom right, var(--danger), #c0392b); }
        .progress-box h3 { font-size: 2.5rem; margin: 0; position: relative; z-index: 2; }
        
        .floating-controls { position: fixed; bottom: 20px; right: 20px; display: flex; flex-direction: column; gap: 10px; z-index: 1000; align-items: flex-end; }
        .reset-save-btn { background: linear-gradient(to right, #11998e, #38ef7d); color: white; padding: 12px 25px; border-radius: 30px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.2); cursor: pointer; font-weight: bold; display: none; align-items: center; gap: 10px; transition: 0.3s; animation: pulse 2s infinite; }
        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.05); } 100% { transform: scale(1); } }
        .db-status { background: white; padding: 8px 15px; border-radius: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 8px; font-size: 0.8rem; font-weight: 600; }
        .status-dot { width: 8px; height: 8px; border-radius: 50%; }
        .status-dot.saving { background: orange; }
        .status-dot.saved { background: var(--accent); }

        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center; backdrop-filter: blur(3px); }
        .modal-card { background: white; width: 90%; max-width: 450px; padding: 30px; border-radius: 15px; position: relative; box-shadow: 0 20px 50px rgba(0,0,0,0.2); animation: slideUp 0.3s ease; max-height: 90vh; overflow-y: auto;}
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .modal-close { position: absolute; top: 20px; right: 20px; font-size: 1.5rem; cursor: pointer; background: none; border: none; color: var(--gray); }
        .level-radio-group { display: flex; flex-direction: column; gap: 10px; }
        .level-radio { display: flex; align-items: center; padding: 12px; border: 1px solid var(--light-gray); border-radius: 8px; cursor: pointer; transition: 0.2s; }
        .level-radio.selected { border-color: var(--primary); background: rgba(74, 111, 165, 0.1); }
        .btn-logout-full { display: block; width: 100%; padding: 15px; background: #fee2e2; color: var(--danger); text-align: center; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 30px; transition: 0.2s; border: 1px solid #fecaca; }

        /* LIST LATIHAN DI MODAL */
        .exercise-list li { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
        .exercise-list li:last-child { border-bottom: none; }
        .ex-name { font-weight: 600; color: var(--dark); }
        .ex-sets { color: var(--gray); font-size: 0.9rem; background: #f0f0f0; padding: 2px 8px; border-radius: 4px;}

        @media (max-width: 768px) { .main-content { flex-direction: column; } }
    </style>
</head>
<body>

<?php if (!$is_logged_in): ?>
    <div class="auth-overlay">
        <div class="auth-box">
            <h2 style="text-align:center; color:var(--primary); margin-bottom:20px;"><i class="fas fa-heartbeat"></i> GenZehat</h2>
            <?php if ($message): ?> <div class="alert alert-<?php echo $msg_type; ?>"><?php echo $message; ?></div> <?php endif; ?>
            <form id="formLogin" method="POST">
                <div class="form-group"><label>Username</label><input type="text" name="username" required></div>
                <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
                <button type="submit" name="login" class="btn-auth">Masuk</button>
                <div class="toggle-link" onclick="toggleForm('register')">Belum punya akun? Daftar</div>
            </form>
            <form id="formRegister" method="POST" style="display:none;">
                <div class="form-group"><label>Buat Username</label><input type="text" name="reg_username" required></div>
                <div class="form-group"><label>Buat Password</label><input type="password" name="reg_password" required></div>
                <button type="submit" name="register" class="btn-auth" style="background:var(--secondary)">Daftar</button>
                <div class="toggle-link" onclick="toggleForm('login')">Sudah punya akun? Login</div>
            </form>
        </div>
    </div>
    <script>function toggleForm(show){document.getElementById('formLogin').style.display=show==='login'?'block':'none';document.getElementById('formRegister').style.display=show==='register'?'block':'none';}</script>

<?php else: ?>
    <header>
        <div class="container header-container">
            <div class="logo"><i class="fas fa-heartbeat"></i> GenZehat</div>
            <div class="user-info">
                <span>Halo, <b><?php echo htmlspecialchars($current_user); ?></b></span>
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
            <div style="font-size:0.9rem; color:var(--gray); margin-bottom:10px;">Jadwal Rutin:</div>
            <div id="levelDetails"></div>
            <button class="btn-activity" onclick="openHistory()"><i class="fas fa-history"></i> Pelacakan Aktivitas</button>
            <div style="margin-top: 20px; font-size: 0.8rem; color: #999; text-align: center;">Ganti level di menu <i class="fas fa-cog"></i> Pengaturan</div>
        </div>

        <div class="jadwal-area">
            <div class="card">
                <h2 style="color:var(--primary); margin-bottom:20px;"><i class="fas fa-chart-bar"></i> Statistik</h2>
                <div class="progress-container">
                    <div class="progress-box completed"><h3 id="completedCount">0</h3><p>Selesai</p></div>
                    <div class="progress-box missed"><h3 id="missedCount">0</h3><p>Terlewat</p></div>
                </div>
                
                <h4 style="margin-bottom: 10px; color: var(--gray);">Progress Grafik</h4>
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
            <a href="?logout=true" class="btn-logout-full"><i class="fas fa-sign-out-alt"></i> Keluar Akun</a>
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
        const currentUser = "<?php echo $current_user; ?>";
        let userProgress = <?php echo $db_progress; ?>; 
        let userHistory = <?php echo $db_history; ?>; 
        let currentLevel = localStorage.getItem('user_level_pref') || 'pemula';

        // DATA LATIHAN DENGAN VARIASI LEBIH BANYAK (6-8 Gerakan)
        const appData = {
            schedule: {
                pemula: { name: 'Pemula', desc: 'Basic Foundation', details: 'Senin: Full Body A. Selasa: Cardio. Rabu: Istirahat. Kamis: Full Body B. Jumat: Cardio. Sabtu: Latihan Ringan. Minggu: Istirahat.', exercises: ['Full Body A', 'Cardio', 'Istirahat', 'Full Body B', 'Cardio', 'Latihan Ringan', 'Istirahat'] },
                menengah: { name: 'Menengah', desc: 'Strength & Hypertrophy', details: 'Senin: Upper Power. Selasa: Lower Power. Rabu: Istirahat. Kamis: Upper Hypertrophy. Jumat: Lower Hypertrophy. Sabtu: Cardio & Core. Minggu: Recovery.', exercises: ['Upper Power', 'Lower Power', 'Istirahat', 'Upper Hypertrophy', 'Lower Hypertrophy', 'Active Recovery', 'Istirahat'] },
                profesional: { name: 'Profesional', desc: 'High Volume PPL', details: 'Senin: Push Heavy. Selasa: Pull Heavy. Rabu: Legs Heavy. Kamis: Push Volume. Jumat: Pull Volume. Sabtu: Legs Volume. Minggu: Rest.', exercises: ['Push Day (Heavy)', 'Pull Day (Heavy)', 'Legs Day (Heavy)', 'Push Day (Volume)', 'Pull Day (Volume)', 'Legs Day (Volume)', 'Rest Day'] }
            },
            exerciseDetails: {
                // Pemula Variatif
                'Full Body A': { icon: 'fa-dumbbell', list: [
                    {name:'Bodyweight Squat', sets:'3x12'}, {name:'Push-up', sets:'3x10'}, {name:'Dumbbell Row', sets:'3x12'}, 
                    {name:'Lunges', sets:'3x10/kaki'}, {name:'Plank', sets:'3x30s'}, {name:'Jumping Jacks', sets:'3x45s'}
                ]},
                'Full Body B': { icon: 'fa-dumbbell', list: [
                    {name:'Goblet Squat', sets:'3x12'}, {name:'Overhead Press', sets:'3x10'}, {name:'Lat Pulldown', sets:'3x12'}, 
                    {name:'Glute Bridge', sets:'3x15'}, {name:'Leg Raise', sets:'3x15'}, {name:'Mountain Climbers', sets:'3x30s'}
                ]},
                'Cardio': { icon: 'fa-running', list: [
                    {name:'Jogging', sets:'30 min'}, {name:'High Knees', sets:'3x45s'}, {name:'Burpees', sets:'3x10'}
                ]},
                
                // Menengah Variatif
                'Upper Power': { icon: 'fa-fist-raised', list: [
                    {name:'Bench Press', sets:'4x5'}, {name:'Bent Over Row', sets:'4x6'}, {name:'Overhead Press', sets:'3x8'},
                    {name:'Weighted Dips', sets:'3x8'}, {name:'Barbell Curl', sets:'3x10'}, {name:'Skullcrushers', sets:'3x10'}
                ]},
                'Lower Power': { icon: 'fa-running', list: [
                    {name:'Barbell Squat', sets:'4x5'}, {name:'Deadlift', sets:'3x5'}, {name:'Leg Press', sets:'3x8'},
                    {name:'Standing Calf Raise', sets:'4x10'}, {name:'Hanging Leg Raise', sets:'3x10'}
                ]},
                'Upper Hypertrophy': { icon: 'fa-child', list: [
                    {name:'Incline DB Press', sets:'3x12'}, {name:'Pull Ups', sets:'3xMax'}, {name:'Lateral Raise', sets:'3x15'},
                    {name:'Cable Fly', sets:'3x15'}, {name:'Preacher Curl', sets:'3x12'}, {name:'Tricep Pushdown', sets:'3x15'}
                ]},
                'Lower Hypertrophy': { icon: 'fa-walking', list: [
                    {name:'Hack Squat', sets:'3x12'}, {name:'Romanian Deadlift', sets:'3x10'}, {name:'Leg Extension', sets:'3x15'},
                    {name:'Hamstring Curl', sets:'3x15'}, {name:'Seated Calf Raise', sets:'3x20'}, {name:'Walking Lunges', sets:'3x20'}
                ]},

                // Profesional PPL Variatif
                'Push Day (Heavy)': { icon: 'fa-dumbbell', list: [
                    {name:'Flat Bench Press', sets:'5x5'}, {name:'Overhead Press', sets:'4x6'}, {name:'Weighted Dips', sets:'3x8'},
                    {name:'Close Grip Bench', sets:'3x8'}, {name:'Lateral Raise', sets:'3x12'}, {name:'Face Pulls', sets:'3x15'}
                ]},
                'Pull Day (Heavy)': { icon: 'fa-anchor', list: [
                    {name:'Deadlift', sets:'1x5'}, {name:'Weighted Pull Up', sets:'4x6'}, {name:'Barbell Row', sets:'4x8'},
                    {name:'T-Bar Row', sets:'3x10'}, {name:'Hammer Curl', sets:'3x10'}, {name:'Shrugs', sets:'3x15'}
                ]},
                'Legs Day (Heavy)': { icon: 'fa-running', list: [
                    {name:'Squat', sets:'5x5'}, {name:'Romanian Deadlift', sets:'4x8'}, {name:'Leg Press', sets:'3x10'},
                    {name:'Hip Thrust', sets:'3x10'}, {name:'Standing Calf Raise', sets:'4x12'}
                ]},
                'Push Day (Volume)': { icon: 'fa-hand-rock', list: [
                    {name:'Incline DB Press', sets:'3x12'}, {name:'Cable Fly', sets:'3x15'}, {name:'Lateral Raise', sets:'4x15 (Dropset)'},
                    {name:'Tricep Pushdown', sets:'3x15'}, {name:'Overhead Ext', sets:'3x15'}, {name:'Dips', sets:'AMRAP'}
                ]},
                'Pull Day (Volume)': { icon: 'fa-hand-paper', list: [
                    {name:'Lat Pulldown', sets:'3x12'}, {name:'Cable Row', sets:'3x12'}, {name:'Straight Arm Pulldown', sets:'3x15'},
                    {name:'Face Pull', sets:'3x15'}, {name:'Concentration Curl', sets:'3x12'}, {name:'Preacher Curl', sets:'3x12'}
                ]},
                'Legs Day (Volume)': { icon: 'fa-walking', list: [
                    {name:'Hack Squat', sets:'3x12'}, {name:'Bulgarian Split Squat', sets:'3x12'}, {name:'Leg Extension', sets:'3x15 (Superset)'},
                    {name:'Hamstring Curl', sets:'3x15 (Superset)'}, {name:'Calf Raise', sets:'4x20'}, {name:'Plank', sets:'3x1min'}
                ]},
                
                'Istirahat': { icon: 'fa-bed', list: [{name:'Tidur Cukup', sets:'8 Jam'}, {name:'Nutrisi', sets:'High Protein'}] },
                'Latihan Ringan': { icon: 'fa-leaf', list: [{name:'Jalan Kaki', sets:'45 min'}, {name:'Stretching', sets:'15 min'}] },
                'Active Recovery': { icon: 'fa-swimmer', list: [{name:'Renang', sets:'30 min'}, {name:'Foam Rolling', sets:'15 min'}] },
                'Rest Day': { icon: 'fa-coffee', list: [{name:'Relax', sets:'Full'}, {name:'Meal Prep', sets:'Healthy'}] }
            }
        };

        const days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        document.addEventListener('DOMContentLoaded', () => {
            renderAll();
            updateDBStatus('saved');
            updateSettingsUI();
        });

        function renderAll() {
            const info = appData.schedule[currentLevel];
            document.getElementById('displayLevelName').innerText = info.name;
            document.getElementById('displayLevelDesc').innerText = info.desc;
            document.getElementById('levelDetails').innerText = info.details; 
            renderSchedule();
            renderStats();
        }

        function renderSchedule() {
            const container = document.getElementById('daysContainer');
            container.innerHTML = '';
            const exercises = appData.schedule[currentLevel].exercises;

            exercises.forEach((exName, idx) => {
                const key = `${currentLevel}_${idx}`;
                const status = (userProgress[key] && userProgress[key] !== 'pending') ? userProgress[key] : 'pending';
                let iconClass = 'fa-question';
                if(status === 'completed') iconClass = 'fa-check-circle';
                if(status === 'missed') iconClass = 'fa-times-circle';
                const undoStyle = (status === 'pending') ? 'display:none' : 'display:inline-block';

                container.innerHTML += `
                    <div class="day-box ${status}" onclick="showDetail('${exName}')">
                        <div style="font-weight:bold; color:var(--primary); margin-bottom:5px;">${days[idx]}</div>
                        <div style="font-size:0.9rem; margin-bottom:10px;">${exName}</div>
                        <div class="day-status ${status}"><i class="fas ${iconClass}"></i></div>
                        <div class="action-buttons">
                            <button class="action-btn complete-btn" title="Selesai" onclick="updateStatus(event, '${key}', 'completed')"><i class="fas fa-check"></i></button>
                            <button class="action-btn miss-btn" title="Terlewat" onclick="updateStatus(event, '${key}', 'missed')"><i class="fas fa-times"></i></button>
                            <button class="action-btn reset-day-btn" style="${undoStyle}" title="Reset Hari Ini" onclick="updateStatus(event, '${key}', 'pending')"><i class="fas fa-undo"></i></button>
                        </div>
                    </div>
                `;
            });
        }

        function renderStats() {
            let completed = 0, missed = 0;
            for(let i=0; i<7; i++) {
                const key = `${currentLevel}_${i}`;
                if(userProgress[key] === 'completed') completed++;
                if(userProgress[key] === 'missed') missed++;
            }
            document.getElementById('completedCount').innerText = completed;
            document.getElementById('missedCount').innerText = missed;
            
            // UPDATE BAR CHART
            let greenHeight = (completed / 7) * 100;
            let redHeight = (missed / 7) * 100;
            document.getElementById('barGreen').style.height = `${greenHeight}%`;
            document.getElementById('barRed').style.height = `${redHeight}%`;

            updateWeeklyFeedback(completed);
            
            const totalAction = completed + missed;
            const resetBtn = document.getElementById('resetSaveBtn');
            resetBtn.style.display = (totalAction === 7) ? 'flex' : 'none';
        }

        function updateWeeklyFeedback(count) {
            const container = document.getElementById('feedbackContainer');
            const icon = document.getElementById('feedbackIcon');
            const title = document.getElementById('feedbackTitle');
            const text = document.getElementById('feedbackText');
            container.className = 'feedback-card';
            container.style.display = 'block';

            if (count === 7) {
                container.classList.add('feedback-perfect');
                icon.className = 'feedback-icon fas fa-medal';
                title.innerText = "Luar Biasa! Minggu Sempurna!";
                text.innerText = "Selamat! Anda telah menyelesaikan semua latihan minggu ini. Pertahankan disiplin ini!";
            } else if (count >= 4) {
                container.classList.add('feedback-good');
                icon.className = 'feedback-icon fas fa-star-half-alt';
                title.innerText = "Kerja Bagus! Sedikit Lagi!";
                text.innerText = `Anda sudah menyelesaikan ${count} hari. Ayo perbaiki di minggu selanjutnya agar bisa sempurna!`;
            } else {
                container.classList.add('feedback-bad');
                icon.className = 'feedback-icon fas fa-battery-quarter';
                title.innerText = "Jangan Menyerah!";
                text.innerText = "Awal yang berat memang wajar. Ayo bangkit lagi dan mulai cicil latihanmu besok!";
            }
        }

        // --- SISTEM PELACAKAN AKTIVITAS ---
        function openHistory() { renderHistoryList(); document.getElementById('historyModal').style.display = 'flex'; }
        function closeHistory() { document.getElementById('historyModal').style.display = 'none'; }

        function renderHistoryList() {
            const listContainer = document.getElementById('historyListContainer');
            listContainer.innerHTML = '';
            if (userHistory.length === 0) {
                listContainer.innerHTML = '<div class="empty-log">Belum ada riwayat aktivitas.<br>Selesaikan 1 minggu latihan untuk mulai merekam.</div>';
                return;
            }
            userHistory.forEach(log => {
                let colorClass = 'red';
                if (log.count >= 6) colorClass = 'green';
                else if (log.count >= 3) colorClass = 'yellow';
                listContainer.innerHTML += `
                    <div class="log-item ${colorClass}">
                        <div>
                            <div class="log-title">${log.title}</div>
                            <div class="log-desc">${log.desc}</div>
                        </div>
                        <div class="log-date">${log.date}</div>
                    </div>
                `;
            });
        }

        function archiveWeek() {
            if(!confirm("Simpan progress minggu ini ke riwayat dan mulai minggu baru?")) return;
            let completedCount = 0;
            for(let i=0; i<7; i++) {
                const key = `${currentLevel}_${i}`;
                if(userProgress[key] === 'completed') completedCount++;
            }
            updateDBStatus('saving');
            const formData = new FormData();
            formData.append('action', 'archive_week');
            formData.append('completed_count', completedCount);
            fetch(window.location.href, { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                userHistory = data; userProgress = {}; updateDBStatus('saved'); renderAll(); alert("Minggu berhasil disimpan!"); openHistory();
            });
        }

        function clearHistory() {
            if(!confirm("PERINGATAN: Hapus SEMUA riwayat aktivitas?\n\nData tidak dapat dikembalikan!")) return;
            const formData = new FormData();
            formData.append('action', 'reset_history');
            fetch(window.location.href, { method: 'POST', body: formData })
            .then(res => res.text())
            .then(data => { if(data.trim() === 'Cleared') { userHistory = []; renderHistoryList(); alert("Semua riwayat telah dihapus."); }});
        }

        // --- CORE & MODALS ---
        function updateStatus(e, key, status) { e.stopPropagation(); userProgress[key] = status; renderAll(); saveToDatabase(); }
        function saveToDatabase() {
            updateDBStatus('saving');
            const formData = new FormData();
            formData.append('action', 'save_progress');
            formData.append('data', JSON.stringify(userProgress));
            fetch(window.location.href, { method: 'POST', body: formData })
            .then(res => res.text())
            .then(data => { if(data.trim() === 'Saved') setTimeout(() => updateDBStatus('saved'), 500); });
        }
        function updateDBStatus(state) {
            const dot = document.getElementById('dbDot');
            const text = document.getElementById('dbText');
            if(state === 'saving') { dot.className = 'status-dot saving'; text.innerText = 'Menyimpan...'; }
            else { dot.className = 'status-dot saved'; text.innerText = 'Tersinkronisasi'; }
        }
        function openSettings() { document.getElementById('settingsModal').style.display = 'flex'; updateSettingsUI(); }
        function closeSettings() { document.getElementById('settingsModal').style.display = 'none'; }
        function showDetail(exName) {
            const detail = appData.exerciseDetails[exName] || { icon: 'fa-dumbbell', list: [{name:'Lihat instruksi', sets:'-'}] };
            document.getElementById('modalTitle').innerText = exName;
            document.getElementById('modalIcon').className = `fas ${detail.icon}`;
            const listEl = document.getElementById('modalList');
            listEl.innerHTML = '';
            detail.list.forEach(item => { listEl.innerHTML += `<li><span class="ex-name">${item.name}</span> <span class="ex-sets">${item.sets}</span></li>`; });
            document.getElementById('detailModal').style.display = 'flex';
        }
        function closeModal() { document.getElementById('detailModal').style.display = 'none'; }
        function changeLevel(newLevel) { currentLevel = newLevel; localStorage.setItem('user_level_pref', newLevel); renderAll(); updateSettingsUI(); }
        function updateSettingsUI() { document.querySelectorAll('.level-radio').forEach(el => el.classList.remove('selected')); document.getElementById('opt-' + currentLevel).classList.add('selected'); }
        window.onclick = function(e) { 
            if(e.target == document.getElementById('detailModal')) closeModal();
            if(e.target == document.getElementById('settingsModal')) closeSettings();
            if(e.target == document.getElementById('historyModal')) closeHistory();
        }
    </script>
<?php endif; ?>
</body>
</html>