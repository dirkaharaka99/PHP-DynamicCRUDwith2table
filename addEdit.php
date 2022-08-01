<?php

// Start session
session_start();

$postData = $barangData = array();

// Get session data
$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:'';


// Get status message from session
if(!empty($sessData['status']['msg'])){
    $statusMsg = $sessData['status']['msg'];
    $statusMsgType = $sessData['status']['type'];
    unset($_SESSION['sessData']['status']);
}

// Get posted data from session
if(!empty($sessData['postData'])){
    $postData = $sessData['postData'];
    unset($_SESSION['sessData']['postData']);
}


// Get user data
if(!empty($_GET['id'])){
    include 'DBcon.class.php';
    $db = new DBcon();
    $conditions['where'] = array(
        'id' => $_GET['id'],
    );
    $conditions['return_type'] = 'single';
    $barangData = $db->getRows('barang', $conditions);
}

// Pre-filled data
$barangData = !empty($postData)?$postData:$barangData;

// Define action
$actionLabel = !empty($_GET['id'])?'Ubah':'Tambah';



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tambah Data Barang</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="assets/css/style.css"> -->
</head>
<body>
    <div class="container">
        <h2 style="text-align: center;"><?php echo $actionLabel; ?> Data Barang</h2>
        <div class="row">
            <div class="col-md-offset-3 col-md-6">
                <!-- Display status message -->
                <?php if(!empty($statusMsg) && ($statusMsgType == 'success')){ ?>
                <div class="alert alert-success"><?php echo $statusMsg; ?></div>
                <?php }elseif(!empty($statusMsg) && ($statusMsgType == 'error')){ ?>
                <div class="alert alert-danger"><?php echo $statusMsg; ?></div>
                <?php } ?>

              

                <!-- Add/Edit form -->
                <div class="panel panel-default">
                    <!-- <div class="panel-heading"><a href="index.php" class="glyphicon glyphicon-arrow-left"></a></div> -->
                    <div class="panel-heading"><?php echo $actionLabel; ?> Data</div>
                    <div class="panel-body">
                        <form method="post" action="userAction.php" class="form" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Kode Barang</label>
                                <input type="text" class="form-control" name="kode_barang" value="<?php echo !empty($barangData['kode_barang'])?$barangData['kode_barang']:''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Nama Barang</label>
                                <input type="text" class="form-control" name="nama_barang" value="<?php echo !empty($barangData['nama_barang'])?$barangData['nama_barang']:''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Stok</label>
                                <input type="text" class="form-control" name="stok" value="<?php echo !empty($barangData['stok'])?$barangData['stok']:''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Harga Barang</label>
                                <input type="text" class="form-control" name="harga_barang" value="<?php echo !empty($barangData['harga_barang'])?$barangData['harga_barang']:''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Gambar</label>
                                <input type="file" class="form-control" name="gambar_barang" accept="image/x-png,image/gif,image/jpeg" value="<?php echo !empty($barangData['gambar_barang'])?$barangData['gambar_barang']:''; ?>">
                            </div>
                        
                            <input type="hidden" name="id" value="<?php echo !empty($barangData['id'])?$barangData['id']:''; ?>">
                            <div class="row">
                                <div class="col-xs-1 col-md-1">
                                    <input type="submit" name="barangSubmit" class="btn btn-success" value="MASUKKAN" title="Klik untuk Menyelesaikan"/>
                                </div>
                                <div class="col-xs-1 col-xs-offset-5 col-sm-1 col-sm-offset-8 col-md-1 col-md-offset-8">
                                    <span class="panel-heading"><a href="index.php" title="Klik untuk Kembali" class="btn btn-default"><i class="glyphicon glyphicon-arrow-left"></i> Kembali</a></span>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
    
            </div>
        </div>
    </div>
    
</body>
</html>
