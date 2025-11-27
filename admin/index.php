<?php
include "templates/header.php";
include "templates/sidebar-home.php";

// Query untuk statistik
$total_pengaduan = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pengaduan"));
$sedang_diajukan = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pengaduan WHERE status='Sedang diajukan'"));
$sedang_diproses = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pengaduan WHERE status='Sedang diproses'"));
$selesai_diproses = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pengaduan WHERE status='Selesai diproses'"));

// Query untuk total biaya bulan ini
$query_biaya = mysqli_query($conn, "SELECT SUM(estimasi_biaya) as total_biaya 
    FROM pengaduan 
    WHERE estimasi_biaya IS NOT NULL 
      AND MONTH(tgl_lapor) = MONTH(CURDATE()) 
      AND YEAR(tgl_lapor) = YEAR(CURDATE())
");
$result_biaya = mysqli_fetch_assoc($query_biaya);
$total_biaya = $result_biaya['total_biaya'] ?? 0;

// Query untuk statistik per departemen
$stat_dept = query("SELECT d_pelapor, COUNT(*) as jumlah FROM pengaduan GROUP BY d_pelapor ORDER BY jumlah DESC");

// Query untuk trend bulanan (6 bulan terakhir)
$trend_bulan = query("SELECT DATE_FORMAT(tgl_lapor, '%Y-%m') as bulan, COUNT(*) as jumlah 
                      FROM pengaduan 
                      WHERE tgl_lapor >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                      GROUP BY DATE_FORMAT(tgl_lapor, '%Y-%m')
                      ORDER BY bulan ASC");

// Query untuk pengaduan terbaru
$pengaduan_terbaru = query("SELECT * FROM pengaduan ORDER BY tgl_lapor DESC LIMIT 5");
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Dashboard Monitoring</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      
      <!-- Info boxes -->
      <div class="row">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3><?= $total_pengaduan; ?></h3>
              <p>Total Pengaduan</p>
            </div>
            <div class="icon">
              <i class="fas fa-clipboard-list"></i>
            </div>
            <a href="data-pengaduan.php" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3><?= $sedang_diajukan; ?></h3>
              <p>Sedang Diajukan</p>
            </div>
            <div class="icon">
              <i class="fas fa-hourglass-half"></i>
            </div>
            <a href="data-pengaduan.php" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        
        <div class="col-lg-3 col-6">
          <div class="small-box bg-primary">
            <div class="inner">
              <h3><?= $sedang_diproses; ?></h3>
              <p>Sedang Diproses</p>
            </div>
            <div class="icon">
              <i class="fas fa-tools"></i>
            </div>
            <a href="data-pengaduan.php" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3><?= $selesai_diproses; ?></h3>
              <p>Selesai Diproses</p>
            </div>
            <div class="icon">
              <i class="fas fa-check-circle"></i>
            </div>
            <a href="data-pengaduan.php" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>

      <!-- Total Biaya -->
      <div class="row">
        <div class="col-lg-12">
          <div class="info-box bg-gradient-danger">
            <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Estimasi Biaya Perbaikan Bulan Ini</span>
              <span class="info-box-number">Rp <?= number_format($total_biaya, 0, ',', '.'); ?></span>
              <div class="progress">
                <div class="progress-bar" style="width: 100%"></div>
              </div>
              <span class="progress-description">
                Akumulasi biaya dari semua pengaduan Bulan Ini
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Chart Row -->
      <div class="row">
        <!-- Grafik Trend -->
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-chart-line mr-1"></i>
                Trend Pengaduan 6 Bulan Terakhir
              </h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <canvas id="trendChart" style="height: 300px;"></canvas>
            </div>
          </div>
        </div>

        <!-- Statistik Per Departemen -->
        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-building mr-1"></i>
                Top 5 Departemen
              </h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body p-0">
              <ul class="list-group list-group-flush">
                <?php 
                $colors = ['primary', 'info', 'success', 'warning', 'danger'];
                $index = 0;
                foreach (array_slice($stat_dept, 0, 5) as $dept): 
                ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <?= $dept['d_pelapor']; ?>
                  <span class="badge badge-<?= $colors[$index % 5]; ?> badge-pill"><?= $dept['jumlah']; ?></span>
                </li>
                <?php 
                $index++;
                endforeach; 
                ?>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Grafik Status dan Pengaduan Terbaru -->
      <div class="row">
        <!-- Grafik Status -->
        <div class="col-md-5">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-chart-pie mr-1"></i>
                Distribusi Status Pengaduan
              </h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <canvas id="statusChart" style="height: 300px;"></canvas>
            </div>
          </div>
        </div>

        <!-- Pengaduan Terbaru -->
        <div class="col-md-7">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-clock mr-1"></i>
                Pengaduan Terbaru
              </h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-sm table-hover">
                  <thead>
                    <tr>
                      <th>No. Pengaduan</th>
                      <th>Pelapor</th>
                      <th>Barang</th>
                      <th>Tanggal</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($pengaduan_terbaru as $p): ?>
                    <tr>
                      <td><a href="detail-pengaduan.php?id=<?= $p['id']; ?>"><?= $p['id']; ?></a></td>
                      <td><?= $p['n_pelapor']; ?></td>
                      <td><?= $p['n_barang']; ?></td>
                      <td><?= date('d/m/Y', strtotime($p['tgl_lapor'])); ?></td>
                      <td>
                        <?php if ($p['status'] == 'Sedang diajukan'): ?>
                          <span class="badge badge-secondary">Diajukan</span>
                        <?php elseif ($p['status'] == 'Sedang diproses'): ?>
                          <span class="badge badge-primary">Diproses</span>
                        <?php else: ?>
                          <span class="badge badge-success">Selesai</span>
                        <?php endif; ?>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="card-footer text-center">
              <a href="data-pengaduan.php">Lihat Semua Pengaduan</a>
            </div>
          </div>
        </div>
      </div>

    </section>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
  <script>
    // Data untuk grafik trend
    var trendLabels = <?= json_encode(array_column($trend_bulan, 'bulan')); ?>;
    var trendData = <?= json_encode(array_column($trend_bulan, 'jumlah')); ?>;

    // Grafik Trend Line
    var ctxTrend = document.getElementById('trendChart').getContext('2d');
    var trendChart = new Chart(ctxTrend, {
      type: 'line',
      data: {
        labels: trendLabels,
        datasets: [{
          label: 'Jumlah Pengaduan',
          data: trendData,
          borderColor: 'rgb(75, 192, 192)',
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          tension: 0.4,
          fill: true
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1
            }
          }
        }
      }
    });

    // Grafik Status Pie
    var ctxStatus = document.getElementById('statusChart').getContext('2d');
    var statusChart = new Chart(ctxStatus, {
      type: 'doughnut',
      data: {
        labels: ['Sedang Diajukan', 'Sedang Diproses', 'Selesai Diproses'],
        datasets: [{
          data: [<?= $sedang_diajukan; ?>, <?= $sedang_diproses; ?>, <?= $selesai_diproses; ?>],
          backgroundColor: [
            'rgb(255, 193, 7)',
            'rgb(0, 123, 255)',
            'rgb(40, 167, 69)'
          ]
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom'
          }
        }
      }
    });
  </script>

<?php
include "templates/footer.php";
?>