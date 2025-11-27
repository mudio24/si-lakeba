<?php
include "templates/header.php";
include "templates/sidebar-pengaduan.php";

// Handle pick/unpick action
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];
    $user_id = $_SESSION["login"]["user_id"];
    
    if ($action == 'pick') {
        // Cek apakah sudah di-pick oleh user lain
        $check = mysqli_query($conn, "SELECT assigned_to FROM pengaduan_assignment WHERE pengaduan_id = '$id'");
        $row = mysqli_fetch_assoc($check);
        
        if ($row && $row['assigned_to']) {
            echo "<script>alert('Pengaduan ini sudah di-pick oleh user lain!'); window.location='data-pengaduan.php';</script>";
        } else {
            $result = mysqli_query($conn, "INSERT INTO pengaduan_assignment (pengaduan_id, assigned_to, assigned_at) VALUES ('$id', '$user_id', NOW())");
            if ($result) {
                echo "<script>alert('Pengaduan berhasil di-pick!'); window.location='data-pengaduan.php';</script>";
            } else {
                echo "<script>alert('Gagal pick pengaduan!'); window.location='data-pengaduan.php';</script>";
            }
        }
    } elseif ($action == 'unpick') {
        // Cek apakah user yang unpick = user yang pick
        $check = mysqli_query($conn, "SELECT assigned_to FROM pengaduan_assignment WHERE pengaduan_id = '$id'");
        $row = mysqli_fetch_assoc($check);
        
        if ($row && $row['assigned_to'] != $user_id) {
            echo "<script>alert('Anda tidak bisa membatalkan pick pengaduan yang sudah di-pick oleh user lain!'); window.location='data-pengaduan.php';</script>";
        } else {
            $result = mysqli_query($conn, "DELETE FROM pengaduan_assignment WHERE pengaduan_id = '$id'");
            if ($result) {
                echo "<script>alert('Pick pengaduan dibatalkan!'); window.location='data-pengaduan.php';</script>";
            } else {
                echo "<script>alert('Gagal membatalkan pick pengaduan!'); window.location='data-pengaduan.php';</script>";
            }
        }
    }
}
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Pengaduan Kerusakan Barang</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Data Pengaduan</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-user-clock mr-3"></i>Belum Diproses</h3>
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
          <div class="table-responsive">
            <table class="table table-hover" id="table" width="100%">
              <thead align="center">
                <th>No.</th>
                <th>Nama Pegawai</th>
                <th>Nama Barang</th>
                <th>Keterangan</th>
                <th>Tgl. Lapor</th>
                <th>Pick By</th>
                <th width="200">Action</th>
              </thead>
              <tbody align="center">
                <?php
                $data = query("SELECT p.*, pa.assigned_to, pa.assigned_at FROM pengaduan p LEFT JOIN pengaduan_assignment pa ON pa.pengaduan_id = p.id WHERE p.status='Sedang diajukan'");
                foreach ($data as $d) :
                ?>
                <tr>
                  <td><?= $d['id']; ?></td>
                  <td><?= $d['n_pelapor']; ?></td>
                  <td><?= $d['n_barang']; ?></td>
                  <td><?= $d['ket']; ?></td>
                  <td><?= $d['tgl_lapor']; ?></td>
                  <td>
                    <?php 
                    if ($d['assigned_to']) {
                      $assign_user = query("SELECT name FROM user WHERE user_id = '{$d['assigned_to']}';")[0];
                      echo '<span class="badge badge-info">' . $assign_user['name'] . '</span>';
                    } else {
                      echo '<span class="badge badge-secondary">Belum di-pick</span>';
                    }
                    ?>
                  </td>
                  <td>
                    <a href="detail-pengaduan.php?id=<?= $d['id']; ?>" class="btn btn-sm btn-outline-info" style="font-size: 15px; padding: 6px 12px; white-space: nowrap; margin-right: 4px;"><i class="fas fa-search mr-1"></i>Detail</a>
                    <?php if (!$d['assigned_to']): ?>
                    <a href="?action=pick&id=<?= $d['id']; ?>" class="btn btn-sm btn-outline-success" style="font-size: 15px; padding: 6px 12px; white-space: nowrap; margin-right: 4px;" onclick="return confirm('Pick pengaduan ini?');"><i class="fas fa-check mr-1"></i>Pick</a>
                    <?php elseif ($d['assigned_to'] == $_SESSION['login']['user_id']): ?>
                    <a href="?action=unpick&id=<?= $d['id']; ?>" class="btn btn-sm btn-outline-warning" style="font-size: 15px; padding: 6px 12px; white-space: nowrap; margin-right: 4px;" onclick="return confirm('Batalkan pick pengaduan ini?');"><i class="fas fa-times mr-1"></i>Unpick</a>
                    <?php endif; ?>
                    <a href="delete-pengaduan.php?id=<?= $d['id']; ?>" class="btn btn-sm btn-outline-danger" style="font-size: 15px; padding: 6px 12px; white-space: nowrap;"><i class="fas fa-trash-alt mr-1"></i>Delete</a>
                  </td>
                </tr>
                <?php
                endforeach;
                ?>
              </tbody>

            </table>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
      <!-- Default box -->
      <div class="card" style="margin-top: 20px;">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-hourglass-half mr-3"></i>Sedang Diproses</h3>
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
        <div class="table">
            <table class="table-hover" id="table2" width="100%">
              <thead align="center">
                <th>No.</th>
                <th>Nama Pegawai</th>
                <th>Nama Barang</th>
                <th>Keterangan</th>
                <th>Tgl. Lapor</th>
                <th>Pick By</th>
                <th width="200">Action</th>
              </thead>
              <tbody align="center">
                <?php
                $data = query("SELECT p.*, pa.assigned_to, pa.assigned_at FROM pengaduan p LEFT JOIN pengaduan_assignment pa ON pa.pengaduan_id = p.id WHERE p.status='Sedang diproses'");
                foreach ($data as $d) :
                ?>
                <tr>
                <td><?= $d['id']; ?></td>
                  <td><?= $d['n_pelapor']; ?></td>
                  <td><?= $d['n_barang']; ?></td>
                  <td><?= $d['ket']; ?></td>
                  <td><?= $d['tgl_lapor']; ?></td>
                  <td>
                    <?php 
                    if ($d['assigned_to']) {
                      $assign_user = query("SELECT name FROM user WHERE user_id = '{$d['assigned_to']}';")[0];
                      echo '<span class="badge badge-info">' . $assign_user['name'] . '</span>';
                    } else {
                      echo '<span class="badge badge-secondary">Belum di-pick</span>';
                    }
                    ?>
                  </td>
                  <td>
                    <a href="detail-pengaduan.php?id=<?= $d['id']; ?>" class="btn btn-sm btn-outline-info" style="font-size: 15px; padding: 6px 12px; white-space: nowrap; margin-right: 4px;"><i class="fas fa-search mr-1"></i>Detail</a>
                    <?php if (!$d['assigned_to']): ?>
                    <a href="?action=pick&id=<?= $d['id']; ?>" class="btn btn-sm btn-outline-success" style="font-size: 15px; padding: 6px 12px; white-space: nowrap; margin-right: 4px;" onclick="return confirm('Pick pengaduan ini?');"><i class="fas fa-check mr-1"></i>Pick</a>
                    <?php elseif ($d['assigned_to'] == $_SESSION['login']['user_id']): ?>
                    <a href="?action=unpick&id=<?= $d['id']; ?>" class="btn btn-sm btn-outline-warning" style="font-size: 15px; padding: 6px 12px; white-space: nowrap; margin-right: 4px;" onclick="return confirm('Batalkan pick pengaduan ini?');"><i class="fas fa-times mr-1"></i>Unpick</a>
                    <?php endif; ?>
                    <a href="delete-pengaduan.php?id=<?= $d['id']; ?>" class="btn btn-sm btn-outline-danger" style="font-size: 15px; padding: 6px 12px; white-space: nowrap;"><i class="fas fa-trash-alt mr-1"></i>Delete</a>
                  </td>
                </tr>
                <?php
                endforeach;
                ?>
              </tbody>
              
            </table>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
      <!-- Default box -->
      <div class="card mb-auto" style="margin-top: 20px;">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-tasks mr-3"></i>Telah Diproses</h3>
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
          <div class="table">
            <table class="table-hover" id="table3" width="100%">
              <thead align="center">
              <th>No.</th>
                <th>Nama Pegawai</th>
                <th>Nama Barang</th>
                <th>Keterangan</th>
                <th>Tgl. Lapor</th>
                <th>Pick By</th>
                <th width="200">Action</th>
              </thead>
              <tbody align="center">
                <?php
                $data = query("SELECT p.*, pa.assigned_to, pa.assigned_at FROM pengaduan p LEFT JOIN pengaduan_assignment pa ON pa.pengaduan_id = p.id WHERE p.status='Selesai diproses'");
                foreach ($data as $d) :
                ?>
                <tr>
                  <td><?= $d['id']; ?></td>
                  <td><?= $d['n_pelapor']; ?></td>
                  <td><?= $d['n_barang']; ?></td>
                  <td><?= $d['ket']; ?></td>
                  <td><?= $d['tgl_lapor']; ?></td>
                  <td>
                    <?php 
                    if ($d['assigned_to']) {
                      $assign_user = query("SELECT name FROM user WHERE user_id = '{$d['assigned_to']}';")[0];
                      echo '<span class="badge badge-info">' . $assign_user['name'] . '</span>';
                    } else {
                      echo '<span class="badge badge-secondary">Belum di-pick</span>';
                    }
                    ?>
                  </td>
                  <td>
                    <a href="detail-pengaduan.php?id=<?= $d['id']; ?>" class="btn btn-sm btn-outline-info" style="font-size: 15px; padding: 6px 12px; white-space: nowrap; margin-right: 4px;"><i class="fas fa-search mr-1"></i>Detail</a>
                    <?php if (!$d['assigned_to']): ?>
                    <a href="?action=pick&id=<?= $d['id']; ?>" class="btn btn-sm btn-outline-success" style="font-size: 15px; padding: 6px 12px; white-space: nowrap; margin-right: 4px;" onclick="return confirm('Pick pengaduan ini?');"><i class="fas fa-check mr-1"></i>Pick</a>
                    <?php elseif ($d['assigned_to'] == $_SESSION['login']['user_id']): ?>
                    <a href="?action=unpick&id=<?= $d['id']; ?>" class="btn btn-sm btn-outline-warning" style="font-size: 15px; padding: 6px 12px; white-space: nowrap; margin-right: 4px;" onclick="return confirm('Batalkan pick pengaduan ini?');"><i class="fas fa-times mr-1"></i>Unpick</a>
                    <?php endif; ?>
                    <a href="delete-pengaduan.php?id=<?= $d['id']; ?>" class="btn btn-sm btn-outline-danger" style="font-size: 15px; padding: 6px 12px; white-space: nowrap;"><i class="fas fa-trash-alt mr-1"></i>Delete</a>
                  </td>
                </tr>
                <?php
                endforeach;
                ?>
              </tbody>

            </table> 
          </div>
        </div>
      </div> 
      <!-- /.card -->
    </section>
    <!-- /.content -->
</div>
<?php
include "templates/footer.php";
?>