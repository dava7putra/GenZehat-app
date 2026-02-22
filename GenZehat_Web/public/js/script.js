// =========================================
// 1. INISIALISASI VARIABEL GLOBAL
// =========================================
// Kita definisikan dulu sebagai object kosong agar tidak error
var userProgress = {}; 

// Level default
var currentLevel = localStorage.getItem('user_level_pref') || 'pemula';

// =========================================
// 2. DATA LATIHAN (DATABASE LOKAL)
// =========================================
const workoutData = {
    pemula: {
        name: "Pemula (Start)",
        desc: "Fokus pada penguasaan gerakan dasar dan postur yang benar.",
        schedule: [
            { day: "Senin", focus: "Full Body A", icon: "fas fa-child", exercises: [
                { name: "Knee Push Ups", reps: "3 set x 8-10 reps", desc: "Posisi push up dengan tumpuan lutut. Jaga punggung tetap lurus." },
                { name: "Bodyweight Squats", reps: "3 set x 10-12 reps", desc: "Berdiri tegak, turunkan pantat seperti mau duduk di kursi, lalu berdiri lagi." },
                { name: "Plank", reps: "3 set x 15-20 detik", desc: "Tahan posisi lurus dengan tumpuan siku dan jari kaki. Kencangkan perut." }
            ]},
            { day: "Selasa", focus: "Istirahat / Jalan", icon: "fas fa-bed", exercises: [
                { name: "Jalan Santai", reps: "30 Menit", desc: "Jalan kaki santai untuk melancarkan aliran darah." },
                { name: "Stretching", reps: "10 Menit", desc: "Peregangan ringan seluruh tubuh." }
            ]},
            { day: "Rabu", focus: "Lower Body", icon: "fas fa-walking", exercises: [
                { name: "Lunges", reps: "3 set x 8/kaki", desc: "Langkah lebar ke depan, tekuk lutut belakang mendekati lantai." },
                { name: "Glute Bridges", reps: "3 set x 12 reps", desc: "Tidur telentang, tekuk lutut, angkat pinggul ke atas sampai lurus." },
                { name: "Knee Tucks", reps: "3 set x 10 reps", desc: "Duduk di lantai, tangan di samping pinggul, tarik lutut ke arah dada." }
            ]},
            { day: "Kamis", focus: "Istirahat", icon: "fas fa-bed", exercises: [
                { name: "Istirahat Total", reps: "-", desc: "Biarkan otot memulihkan diri." }
            ]},
            { day: "Jumat", focus: "Upper Body", icon: "fas fa-hand-rock", exercises: [
                { name: "Wall Push Ups", reps: "3 set x 12-15 reps", desc: "Push up berdiri dengan tangan menempel di dinding." },
                { name: "Doorframe Rows", reps: "3 set x 10-12 reps", desc: "Pegang kusen pintu, condongkan badan ke belakang, tarik badan ke depan." },
                { name: "Shoulder Taps", reps: "3 set x 10 total", desc: "Posisi plank lurus, tepuk bahu kiri pakai tangan kanan bergantian." }
            ]},
            { day: "Sabtu", focus: "Cardio Ringan", icon: "fas fa-running", exercises: [
                { name: "Jumping Jacks", reps: "3 set x 30 detik", desc: "Lompat buka tutup kaki dan tangan." },
                { name: "High Knees", reps: "3 set x 20 detik", desc: "Lari di tempat dengan lutut diangkat tinggi." }
            ]},
            { day: "Minggu", focus: "Istirahat", icon: "fas fa-coffee", exercises: [
                 { name: "Istirahat Total", reps: "-", desc: "Persiapan untuk minggu depan." }
            ]}
        ]
    },
    menengah: {
        name: "Menengah",
        desc: "Meningkatkan intensitas dan variasi gerakan.",
        schedule: [
            { day: "Senin", focus: "Push (Dorong)", icon: "fas fa-fist-raised", exercises: [
                { name: "Standard Push Ups", reps: "4 set x 10-12 reps", desc: "Push up biasa kaki lurus. Dada hampir menyentuh lantai." },
                { name: "Pike Push Ups", reps: "3 set x 8 reps", desc: "Posisi V terbalik (pantat naik), tekuk siku kepala ke arah lantai (melatih bahu)." },
                { name: "Tricep Dips (Kursi)", reps: "3 set x 10 reps", desc: "Belakangi kursi, tumpu tangan di kursi, turunkan badan." }
            ]},
            { day: "Selasa", focus: "Legs (Kaki)", icon: "fas fa-running", exercises: [
                { name: "Jump Squats", reps: "4 set x 10 reps", desc: "Squat biasa, tapi saat naik lakukan lompatan eksplosif." },
                { name: "Reverse Lunges", reps: "3 set x 10/kaki", desc: "Langkah mundur ke belakang, tekuk lutut, kembali ke depan." },
                { name: "Calf Raises", reps: "4 set x 20 reps", desc: "Jinjit di tempat atau di tepi tangga." }
            ]},
            { day: "Rabu", focus: "Pull & Core", icon: "fas fa-user-ninja", exercises: [
                { name: "Superman Hold", reps: "4 set x 30 detik", desc: "Tidur tengkurap, angkat tangan dan kaki bersamaan seperti terbang." },
                { name: "Doorframe Rows", reps: "4 set x 15 reps", desc: "Tarik badan menggunakan kusen pintu (satu tangan atau dua tangan)." },
                { name: "Leg Raises", reps: "3 set x 12 reps", desc: "Tidur telentang, angkat kedua kaki lurus ke atas 90 derajat." }
            ]},
            { day: "Kamis", focus: "Cardio", icon: "fas fa-fire", exercises: [
                { name: "Burpees", reps: "3 set x 10 reps", desc: "Jongkok, lempar kaki ke belakang, tarik lagi, lompat." },
                { name: "Mountain Climbers", reps: "3 set x 30 detik", desc: "Posisi push up, lari di tempat lutut ke dada." }
            ]},
            { day: "Jumat", focus: "Full Body", icon: "fas fa-bolt", exercises: [
                { name: "Push Ups", reps: "3 set x Max", desc: "Lakukan sebanyak mungkin." },
                { name: "Squats", reps: "3 set x Max", desc: "Lakukan sebanyak mungkin." },
                { name: "Plank", reps: "3 set x Max", desc: "Tahan selama mungkin." }
            ]},
            { day: "Sabtu", focus: "Active Recovery", icon: "fas fa-swimmer", exercises: [
                { name: "Jogging / Renang", reps: "45 Menit", desc: "Aktivitas aerobik ringan." }
            ]},
            { day: "Minggu", focus: "Istirahat", icon: "fas fa-coffee", exercises: [
                { name: "Istirahat", reps: "-", desc: "Recovery." }
            ]}
        ]
    },
    profesional: {
        name: "Profesional",
        desc: "Volume tinggi dan gerakan eksplosif.",
        schedule: [
            { day: "Senin", focus: "Explosive Push", icon: "fas fa-bomb", exercises: [
                { name: "Diamond Push Ups", reps: "4 set x 15 reps", desc: "Push up dengan tangan membentuk wajik di tengah dada." },
                { name: "Clapping Push Ups", reps: "3 set x 8 reps", desc: "Push up eksplosif sampai tangan tepuk tangan di udara." },
                { name: "Handstand Hold (Dinding)", reps: "3 set x 30 detik", desc: "Berdiri dengan tangan menempel dinding." }
            ]},
            { day: "Selasa", focus: "Legs Power", icon: "fas fa-bolt", exercises: [
                { name: "Bulgarian Split Squat", reps: "3 set x 12/kaki", desc: "Satu kaki ditaruh di kursi belakang, squat satu kaki." },
                { name: "Pistol Squat (Assisted)", reps: "3 set x 5/kaki", desc: "Squat satu kaki lurus ke depan." },
                { name: "Box Jumps", reps: "4 set x 12 reps", desc: "Lompat ke atas kursi kokoh/tangga beton." }
            ]},
            { day: "Rabu", focus: "Core Killer", icon: "fas fa-burn", exercises: [
                { name: "L-Sit Hold", reps: "5 set x 15 detik", desc: "Duduk, tangan di lantai, angkat pantat dan kaki dari lantai." },
                { name: "Bicycle Crunches", reps: "4 set x 20 total", desc: "Tidur, siku kanan ketemu lutut kiri bergantian." },
                { name: "Hollow Body", reps: "4 set x 45 detik", desc: "Tidur, badan melengkung seperti pisang (bahu & kaki naik)." }
            ]},
            { day: "Kamis", focus: "Endurance A", icon: "fas fa-stopwatch", exercises: [
                { name: "Max Push Ups", reps: "1 set x Gagal", desc: "Lakukan sampai tidak kuat lagi." },
                { name: "Max Squats", reps: "1 set x Gagal", desc: "Lakukan sampai tidak kuat lagi." }
            ]},
            { day: "Jumat", focus: "Endurance B", icon: "fas fa-stopwatch", exercises: [
                { name: "Burpees", reps: "100 Reps (Total)", desc: "Selesaikan 100 burpees secepat mungkin (boleh istirahat)." }
            ]},
            { day: "Sabtu", focus: "Cardio", icon: "fas fa-running", exercises: [
                { name: "Lari Jarak Jauh", reps: "5 KM", desc: "Lari dengan kecepatan stabil." }
            ]},
            { day: "Minggu", focus: "Rest", icon: "fas fa-coffee", exercises: [
                { name: "Istirahat", reps: "-", desc: "Full recovery." }
            ]}
        ]
    }
};

// =========================================
// 3. FUNGSI-FUNGSI LOGIKA
// =========================================

function renderAll() {
    if (window.dbData) userProgress = window.dbData;
    if (!workoutData[currentLevel]) {
        console.error("Level tidak ditemukan:", currentLevel);
        currentLevel = 'pemula';
    }

    const info = workoutData[currentLevel];
    
    // Update Judul & Deskripsi
    const titleEl = document.getElementById('displayLevelName');
    const descEl = document.getElementById('displayLevelDesc');
    const detailsEl = document.getElementById('levelDetails');
    
    if(titleEl) titleEl.innerText = info.name;
    if(descEl) descEl.innerText = info.desc;
    
    // Ringkasan Sidebar
    if(detailsEl) {
        let detailsHtml = '';
        info.schedule.forEach(s => {
            detailsHtml += `<div style="font-size:0.8rem; margin-bottom:4px;"><b>${s.day}:</b> ${s.focus}</div>`;
        });
        detailsEl.innerHTML = detailsHtml;
    }

    renderSchedule();
    renderStats();
}

function renderSchedule() {
    const container = document.getElementById('daysContainer');
    if(!container) return; 

    container.innerHTML = '';
    const schedule = workoutData[currentLevel].schedule;
    const daysMap = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
    const todayIndex = new Date().getDay(); 
    const todayName = daysMap[todayIndex]; 

    schedule.forEach((dayData, idx) => {
        // Key Unik: "pemula_0", "menengah_1", dll
        const key = `${currentLevel}_${idx}`;
        const status = userProgress[key]; // Ambil status dari data DB
        
        // Logika Tampilan Icon & Warna
        let iconClass = 'fa-circle'; 
        let boxColorClass = ''; 
        let undoStyle = 'display:none';

        // Paksa tampilan sesuai status di database
        if (status === 'completed') {
            iconClass = 'fa-check-circle';
            boxColorClass = 'completed'; 
            undoStyle = 'display:inline-block';
        } else if (status === 'missed') {
            iconClass = 'fa-times-circle';
            boxColorClass = 'missed';
            undoStyle = 'display:inline-block';
        }

        // Cek Hari Ini
        let isToday = dayData.day.includes(todayName); 
        let todayClass = isToday ? 'today-card' : '';
        let todayBadge = isToday ? '<span class="today-badge">HARI INI</span>' : '';

        container.innerHTML += `
            <div class="day-box ${boxColorClass} ${todayClass}" onclick="showDetail(${idx})">
                <div style="font-weight:bold; margin-bottom:5px; display:flex; align-items:center; justify-content:center;">
                    ${dayData.day} ${todayBadge}
                </div>
                <div style="font-size:0.9rem; margin-bottom:10px;">${dayData.focus}</div>
                
                <div class="day-status"><i class="fas ${iconClass}"></i></div>
                
                <div class="action-buttons">
                    <button class="action-btn complete-btn" title="Selesai" 
                        onclick="updateStatus(event, '${key}', 'completed')">
                        <i class="fas fa-check"></i>
                    </button>
                    
                    <button class="action-btn miss-btn" title="Terlewat" 
                        onclick="updateStatus(event, '${key}', 'missed')">
                        <i class="fas fa-times"></i>
                    </button>
                    
                    <button class="action-btn reset-day-btn" style="${undoStyle}" title="Reset" 
                        onclick="updateStatus(event, '${key}', 'unchecked')">
                        <i class="fas fa-undo"></i>
                    </button>
                </div>
            </div>
        `;
    });
}

function renderStats() {
    const schedule = workoutData[currentLevel].schedule;
    const totalDays = schedule.length; 
    
    let completed = 0, missed = 0;
    for(let i=0; i<totalDays; i++) {
        const key = `${currentLevel}_${i}`;
        if(userProgress[key] === 'completed') completed++;
        if(userProgress[key] === 'missed') missed++;
    }
    
    const compEl = document.getElementById('completedCount');
    const missEl = document.getElementById('missedCount');
    if(compEl) compEl.innerText = completed;
    if(missEl) missEl.innerText = missed;
    
    let greenHeight = (completed / totalDays) * 100;
    let redHeight = (missed / totalDays) * 100;
    
    const barG = document.getElementById('barGreen');
    const barR = document.getElementById('barRed');
    if(barG) barG.style.height = `${greenHeight}%`;
    if(barR) barR.style.height = `${redHeight}%`;

    updateWeeklyFeedback(completed, totalDays);
    
    const totalAction = completed + missed;
    const resetBtn = document.getElementById('resetSaveBtn');
    if(resetBtn) resetBtn.style.display = (totalAction === totalDays) ? 'flex' : 'none';
}

function updateWeeklyFeedback(count, total) {
    const container = document.getElementById('feedbackContainer');
    const icon = document.getElementById('feedbackIcon');
    const title = document.getElementById('feedbackTitle');
    const text = document.getElementById('feedbackText');
    
    if(!container) return;

    container.className = 'feedback-card';
    container.style.display = 'block';

    if (count === total) {
        container.classList.add('feedback-perfect');
        icon.className = 'feedback-icon fas fa-medal';
        title.innerText = "Luar Biasa!";
        text.innerText = "Minggu sempurna! Kamu disiplin sekali.";
    } else if (count >= (total/2)) {
        container.classList.add('feedback-good');
        icon.className = 'feedback-icon fas fa-star-half-alt';
        title.innerText = "Kerja Bagus!";
        text.innerText = `Kamu sudah menyelesaikan ${count} hari. Pertahankan!`;
    } else {
        container.classList.add('feedback-bad');
        icon.className = 'feedback-icon fas fa-battery-quarter';
        title.innerText = "Ayo Semangat!";
        text.innerText = "Awal yang berat itu wajar. Jangan menyerah!";
    }
}

// --- FUNGSI UPDATE STATUS KE DATABASE ---
function updateStatus(e, key, status) {
    if(e) e.stopPropagation();
    
    // 1. Update Tampilan Langsung (Biar terasa cepat)
    if (status === 'unchecked') {
        delete userProgress[key];
    } else {
        userProgress[key] = status;
    }
    renderAll();
    
    // 2. Kirim ke Backend
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
    if(!tokenMeta) return; 
    
    const token = tokenMeta.getAttribute('content');

    fetch('/save-progress', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            key: key,
            status: status 
        })
    })
    .then(res => res.json())
    .then(data => {
        console.log("DB Updated:", data);
        updateDBStatus('saved');
    })
    .catch(err => {
        console.error("Gagal simpan:", err);
    });
}

function archiveWeek() {
    if(!confirm("Anda yakin ingin mengarsipkan minggu ini dan mereset progress?")) return;
    
    let completed = 0;
    const schedule = workoutData[currentLevel].schedule;
    for(let i=0; i<schedule.length; i++) {
        const key = `${currentLevel}_${i}`;
        if(userProgress[key] === 'completed') completed++;
    }

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const formData = new FormData();
    formData.append('completed_count', completed);

    fetch('/archive-week', { 
        method: 'POST', 
        body: formData,
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            alert("Minggu berhasil diarsipkan!");
            location.reload(); 
        }
    })
    .catch(err => {
        console.error(err);
        alert("Terjadi kesalahan sistem.");
    });
}

function changeLevel(level) {
    console.log("Mengganti Level ke:", level);
    localStorage.setItem('user_level_pref', level);
    currentLevel = level; 
    updateSettingsUI();
    renderAll();
}

function updateDBStatus(state) {
    const dot = document.getElementById('dbDot');
    const text = document.getElementById('dbText');
    if(!dot) return;

    if(state === 'saving') { dot.className = 'status-dot saving'; text.innerText = 'Menyimpan...'; }
    else { dot.className = 'status-dot saved'; text.innerText = 'Tersinkronisasi'; }
}

function toggleForm(show){
    const fLogin = document.getElementById('formLogin');
    const fReg = document.getElementById('formRegister');
    if(fLogin) fLogin.style.display = show==='login'?'block':'none';
    if(fReg) fReg.style.display = show==='register'?'block':'none';
}

function openSettings() { document.getElementById('settingsModal').style.display = 'flex'; updateSettingsUI(); }
function closeSettings() { document.getElementById('settingsModal').style.display = 'none'; }
function openHistory() { initHistoryLog(); document.getElementById('historyModal').style.display = 'flex'; }
function closeHistory() { document.getElementById('historyModal').style.display = 'none'; }
function closeModal() { document.getElementById('detailModal').style.display = 'none'; }

function updateSettingsUI() { 
    document.querySelectorAll('.level-radio').forEach(el => el.classList.remove('selected')); 
    const activeEl = document.getElementById('opt-' + currentLevel);
    if(activeEl) activeEl.classList.add('selected'); 
}

function showDetail(index) {
    const dayData = workoutData[currentLevel].schedule[index];
    document.getElementById('modalTitle').innerText = `${dayData.day} - ${dayData.focus}`;
    document.getElementById('modalIcon').className = dayData.icon;
    
    const listEl = document.getElementById('modalList');
    listEl.innerHTML = '';
    
    dayData.exercises.forEach(item => {
        listEl.innerHTML += `
            <li style="display:block; border-bottom:1px solid #eee; padding-bottom:10px; margin-bottom:10px;">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span class="ex-name" style="font-weight:bold; color:var(--primary)">${item.name}</span> 
                    <span class="ex-sets" style="background:#eee; padding:2px 8px; border-radius:4px; font-size:0.8rem;">${item.reps}</span>
                </div>
                <div style="margin-top:5px; font-size:0.85rem; color:#666; background:#f9f9f9; padding:8px; border-left:3px solid var(--secondary); border-radius:3px;">
                    <i class="fas fa-info-circle"></i> ${item.desc}
                </div>
            </li>
        `;
    });
    document.getElementById('detailModal').style.display = 'flex';
}

window.onclick = function(e) { 
    if(e.target == document.getElementById('detailModal')) closeModal();
    if(e.target == document.getElementById('settingsModal')) closeSettings();
    if(e.target == document.getElementById('historyModal')) closeHistory();
}

function initHistoryLog() {
    const list = document.getElementById('historyListContainer');
    const historyData = window.userHistory || [];
    
    if(!list) return;
    list.innerHTML = '';
    
    if(historyData.length === 0) {
        list.innerHTML = '<div class="empty-log">Belum ada riwayat aktivitas. Selesaikan satu minggu latihan!</div>';
    } else {
        historyData.forEach(log => {
            let colorClass = 'red';
            if(log.latihan_selesai >= 6) colorClass = 'green';
            else if(log.latihan_selesai >= 4) colorClass = 'yellow';

            const date = log.created_at ? new Date(log.created_at).toLocaleDateString('id-ID') : '-';

            list.innerHTML += `
                <div class="log-item ${colorClass}">
                    <div>
                        <div class="log-title">Minggu ke-${log.minggu_ke}</div>
                        <div class="log-desc">${log.latihan_selesai} Selesai dari 7 Latihan</div>
                        <div class="log-date">${date}</div>
                    </div>
                    <div style="font-size:1.5rem; font-weight:bold;">${log.latihan_selesai}/7</div>
                </div>
            `;
        });
    }
}

function initHistoryChart() {
    const ctx = document.getElementById('historyChart');
    if(!ctx) return;
    
    const msg = document.getElementById('noDataMsg');
    const historyData = window.userHistory || [];

    if (historyData.length === 0) {
        if(msg) msg.style.display = 'block';
        ctx.style.display = 'none';
        return;
    } else {
        if(msg) msg.style.display = 'none';
        ctx.style.display = 'block';
    }

    const sorted = [...historyData].sort((a,b) => a.id - b.id);
    const labels = sorted.map(h => `Mg ${h.minggu_ke}`);
    const values = sorted.map(h => h.latihan_selesai);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Latihan Selesai',
                data: values,
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            plugins: { legend: {display: false} },
            scales: { y: { beginAtZero: true, max: 8 } }
        }
    });
}

// =========================================
// 4. MAIN EXECUTION (JALAN SAAT LOAD)
// =========================================
document.addEventListener('DOMContentLoaded', function() {
    console.log("🚀 Aplikasi Dimulai...");

    // [FIX 1] Paksa ambil data terbaru dari window.dbData
    // Ini memastikan data terbaca meskipun ada delay dari server
    if (window.dbData && Object.keys(window.dbData).length > 0) {
        userProgress = window.dbData;
        console.log("✅ Data Database Ditemukan:", userProgress);
    } else {
        console.warn("⚠️ Data Database Kosong atau Key Tidak Cocok.");
    }

    // [FIX 2] Tentukan level yang harus dimuat
    // Prioritas: Level tersimpan di browser -> Default 'pemula'
    let levelToLoad = localStorage.getItem('user_level_pref') || 'pemula';
    
    // Simpan ke variabel global agar sinkron
    currentLevel = levelToLoad; 

    // Render Tampilan
    updateSettingsUI(); // Update radio button di setting
    renderAll();        // Render jadwal & ceklis
    initHistoryChart(); // Render grafik

    // Update Nama User di UI jika ada
    const userDisplay = document.getElementById('usernameDisplay');
    if (userDisplay && window.currentUser) {
        userDisplay.innerText = window.currentUser;
    }
});