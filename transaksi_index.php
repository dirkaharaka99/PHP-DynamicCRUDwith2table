<?php

// Start session
session_start();

// Get session data
$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:'';

// Get status message from session
if(!empty($sessData['status']['msg'])){
    $statusMsg = $sessData['status']['msg'];
    $statusMsgType = $sessData['status']['type'];
    unset($_SESSION['sessData']['status']);
}

// Load pagination class
require_once 'Pagination.class.php';


// Load and initialize database class
require_once 'DBcon.class.php';
$db = new DBcon();

// Page offset and limit
$perPageLimit = 5;
$offset = !empty($_GET['page'])?(($_GET['page']-1)*$perPageLimit):0;

// Get search keyword
$searchKeyword = !empty($_GET['sq'])?$_GET['sq']:'';
$searchStr = !empty($searchKeyword)?'?sq='.$searchKeyword:'';

// Search DB query
$searchArr = '';
if(!empty($searchKeyword)){
    $searchArr = array(
        'kode_barang' => $searchKeyword,
        'qty' => $searchKeyword,
        'jumlah_harga' => $searchKeyword
    );
}

// Get count of the users
$con = array(
    'like_or' => $searchArr,
    'return_type' => 'count'
);
$rowCount = $db->getRows('transaksi', $con);

// Initialize pagination class
$pagConfig = array(
    'baseURL' => 'transaksi_index.php'.$searchStr,
    'totalRows' => $rowCount,
    'perPage' => $perPageLimit
);
$pagination = new Pagination($pagConfig);

// Get users from database
$con = array(
    'like_or' => $searchArr,
    'start' => $offset,
    'limit' => $perPageLimit,
    'order_by' => 'id ASC',
);
$transaksis = $db->getRows('transaksi', $con);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Daftar Transaksi</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="assets/css/style.css"> -->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico"/>
</head>
<body>
    <div class="container">
        <h2 style="text-align: center;">Daftar Transaksi</h2>
        <div class="buttonkembali" style="text-align: left;">
            <span class="panel-heading"><a href="index.php" title="Klik untuk Kembali" class="btn btn-success"><i class="glyphicon glyphicon-arrow-left"></i> Kembali Ke Daftar Barang</a></span>
        </div>
        <!-- Display status message -->
        <?php if(!empty($statusMsg) && ($statusMsgType == 'success')){ ?>
        <div class="alert alert-success"><?php echo $statusMsg; ?></div>
        <?php }elseif(!empty($statusMsg) && ($statusMsgType == 'error')){ ?>
        <div class="alert alert-danger"><?php echo $statusMsg; ?></div>
        <?php } ?>

        <div class="row">
            <div class="col-md-3 search-panel">
                <!-- Search form -->
                <form>
                <div class="input-group">
                    <input type="text" name="sq" class="form-control" placeholder="Cari data..." value="<?php echo $searchKeyword; ?>">
                    <div class="input-group-btn">
                        <button title="Klik untuk Mencari" class="btn btn-default" type="submit">
                            <i class="glyphicon glyphicon-search"></i>
                        </button>
                    </div>
                </div>
                </form>
            </div>
            <div class="col-md-4 col-md-offset-5">
                <!-- Add link -->
                <span class="pull-right">
                    <a href="transaksi_addEdit.php" title="Klik untuk Menambah Data" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Tambah Data</a>
                    <br>
                </span>
            </div>
        </div>
        <!-- Data list table --> 
        <table class="table table-striped table-bordered">
            <br>
            <thead>
                <tr>
                    <th></th>
                    <th>Kode</th>
                    <th>Qty</th>
                    <th>Jumlah Harga</th>
                    <th>Created At</th>
                    <th>Updated_at</th>
                    <th>Aksi</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php
                if(!empty($transaksis)){ $count = 0; 
                    foreach($transaksis as $transaksi){ $count++;
                ?>
                <tr>
                    <td><?php echo '#'.$count; ?></td>
                    <td><?php echo $transaksi['kode_barang']; ?></td>
                    <td><?php echo $transaksi['qty']; ?></td>
                    <td><?php echo 'Rp ' . number_format($transaksi['jumlah_harga'],2,",","."); ?></td>
                    <td><?php echo $transaksi['created_at']; ?></td>
                    <td><?php echo $transaksi['updated_at']; ?></td>
                    <td>
                        <a title="Sunting" href="transaksi_addEdit.php?id=<?php echo $transaksi['id']; ?>" class="glyphicon glyphicon-edit"></a>
                        <a title="Hapus" href="transaksi_userAction.php?action_type=delete&id=<?php echo $transaksi['id']; ?>" class="glyphicon glyphicon-trash" onclick="return confirm('Yakin ingin Menghapus Data?')"></a>
                    </td>
                </tr>
                <?php } }else{ ?>
                <tr><td colspan="5">DATA TIDAK DITEMUKAN</td></tr>
                <?php } ?>
            </tbody>
            <tfoot>
                
            </tfoot>
        </table>
        
        <!-- Display pagination links -->
        <?php echo $pagination->createLinks(); ?>
    </div>
  
</body>
</html>