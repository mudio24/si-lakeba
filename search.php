<?php
include 'templates/header.php';
require 'function.php';
?>      

<div class="row align-items-center">
  <div class="col-lg-10 hero-content">      
    <div class="container main-content">
      <h1 class="display-4 mb-4">Status Pengaduan Anda</h1>
      
      <?php
        $keyword = $_POST['keyword'];
        $data = query("SELECT p.*, pa.assigned_to, pa.assigned_at, u.name AS assigned_name FROM pengaduan p LEFT JOIN pengaduan_assignment pa ON pa.pengaduan_id = p.id LEFT JOIN user u ON u.user_id = pa.assigned_to WHERE p.id = '$keyword'");
        if ($data) {
        foreach ($data as $d) :
      ?>
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Detail Pengaduan #<?= $d['id']; ?></h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <p><strong>Tanggal Pengaduan:</strong> <?= $d['tgl_lapor']; ?></p>
                <p><strong>Nama Pelapor:</strong> <?= $d['n_pelapor']; ?></p>
                <p><strong>Jabatan:</strong> <?= $d['j_pelapor']; ?></p>
                <p><strong>Departemen:</strong> <?= $d['d_pelapor']; ?></p>
              </div>
              <div class="col-md-6">
                <p><strong>Nama Barang:</strong> <?= $d['n_barang']; ?></p>
                <p><strong>Status:</strong> 
                  <?php 
                    $status = $d['status'];
                    if ($status == 'Pending' || $status == 'Sedang diajukan') {
                      echo '<span class="badge badge-secondary">'.$status.'</span>';
                    } elseif ($status == 'Diproses' || $status == 'Sedang diproses') {
                      echo '<span class="badge badge-primary">'.$status.'</span>';
                    } elseif ($status == 'Selesai' || $status == 'Selesai diproses') {
                      echo '<span class="badge badge-success">'.$status.'</span>';
                    } else {
                      echo '<span class="badge badge-secondary">'.$status.'</span>';
                    }
                  ?>
                </p>
                <?php if (!empty($d['assigned_to'])): ?>
                <p><strong>Ditindak oleh:</strong> <span class="badge badge-info"><?= $d['assigned_name']; ?></span><br>
                  <small><?= date('d/m/Y H:i', strtotime($d['assigned_at'])); ?></small>
                </p>
                <?php endif; ?>
              </div>
            </div>
            
            <div class="form-group">
              <label><strong>Keterangan:</strong></label>
              <p class="border p-3 rounded"><?= $d['ket']; ?></p>
            </div>
            
            <?php if (!empty($d['ket_petugas'])): ?>
            <div class="form-group bg-light p-3 rounded">
              <label><strong>Catatan dari petugas:</strong></label>
              <p><?= $d['ket_petugas']; ?></p>
            </div>
            <?php endif; ?>
          </div>
        </div>
      <?php
        endforeach;
        } else {
            echo"<div class='alert alert-warning text-center'>Data pengaduan tidak ditemukan.</div>";
        }
      ?>
      
      <div class="text-center mt-3">
        <form action="index.php" method="get">
          <button type="submit" class="btn btn-primary">
            <span class="fas fa-arrow-left mr-2"></span>Kembali ke Beranda
          </button>
        </form>
      </div>
    </div>
  </div>   


<div class="container main-content">
  <div class="text-center">
    <p>Created by <a href='https://www.instagram.com/io.dii/' title='instagram' target='_blank'>io.dii</a></p>
  </div>
</div>

<?php
include 'templates/footer.php';
?>