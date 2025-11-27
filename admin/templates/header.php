<?php
session_start();
if (!isset($_SESSION["login"])) {
  header("Location: ../auth/login.php");
}
include '../function.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SI-LAKEBA</title>
  <link rel="icon" href="../assets/dist/img/setda-logo5.png">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="../assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
  <!-- jQuery UI CSS removed (not used) -->
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    <?php
    $newCount = countNewReports();
    ?>
    <ul class="navbar-nav ml-auto">
      <!-- Notifikasi Laporan Baru (ikon lonceng) -->
      <li class="nav-item">
        <a class="nav-link" href="#" role="button" title="Laporan Baru" data-toggle="modal" data-target="#notificationModal" style="cursor: pointer;">
          <i class="far fa-bell"></i>
          <?php if ($newCount > 0): ?>
            <span class="badge badge-danger navbar-badge"><?php echo $newCount; ?></span>
          <?php endif; ?>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt mr-2"></i>Fullscreen
        </a>
      </li>
    </ul>
  </nav>
  
  <!-- /.navbar -->
  
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <img src="../assets/dist/img/setda-logo5.png" alt="SI-PEKERBA" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light" style="font-size: 19px;">SI-LAKEBA</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <?php
          $id = $_SESSION["login"]["user_id"];
          $data = query("SELECT * FROM user WHERE user_id = '$id'")[0];
          ?>
          <img src="../assets/img/profile/<?= $data['img'];?> " class="img-circle" alt="User Image">
        </div>
        <div class="info">
          <a class="d-block" style="cursor: default; margin-top:-12px;"><?php echo  $_SESSION["login"]["name"] ?></a>
          <a class="d-block" style="cursor: default;"><?php echo  $_SESSION["login"]["user_id"] ?></a>
        </div>
      </div>

    <!-- Modal Notifikasi -->
    <div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="notificationModalLabel">
              <i class="fas fa-bell mr-2"></i>Pengaduan Baru
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <?php 
            $pengaduan_baru = query("SELECT * FROM pengaduan WHERE status='Sedang diajukan' ORDER BY tgl_lapor DESC LIMIT 10");
            if (!empty($pengaduan_baru)): 
            ?>
              <div class="table-responsive">
                <table class="table table-hover table-sm">
                  <thead class="bg-light">
                    <tr>
                      <th>No. Pengaduan</th>
                      <th>Pelapor</th>
                      <th>Barang</th>
                      <th>Tanggal</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($pengaduan_baru as $p): ?>
                    <tr>
                      <td><strong><?= $p['id']; ?></strong></td>
                      <td><?= $p['n_pelapor']; ?></td>
                      <td><?= $p['n_barang']; ?></td>
                      <td><?= date('d/m/Y', strtotime($p['tgl_lapor'])); ?></td>
                      <td>
                        <a href="detail-pengaduan.php?id=<?= $p['id']; ?>" class="btn btn-sm btn-outline-primary" style="padding: 4px 8px; font-size: 12px;">
                          <i class="fas fa-eye"></i> Lihat
                        </a>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php else: ?>
              <div class="alert alert-info">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Tidak ada pengaduan baru.</strong> Semua pengaduan telah diproses.
              </div>
            <?php endif; ?>
          </div>
          <div class="modal-footer">
            <a href="data-pengaduan.php" class="btn btn-primary">
              <i class="fas fa-list mr-2"></i>Lihat Semua Pengaduan
            </a>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
    <!-- End Modal Notifikasi -->
