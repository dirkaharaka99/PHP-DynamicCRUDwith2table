<?php

// Start session
session_start();

$postData = $transaksiData = array();

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
    $transaksiData = $db->getRows('transaksi', $conditions);
}

// Pre-filled data
$transaksiData = !empty($postData)?$postData:$transaksiData;

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
        <h2 style="text-align: center;">Tambah/Edit Data Transaksi</h2>
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
                        <form method="post" action="transaksi_userAction.php" class="form" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Kode Barang</label>
                                <input type="text" class="form-control" name="kode_barang" value="<?php echo !empty($transaksiData['kode_barang'])?$transaksiData['kode_barang']:''; ?>">
                            </div>
                           
                            <div class="form-group">
                                <label>Qty</label>
                                <input type="text" class="form-control" name="qty" value="<?php echo !empty($transaksiData['qty'])?$transaksiData['qty']:''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Jumlah Harga</label>
                                <input type="text" class="form-control" name="jumlah_harga" value="<?php echo !empty($transaksiData['jumlah_harga'])?$transaksiData['jumlah_harga']:''; ?>">
                            </div>
                           
                            <input type="hidden" name="id" value="<?php echo !empty($transaksiData['id'])?$transaksiData['id']:''; ?>">
                            <div class="row">
                                <div class="col-xs-1 col-md-1">
                                    <input type="submit" name="transaksiSubmit" class="btn btn-success" value="MASUKKAN" title="Klik untuk Menyelesaikan"/>
                                </div>
                                <div class="col-xs-1 col-xs-offset-5 col-sm-1 col-sm-offset-8 col-md-1 col-md-offset-8">
                                    <span class="panel-heading"><a href="transaksi_index.php" title="Klik untuk Kembali" class="btn btn-default"><i class="glyphicon glyphicon-arrow-left"></i> Kembali</a></span>
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
