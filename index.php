<?php

// Start session
session_start();

// Get session data
$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:'';
// $sessData2 = !empty($_SESSION['sessData2'])?$_SESSION['sessData2']:'';

// Get status message from session
if(!empty($sessData['status']['msg'])){
    $statusMsg = $sessData['status']['msg'];
    $statusMsgType = $sessData['status']['type'];
    unset($_SESSION['sessData']['status']);
}

// if(!empty($sessData2['status2']['msg2'])){
//     $statusMsg2 = $sessData2['status2']['msg2'];
//     $statusMsgType2 = $sessData2['status2']['type2'];
//     unset($_SESSION['sessData2']['status2']);
// }

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
        'nama_barang' => $searchKeyword,
        'stok' => $searchKeyword,
        'harga_barang' => $searchKeyword

    );
}

// Get count of the users
$con = array(
    'like_or' => $searchArr,
    'return_type' => 'count'
);
$rowCount = $db->getRows('barang', $con);

// Initialize pagination class
$pagConfig = array(
    'baseURL' => 'index.php'.$searchStr,
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
$barangs = $db->getRows('barang', $con);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Daftar Barang</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="assets/css/style.css"> -->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico"/>
</head>
<body>
    <div class="container">
        <h2 style="text-align: center;">Daftar Barang</h2>
        <div class=" buttonkembali" style="text-align: left;">
            <span class="panel-heading"><a href="transaksi_index.php" title="Ke Daftar Transaksi" class="btn btn-success"><i class="glyphicon glyphicon-arrow-right"></i> Daftar Transaksi</a></span>
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
                    <a href="addEdit.php" title="Klik untuk Menambah Data" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Tambah Data</a>
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
                    <th>Gambar</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Created At</th>
                    <th>Updated_at</th>
                    <th>Aksi</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php
                if(!empty($barangs)){ $count = 0; 
                    foreach($barangs as $barang){ $count++;
                ?>
                <tr>
                    <td><?php echo '#'.$count; ?></td>
                    <td><?php  $dirUpload = "terupload/"; echo '<img src = "' . $dirUpload . $barang["gambar_barang"] .  '"width="150" height="150"' . '>' ; ?></td>
                    <td><?php echo $barang['kode_barang']; ?></td>
                    <td><?php echo $barang['nama_barang']; ?></td>
                    <td><?php echo $barang['stok']; ?></td>
                    <td><?php echo 'Rp ' . number_format($barang['harga_barang'],2,",","."); ?></td>
                    <td><?php echo $barang['created_at']; ?></td>
                    <td><?php echo $barang['updated_at']; ?></td>
                    <td>
                        <a title="Sunting" href="addEdit.php?id=<?php echo $barang['id']; ?>" class="glyphicon glyphicon-edit"></a>
                        <a title="Hapus" href="userAction.php?action_type=delete&id=<?php echo $barang['id']; ?>" class="glyphicon glyphicon-trash" onclick="return confirm('Yakin ingin Menghapus Data?')"></a>
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

