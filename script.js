// --- LOGIKA UTAMA APLIKASI ---

// Fungsi Toggle Form Login/Register (Dipindahkan ke sini agar rapi)
function toggleForm(show){
    document.getElementById('formLogin').style.display = show==='login'?'block':'none';
    document.getElementById('formRegister').style.display = show==='register'?'block':'none';
}

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

// Jika user sudah login (currentUser ada), jalankan logika
if (typeof currentUser !== 'undefined' && currentUser !== '') {
    document.addEventListener('DOMContentLoaded', () => {
        renderAll();
        updateDBStatus('saved');
        updateSettingsUI();
    });
}

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

// --- SISTEM PENYIMPANAN (AJAX) ---
function updateStatus(e, key, status) {
    e.stopPropagation();
    userProgress[key] = status;
    renderAll();
    saveProgress();
}

function saveProgress() {
    updateDBStatus('saving');
    const formData = new FormData();
    formData.append('action', 'save_progress');
    formData.append('data', JSON.stringify(userProgress));

    fetch(window.location.href, { method: 'POST', body: formData })
    .then(res => res.text())
    .then(data => {
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
    formData.append('action', 'archive_week');
    formData.append('completed_count', completed);

    fetch(window.location.href, { method: 'POST', body: formData })
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
    const formData = new FormData();
    formData.append('action', 'reset_history');
    
    fetch(window.location.href, { method: 'POST', body: formData })
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