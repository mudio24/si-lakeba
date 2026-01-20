<?php
include "templates/header-report.php";

// Check if user is admin (user_id = '1')
if ($_SESSION["login"]["user_id"] !== '1') {
    echo "<script>alert('Mohon maaf Ini Bukan secangkir kopi anda'); window.location='index.php';</script>";
    exit;
}

include "templates/sidebar-report.php";
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Report Tahunan</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Report</a></li>
              <li class="breadcrumb-item active">Report Tahunan</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

    <!-- Filter Card -->
    <div class="card card-warning">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-filter mr-2"></i>Filter Report Tahunan</h3>
        </div>
        <form method="POST" action="report-tahunan.php">
          <div class="card-body">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="year1">Tahun Awal</label>
                  <select class="form-control" id="year1" name="year1" required>
                    <option value="">-- Pilih Tahun Awal --</option>
                    <?php 
                      $currentYear = date('Y');
                      for ($y = $currentYear; $y >= 2020; $y--) {
                        echo "<option value='$y'>$y</option>";
                      }
                    ?>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="year2">Tahun Akhir</label>
                  <select class="form-control" id="year2" name="year2" required>
                    <option value="">-- Pilih Tahun Akhir --</option>
                    <?php 
                      $currentYear = date('Y');
                      for ($y = $currentYear; $y >= 2020; $y--) {
                        echo "<option value='$y'>$y</option>";
                      }
                    ?>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>&nbsp;</label>
                  <button type="submit" class="btn btn-warning btn-block">
                    <i class="fas fa-search mr-2"></i>Filter
                  </button>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>&nbsp;</label>
                  <a href="report-tahunan.php" class="btn btn-secondary btn-block">
                    <i class="fas fa-redo mr-2"></i>Reset
                  </a>
                </div>
              </div>
            </div>
          </div>
        </form>
    </div>
    <!-- /.card -->

    <div class="card">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-book mr-3"></i>Data Pengaduan Kerusakan Barang</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <style>
          @media print {
            .d-none, .d-print-none { display: table-cell !important; }
            .no-print { display:none !important; }
            table, thead, tbody, th, td, tr { page-break-inside: avoid; }
          }
          </style>
          <div class="table-responsive">
            <table class="table table-hover" id="table-report" width="100%">
              <thead align="center">
                <th>No.</th>
                <th>Nama Pegawai</th>
                <th>No. Telepon</th>
                <th>Jabatan/Pangkat</th>
                <th>Departemen</th>
                <th>Nama Barang</th>
                <th>Keterangan</th>
                <th>Status</th>
                <th>Catatan</th>
                <th>Tgl</th>
                <th>Estimasi Biaya</th>
                <th>Ditindak Oleh</th>
                
              </thead>
              <tbody align="center">
                <?php
                $year1 = $_POST['year1'];
                $year2 = $_POST['year2'];
                $data = query("SELECT p.*, COALESCE(u.name, '-') as ditindak_oleh FROM pengaduan p LEFT JOIN pengaduan_assignment pa ON p.id = pa.pengaduan_id LEFT JOIN user u ON pa.assigned_to = u.user_id WHERE YEAR(p.tgl_lapor) BETWEEN '$year1' AND '$year2' ORDER BY p.tgl_lapor DESC");
                foreach ($data as $d) :
                ?>
                <tr>
                  <td><?= $d['id']; ?></td>
                  <td><?= $d['n_pelapor']; ?></td>
                  <td><?= isset($d['no_telp']) ? $d['no_telp'] : '-'; ?></td>
                  <td><?= $d['j_pelapor']; ?></td>
                  <td><?= $d['d_pelapor']; ?></td>
                  <td><?= $d['n_barang']; ?></td>
                  <td><?= $d['ket']; ?></td>
                  <td><?= $d['status']; ?></td>
                  <td><?= $d['ket_petugas']; ?></td>
                  <td><?= $d['tgl_lapor']; ?></td>
                  <td><?= $d['ditindak_oleh']; ?></td>
                  <td><?= (isset($d['estimasi_biaya']) && $d['estimasi_biaya'] !== null && $d['estimasi_biaya'] !== '') ? 'Rp ' . number_format((float)$d['estimasi_biaya'], 0, ',', '.') : '-'; ?></td>

                </tr>
                <?php
                endforeach;
                ?>
              </tbody>
                <th>No.</th>
                <th>Nama Pegawai</th>
                <th>No. Telepon</th>
                <th>Jabatan</th>
                <th>Departemen</th>
                <th>Nama Barang</th>
                <th>Keterangan</th>
                <th>Status</th>
                <th>Catatan</th>
                <th>Tgl</th>
                <th>Estimasi Biaya</th>
                <th>Ditindak Oleh</th>
                
                
            </table>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
<?php
include "templates/footer.php";
?>