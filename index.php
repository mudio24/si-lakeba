<?php
include 'templates/header.php';

?>      
  <h1 class="display-4">Laporan Kerusakan Barang?</h1>
  <p class="lead">Jangan ambil pusing! Sampaikan kepada kami.</p>
  <div class="jumbotron-search">
    <form action="search.php" method="POST" class="search-form">
      <div class="form-group mb-0">
        <label for="keyword" class="lead mb-2">Cek status pengaduan Anda</label>
        <div class="input-group">
          <input type="text" name="keyword" id="keyword" class="form-control search-input" placeholder="Masukkan nomor pengaduan Anda disini">
          <div class="input-group-append">
            <button type="submit" class="btn btn-primary search-button" value="cari">
              <span class="fas fa-search"></span>
            </button>
          </div>
        </div>
      </div>
    </form>
    <p class="lead mt-3 mb-2">atau ajukan pengaduan Anda</p>
    <a href="form-pengaduan.php" class="btn btn-primary sub-button">
      <span class="fas fa-chevron-right mr-2"></span>Disini
    </a>
  </div>
</div>
<div class="col-lg-6 text-center d-none d-lg-block">
  <img src="assets/img/homepage1.png" class="hero-img img-fluid" alt="Ilustrasi Pengaduan">
</div>

<div class="container main-content">
  <div class="features-section">
    <div class="row text-center">
      <div class="col-md-4 mb-3">
        <div class="feature-card p-4 rounded shadow-sm">
          <i class="fas fa-edit fa-2x mb-3 text-primary"></i>
          <h5>Lapor Kerusakan</h5>
          <p class="text-muted">Ajukan laporan kerusakan barang secara online dengan mudah</p>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="feature-card p-4 rounded shadow-sm">
          <i class="fas fa-sync fa-2x mb-3 text-primary"></i>
          <h5>Pantau Status</h5>
          <p class="text-muted">Lacak status pengaduan Anda kapan saja dengan nomor pengaduan</p>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="feature-card p-4 rounded shadow-sm">
          <i class="fas fa-check-circle fa-2x mb-3 text-primary"></i>
          <h5>Diproses Cepat</h5>
          <p class="text-muted">Pengaduan Anda akan diproses dengan cepat oleh tim teknis</p>
        </div>
      </div>
    </div>
  </div>

  <div class="text-center">
    <p>Created by <a href='https://www.instagram.com/io.dii/' title='instagram' target='_blank'>io.dii</a></p>
  </div>
</div>
<?php
include 'templates/footer.php';
?>