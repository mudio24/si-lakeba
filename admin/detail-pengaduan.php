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
            echo "<script>alert('Pengaduan ini sudah di-pick oleh user lain!'); window.location='detail-pengaduan.php?id=$id';</script>";
        } else {
            $result = mysqli_query($conn, "INSERT INTO pengaduan_assignment (pengaduan_id, assigned_to, assigned_at) VALUES ('$id', '$user_id', NOW())");
            if ($result) {
                echo "<script>alert('Pengaduan berhasil di-pick!'); window.location='detail-pengaduan.php?id=$id';</script>";
            } else {
                echo "<script>alert('Gagal pick pengaduan!'); window.location='detail-pengaduan.php?id=$id';</script>";
            }
        }
    } elseif ($action == 'unpick') {
        // Cek apakah user yang unpick = user yang pick
        $check = mysqli_query($conn, "SELECT assigned_to FROM pengaduan_assignment WHERE pengaduan_id = '$id'");
        $row = mysqli_fetch_assoc($check);
        
        if ($row && $row['assigned_to'] != $user_id) {
            echo "<script>alert('Anda tidak bisa membatalkan pick pengaduan yang sudah di-pick oleh user lain!'); window.location='detail-pengaduan.php?id=$id';</script>";
        } else {
            $result = mysqli_query($conn, "DELETE FROM pengaduan_assignment WHERE pengaduan_id = '$id'");
            if ($result) {
                echo "<script>alert('Pick pengaduan dibatalkan!'); window.location='detail-pengaduan.php?id=$id';</script>";
            } else {
                echo "<script>alert('Gagal membatalkan pick pengaduan!'); window.location='detail-pengaduan.php?id=$id';</script>";
            }
        }
    }
}

if (isset($_POST['submit'])) {
  if (updatePengaduan($_POST) > 0) {
    echo "<script>alert('Update data successfully!'); window.location='data-pengaduan.php';</script>";
    } else {
        echo "<script>alert('Data update failed or you did not make any changes!'); window.location='data-pengaduan.php';</script>";
    }
  }

$id = $_GET['id'];
$data = query("SELECT p.*, pa.assigned_to, pa.assigned_at FROM pengaduan p LEFT JOIN pengaduan_assignment pa ON pa.pengaduan_id = p.id WHERE p.id = '$id'");
foreach ($data as $d) :

?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Detail Pengaduan <?= $d['id']; ?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Report</a></li>
              <li class="breadcrumb-item active">Bulanan</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <div class="card">
        <div class="card-body">
          <form action="" method="POST" enctype="multipart/form-data">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-2">
              <label for="id">Nomor Pengaduan :</label>
              <input type="text" name="id" id="id" class="form-control mb-3 bg-transparent" style="cursor: default;" value="<?= $d['id']; ?>" readonly>
              </div>
              <div class="col-md-2">
              <label for="tgl">Tanggal Pengaduan :</label>
              <input type="text" name="tgl" id="tgl" class="form-control mb-3 bg-transparent" style="cursor: default;" value="<?= $d['tgl_lapor']; ?>" readonly>
              </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="np">Nama Pelapor :</label>
                  <input type="text" name="np" id="np" class="form-control mb-3 bg-transparent" style="cursor: default;" value="<?= $d['n_pelapor']; ?>" readonly>
                </div>
                <div class="col-md-4">
                  <label for="no_telp">No. Telepon :</label>
                  <input type="text" name="no_telp" id="no_telp" class="form-control mb-3 bg-transparent" style="cursor: default;" value="<?= isset($d['no_telp']) ? $d['no_telp'] : '-'; ?>" readonly>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="jp">Jabatan :</label>
                  <input type="text" name="jp" id="jp" class="form-control mb-3 bg-transparent" style="cursor: default;" value="<?= $d['j_pelapor']; ?>" readonly>
                </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="dp">Departemen :</label>
                  <input type="text" name="dp" id="dp" class="form-control mb-3 bg-transparent" style="cursor: default;" value="<?= $d['d_pelapor']; ?>" readonly>
                </div>
                <div class="col-md-4">
                  <label for="nb">Nama Barang :</label>
                  <input type="text" name="nb" id="nb" class="form-control mb-3 bg-transparent" style="cursor: default;" value="<?= $d['n_barang']; ?>" readonly>
                </div>
              </div>
              <div class="row">
                <div class="col-md-8">
                  <label for="ket">Keterangan :</label>
                  <textarea name="ket" id="ket" class="form-control mb-3 bg-transparent" style="cursor: default;" readonly><?= $d['ket']; ?></textarea>
                </div>
              </div>
              <div class="row">
                <div class="col-md-8">
                  <label>Foto Bukti Kerusakan :</label>
                  <div class="mb-3" style="border: 1px solid #ced4da; border-radius: 5px; padding: 15px; background-color: #f8f9fa;">
                    <?php if (!empty($d['foto_bukti']) && file_exists('../assets/img/bukti_kerusakan/' . $d['foto_bukti'])): ?>
                      <img src="../assets/img/bukti_kerusakan/<?= $d['foto_bukti']; ?>" 
                           alt="Foto Bukti Kerusakan" 
                           class="img-fluid" 
                           style="max-width: 400px; max-height: 400px; cursor: pointer; border-radius: 5px;"
                           onclick="showImageModal(this.src)">
                      <p class="text-muted mt-2 mb-0"><small><i class="fas fa-info-circle"></i> Klik gambar untuk memperbesar</small></p>
                    <?php else: ?>
                      <p class="text-muted mb-0"><i class="fas fa-exclamation-triangle"></i> Foto bukti tidak tersedia</p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4" style="border: 1px solid #ced4da; border-radius: 5px; margin: 7px 7px; padding: 7px 10px;">
                  <p><b>Status Pengerjaan :</b></p>
                  <div class="alert alert-light" style="margin: 0; padding: 10px;">
                    <?php 
                    if ($d['assigned_to']) {
                      $assign_user = query("SELECT name FROM user WHERE user_id = '{$d['assigned_to']}'")[0];
                      $assigned_date = date('d/m/Y H:i', strtotime($d['assigned_at']));
                      echo '<span class="badge badge-info" style="font-size: 14px; padding: 8px 12px;">';
                      echo '<i class="fas fa-user-check mr-1"></i>' . $assign_user['name'] . '<br>';
                      echo '<small>' . $assigned_date . '</small>';
                      echo '</span>';
                    } else {
                      echo '<span class="badge badge-secondary" style="font-size: 14px; padding: 8px 12px;">';
                      echo '<i class="fas fa-user-times mr-1"></i>Belum di-pick';
                      echo '</span>';
                    }
                    ?>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4" style="border: 1px solid #ced4da; border-radius: 5px; margin: 7px 7px; padding: 7px 10px;">
                  <p><b>Status :</b></p>
                  <?php
                    if ($d['status'] == 'Sedang diajukan') {
                  ?>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" value="Sedang diajukan" id="opt1" name="status" class="custom-control-input" checked>
                    <label class="custom-control-label" for="opt1">Sedang diajukan</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" value="Sedang diproses" id="opt2" name="status" class="custom-control-input">
                    <label class="custom-control-label" for="opt2">Sedang diproses</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" value="Selesai diproses" id="opt3" name="status" class="custom-control-input">
                    <label class="custom-control-label" for="opt3">Selesai diproses</label>
                  </div>
                    <?php
                    } elseif ($d['status'] == 'Sedang diproses') {
                    ?>
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" value="Sedang diajukan" id="opt1" name="status" class="custom-control-input">
                        <label class="custom-control-label" for="opt1">Sedang diajukan</label>
                      </div>
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" value="Sedang diproses" id="opt2" name="status" class="custom-control-input" checked>
                        <label class="custom-control-label" for="opt2">Sedang diproses</label>
                      </div>
                      <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" value="Selesai diproses" id="opt3" name="status" class="custom-control-input">
                        <label class="custom-control-label" for="opt3">Selesai diproses</label>
                      </div>
                        <?php                
                        } else {
                        ?>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" value="Sedang diajukan" id="opt1" name="status" class="custom-control-input">
                            <label class="custom-control-label" for="opt1">Sedang diajukan</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" value="Sedang diproses" id="opt2" name="status" class="custom-control-input">
                            <label class="custom-control-label" for="opt2">Sedang diproses</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" value="Selesai diproses" id="opt3" name="status" class="custom-control-input" checked>
                            <label class="custom-control-label" for="opt3">Selesai diproses</label>
                          </div>
                        <?php
                        }
                        ?>
                </div>
              </div>
              <div class="row">
                <div class="col-md-8 mt-2">
                  <label for="ket_petugas">Catatan dari petugas :</label>
                  <textarea name="ket_petugas" id="ket_petugas" class="form-control mb-2"><?= $d['ket_petugas']; ?></textarea>
                </div>
              </div>
              <div class="row">
                <div class="col-md-8 mt-2">
                  <?php if (!$d['assigned_to']): ?>
                  <a href="?action=pick&id=<?= $d['id']; ?>" class="btn btn-outline-success mr-2" style="padding: 6px 12px; white-space: nowrap;" onclick="return confirm('Pick pengaduan ini?');">
                    <span class="fas fa-check mr-2"></span>Pick Sekarang
                  </a>
                  <?php elseif ($d['assigned_to'] == $_SESSION['login']['user_id']): ?>
                  <a href="?action=unpick&id=<?= $d['id']; ?>" class="btn btn-outline-warning mr-2" style="padding: 6px 12px; white-space: nowrap;" onclick="return confirm('Batalkan pick pengaduan ini?');">
                    <span class="fas fa-times mr-2"></span>Batalkan Pick
                  </a>
                  <?php endif; ?>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4 mt-2">
                  <label for="estimasi_biaya">Estimasi Biaya Perbaikan :</label>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text">Rp</span>
                    </div>
                    <input type="number" name="estimasi_biaya" id="estimasi_biaya" class="form-control" 
                           value="<?= $d['estimasi_biaya'] ?? ''; ?>" 
                           placeholder="0" step="0.01" min="0">
                  </div>
                  <small class="text-muted">Isi 0 atau kosongkan jika tidak ada biaya</small>
                </div>
              </div>
              <div class="row">
                <div class="col-md-8 mt-2">
                  <label for="bukti_invoice">Upload Bukti Invoice/Nota :</label>
                  <input type="file" name="bukti_invoice" id="bukti_invoice" class="form-control-file mb-2" accept="image/*,.pdf">
                  <small class="form-text text-muted">
                    Format: JPG, JPEG, PNG, PDF. Maksimal 5MB.
                  </small>
                  
                  <?php if (!empty($d['bukti_invoice'])): ?>
                    <div class="mt-2 p-3" style="border: 1px solid #ced4da; border-radius: 5px; background-color: #f8f9fa;">
                      <p class="mb-2"><strong>Invoice yang sudah diupload:</strong></p>
                      <?php 
                      $file_ext = pathinfo($d['bukti_invoice'], PATHINFO_EXTENSION);
                      if (in_array(strtolower($file_ext), ['jpg', 'jpeg', 'png'])): 
                      ?>
                        <img src="../assets/img/invoice/<?= $d['bukti_invoice']; ?>" 
                             alt="Bukti Invoice" 
                             class="img-fluid mb-2" 
                             style="max-width: 300px; cursor: pointer; border-radius: 5px;"
                             onclick="showInvoiceModal(this.src)">
                        <br>
                      <?php else: ?>
                        <a href="../assets/img/invoice/<?= $d['bukti_invoice']; ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                          <i class="fas fa-file-pdf"></i> Lihat Invoice (PDF)
                        </a>
                        <br>
                      <?php endif; ?>
                      <small class="text-muted">Upload file baru untuk mengganti invoice</small>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
              <div class="row">
                <div class="col-md-8 mt-2">
                  <button type="submit" value="submit" name="submit" class="btn btn-outline-success mr-2" style="padding: 6px 12px; white-space: nowrap;">
                    <span class="fas fa-check mr-2"></span>
                    Save
                  </button>
                  <button type="reset" value="reset" class="btn btn-outline-danger mr-2" style="padding: 6px 12px; white-space: nowrap;">
                    <span class="fas fa-times mr-2"></span>
                    Reset
                  </button>
                  <a href="data-pengaduan.php" class="btn btn-outline-primary" style="padding: 6px 12px; white-space: nowrap;">
                    <span class="fas fa-arrow-left mr-2"></span>
                    Back
                  </a>
                </div>
              </div>
          </div>
          </form>
        </div>
        <!-- /.card-body -->
        <?php
        endforeach;
        ?>
        
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>

  <!-- Modal untuk memperbesar gambar -->
  <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalLabel">Foto Bukti Kerusakan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center">
          <img id="modalImage" src="" class="img-fluid" style="max-width: 100%; max-height: 80vh;">
        </div>
      </div>
    </div>
  </div>

  <!-- Modal untuk invoice -->
  <div class="modal fade" id="invoiceModal" tabindex="-1" role="dialog" aria-labelledby="invoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="invoiceModalLabel">Bukti Invoice</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center">
          <img id="modalInvoice" src="" class="img-fluid" style="max-width: 100%; max-height: 80vh;">
        </div>
      </div>
    </div>
  </div>

  <script>
  function showImageModal(src) {
    document.getElementById('modalImage').src = src;
    $('#imageModal').modal('show');
  }
  
  function showInvoiceModal(src) {
    document.getElementById('modalInvoice').src = src;
    $('#invoiceModal').modal('show');
  }
  
  // Format input rupiah
  document.getElementById('estimasi_biaya').addEventListener('keyup', function(e) {
    let value = this.value.replace(/[^\d]/g, '');
    this.value = value;
  });
  </script>

<?php
include "templates/footer.php";
?>