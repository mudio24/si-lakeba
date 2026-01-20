<?php
include 'templates/header2.php';
require 'function.php';

if (isset($_POST['submit'])) {
    if (insertPengaduan($_POST) > 0) {
        // Ambil data yang baru saja diinput
        $id = $_POST['id'];
        $nama = $_POST['nama'];
        $no_telp = $_POST['no_telp'];
        $jabatan = $_POST['jabatan'];
        $dept = $_POST['dept'];
        $nama_barang = $_POST['nama_barang'];
        $ket = $_POST['ket'];
        $tanggal = date('Y-m-d H:i:s');
        
        // Buat konten file download
        $content = "===== BUKTI PENGADUAN =====\n\n";
        $content .= "Nomor Pengaduan: " . $id . "\n";
        $content .= "Tanggal: " . $tanggal . "\n";
        $content .= "Nama Pelapor: " . $nama . "\n";
        $content .= "No. Telepon: " . $no_telp . "\n";
        $content .= "Jabatan: " . $jabatan . "\n";
        $content .= "Departemen: " . $dept . "\n";
        $content .= "Nama Barang: " . $nama_barang . "\n";
        $content .= "Keterangan: " . $ket . "\n\n";
        $content .= "===========================\n";
        $content .= "Simpan bukti ini untuk pengecekan status pengaduan.\n";
        $content .= "Cek status di: [http://localhost/pkl/si-lakeba/index.php]\n";
        
        // Encode konten untuk JavaScript
        $encodedContent = base64_encode($content);
        $filename = "Pengaduan_" . $id . ".txt";
        
        echo "<script>
            // Auto download file
            var content = atob('" . $encodedContent . "');
            var blob = new Blob([content], { type: 'text/plain' });
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = '" . $filename . "';
            link.click();
            
            // Redirect setelah download
            setTimeout(function() {
                alert('Data pengaduan Anda berhasil terkirim dan bukti telah diunduh.');
                window.location='index.php';
            }, 500);
        </script>";
    } else {
        echo "<script>alert('Data pengaduan Anda gagal terkirim.'); window.location='form-pengaduan.php';</script>";
    }
}

$query = mysqli_query($conn, "SELECT max(id) as kodeTerbesar FROM pengaduan");
$r = mysqli_fetch_array($query);
$kodeBarang = $r['kodeTerbesar'];

// mengambil angka dari kode barang terbesar, menggunakan fungsi substr
// dan diubah ke integer dengan (int)
$urutan = (int) substr($kodeBarang, 4, 4);

// bilangan yang diambil ini ditambah 1 untuk menentukan nomor urut berikutnya
$urutan++;

// membentuk kode barang baru
// perintah sprintf("%03s", $urutan); berguna untuk membuat string menjadi 3 karakter
// misalnya perintah sprintf("%03s", 15); maka akan menghasilkan '015'
// angka yang diambil taji digabungkan dengan kode huruf yang kita inginkan, misalnya BRG 
$huruf = "NP";
$kodeBarang = $huruf . sprintf("%04s", $urutan);

// Daftar departemen Setda Kota Cirebon
$departemen = [
    "Sekretariat Daerah",
    "Bagian Umum",
    "Bagian Perlengkapan",
    "Bagian Organisasi",
    "Bagian Hukum",
    "Bagian Hubungan Masyarakat",
    "Bagian Protokol",
    "Bagian Kesejahteraan Rakyat",
    "Bagian Perekonomian",
    "Bagian Administrasi Pembangunan",
    "Bagian Keuangan"
];
?>      
  <h1 class="mb-3">Form Laporan Kerusakan Barang</h1>
  <div class="card shadow-sm">
    <div class="card-body">
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-row">
          <div class="form-group col-md-12">
            <label for="id">Nomor Pengaduan</label>
            <input type="text" name="id" id="id" class="form-control" value="<?= $kodeBarang; ?>" style="cursor: default;" readonly>
            <small class="form-text text-muted">
              <span style="color: red;">*</span>Harap catat kode ini untuk melakukan pengecekan sendiri melalui kolom pencarian.
            </small>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group col-md-12">
            <label for="nama">Nama Pelapor</label>
            <input type="text" name="nama" id="nama" class="form-control" required>
          </div>
          
          <div class="form-group col-md-12">
            <label for="no_telp">Nomor Telepon</label>
            <input type="tel" name="no_telp" id="no_telp" class="form-control" placeholder="Contoh: 081234567890" required>
            <small class="form-text text-muted">Masukkan nomor telepon yang bisa dihubungi</small>
          </div>
          
          <div class="form-group col-md-12">
            <label for="jabatan">Jabatan Pelapor</label>
            <input type="text" name="jabatan" id="jabatan" class="form-control" required>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group col-md-12">
            <label for="dept">Setda Bagian</label>
            <select name="dept" id="dept" class="form-control" required style="height: 50px;">
                <option value="">-- Pilih Bagian --</option>
                <?php foreach ($departemen as $dept): ?>
                    <option value="<?= $dept; ?>"><?= $dept; ?></option>
                <?php endforeach; ?>
            </select>
          </div>
          
          <div class="form-group col-md-12">
            <label for="nama_barang">Nama Barang</label>
            <input type="text" name="nama_barang" id="nama_barang" class="form-control" required>
          </div>
        </div>
        
        <div class="form-group">
          <label for="ket">Keterangan</label>
          <textarea name="ket" id="ket" class="form-control" rows="4" required></textarea>
        </div>
        
        <div class="form-group">
          <label for="foto_bukti">Upload Foto Bukti Kerusakan</label>
          <input type="file" name="foto_bukti" id="foto_bukti" class="form-control-file" accept="image/*" required>
          <small class="form-text text-muted">
            <span style="color: red;">*</span>Format: JPG, JPEG, PNG. Maksimal 2MB.
          </small>
          <div id="preview-container" class="mt-3" style="display: none;">
            <img id="preview-image" src="#" alt="Preview" class="img-thumbnail" style="max-width: 300px; max-height: 300px;">
          </div>
        </div>
        
        <div class="form-group mb-0">
          <button class="btn btn-success mr-3" type="submit" name="submit">
            <span class="fas fa-paper-plane mr-2"></span>Kirim
          </button>
          <button class="btn btn-danger" type="reset" name="reset">
            <span class="fas fa-undo mr-2"></span>Reset Form
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Kolom Gambar (Kanan) -->
<div class="col-lg-6 text-center d-none d-lg-block">
  <img src="assets/img/homepage2.png" class="hero-img img-fluid" alt="Ilustrasi Pengaduan">
</div>

<div class="container main-content">
  <div class="text-center">
    <p>Created by <a href='https://www.instagram.com/io.dii/' title='instagram' target='_blank'>io.dii</a></p>
  </div>
</div>

<script>
// Preview gambar sebelum upload
document.getElementById('foto_bukti').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Validasi ukuran file (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimal 2MB.');
            this.value = '';
            document.getElementById('preview-container').style.display = 'none';
            return;
        }
        
        // Validasi tipe file
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            alert('Format file tidak didukung! Gunakan JPG, JPEG, atau PNG.');
            this.value = '';
            document.getElementById('preview-container').style.display = 'none';
            return;
        }
        
        // Tampilkan preview
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
            document.getElementById('preview-container').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});

// Reset preview saat form di-reset
document.querySelector('button[type="reset"]').addEventListener('click', function() {
    document.getElementById('preview-container').style.display = 'none';
});
</script>

<?php
include 'templates/footer.php';
?>