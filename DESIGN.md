# Design System — GASKEUN

Dokumen ini adalah acuan desain untuk seluruh halaman/komponen sistem **GASKEUN** (Galeri Alokasi dan Sistem Kendali Elpiji Untuk Nilai Sasaran). Gunakan file ini sebagai referensi saat membangun view Blade, komponen, atau styling Tailwind di project Laravel, supaya seluruh tampilan konsisten antar modul (Super Admin, Disperindag, Agen LPG, Pangkalan LPG, Hiswana Migas, Pimpinan Daerah, Publik).

**Gaya desain:** Modern govtech — bersih, profesional, data-focused, tanpa ilustrasi/foto dekoratif. Visual dibangun murni dari tipografi, warna, ikon line-style, dan bentuk geometris sederhana.

---

## 1. Palet Warna

### Warna Primer
| Token | Hex | Penggunaan |
|---|---|---|
| `primary` | `#22B573` | Sidebar aktif, tombol utama, ikon aktif, garis aksen, elemen brand |
| `primary-dark` | `#178A57` | Hover state tombol primer, sidebar latar (jika ingin sedikit lebih gelap dari primary) |
| `primary-light` | `#E6F7EF` | Latar highlight/selected row, badge sukses versi pudar, card rekomendasi |

### Warna Status (Badge)
| Token | Hex | Arti |
|---|---|---|
| `status-success` | `#059669` | Aman / Aktif / Selesai / Terima Sesuai |
| `status-warning` | `#D97706` | Waspada / Menipis / Pending |
| `status-danger` | `#DC2626` | Kritis / Kosong / Nonaktif / Aksi destruktif (Hapus, Nonaktifkan) |

### Warna Netral
| Token | Hex | Penggunaan |
|---|---|---|
| `bg-page` | `#F8FAFC` | Latar area konten utama |
| `bg-card` | `#FFFFFF` | Latar card, tabel, modal |
| `border` | `#E2E8F0` | Border tabel, input, divider |
| `text-primary` | `#1E293B` | Teks judul, heading |
| `text-secondary` | `#64748B` | Teks deskripsi, label, sub-teks |
| `text-disabled` | `#CBD5E1` | Teks/ikon nonaktif |

### Warna Role (Badge Role — versi pastel/soft)
| Role | Hex (badge bg) | Hex (badge text) |
|---|---|---|
| Super Admin | `#E6F7EF` | `#178A57` |
| Disperindag ESDM | `#DBEAFE` | `#1D4ED8` |
| Agen LPG | `#FEF3C7` | `#B45309` |
| Pangkalan LPG | `#FFEDD5` | `#C2410C` |
| Hiswana Migas | `#EDE9FE` | `#6D28D9` |
| Pimpinan Daerah | `#FEE2E2` | `#B91C1C` |

---

## 2. Tipografi

- **Font:** Sans-serif modern, gaya **Inter** (fallback: `ui-sans-serif, system-ui`)
- **Heading (h1/judul halaman):** bold, ukuran 24-28px
- **Sub-heading (judul card):** semibold, 16-18px
- **Body text:** regular, 14px
- **Label kecil / caption:** regular, 12px, warna `text-secondary`
- **Angka statistik besar (dashboard):** bold, 28-32px

---

## 3. Spacing, Radius & Shadow

- **Border radius:** 8-12px untuk card, tombol, input, badge (badge/pill pakai `rounded-full`)
- **Shadow standar:** tipis dan halus — `0 1px 3px rgba(0,0,0,0.08)`
- **Padding card:** 20px
- **Spacing antar elemen/section:** lega, minimal 16-24px

---

## 4. Layout Shell (Wajib Identik di Semua Halaman Internal)

### Sidebar
- Fixed kiri, lebar **240px**
- Latar warna `primary` (`#22B573`) atau versi lebih gelap solid
- Bagian atas: logo teks **"GASKEUN"** bold putih + subtitle kecil sesuai role (misal "Super Admin Panel")
- Menu vertikal dengan ikon line-style putih di kiri tiap label
- Menu aktif: latar putih transparan ~15% + garis aksen kiri putih tebal 3px
- Bagian **paling bawah** sidebar (terpisah dari menu utama): card kecil profil user (avatar + nama + badge role) + tombol "Keluar" dengan ikon logout

### Topbar
- Fixed atas, tinggi **64px**, latar putih, border bawah tipis (`border`)
- Kiri: judul halaman atau breadcrumb
- Tengah/kanan: search bar (placeholder Bahasa Indonesia)
- Kanan: ikon lonceng notifikasi (badge angka merah jika ada), ikon gear/pengaturan, teks role + avatar user

### Daftar Menu Super Admin (acuan urutan tetap)
1. Dashboard
2. Kelola User
3. Role & Hak Akses
4. Master Data
5. Peta GIS
6. Heatmap Kelangkaan
7. Log Aktivitas

---

## 5. Komponen

### Tombol
- **Primary:** latar `primary` solid, teks putih bold, radius 8-12px, hover → `primary-dark`
- **Outline:** border `primary`, teks `primary`, latar transparan, hover → latar `primary-light`
- **Destructive:** border/teks `status-danger`, hover → latar merah muda pudar (`#FEE2E2`)
- **Full-width** untuk tombol utama di form/modal

### Badge / Pill Status
- Bentuk `rounded-full`, padding horizontal 10-12px, padding vertikal 2-4px
- Warna sesuai tabel status di atas (Section 1)

### Tabel
- Header: latar abu-abu sangat terang, teks bold kecil, uppercase opsional
- Border tipis antar baris (`border`)
- Hover state baris: latar sedikit lebih terang dari `bg-page`
- Kolom "Aksi": ikon pensil (edit, abu-abu → hover `primary`), ikon trash (hapus, `status-danger`), area klik lingkaran 32x32px dengan hover background lembut
- Footer tabel: info jumlah data + pagination

### Card
- Latar putih, radius 10px, shadow standar, padding 20px
- Header card: ikon kecil + judul section (contoh: ikon info + "Informasi Umum")

### Modal / Overlay
- Muncul di TENGAH layar, di atas halaman yang sama (bukan screen kosong terpisah)
- Background halaman di belakang modal: dim overlay hitam ~40% opacity + sedikit blur
- Modal: latar putih, radius 10-12px, shadow tegas, header berisi judul + tombol close (X), footer berisi tombol "Batal" (outline) dan aksi utama (primary/destructive)

### Form
- Label di atas input, input dengan border tipis, radius 8px, ikon pendukung di dalam field jika relevan (user, gembok, dsb)
- Dropdown/select bergaya sama dengan input text
- Toggle switch untuk status Aktif/Nonaktif dan permission (ON = `primary`, OFF = abu-abu)

### Peta (GIS & Heatmap)
- **Tidak pernah** memakai screenshot peta realistis (Google Maps asli, dsb)
- Representasi flat/minimalis: area dengan garis tipis membentuk kontur/grid wilayah
- Marker lokasi: lingkaran solid berwarna dengan shadow tipis (hijau/kuning/merah sesuai status)
- Heatmap: choropleth per kecamatan (fill polygon warna solid opacity ~70%), bukan marker titik

### Ikon
- Line-style/outline (gaya Feather Icons atau Material Icons Outlined), ukuran standar 18-20px

---

## 6. Contoh Implementasi Tailwind Config

```js
// tailwind.config.js
module.exports = {
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#22B573',
          dark: '#178A57',
          light: '#E6F7EF',
        },
        status: {
          success: '#059669',
          warning: '#D97706',
          danger: '#DC2626',
        },
      },
      borderRadius: {
        card: '10px',
        DEFAULT: '8px',
      },
      boxShadow: {
        card: '0 1px 3px rgba(0,0,0,0.08)',
      },
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
      },
    },
  },
}
```

---

## 7. Catatan Penggunaan

- Setiap halaman/screen baru **wajib** memakai shell Sidebar + Topbar identik seperti di Section 4 — jangan buat versi shell baru per halaman.
- Warna hijau utama sistem adalah **`#22B573`** (bukan hijau tua/teal generik) — gunakan token `primary` ini secara konsisten di seluruh Blade component/CSS.
- Dokumen ini sebaiknya disimpan di root project (`design.md`) dan dijadikan acuan saat membuat Blade component baru (`resources/views/components/`) agar tim tetap konsisten meski dikerjakan paralel oleh beberapa developer.