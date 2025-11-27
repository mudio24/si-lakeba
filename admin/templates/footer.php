  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0.2025umc
    </div>
    <strong>Copyright &copy; 2025. Created by PKL UMC</a>.</strong> All rights reserved.</a>
    
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../assets/plugins/bootstrap4/js/bootstrap.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="../assets/plugins/datatables-bs4/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../assets/plugins/jszip/jszip.min.js"></script>
<script src="../assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="../assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="../assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="../assets/dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script>
  $(function () {
    $('#table_user').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "responsive": true,
      
    });
    $('#table').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "responsive": true,
    });
    $('#table2').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "responsive": true,
      
    });
    $('#table3').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "responsive": true,
    });
    $('#table-report').DataTable({
      dom: 'Btlip',
      buttons: [
          'colvis', 'excel', 'pdf', 'print'
      ],
    });
  });
</script>


<!-- Modal Notifikasi (pindah dari header.php) -->
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

</body>
</html>