<?php
include "templates/header.php";
include "templates/sidebar-pengguna.php";
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Pengguna</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active"></li>
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
          <h3 class="card-title">Daftar Pengguna</h3>

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
            <table class="table table-hover" id="table_user" width="100%">
              <thead align="center">
                <th>User Id</th>
                <th>Username</th>
                <th width="240">Full Name</th>
                <th>Photo</th>
                <th width="100">Status</th>
                <th>Action</th>
              </thead>
              <tbody>
                <?php
                $data = query("SELECT * FROM user");
                
                // Cek apakah user yang login adalah admin
                $is_admin = false;
                
                if (isset($_SESSION['login'])) {
                    $login = $_SESSION['login'];
                    if (($login['user_id'] == '1') || 
                        (strtolower($login['username']) === 'admin') || 
                        (strtolower($login['name']) === 'admin')) {
                        $is_admin = true;
                    }
                }
                
                foreach ($data as $d) :
                ?>
                  <tr align="center">
                    <td><?php echo $d['user_id']; ?></td>
                    <td><?php echo $d['username']; ?></td>
                    <td><?php echo $d['name']; ?></td>
                    <td><img src="../assets/img/profile/<?= $d['img']; ?>" alt="user image" width="100" height="100"></td>
                    <?php
                    if ($d['status'] == 1) { ?>
                      <td><a href="#" class="btn btn-sm btn-success" style="padding: 6px 12px; white-space: nowrap;">Activated</a></td>
                    <?php } else { ?>
                      <td><a href="#" class="btn btn-sm btn-danger" style="padding: 6px 12px; white-space: nowrap;">Disactivated</a></td>
                    <?php } ?>
                    <td>
                      <?php
                        // Hanya admin yang bisa melakukan action
                        if ($is_admin) {
                      ?>
                        <a href="delete-acc.php?id=<?php echo $d['user_id']; ?>" class="btn btn-sm btn-outline-danger mb-2" style="padding: 6px 12px; white-space: nowrap;"><span class="fas fa-trash-alt mr-2"></span>Delete</a>
                      <?php
                        if ($d['status'] == 0) {
                      ?>
                        <a href="active-acc.php?user_id=<?php echo $d['user_id']; ?>" class="btn btn-sm btn-outline-success mb-2 mr-2" style="padding: 6px 12px; white-space: nowrap;"><span class="fas fa-check mr-2"></span>Active</a>
                      <?php
                        } else { 
                      ?>
                        <a href="nonactive-acc.php?user_id=<?php echo $d['user_id']; ?>" class="btn btn-sm btn-outline-secondary mb-2 mr-2" style="padding: 6px 12px; white-space: nowrap;" ><span class="fas fa-times mr-2"></span>Non-active</a>
                      <?php 
                        } 
                      } else {
                        // User biasa tidak bisa melakukan action
                        echo '<span class="text-muted">No Access</span>';
                      }
                      ?>
                    </td>
                  </tr>
                <?php
                endforeach;
                ?>
              </tbody>
              <tfoot align="center">
                <th>User Id</th>
                <th>Username</th>
                <th width="240">Full Name</th>
                <th>Photo</th>
                <th width="100">Status</th>
                <th>Action</th>
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