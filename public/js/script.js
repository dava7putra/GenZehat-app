// --- LOGIKA UTAMA APLIKASI ---

// Fungsi Toggle Form Login/Register (Dipindahkan ke sini agar rapi)
function toggleForm(show){
    document.getElementById('formLogin').style.display = show==='login'?'block':'none';
    document.getElementById('formRegister').style.display = show==='register'?'block':'none';
}

// DATA LATIHAN CALISTHENICS (TANPA ALAT) + INSTRUKSI
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

const days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

// Jika user sudah login (currentUser ada), jalankan logika
if (typeof currentUser !== 'undefined' && currentUser !== '') {
    document.addEventListener('DOMContentLoaded', () => {
        renderAll();
        updateDBStatus('saved');
        updateSettingsUI();
        
        // TAMBAHKAN INI:
        initHistoryChart(); 
    });
}

// --- LOGIKA TAMPILAN BARU (SESUAI DATA CALISTHENICS) ---

function renderAll() {
    // Ambil data dari workoutData, bukan appData
    const info = workoutData[currentLevel];
    document.getElementById('displayLevelName').innerText = info.name;
    document.getElementById('displayLevelDesc').innerText = info.desc;
    
    // Bikin ringkasan jadwal untuk sidebar
    let detailsHtml = '';
    info.schedule.forEach(s => {
        detailsHtml += `<div style="font-size:0.8rem; margin-bottom:4px;"><b>${s.day}:</b> ${s.focus}</div>`;
    });
    document.getElementById('levelDetails').innerHTML = detailsHtml;

    renderSchedule();
    renderStats();
}

function renderSchedule() {
    const container = document.getElementById('daysContainer');
    container.innerHTML = '';
    
    // Ambil data jadwal
    const schedule = workoutData[currentLevel].schedule;

    // --- LOGIKA DETEKSI HARI INI ---
    const daysMap = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
    const todayIndex = new Date().getDay(); // 0 = Minggu, 1 = Senin, dst.
    const todayName = daysMap[todayIndex];  // Misal: "Jumat"
    // --------------------------------

    schedule.forEach((dayData, idx) => {
        const key = `${currentLevel}_${idx}`;
        // Cek status dari database (completed/missed/pending)
        const status = (userProgress[key] && userProgress[key] !== 'pending') ? userProgress[key] : 'pending';
        
        let iconClass = 'fa-question';
        if(status === 'completed') iconClass = 'fa-check-circle';
        if(status === 'missed') iconClass = 'fa-times-circle';

        const undoStyle = (status === 'pending') ? 'display:none' : 'display:inline-block';

        // --- CEK APAKAH INI KARTU HARI INI? ---
        // Kita cek apakah nama hari di kartu (misal "Jumat") sama dengan hari nyata
        let isToday = dayData.day.includes(todayName); 
        let todayClass = isToday ? 'today-card' : '';
        let todayBadge = isToday ? '<span class="today-badge">HARI INI</span>' : '';
        // --------------------------------------

        container.innerHTML += `
            <div class="day-box ${status} ${todayClass}" onclick="showDetail(${idx})">
                <div style="font-weight:bold; color:var(--primary); margin-bottom:5px; display:flex; align-items:center; justify-content:center;">
                    ${dayData.day} ${todayBadge}
                </div>
                <div style="font-size:0.9rem; margin-bottom:10px;">${dayData.focus}</div>
                <div class="day-status ${status}"><i class="fas ${iconClass}"></i></div>
                
                <div class="action-buttons">
                    <button class="action-btn complete-btn" title="Selesai" onclick="updateStatus(event, '${key}', 'completed')"><i class="fas fa-check"></i></button>
                    <button class="action-btn miss-btn" title="Terlewat" onclick="updateStatus(event, '${key}', 'missed')"><i class="fas fa-times"></i></button>
                    <button class="action-btn reset-day-btn" style="${undoStyle}" title="Reset" onclick="updateStatus(event, '${key}', 'pending')"><i class="fas fa-undo"></i></button>
                </div>
            </div>
        `;
    });
}

function renderStats() {
    const schedule = workoutData[currentLevel].schedule;
    const totalDays = schedule.length; // Otomatis 7
    
    let completed = 0, missed = 0;
    for(let i=0; i<totalDays; i++) {
        const key = `${currentLevel}_${i}`;
        if(userProgress[key] === 'completed') completed++;
        if(userProgress[key] === 'missed') missed++;
    }
    
    document.getElementById('completedCount').innerText = completed;
    document.getElementById('missedCount').innerText = missed;
    
    // Update Grafik Batang
    let greenHeight = (completed / totalDays) * 100;
    let redHeight = (missed / totalDays) * 100;
    document.getElementById('barGreen').style.height = `${greenHeight}%`;
    document.getElementById('barRed').style.height = `${redHeight}%`;

    updateWeeklyFeedback(completed, totalDays);
    
    const totalAction = completed + missed;
    const resetBtn = document.getElementById('resetSaveBtn');
    resetBtn.style.display = (totalAction === totalDays) ? 'flex' : 'none';
}

function updateWeeklyFeedback(count, total) {
    const container = document.getElementById('feedbackContainer');
    const icon = document.getElementById('feedbackIcon');
    const title = document.getElementById('feedbackTitle');
    const text = document.getElementById('feedbackText');
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

// --- SISTEM PENYIMPANAN (AJAX) ---
function updateStatus(e, key, status) {
    e.stopPropagation();
    userProgress[key] = status;
    renderAll();
    saveProgress();
}

function saveProgress() {
    updateDBStatus('saving');

    // --- OBAT PENAWAR BUG (Tambahkan Bagian Ini) ---
    // Kita paksa userProgress disalin menjadi Object Murni {}
    // Ini memperbaiki masalah Array [] yang tidak mau nyimpan nama hari
    var dataYangMauDikirim = Object.assign({}, userProgress);

    console.log("Data Asli:", userProgress);       // Cek: Mungkin bentuknya []
    console.log("Data Fix:", dataYangMauDikirim);  // Cek: Pasti bentuknya {}
    // -----------------------------------------------

    const formData = new FormData();
    // Gunakan dataYangMauDikirim, JANGAN userProgress
    formData.append('data', JSON.stringify(dataYangMauDikirim));

    // Ambil Token CSRF
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/save-progress', { 
        method: 'POST', 
        body: formData,
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        }
    })
    .then(res => res.text())
    .then(data => {
        // Jika server balas "Saved", ubah status jadi saved
        if(data === 'Saved') setTimeout(() => updateDBStatus('saved'), 500);
    })
    .catch(err => console.error(err));
}

function archiveWeek() {
    if(!confirm("Anda yakin ingin mengarsipkan minggu ini dan mereset progress?")) return;
    
    let completed = 0;
    for(let i=0; i<7; i++) {
        const key = `${currentLevel}_${i}`;
        if(userProgress[key] === 'completed') completed++;
    }

    const formData = new FormData();
    formData.append('completed_count', completed);

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Arahkan ke URL '/archive-week'
    fetch('/archive-week', { 
        method: 'POST', 
        body: formData,
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(newHistory => {
        userHistory = newHistory; 
        userProgress = {}; 
        renderAll();
        alert("Minggu berhasil diarsipkan!");
    });
}

// --- SISTEM HISTORY ---
function openHistory() {
    const list = document.getElementById('historyListContainer');
    list.innerHTML = '';
    
    if(userHistory.length === 0) {
        list.innerHTML = '<div class="empty-log">Belum ada riwayat aktivitas. Selesaikan satu minggu latihan!</div>';
    } else {
        userHistory.slice().reverse().forEach(log => {
            let colorClass = 'red';
            if(log.count === 7) colorClass = 'green';
            else if(log.count >= 4) colorClass = 'yellow';

            list.innerHTML += `
                <div class="log-item ${colorClass}">
                    <div>
                        <div class="log-title">${log.title}</div>
                        <div class="log-desc">${log.desc}</div>
                        <div class="log-date">${log.date}</div>
                    </div>
                    <div style="font-size:1.5rem; font-weight:bold;">${log.count}/7</div>
                </div>
            `;
        });
    }
    document.getElementById('historyModal').style.display = 'flex';
}
function closeHistory() { document.getElementById('historyModal').style.display = 'none'; }

function clearHistory() {
    if(!confirm("Yakin hapus semua riwayat? Data tidak bisa dikembalikan.")) return;
    
    const formData = new FormData(); // Kosong tidak apa-apa, yang penting kirim Token
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Arahkan ke URL '/reset-history'
    fetch('/reset-history', { 
        method: 'POST', 
        body: formData,
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        }
    })
    .then(res => res.text())
    .then(() => {
        userHistory = [];
        openHistory(); 
    });
}

// --- UI HELPERS ---
function updateDBStatus(state) {
    const dot = document.getElementById('dbDot');
    const text = document.getElementById('dbText');
    if(state === 'saving') { dot.className = 'status-dot saving'; text.innerText = 'Menyimpan...'; }
    else { dot.className = 'status-dot saved'; text.innerText = 'Tersinkronisasi'; }
}
function openSettings() { document.getElementById('settingsModal').style.display = 'flex'; updateSettingsUI(); }
function closeSettings() { document.getElementById('settingsModal').style.display = 'none'; }

function showDetail(index) {
    // Ambil data berdasarkan urutan hari (index)
    const dayData = workoutData[currentLevel].schedule[index];
    
    // Update Judul Modal
    document.getElementById('modalTitle').innerText = `${dayData.day} - ${dayData.focus}`;
    document.getElementById('modalIcon').className = dayData.icon;
    
    const listEl = document.getElementById('modalList');
    listEl.innerHTML = '';
    
    // Loop isi latihan dan tampilkan DESKRIPSI (desc)
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

function closeModal() { document.getElementById('detailModal').style.display = 'none'; }
function changeLevel(newLevel) { currentLevel = newLevel; localStorage.setItem('user_level_pref', newLevel); renderAll(); updateSettingsUI(); }
function updateSettingsUI() { document.querySelectorAll('.level-radio').forEach(el => el.classList.remove('selected')); document.getElementById('opt-' + currentLevel).classList.add('selected'); }
window.onclick = function(e) { 
    if(e.target == document.getElementById('detailModal')) closeModal();
    if(e.target == document.getElementById('settingsModal')) closeSettings();
    if(e.target == document.getElementById('historyModal')) closeHistory();
}

// --- FITUR GRAFIK RIWAYAT (BARU) ---

function initHistoryChart() {
    const ctx = document.getElementById('historyChart').getContext('2d');
    const msg = document.getElementById('noDataMsg');
    const canvas = document.getElementById('historyChart');

    // Cek apakah ada data history?
    if (!userHistory || userHistory.length === 0) {
        msg.style.display = 'block';
        canvas.style.display = 'none';
        return;
    } else {
        msg.style.display = 'none';
        canvas.style.display = 'block';
    }

    // Urutkan data dari minggu lama ke baru (supaya garisnya naik ke kanan)
    // Data asli biasanya tersimpan: [Minggu 1, Minggu 2, ...]
    const sortedHistory = userHistory; 

    // Siapkan Label (Sumbu X) dan Data (Sumbu Y)
    const labels = sortedHistory.map(log => log.title.replace('Minggu ke-', 'Mg ')); // Jadi "Mg 1", "Mg 2"
    const dataPoints = sortedHistory.map(log => log.count); // Jumlah hari selesai (misal: 5, 7, 4)

    // Render Chart
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Hari Selesai',
                data: dataPoints,
                borderColor: '#4e73df', // Warna Garis (Biru)
                backgroundColor: 'rgba(78, 115, 223, 0.1)', // Warna Arsiran bawah garis
                borderWidth: 2,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#4e73df',
                pointRadius: 4,
                tension: 0.3, // Membuat garis sedikit melengkung (smooth)
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }, // Sembunyikan legenda biar rapi
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw + ' Hari Selesai';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 8, // Maksimal 7 hari + sedikit ruang
                    ticks: { stepSize: 1, font: {size: 10} },
                    grid: { display: true, drawBorder: false }
                },
                x: {
                    ticks: { font: {size: 10} },
                    grid: { display: false }
                }
            }
        }
    });
}