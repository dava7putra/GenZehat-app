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
    <link rel="stylesheet" href="style.css">
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
        // Variabel PHP yang dikirim ke Javascript
        const currentUser = "<?php echo $current_user; ?>";
        let userProgress = <?php echo $db_progress; ?>; 
        let userHistory = <?php echo $db_history; ?>; 
        let currentLevel = localStorage.getItem('user_level_pref') || 'pemula';
    </script>
    
    <script src="script.js"></script>

<?php endif; ?>
<?php if (!$is_logged_in): ?>
    <script src="script.js"></script>
<?php endif; ?>
</body>
</html>