# Bank Transfer Service (Laravel)

## Deskripsi

Project ini merupakan implementasi **Bank Transfer Service** menggunakan **Laravel** sebagai bagian dari technical test.  
Sistem ini menangani transfer uang dari rekening perusahaan ke rekening tujuan dengan berbagai **aturan bisnis**, **jadwal transfer**, dan **prioritas bank**.

Fokus utama project:

-   Clean Architecture
-   Business Logic
-   Error Handling
-   Logging menggunakan SQLite

---

## Teknologi yang Digunakan

-   PHP 8.2+
-   Laravel 11
-   SQLite (khusus untuk logging transfer)
-   REST API

---

## Bank yang Didukung

| Bank         | Kode | Status          |
| ------------ | ---- | --------------- |
| Bank Alpha   | A01  | Terhubung       |
| Bank Beta    | B02  | Terhubung       |
| Bank Charlie | C03  | Terhubung       |
| Bank Delta   | D04  | Belum Terhubung |
| Bank Echo    | E05  | Belum Terhubung |
| Bank Fanta   | F06  | Belum Terhubung |

> Bank yang belum terhubung tetap bisa digunakan melalui **Online Transfer** menggunakan bank prioritas.

---

## Jenis Transfer

1. **Inhouse Transfer**  
   Transfer ke rekening pada bank yang sama (lebih cepat & murah)

2. **Online Transfer**  
   Transfer antar bank dengan aturan jadwal dan prioritas bank

---

## Mata Uang yang Didukung

-   IDR (Indonesian Rupiah)
-   USD (US Dollar)

---

## Jadwal Transfer Online & Prioritas Bank

| Waktu         | Prioritas Bank         | Keterangan                            |
| ------------- | ---------------------- | ------------------------------------- |
| 00:00 - 03:59 | ❌                     | Tidak ada transfer (ditunda ke 04:00) |
| 04:00 - 09:59 | Alpha → Beta → Charlie | Semua mata uang                       |
| 10:00 - 16:59 | Beta → Charlie → Alpha | Semua mata uang                       |
| 17:00 - 21:59 | Charlie → Alpha → Beta | **IDR saja**                          |
| 22:00 - 23:59 | Beta                   | Hanya Bank Beta                       |

**Catatan Khusus**

-   Jika bank prioritas pertama gagal → fallback ke bank berikutnya
-   Transfer USD pada jam 17:00–21:59 akan **ditunda ke jam 22:00**
-   Jika semua bank prioritas gagal → transaksi gagal

---

## Arsitektur & Desain

Project ini menggunakan pendekatan:

-   **DTO (Data Transfer Object)** untuk request & response
-   **Service Layer** untuk business logic
-   **Enum** untuk validasi data bisnis
-   **Interface Bank** untuk mendukung Open/Closed Principle

## Struktur utama:

    app/
    ├── DTO/
    ├── Services/
    ├── Banks/
    ├── Enums/
    └── Models/

## API Endpoint

### Transfer uang

POST /api/transfer

#### Request Body

```json
{
  "from_bank": "A01",
  "to_bank": "B02",
  "to_account": "123456789",
  "amount": 100000,
  "currency": "IDR",
  "time": "10:00"
}

  - Response Sukses:
  {
  "success": true,
  "message": "Transfer berhasil via bank B02",
  "scheduledAt": null
  }

  - Response Ditunda :
  {
  "success": false,
  "message": "Transfer ditunda",
  "scheduledAt": "2026-01-02T04:00:00"
  }

=> Test Scenario yang Diimplementasikan

      1. Transfer Inhouse Berhasil
      2. Transfer Online dengan Fallback Bank
      3. Transfer USD Ditunda
      4. Transfer di Luar Jam Operasional
      5. Semua skenario telah diuji dan berjalan sesuai requirement.


=> Logging Database (SQLite)
  Lokasi Database :
    database/database.sqlite
  Tabel :
    transfer_logs
  Kolom Utama :
    - from_bank
    - to_bank
    - amount
    - currency
    - status
    - scheduled_at
    - created_at

=> Cara Menjalankan Projek
    1. Install Dependency
        composer install

    2. Setup Environment
        php artisan key:generate

      - set database di .env:
        DB_CONNECTION=sqlite
        DB_DATABASE=database/database.sqlite
    3. Migrasi Database
        php artisan migrate
    4. Jalankan Server
        php artisan serve

=> Asumsi Yang Digunakan :

    - Tidak ada real bank API → kegagalan bank disimulasikan
    - Database tidak digunakan sebagai sumber transaksi utama
    - Semua request dianggap valid secara format (fokus pada business logic)

=> Catatan Akhir
  Project ini dibuat dengan fokus pada:

      - Struktur kode
      - Keterbacaan
      - Pemisahan tanggung jawab
      - Kesesuaian dengan requirement tes


Terima kasih 🙏
```
