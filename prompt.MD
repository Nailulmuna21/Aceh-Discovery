Aku ingin melanjutkan implementasi video pada homepage **Aceh Discovery** yang sekarang sudah menggunakan Laravel.

Aku sudah memiliki **4 file MP4** untuk bagian Sorotan Editorial, dan sekarang aku juga ingin **hero image utama di `.hero-main-frame` diganti menjadi video**.

## TUJUAN UTAMA

Aku ingin homepage terasa lebih hidup/cinematic, tetapi **tetap ringan**.

Ada **5 video total**:

### Hero

- Hero utama di `.hero-main-frame`

### Sorotan Editorial

1. Tari Saman
2. Kopi Gayo
3. Rumoh Aceh
4. Sabang

Prinsip performanya sama untuk semuanya:

> **Jangan download semua video ketika homepage pertama kali dibuka.**

Video hanya boleh mulai di-load ketika memang diperlukan berdasarkan posisi viewport.

---

# 1. HERO — `.hero-main-frame`

Saat ini `.hero-main-frame` menampilkan hero image statis.

Aku ingin **image tersebut diganti menjadi video**.

Namun, hero memiliki perlakuan sedikit berbeda dari Sorotan Editorial karena hero langsung terlihat ketika halaman pertama dibuka.

### Behavior yang diinginkan:

Saat initial page load:

```text
Homepage dibuka
        ↓
Hero poster/image langsung terlihat
        ↓
Hero video mulai di-load
        ↓
Hero video autoplay muted
        ↓
Hero video loop
```

Jadi **hanya video HERO yang boleh mulai loading pada initial page load**, karena memang hero berada di viewport sejak awal.

Tetapi **4 video Sorotan Editorial TIDAK boleh ikut ter-load**.

Dengan kata lain:

```text
Initial page load:

Hero video     → LOAD + PLAY
Tari Saman     → BELUM LOAD
Kopi Gayo      → BELUM LOAD
Rumoh Aceh     → BELUM LOAD
Sabang         → BELUM LOAD
```

Gunakan HTML5 `<video>` dengan:

```html
muted
autoplay
playsinline
loop
```

dan jangan tampilkan video controls.

Gunakan poster/image fallback agar hero tetap terlihat bagus ketika video belum siap atau gagal dimainkan.

---

# 2. SOROTAN EDITORIAL

Pertahankan 4 konten yang sudah ada:

1. **Tari Saman**
2. **Kopi Gayo**
3. **Rumoh Aceh**
4. **Sabang**

Area gambar masing-masing highlight diganti menjadi video.

Behavior:

```text
Initial page load
       ↓
Poster saja
       ↓
User scroll mendekati Sorotan Editorial
       ↓
Video yang relevan mulai di-load
       ↓
Autoplay + muted + loop
       ↓
User scroll melewati video
       ↓
Video pause
```

Jangan membuat keempat video langsung di-load.

Gunakan **Intersection Observer** untuk mendeteksi viewport.

---

# 3. LAZY LOADING — SANGAT PENTING

Aku ingin benar-benar menghindari kondisi seperti ini:

```text
Homepage dibuka
↓
browser langsung download:
tari-saman.mp4
kopi-gayo.mp4
rumoh-aceh.mp4
sabang.mp4
↓
bandwidth besar
↓
initial page load berat
```

Yang aku inginkan:

```text
Homepage dibuka
↓
Hero video → load/play
↓
Editorial videos → belum load
↓
User scroll
↓
Video yang mendekati viewport → baru load
↓
Video play
↓
Keluar viewport → pause
```

Gunakan pendekatan seperti:

```html
<video
    muted
    playsinline
    loop
    preload="none"
    poster="..."
    data-src="...">
</video>
```

Jangan langsung memasukkan MP4 ke `src` pada initial HTML untuk video editorial jika itu menyebabkan browser melakukan request sebelum Intersection Observer bekerja.

JavaScript kemudian memberikan `data-src` ke `src` ketika video sudah mendekati viewport.

Gunakan `rootMargin` sekitar `200px–400px` bila sesuai, sehingga video mulai loading sedikit sebelum terlihat tanpa melakukan preload seluruh halaman.

---

# 4. VIDEO YANG SUDAH AKU MILIKI

Aku sudah memiliki file MP4-nya.

Gunakan file yang aku berikan, **jangan mencari atau mengganti dengan video lain**.

Untuk sementara struktur storage Laravel:

```text
storage/app/public/editorial/
├── tari-saman.mp4
├── kopi-gayo.mp4
├── rumoh-aceh.mp4
└── sabang.mp4
```

Hero video akan berada di lokasi yang sesuai dengan asset/storage project saat ini.

Jangan memasukkan file MP4 ke database sebagai BLOB.

Kalau diperlukan database/configuration, simpan **path/URL**, bukan binary video.

---

# 5. POSTER / FALLBACK

Setiap video harus memiliki poster.

Untuk editorial:

```text
Tari Saman → poster Tari Saman
Kopi Gayo → poster Kopi Gayo
Rumoh Aceh → poster Rumoh Aceh
Sabang → poster Sabang
```

Untuk hero juga gunakan poster hero.

Poster ditampilkan sebelum video siap.

Jika video gagal:

- poster tetap terlihat,
- tidak ada broken element,
- layout tidak rusak.

---

# 6. HERO VS EDITORIAL

Tolong buat behavior yang jelas berbeda:

### HERO

Hero berada di viewport sejak awal:

```text
initial load
→ poster tampil
→ hero video load
→ autoplay
→ muted
→ loop
```

### EDITORIAL

Editorial belum berada di viewport:

```text
initial load
→ poster tampil
→ TIDAK load video

scroll mendekati section
→ video load
→ autoplay
→ muted
→ loop

keluar viewport
→ pause
```

Jangan sampai Intersection Observer membuat hero menunggu terlalu lama jika hero memang sudah terlihat.

---

# 7. REUSABLE JAVASCRIPT

Kalau memungkinkan, buat **satu reusable video lazy-loader** yang bisa menangani:

- hero video
- 4 editorial videos

Tetapi behavior hero tetap bisa dikonfigurasi berbeda dari editorial.

Jangan menulis lima implementasi JavaScript terpisah yang duplikatif.

Contohnya secara konsep:

```text
Video manager
├── Hero
│   └── load immediately because it is above the fold
│
└── Editorial videos
    ├── Tari Saman
    ├── Kopi Gayo
    ├── Rumoh Aceh
    └── Sabang
    └── load when approaching viewport
```

Gunakan struktur yang paling sesuai dengan arsitektur Laravel/Blade/JS project yang sekarang.

---

# 8. PERFORMANCE

Aku sangat mementingkan performa.

Jangan:

- download 4 editorial videos saat initial load,
- autoplay semua video sekaligus,
- preload video yang belum diperlukan,
- menyimpan MP4 sebagai database BLOB,
- mengubah MP4 menjadi base64,
- memasukkan video besar ke bundle JavaScript,
- membuat browser terus memainkan video yang jauh di luar viewport.

Kalau memungkinkan, ketika video editorial keluar viewport, lakukan:

```text
pause()
```

Dan jangan langsung menghapus `src` setiap kali keluar viewport jika itu justru menyebabkan video harus download ulang setiap kali user scroll naik-turun.

Pilih behavior yang paling efisien.

---

# 9. REDUCED MOTION

Hormati:

```css
@media (prefers-reduced-motion: reduce)
```

Jika user mengaktifkan reduced motion:

- jangan autoplay video,
- tampilkan poster,
- jangan membuat motion yang tidak diperlukan.

---

# 10. RESPONSIVE & ASPECT RATIO VIDEO

**PENTING:** Jangan menganggap keempat MP4 memiliki aspect ratio/orientasi yang sama.

Setiap video harus **mempertahankan komposisi dan aspect ratio aslinya**.

Jangan memaksa semua video menjadi:

```css
width: 100%;
height: 100%;
object-fit: cover;
```

jika itu menyebabkan bagian penting dari footage terpotong.

### Yang aku inginkan:

**Layout editorial tetap mengikuti struktur/design yang sekarang**, termasuk alternating layout kiri/kanan.

Tetapi **frame/container video harus menyesuaikan dengan karakter masing-masing MP4**.

Contohnya:

```text
Tari Saman
→ gunakan framing yang paling sesuai dengan aspect ratio footage Saman

Kopi Gayo
→ gunakan framing yang paling sesuai dengan aspect ratio footage Kopi Gayo

Rumoh Aceh
→ gunakan framing yang paling sesuai dengan aspect ratio footage Rumoh Aceh

Sabang
→ gunakan framing yang paling sesuai dengan aspect ratio footage Sabang
```

Jadi bukan:

> "semua video harus dipaksa masuk ke satu ukuran frame yang identik."

Melainkan:

> **"layout editorial tetap konsisten, tetapi visual video beradaptasi dengan aspect ratio asli masing-masing footage."**

### PRIORITAS

Urutan prioritas:

1. **Jangan crop bagian penting dari footage.**
2. Pertahankan aspect ratio asli video.
3. Pertahankan kualitas visual.
4. Buat ukuran visual tetap proporsional dengan area text di sebelahnya.
5. Hindari distorsi/stretching.
6. Jangan membuat video terlihat gepeng atau terlalu tinggi.
7. Jangan membuat layout menjadi berantakan.

Jika video landscape 16:9:

```text
┌─────────────────────────┐
│                         │
│         VIDEO           │
│                         │
└─────────────────────────┘
```

Jika video portrait/lebih tinggi:

```text
┌───────────────┐
│               │
│     VIDEO     │
│               │
│               │
└───────────────┘
```

Jika video memiliki aspect ratio yang berbeda, **container boleh memiliki tinggi yang berbeda** selama keseluruhan section tetap terlihat intentional dan rapi.

### JANGAN

Jangan melakukan hal-hal berikut:

- stretch video untuk memenuhi frame,
- crop ekstrem,
- memaksa semua video menjadi 16:9,
- memaksa semua video memiliki tinggi identik,
- menggunakan `object-fit: cover` secara membabi buta,
- mengubah aspect ratio video,
- menambahkan black bars besar jika sebenarnya layout bisa menyesuaikan secara natural.

Gunakan pendekatan CSS yang paling sesuai, misalnya:

```css
video {
    width: 100%;
    height: auto;
    display: block;
}
```

atau wrapper/aspect-ratio yang disesuaikan **per video** jika diperlukan.

**Jangan langsung menerapkan solusi CSS di atas tanpa melihat aspect ratio keempat MP4 terlebih dahulu.**

Sebelum implementasi, periksa:
- width,
- height,
- aspect ratio,
- orientation,
- duration

dari masing-masing MP4.

Kemudian tentukan framing terbaik untuk masing-masing video.

---

# 11. DESAIN EDITORIAL

Pertahankan identitas visual **Sorotan Editorial** yang sekarang.

Yang tetap:

- alternating layout kiri/kanan,
- hubungan visual antara video dan text,
- typography,
- warna,
- badge,
- metadata,
- judul,
- deskripsi,
- CTA,
- spacing utama.

Tetapi **area visual tidak harus identik ukurannya untuk setiap item**.

Aku justru ingin masing-masing footage terasa seperti memiliki karakter visual sendiri.

Contohnya:

**Tari Saman**
→ beri ruang yang cukup agar formasi dan gerakan para penari tidak terpotong.

**Kopi Gayo**
→ kalau footage berupa close-up/portrait, biarkan framing tersebut tetap terasa intimate.

**Rumoh Aceh**
→ beri ruang yang cukup untuk menunjukkan bentuk arsitektur rumah secara utuh.

**Sabang**
→ kalau footage underwater memiliki framing horizontal yang lebar, biarkan footage tersebut memanfaatkan ruang horizontalnya.

Jadi hasil akhirnya harus terasa seperti **editorial travel feature**, bukan empat video yang ditempel ke template yang sama secara paksa.

### Prinsip visual:

> **Consistent layout, flexible framing.**

Layout keseluruhan tetap satu sistem, tetapi **setiap video diperlakukan sesuai bentuk dan komposisi footage aslinya.**

---

# 12. MOBILE

Pada mobile, layout boleh berubah menjadi single-column seperti existing responsive behavior.

Pastikan aspect ratio asli setiap video tetap dipertahankan.

Jangan membuat video portrait menjadi landscape hanya demi menyamakan tinggi.

Jangan crop subjek utama hanya untuk membuat semua card identik.

Jika diperlukan, tinggi visual dapat berbeda antar editorial item pada mobile maupun desktop.

Yang penting:

- tidak overflow,
- tidak distorsi,
- tidak crop ekstrem,
- tetap rapi,
- tetap terasa sebagai satu desain.

---


Catat:

```text
filename
width
height
aspect ratio
orientation
duration
```

Kemudian gunakan hasil tersebut untuk menentukan layout/framing masing-masing video.

Kalau ada video portrait, square, ultrawide, atau aspect ratio yang tidak biasa, **jangan dipaksa menjadi 16:9**.

---

# HASIL YANG AKU INGINKAN

Empat editorial video tetap berada dalam satu sistem desain, tetapi tidak harus memiliki frame identik.

**Tari Saman → framing mengikuti footage Saman**  
**Kopi Gayo → framing mengikuti footage Kopi Gayo**  
**Rumoh Aceh → framing mengikuti footage Rumoh Aceh**  
**Sabang → framing mengikuti footage Sabang**

Dengan kata lain:

> **Jangan membuat video mengikuti template. Buat template yang cukup fleksibel untuk mengikuti video.**

Dan tetap pertahankan requirement performa sebelumnya:

**poster → lazy-load → autoplay muted → loop → pause saat keluar viewport.**

# 13. TESTING

Setelah implementasi, test dengan browser DevTools → Network.

Aku ingin kamu memastikan:

### Saat initial page load:

```text
Hero MP4
→ request/load

Tari Saman MP4
→ TIDAK request

Kopi Gayo MP4
→ TIDAK request

Rumoh Aceh MP4
→ TIDAK request

Sabang MP4
→ TIDAK request
```

Kemudian scroll ke Sorotan Editorial.

Pastikan video yang mendekati viewport mulai request/load dan autoplay.

Scroll keluar dari video:

```text
video → pause
```

Scroll kembali:

```text
video → play kembali
```

Pastikan tidak terjadi download ulang yang tidak perlu.

Test juga:

- desktop,
- mobile/responsive viewport,
- browser tanpa autoplay permission,
- `prefers-reduced-motion`,
- video gagal load,
- console tidak memiliki error.

---

# 14. SEBELUM CODING

Sebelum melakukan perubahan:

1. Cari implementation `.hero-main-frame`.
2. Cari implementation section `Sorotan Editorial`.
3. Cari CSS dan JavaScript yang mengatur kedua bagian tersebut.
4. Pahami struktur Laravel/Blade project yang sekarang.
5. Pastikan lokasi storage dan `storage:link` yang digunakan project.
6. Jangan membuat struktur baru jika existing architecture sudah menyediakan tempat yang sesuai.

Setelah itu baru implementasikan.

---

# HASIL AKHIR YANG AKU INGINKAN

Homepage memiliki **5 cinematic videos**:

**Hero**
→ langsung load/play karena berada di atas fold.

**Tari Saman**
→ lazy-load saat mendekati viewport.

**Kopi Gayo**
→ lazy-load saat mendekati viewport.

**Rumoh Aceh**
→ lazy-load saat mendekati viewport.

**Sabang**
→ lazy-load saat mendekati viewport.

Prinsip utamanya:

> **Homepage harus terasa hidup, tetapi tidak boleh membayar bandwidth untuk video yang belum dilihat user.**

Setelah selesai, jelaskan:

1. file yang diubah,
2. lokasi video,
3. mekanisme lazy loading,
4. mekanisme autoplay/pause,
5. perbedaan behavior hero dan editorial,
6. hasil pengecekan Network pada initial page load.
