<?php
$host = 'localhost';
$user = '';
$password = '';
$database = '';


$conec = mysqli_connect($host, $user,$password, $database);


if (!$conec) { //cek koneksi
    die("Tidak bisa terkoneksi ke database");
}
$nim        = "";
$nama       = "";
$alamat     = "";
$fakultas   = "";
$email      = "";
$sukses     = "";
$error      = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if($op == 'delete'){
    $id         = $_GET['id'];
    $sql1       = "delete from mahasiswa where id = '$id'";
    $q1         = mysqli_query($conec,$sql1);
    if($q1){
        $sukses = "Berhasil hapus data";
    }else{
        $error  = "Gagal melakukan delete data";
    }
}
if ($op == 'edit') {
    $id         = $_GET['id'];
    $sql1       = "select * from mahasiswa where id = '$id'";
    $q1         = mysqli_query($conec, $sql1); 
    $r1         = mysqli_fetch_array($q1);
    $nim        = $r1['nim'];
    $nama       = $r1['nama'];
    $alamat     = $r1['alamat'];
    $fakultas   = $r1['fakultas'];
    $email      = $r1['email'];

    if ($nim == '') {
        $error = "Data tidak ditemukan";
    }
}
if (isset($_POST['simpan'])) { //untuk create
    $nim        = $_POST['nim'];
    $nama       = $_POST['nama'];
    $alamat     = $_POST['alamat'];
    $fakultas   = $_POST['fakultas'];
    $email      = $_POST['email'];

    if ($nim && $nama && $alamat && $fakultas && $email) {
        if (!preg_match("/^[a-zA-Z ]*$/",$nama)) { //validasi nama tidak boleh mengandung nomor
            $error = "Nama hanya boleh mengandung huruf dan spasi";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { //validasi email
            $error = "Email tidak valid";
        } else {
            if ($op == 'edit') { //untuk update
                $sql1       = "update mahasiswa set nim = '$nim',nama='$nama',alamat = '$alamat',fakultas='$fakultas', email='$email' where id = '$id'";
                $q1         = mysqli_query($conec, $sql1);
                if ($q1) {
                    $sukses = "Data berhasil diupdate";
                } else {
                    $error  = "Data gagal diupdate";
                }
            } else { //untuk insert
                $sql1   = "insert into mahasiswa(nim,nama,alamat,fakultas,email) values ('$nim','$nama','$alamat','$fakultas','$email')";
                $q1     = mysqli_query($conec, $sql1);
                if ($q1) {
                    $sukses     = "Berhasil memasukkan data baru";
                } else {
                    $error      = "Gagal memasukkan data";
                }
            }
        }
    } else {
        $error = "Silakan masukkan semua data";
    }
    
    
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
        .mx-auto {
            width: 1000px
        }

        .card {
            margin-top: 10px;
            padding-bottom:10px;
        }
    </style>
</head>

<body class="pt-5">
    <div class="mx-auto">
        <!-- untuk memasukkan data -->
        <div class="card ">
            <div class="card-header text-center text-bg-dark" >
                I N P U T
            </div>
            <div class="card-body">
              <?php ob_start(); ?>

              <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error?>
                </div>
                <script>
                    setTimeout(function() {
                        window.location.href = 'index.php';
                    }, 3000);
                </script>
                
              <?php endif ?>
              

            <?php if ($sukses): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $sukses ?>
                </div>
                <script>
                    setTimeout(function() {
                        window.location.href = 'index.php';
                    }, 3000);
                </script>
                
            <?php endif ?>

            <?php ob_end_flush(); ?>
                <form action="" method="POST">
                    <div class="mb-3 row">
                        <label for="nim" class="col-sm-2 col-form-label">NIM</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nim" name="nim" value="<?php echo $nim ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $alamat ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="email" name="email" value="<?php echo $email ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="fakultas" class="col-sm-2 col-form-label">Fakultas</label>
                        <div class="col-sm-10">
                            <select class="form-control " name="fakultas" id="fakultas">
                                <option value="">- Pilih Fakultas -</option>
                                <option value="RPL" <?php if ($fakultas == "RPL") echo "selected" ?>>RPL</option>
                                <option value="SI" <?php if ($fakultas == "SI") echo "selected" ?>>SI</option>
                                <option value="HUKUM" <?php if ($fakultas == "HUKUM") echo "selected" ?>>HUKUM</option>
                                <option value="PERTANIAN" <?php if ($fakultas == "PERTANIAN") echo "selected" ?>>PERTANIAN</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 pt-3">
                        <input type="submit" name="simpan" value="SIMPAN" class="btn btn-primary  position-absolute bottom-1 start-3 translate-middle-y" />
                        <a href="index.php" onclick="return confirm('Membatalkan')"><input type="reset" name="cancel" value="BATAL" class="btn btn-danger position-absolute bottom-1 end-0 translate-middle" /></a>
                        
                    </div>
                    
                </form>
            </div>
        </div>

        <!-- untuk mengeluarkan data -->
        <div class="card">
            <div class="card-header text-white bg-dark text-center">
                Data Mahasiswa
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">`</th>
                            <th scope="col">NIM</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">Email</th>
                            <th scope="col">Fakultas</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2   = "select * from mahasiswa order by id asc";
                        $q2     = mysqli_query($conec, $sql2);
                        $urut   = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id         = $r2['id'];
                            $nim        = $r2['nim'];
                            $nama       = $r2['nama'];
                            $alamat     = $r2['alamat'];
                            $email      = $r2['email'];
                            $fakultas   = $r2['fakultas'];

                        ?>
                            <tr>
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td scope="row"><?php echo $nim ?></td>
                                <td scope="row"><?php echo $nama ?></td>
                                <td scope="row"><?php echo $alamat ?></td>
                                <td scope="row"><?php echo $email ?></td>
                                <td scope="row"><?php echo $fakultas ?></td>
                                <td scope="row">
                                    <a href="index.php?op=edit&id=<?php echo $id ?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                    <a href="index.php?op=delete&id=<?php echo $id?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger">Delete</button></a>            
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                    
                </table>
            </div>
        </div>
    </div>
</body>

</html>
