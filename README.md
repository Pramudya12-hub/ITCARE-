ITCARE — Sistem Helpdesk IT Berbasis AI

Overview

ITCARE adalah sistem Helpdesk IT internal berbasis AI yang dibangun menggunakan Laravel dan Bootstrap 5. Sistem ini dirancang untuk membantu organisasi dalam mengelola pengaduan dan dukungan IT secara lebih efisien melalui fitur ticketing, rekomendasi AI, monitoring aktivitas pengguna, serta integrasi knowledge base.

Project ini berfokus pada pengalaman helpdesk modern dengan pendekatan enterprise-style dashboard dan AI operational assistant.


---
Fitur Utama

Authentication & Role Management

Login & Register aman

Role-based Access Control

Pemisahan role User dan IT Support

Session-based Authentication

Session Security Hardening


Ticket Management

Membuat pengaduan IT

Upload gambar/screenshot masalah

Sistem komentar & diskusi ticket

Assignment ticket ke IT Support

Manajemen status ticket:

Open

In Progress

Closed


Manajemen prioritas ticket

Filter & pencarian ticket


Fitur AI

AI Recommendation

IT Support dapat menghasilkan rekomendasi troubleshooting berbasis AI langsung dari detail ticket.

AI menganalisis:

Judul ticket

Kategori

Prioritas

Deskripsi masalah


Hasil AI meliputi:

Ringkasan masalah

Kemungkinan penyebab

Langkah troubleshooting

Rekomendasi tindakan IT Support


AI Summary

Generate ringkasan aktivitas ticket terbaru menggunakan AI.

AI Insight Dashboard

Memberikan insight operasional berbasis AI untuk tim IT Support.


---

Dashboard Analytics

Total ticket

Ticket sedang ditangani

Ticket selesai

Statistik Knowledge Base

Visualisasi volume ticket mingguan

Distribusi kategori ticket

Aktivitas ticket terbaru



---

Knowledge Base

Membuat artikel Knowledge Base

Kategorisasi artikel KB

Manajemen KB oleh IT Support

Akses KB oleh User



---

Export Reporting

Export laporan ticket ke CSV

Dukungan reporting enterprise-style



---

Activity Monitoring

Fitur monitoring aktivitas pengguna:

Tracking last login

Tracking last active

Deteksi Online/Offline

Monitoring aktivitas user oleh IT Support



---

Security Features

Login rate limiting

Session regeneration

CSRF protection

Validasi upload file

XSS prevention

Authorization validation

Middleware role-based access

Secure password hashing



---

Teknologi yang Digunakan

Backend

Laravel

PHP

MySQL


Frontend

Blade Template Engine

Bootstrap 5

Bootstrap Icons


AI Integration

Groq API

Large Language Model (LLM)



---

Alur AI Recommendation

1. User membuat ticket IT


2. IT Support membuka detail ticket


3. IT Support menekan tombol “Generate AI Suggestion”


4. AI menganalisis informasi ticket


5. AI menghasilkan rekomendasi troubleshooting


6. Hasil rekomendasi disimpan permanen di database


7. Rekomendasi dapat dilihat oleh IT Support maupun User




---

Role Pengguna

User

Membuat ticket

Melihat progress ticket

Memberikan komentar

Melihat rekomendasi AI

Mengakses Knowledge Base

Mengubah profile


IT Support

Mengelola ticket

Assignment ticket

Mengubah status ticket

Generate AI recommendation

Mengakses dashboard analytics

Monitoring aktivitas user

Mengelola Knowledge Base

Export laporan

---
Status Project

Status saat ini:

Core system completed

AI operational integration completed

Activity monitoring completed

Security hardening partially implemented

Enterprise-style dashboard implemented

---

Author

Dikembangkan sebagai sistem Helpdesk IT berbasis AI dengan pendekatan enterprise-style dashboard dan integrasi AI operational assistant.
