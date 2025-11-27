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
                <th>Jabatan/Pangkat</th>
                <th>Departemen</th>
                <th>Nama Barang</th>
                <th>Keterangan</th>
                <th>Status</th>
                <th>Catatan</th>
                <th>Tgl</th>
                <th>Estimasi Biaya</th>
              </thead>
              <tbody align="center">
                <?php
                $year1 = $_POST['year1'];
                $year2 = $_POST['year2'];
                $data = query("SELECT * FROM pengaduan WHERE YEAR(tgl_lapor) BETWEEN '$year1' AND '$year2' ORDER BY tgl_lapor DESC");
                foreach ($data as $d) :
                ?>
                <tr>
                  <td><?= $d['id']; ?></td>
                  <td><?= $d['n_pelapor']; ?></td>
                  <td><?= $d['j_pelapor']; ?></td>
                  <td><?= $d['d_pelapor']; ?></td>
                  <td><?= $d['n_barang']; ?></td>
                  <td><?= $d['ket']; ?></td>
                  <td><?= $d['status']; ?></td>
                  <td><?= $d['ket_petugas']; ?></td>
                  <td><?= $d['tgl_lapor']; ?></td>
                  <td><?= (isset($d['estimasi_biaya']) && $d['estimasi_biaya'] !== null && $d['estimasi_biaya'] !== '') ? 'Rp ' . number_format((float)$d['estimasi_biaya'], 0, ',', '.') : '-'; ?></td>
                </tr>
                <?php
                endforeach;
                ?>
              </tbody>
              <tfoot align="center">
                <th>No.</th>
                <th>Nama Pegawai</th>
                <th>Jabatan</th>
                <th>Departemen</th>
                <th>Nama Barang</th>
                <th>Keterangan</th>
                <th>Status</th>
                <th>Catatan</th>
                <th>Tgl</th>
                <th>Estimasi Biaya</th>
              </tfoot>
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