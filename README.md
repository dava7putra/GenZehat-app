# 📚 Dokumentasi Project (Progress Report)

## GenZehat - Calisthenics Workout Tracker (Web Version)
![Laravel](https://img.shields.io/badge/Laravel-11-red?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue?logo=php)
![JavaScript](https://img.shields.io/badge/JavaScript-Fetch_API-F7DF1E?logo=javascript&logoColor=black)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.x-7952B3?logo=bootstrap)

---

## 📖 Deskripsi
GenZehat adalah platform pelacak kebugaran (Fitness Tracker) berbasis web yang dirancang untuk membantu pengguna memantau progres latihan *Calisthenics* (olahraga beban tubuh) secara terstruktur. Aplikasi ini fokus pada pengelolaan jadwal latihan pribadi dan pengarsipan progres mingguan untuk memantau konsistensi pengguna secara mandiri.

### Tujuan Utama:
- Menyediakan jadwal latihan beban tubuh (Bodyweight) yang terstruktur.
- Membantu pengguna memantau konsistensi melalui fitur pencatatan harian interaktif.
- Mendukung pemula hingga profesional dengan sistem Level Latihan.
- Menyediakan riwayat progres (Personal History) untuk evaluasi latihan mandiri.
- Menyediakan basis data terpusat yang terintegrasi dengan aplikasi Android via REST API.

### Tech Stack:
- **Backend:** Laravel 11
- **Frontend:** Blade Templates + Vanilla JavaScript (Fetch API)
- **Database:** MySQL 8.0
- **Authentication:** Laravel Session & CSRF Protection
- **Cross-Platform Support:** Laravel Sanctum (Mendukung endpoint API untuk Mobile)

---

## 📋 User Story

| ID | User Story | Priority |
|----|------------|----------|
| US-01 | Sebagai user, saya ingin membuat akun agar progres latihan tersimpan secara privat | High |
| US-02 | Sebagai user, saya ingin mencentang jadwal harian (Selesai/Terlewat) secara instan | High |
| US-03 | Sebagai user, saya ingin mengarsipkan progres minggu ini untuk melihat statistik keberhasilan | High |
| US-04 | Sebagai user, saya ingin mengganti tingkat kesulitan (Pemula/Menengah/Pro) | Medium |
| US-05 | Sebagai user, saya ingin melihat daftar riwayat (History) mingguan yang sudah saya selesaikan | Medium |

---

## 📝 SRS - Feature List

### Functional Requirements
| ID | Feature | Deskripsi | Status |
|----|---------|-----------|--------|
| FR-01 | Web Authentication | Login, Register, Logout menggunakan Laravel Session | ✅ Done |
| FR-02 | Daily Progress Tracker | Checklist harian interaktif via AJAX Fetch API | ✅ Done |
| FR-03 | Level Management | Pilihan level latihan (Pemula, Menengah, Pro) | ✅ Done |
| FR-04 | Workout Details | Modal popup berisi instruksi gerakan dan repetisi | ✅ Done |
| FR-05 | Personal Archiving | Fitur "Save & Exit" untuk mengarsipkan statistik mingguan | ✅ Done |
| FR-06 | History View | Halaman khusus untuk melihat riwayat progres pribadi | ✅ Done |

### Non-Functional Requirements
| ID | Requirement | Deskripsi |
|----|-------------|-----------|
| NFR-01 | Security | CSRF protection untuk semua request POST di Web |
| NFR-02 | Performance | Update UI instan (Asynchronous) tanpa reload halaman |
| NFR-03 | Data Integrity | Mencegah duplikasi data status harian di database |
| NFR-04 | Usability | Desain responsif untuk penggunaan di browser PC |

---

## 📊 UML Diagrams & ERD

### 1. Use Case Diagram
```mermaid
flowchart LR
    User((User))

    subgraph GenZehat_Web
        UC1(Auth_System)
        UC2(Daily_Tracker)
        UC3(Archive_Process)
        UC4(Personal_History_View)
    end

    User --> UC1
    User --> UC2
    User --> UC3
    User --> UC4
```

### 2. Activity Diagram - Update Status (AJAX)
```mermaid
flowchart TD
    Start([Start]) --> Event[Click_Status_Button]
    Event --> Req[AJAX_Fetch_POST]
    Req --> Route[Web_Middleware]
    
    Route --> Decision{Existing_Data?}
    Decision -- Yes --> Del[Delete_Old_Status]
    Decision -- No --> Ins[Insert_New_Status]
    
    Del --> Ins
    Ins --> Res[JSON_Success_Response]
    Res --> UI[DOM_Update_Color]
    UI --> Finish([Finish])
```

### 3. Sequence Diagram - Personal History Retrieval
```mermaid
sequenceDiagram
    autonumber
    actor User
    participant View as History_Page
    participant Controller as History_Controller
    participant DB as MySQL_DB

    User->>View: Access_History_Menu
    View->>Controller: GET_History_Request
    
    Note over Controller: Identify_Logged_In_User
    Controller->>DB: Query_Histories(auth_user_id)
    DB-->>Controller: Personal_History_Data
    
    Controller-->>View: Send_Data_to_Blade
    View-->>User: Render_Personal_History_UI
```

### 4. Entity Relationship Diagram (ERD)
```mermaid
erDiagram
    users ||--o{ daily_progress : "mencatat"
    users ||--o{ histories : "memiliki"
    users ||--o{ personal_access_tokens : "menggunakan"

    users {
        bigint id PK
        string username
        string password
        string level
        timestamp created_at
        timestamp updated_at
    }

    daily_progress {
        bigint id PK
        bigint user_id FK
        string day_name
        string status
        timestamp created_at
        timestamp updated_at
    }

    histories {
        bigint id PK
        bigint user_id FK
        int minggu_ke
        string detail
        date tanggal
        int persentase
        timestamp created_at
        timestamp updated_at
    }

    personal_access_tokens {
        bigint id PK
        string tokenable_type
        bigint tokenable_id FK
        string name
        string token
        timestamp created_at
    }
```

---

## 🎨 Mock-Up / Screenshots
*(Letakkan file gambar Anda di folder docs/)*
1. **Dashboard & Jadwal:** `![Dashboard](docs/mockup_dashboard.png)`
2. **Personal History:** `![History](docs/mockup_history.png)`

---

## 🔄 SDLC (Software Development Life Cycle)

**Metodologi:** Waterfall dengan iterasi

| Phase | Aktivitas | Output |
|-------|-----------|--------|
| **1. Planning** | Menentukan target latihan & alur aplikasi | Requirement Doc |
| **2. Analysis** | Merancang struktur database (Relasional) | SRS, Feature List |
| **3. Design** | Membuat UML diagram & ERD | UML, ERD, Mockups |
| **4. Development** | Coding Backend (Laravel) & Frontend (JS) | Source code Web |
| **5. Testing** | Uji tombol AJAX & perhitungan persentase | Test Result |
| **6. Deployment** | Setup server lokal & integrasi Mobile API | Live application |

---

## 🚀 Instalasi (Lokal)

### Langkah 1: Clone Repository
```bash
git clone [https://github.com/username-kamu/GenZehat.git](https://github.com/username-kamu/GenZehat.git)
cd GenZehat
```

### Langkah 2: Install Dependencies & Setup
```bash
composer install
cp .env.example .env
php artisan key:generate
```

### Langkah 3: Setup Database
**Edit file `.env`** sesuaikan DB_DATABASE, lalu jalankan:
```bash
php artisan migrate
```

### Langkah 4: Jalankan Server
```bash
php artisan serve
```
Aplikasi Web: **http://localhost:8000**

---

## 📁 Struktur Database
- **users**: Data akun pengguna (Username, Password, Level).
- **daily_progress**: Status latihan harian (Day_name, Status).
- **histories**: Arsip progres mingguan (User_id, Minggu_ke, Persentase).
- **personal_access_tokens**: Tabel bawaan Sanctum untuk mengelola token API.

---

## 🌐 Web Internal Endpoints (AJAX)
| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| POST | `/save-progress` | Menyimpan status harian secara real-time |
| POST | `/save-history` | Mengarsipkan data minggu ini ke tabel history |

---
**Dibuat oleh:** Dava Anugrah Putra
