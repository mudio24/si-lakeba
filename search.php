<?php
include 'templates/header.php';
require 'function.php';
?>
        </div>
      </div>
    </div>
  </div>
  <!-- Close header-section from header.php -->

<style>
/* Hide empty header section */
.header-section {
  display: none !important;
}

/* Responsive Search Result Styles */
.search-page-container {
  background: linear-gradient(135deg, #f5f7fb 0%, #e4edf9 100%);
  min-height: 80vh;
  padding: 20px 0 40px 0;
  margin-top: 0;
}

.search-result-card {
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  margin-bottom: 2rem;
  background: #fff;
}

.search-result-card .card-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 1.25rem;
}

.search-result-card .card-header h5 {
  font-weight: 600;
}

.info-item {
  padding: 0.75rem;
  background: #f8f9fa;
  border-radius: 8px;
  margin-bottom: 0.5rem;
}

.info-label {
  font-weight: 600;
  color: #495057;
  font-size: 0.8rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 0.25rem;
}

.info-value {
  color: #212529;
  font-size: 1rem;
}

.status-badge {
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-size: 0.9rem;
  font-weight: 500;
}

.status-pending {
  background-color: #ffc107;
  color: #212529;
}

.status-process {
  background-color: #17a2b8;
  color: #fff;
}

.status-done {
  background-color: #28a745;
  color: #fff;
}

.keterangan-box {
  background-color: #f8f9fa;
  border-left: 4px solid #667eea;
  padding: 1rem;
  border-radius: 0 8px 8px 0;
  margin-top: 1rem;
}

.catatan-box {
  background-color: #e8f5e9;
  border-left: 4px solid #28a745;
  padding: 1rem;
  border-radius: 0 8px 8px 0;
  margin-top: 1rem;
}

.foto-section {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-left: 1px solid #dee2e6;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 2rem;
  min-height: 100%;
}

.foto-section img {
  max-width: 100%;
  max-height: 450px;
  border-radius: 8px;
  cursor: pointer;
  transition: transform 0.3s ease;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.foto-section img:hover {
  transform: scale(1.02);
}

.foto-section-title {
  font-weight: 600;
  color: #495057;
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 1rem;
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 0.75rem;
}

/* Mobile Responsive */
@media (max-width: 991px) {
  .info-grid {
    grid-template-columns: 1fr;
  }
  
  .search-page-title {
    font-size: 1.75rem !important;
  }
  
  .foto-section {
    border-left: none;
    border-top: 1px solid #dee2e6;
    padding: 1.5rem;
  }
}

/* Tablet */
@media (min-width: 768px) and (max-width: 991px) {
  .info-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
</style>

<div class="search-page-container">
  <div class="container-fluid px-4 px-lg-5">
    <h1 class="search-page-title text-center mb-4" style="font-size: 2.5rem; font-weight: bold; color: #333;">Status Pengaduan Anda</h1>
    
    <?php
      $keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';
      $data = query("SELECT p.*, pa.assigned_to, pa.assigned_at, u.name AS assigned_name FROM pengaduan p LEFT JOIN pengaduan_assignment pa ON pa.pengaduan_id = p.id LEFT JOIN user u ON u.user_id = pa.assigned_to WHERE p.id = '$keyword'");
      if ($data) {
      foreach ($data as $d) :
    ?>
      <!-- Single Card with 2 columns inside -->
      <div class="card search-result-card">
        <div class="card-header text-white">
          <h5 class="mb-0"><i class="fas fa-file-alt mr-2"></i>Detail Pengaduan #<?= $d['id']; ?></h5>
        </div>
        <div class="card-body p-0">
          <div class="row no-gutters">
            <!-- Left Column: Detail Info -->
            <div class="col-12 col-lg-7 p-4">
              <!-- Status Section -->
              <div class="text-center mb-4">
                <span class="info-label d-block mb-2">Status Pengaduan</span>
                <?php 
                  $status = $d['status'];
                  if ($status == 'Pending' || $status == 'Sedang diajukan') {
                    echo '<span class="status-badge status-pending"><i class="fas fa-clock mr-1"></i>'.$status.'</span>';
                  } elseif ($status == 'Diproses' || $status == 'Sedang diproses') {
                    echo '<span class="status-badge status-process"><i class="fas fa-spinner mr-1"></i>'.$status.'</span>';
                  } elseif ($status == 'Selesai' || $status == 'Selesai diproses') {
                    echo '<span class="status-badge status-done"><i class="fas fa-check-circle mr-1"></i>'.$status.'</span>';
                  } else {
                    echo '<span class="status-badge status-pending">'.$status.'</span>';
                  }
                ?>
              </div>
              
              <hr>
              
              <!-- Info Grid -->
              <div class="info-grid">
                <div class="info-item">
                  <div class="info-label"><i class="fas fa-calendar-alt mr-1"></i>Tanggal Pengaduan</div>
                  <div class="info-value"><?= date('d F Y', strtotime($d['tgl_lapor'])); ?></div>
                </div>
                
                <div class="info-item">
                  <div class="info-label"><i class="fas fa-user mr-1"></i>Nama Pelapor</div>
                  <div class="info-value"><?= $d['n_pelapor']; ?></div>
                </div>
                
                <div class="info-item">
                  <div class="info-label"><i class="fas fa-phone mr-1"></i>No. Telepon</div>
                  <div class="info-value"><?= isset($d['no_telp']) && $d['no_telp'] ? $d['no_telp'] : '-'; ?></div>
                </div>
                
                <div class="info-item">
                  <div class="info-label"><i class="fas fa-briefcase mr-1"></i>Jabatan</div>
                  <div class="info-value"><?= $d['j_pelapor']; ?></div>
                </div>
                
                <div class="info-item">
                  <div class="info-label"><i class="fas fa-building mr-1"></i>Departemen</div>
                  <div class="info-value"><?= $d['d_pelapor']; ?></div>
                </div>
                
                <div class="info-item">
                  <div class="info-label"><i class="fas fa-box mr-1"></i>Nama Barang</div>
                  <div class="info-value"><?= $d['n_barang']; ?></div>
                </div>
                
                <?php if (!empty($d['assigned_to'])): ?>
                <div class="info-item">
                  <div class="info-label"><i class="fas fa-user-check mr-1"></i>Ditindak Oleh</div>
                  <div class="info-value">
                    <span class="badge badge-info"><?= $d['assigned_name']; ?></span>
                    <small class="text-muted d-block mt-1"><?= date('d/m/Y H:i', strtotime($d['assigned_at'])); ?></small>
                  </div>
                </div>
                <?php endif; ?>
                
                <?php if (isset($d['estimasi_biaya']) && $d['estimasi_biaya'] !== null && $d['estimasi_biaya'] > 0): ?>
                <div class="info-item">
                  <div class="info-label"><i class="fas fa-money-bill-wave mr-1"></i>Estimasi Biaya</div>
                  <div class="info-value text-success font-weight-bold">Rp <?= number_format((float)$d['estimasi_biaya'], 0, ',', '.'); ?></div>
                </div>
                <?php endif; ?>
              </div>
              
              <!-- Keterangan -->
              <div class="keterangan-box">
                <div class="info-label"><i class="fas fa-info-circle mr-1"></i>Keterangan Kerusakan</div>
                <p class="mb-0 mt-2"><?= $d['ket']; ?></p>
              </div>
              
              <!-- Catatan Petugas -->
              <?php if (!empty($d['ket_petugas']) && $d['ket_petugas'] != '-'): ?>
              <div class="catatan-box">
                <div class="info-label"><i class="fas fa-comment-dots mr-1"></i>Catatan dari Petugas</div>
                <p class="mb-0 mt-2"><?= $d['ket_petugas']; ?></p>
              </div>
              <?php endif; ?>
            </div>
            
            <!-- Right Column: Foto Bukti (inside same card) -->
            <div class="col-12 col-lg-5 foto-section">
              <div class="foto-section-title"><i class="fas fa-camera mr-1"></i>Foto Bukti Kerusakan</div>
              <?php if (!empty($d['foto_bukti']) && file_exists('assets/img/bukti_kerusakan/' . $d['foto_bukti'])): ?>
                <img src="assets/img/bukti_kerusakan/<?= $d['foto_bukti']; ?>" 
                     alt="Foto Bukti Kerusakan" 
                     class="img-fluid mb-3"
                     onclick="window.open(this.src, '_blank');">
                <p class="text-muted text-center"><i class="fas fa-search-plus mr-1"></i>Klik gambar untuk memperbesar</p>
              <?php else: ?>
                <div class="text-center py-4">
                  <i class="fas fa-image fa-4x text-muted mb-3"></i>
                  <p class="text-muted">Foto bukti tidak tersedia</p>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    <?php
      endforeach;
      } else {
    ?>
      <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
          <div class="alert alert-warning text-center">
            <i class="fas fa-exclamation-triangle mr-2"></i>Data pengaduan dengan nomor <strong><?= htmlspecialchars($keyword); ?></strong> tidak ditemukan.
            <br><small>Pastikan Anda memasukkan nomor pengaduan yang benar.</small>
          </div>
        </div>
      </div>
    <?php
      }
    ?>
    
    <div class="text-center mt-4" style="border-top: none;">
      <a href="index.php" class="btn btn-primary btn-lg">
        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Beranda
      </a>
    </div>
  </div>
</div>

<div class="container mt-4 mb-3">
  <div class="text-center" style="border-top: none;">
    <p>Created by <a href='https://www.instagram.com/io.dii/' title='instagram' target='_blank'>io.dii</a></p>
  </div>
</div>

<?php
include 'templates/footer.php';
?>