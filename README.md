# Praktikum 11 – PHP OOP Lanjutan

## Tujuan Praktikum
1. Memahami konsep dasar Framework Modular.
2. Memahami konsep routing menggunakan .htaccess.
3. Membangun mini-framework sederhana berbasis PHP OOP.
4. Melakukan CRUD menggunakan class Database dan Form.

## Struktur Folder
```
lab11_php_oop/
├── .htaccess
├── config.php
├── index.php
├── class/
│   ├── Database.php
│   └── Form.php
├── module/
│   └── artikel/
│       ├── index.php
│       ├── tambah.php
│       ├── ubah.php
│       └── hapus.php
├── template/
│   ├── header.php
│   ├── footer.php
│   └── sidebar.php
└── README.md
```

## Routing
Routing membaca URL dan mengarahkan ke modul sesuai path:
Contoh:
`/artikel/tambah` → `module/artikel/tambah.php`

## Database
Gunakan database berikut:
```
CREATE DATABASE latihan_oop;
USE latihan_oop;

CREATE TABLE artikel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255),
    isi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## Hasil
<img width="1366" height="768" alt="Screenshot from 2025-12-09 18-43-08" src="https://github.com/user-attachments/assets/ddb626b4-f583-4f77-b33c-f421f897d111" />
