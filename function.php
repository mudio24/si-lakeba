<?php
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "rusak";

$conn = mysqli_connect($db_host,$db_user,$db_pass,$db_name);

function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function insertPengaduan($data) {
    global $conn;
    date_default_timezone_set('Asia/Jakarta');
    $id = $data['id'];
    $np = htmlspecialchars($data["nama"]);
    $no_telp = htmlspecialchars($data["no_telp"]);
    $jp = htmlspecialchars($data["jabatan"]);
    $dp = htmlspecialchars($data["dept"]);
    $nb = htmlspecialchars($data["nama_barang"]);
    $ket = mysqli_real_escape_string($conn, $data["ket"]);
    $status = "Sedang diajukan";
    $ket_petugas = "-";
    $tgl_lapor = date("Y-m-d");
    
    // Upload foto bukti
    $foto_bukti = uploadFotoBukti();
    if (!$foto_bukti) {
        return false;
    }

    mysqli_query($conn, "INSERT INTO pengaduan (id, n_pelapor, no_telp, j_pelapor, d_pelapor, n_barang, ket, foto_bukti, status, ket_petugas, tgl_lapor) VALUES('$id', '$np', '$no_telp', '$jp', '$dp', '$nb', '$ket', '$foto_bukti', '$status', '$ket_petugas', '$tgl_lapor')");
    return mysqli_affected_rows($conn);
}

function uploadFotoBukti() {
    $rand = rand();
    $ekstensi = array('png','jpg','jpeg');
    $filename = basename($_FILES['foto_bukti']['name']);
    $ukuran = $_FILES['foto_bukti']['size'];
    $error = $_FILES['foto_bukti']['error'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    if ($error !== UPLOAD_ERR_OK) {
        echo "<script>alert('Terjadi kesalahan saat mengupload foto. Error code: $error');</script>";
        return false;
    }

    if(!in_array($ext,$ekstensi)) {
        echo "<script>alert('Ekstensi tidak diperbolehkan! Gunakan JPG, JPEG, atau PNG.');</script>";
        return false;
    }
    
    if($ukuran > 2044070) {
        echo "<script>alert('Ukuran file terlalu besar! Maksimal 2MB.');</script>";
        return false;
    }
    
    // sanitize filename (hapus spasi & karakter berbahaya)
    $safeName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $filename);
    $namaFileBaru = $rand.'_'.$safeName;
    
    // Gunakan path absolut berdasarkan lokasi function.php
    $uploadDir = __DIR__ . '/assets/img/bukti_kerusakan/';
    
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $targetPath = $uploadDir . $namaFileBaru;
    
    if(move_uploaded_file($_FILES['foto_bukti']['tmp_name'], $targetPath)) {
        return $namaFileBaru;
    } else {
        echo "<script>alert('Gagal mengupload foto!');</script>";
        return false;
    }
}

function registrasi($data) {
    global $conn;

    $username = strtolower(stripslashes($data["username"]));
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $name = htmlspecialchars($data["name"]);
    $nip = htmlspecialchars($data["nip"]);
    $img = "default.jpg";
    $status = "0";

    $cek = mysqli_query($conn, "SELECT username, user_id FROM user WHERE username = '$username' OR user_id = '$nip'");

    if (mysqli_fetch_assoc($cek)) {
        echo "<script>alert('Username $username or NIP $nip was already registered!');</script>";
        return false;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);
    mysqli_query($conn, "INSERT INTO user VALUES('$nip', '$username', '$password', '$name', '$img', '$status')");

    return mysqli_affected_rows($conn);
}

function updatePass($data) {
    global $conn;
    
    $id = $data['id'];
    $password_baru = mysqli_real_escape_string($conn, $data["password_baru"]);
    $password_baru = password_hash($password_baru, PASSWORD_DEFAULT);
    mysqli_query($conn, "UPDATE user SET password='$password_baru' WHERE user_id='$id'"); 

    return mysqli_affected_rows($conn);
}

function updatePengaduan($data) {
    global $conn;
    
    $id = $data['id'];
    $status = $data['status'];
    $ket_petugas = mysqli_real_escape_string($conn, $data['ket_petugas']);
    $estimasi_biaya = isset($data['estimasi_biaya']) && $data['estimasi_biaya'] != '' ? $data['estimasi_biaya'] : NULL;
    
    // Cek apakah ada file invoice yang diupload
    if (isset($_FILES['bukti_invoice']) && $_FILES['bukti_invoice']['error'] != 4) {
        $bukti_invoice = uploadInvoice();
        if ($bukti_invoice) {
            // Hapus invoice lama jika ada
            $query_old = mysqli_query($conn, "SELECT bukti_invoice FROM pengaduan WHERE id='$id'");
            $old_data = mysqli_fetch_assoc($query_old);
            $oldFile = isset($old_data['bukti_invoice']) ? $old_data['bukti_invoice'] : '';
            $oldPath = __DIR__ . '/assets/img/invoice/' . $oldFile;
            if ($oldFile && file_exists($oldPath)) {
                unlink($oldPath);
            }
            
            if ($estimasi_biaya !== NULL) {
                mysqli_query($conn, "UPDATE pengaduan SET status='$status', ket_petugas='$ket_petugas', estimasi_biaya='$estimasi_biaya', bukti_invoice='$bukti_invoice' WHERE id='$id'");
            } else {
                mysqli_query($conn, "UPDATE pengaduan SET status='$status', ket_petugas='$ket_petugas', bukti_invoice='$bukti_invoice' WHERE id='$id'");
            }
        } else {
            return false;
        }
    } else {
        // Update tanpa invoice
        if ($estimasi_biaya !== NULL) {
            mysqli_query($conn, "UPDATE pengaduan SET status='$status', ket_petugas='$ket_petugas', estimasi_biaya='$estimasi_biaya' WHERE id='$id'");
        } else {
            mysqli_query($conn, "UPDATE pengaduan SET status='$status', ket_petugas='$ket_petugas' WHERE id='$id'");
        }
    }

    return mysqli_affected_rows($conn);
}

function uploadInvoice() {
    $rand = rand();
    $ekstensi = array('png','jpg','jpeg','pdf');
    $filename = basename($_FILES['bukti_invoice']['name']);
    $ukuran = $_FILES['bukti_invoice']['size'];
    $error = $_FILES['bukti_invoice']['error'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    if ($error !== UPLOAD_ERR_OK) {
        echo "<script>alert('Terjadi kesalahan saat mengupload invoice. Error code: $error');</script>";
        return false;
    }

    if(!in_array($ext,$ekstensi)) {
        echo "<script>alert('Ekstensi tidak diperbolehkan! Gunakan JPG, JPEG, PNG, atau PDF.');</script>";
        return false;
    }
    
    if($ukuran > 5242880) { // 5MB
        echo "<script>alert('Ukuran file terlalu besar! Maksimal 5MB.');</script>";
        return false;
    }
    
    // sanitize filename
    $safeName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $filename);
    $namaFileBaru = $rand.'_'.$safeName;
    
    // Gunakan path absolut berdasarkan lokasi function.php
    $uploadDir = __DIR__ . '/assets/img/invoice/';
    
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $targetPath = $uploadDir . $namaFileBaru;
    
    if(move_uploaded_file($_FILES['bukti_invoice']['tmp_name'], $targetPath)) {
        return $namaFileBaru;
    } else {
        echo "<script>alert('Gagal mengupload invoice!');</script>";
        return false;
    }
}

function updatePhoto($data) {
    global $conn;
    
    $id = $_SESSION['login']['user_id'];
        
        $rand = rand();
        $ekstensi =  array('png','jpg','jpeg');
        $filename = $_FILES['foto']['name'];
        $ukuran = $_FILES['foto']['size'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        
        if(!in_array($ext,$ekstensi) ) {
            echo "<script>alert('Ekstensi tidak diperbolehkan atau Anda belum memilih file apapun.'); window.location='profil.php';</script>";
        }else{
            if($ukuran < 2044070){		
                $xx = $rand.'_'.$filename;
                move_uploaded_file($_FILES['foto']['tmp_name'], '../assets/img/profile/'.$rand.'_'.$filename);

                mysqli_query($conn, "UPDATE user SET img = '$xx' WHERE user_id='$id'"); 
        
            } else {
                echo "<script>alert('Size file terlalu besar! Size yang diperbolehkan tidak melebihi 2 MB.'); window.location='profil.php';</script>";
            }
        }
    return mysqli_affected_rows($conn);
}

function deleteUser($id) {
    global $conn;
    mysqli_query($conn, "DELETE FROM user WHERE user_id = '$id'");
    return mysqli_affected_rows($conn);
}

function deletePengaduan($id) {
    global $conn;
    
    // Ambil nama file foto untuk dihapus
    $query = mysqli_query($conn, "SELECT foto_bukti FROM pengaduan WHERE id = '$id'");
    $data = mysqli_fetch_assoc($query);
    
    // Hapus file foto dari folder jika ada
    if (isset($data['foto_bukti']) && $data['foto_bukti']) {
        $fotoPath = __DIR__ . '/assets/img/bukti_kerusakan/' . $data['foto_bukti'];
        if (file_exists($fotoPath)) {
            unlink($fotoPath);
        }
    }
    
    mysqli_query($conn, "DELETE FROM pengaduan WHERE id = '$id'");
    return mysqli_affected_rows($conn);
}

function searchPengaduan($keyword) {
    global $conn;
    $data = mysqli_query($conn, "SELECT * FROM pengaduan WHERE id = '$keyword'");
    return mysqli_affected_rows($conn);
}

// Hitung jumlah laporan baru (status 'Sedang diajukan')
function countNewReports() {
    global $conn;
    $res = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM pengaduan WHERE status = 'Sedang diajukan'");
    $row = mysqli_fetch_assoc($res);
    return isset($row['cnt']) ? (int)$row['cnt'] : 0;
}

// Ambil laporan baru terbaru (limit default 5)
function getRecentNewReports($limit = 5) {
    return query("SELECT id, n_pelapor, n_barang, tgl_lapor FROM pengaduan WHERE status = 'Sedang diajukan' ORDER BY tgl_lapor DESC LIMIT " . intval($limit));
}

?>