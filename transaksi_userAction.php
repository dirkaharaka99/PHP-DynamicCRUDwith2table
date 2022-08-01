<?php

// Start session
session_start();

// Load and initialize database class
require_once 'DBcon.class.php';
$db = new DBcon();

$tblName = 'transaksi';

// Set default redirect url
$redirectURL = 'transaksi_index.php';

if(isset($_POST['transaksiSubmit'])){
    // Get submitted data
    $kode_barang   = $_POST['kode_barang'];
    $qty  = $_POST['qty'];
    $jumlah_harga  = $_POST['jumlah_harga'];
    $id     = $_POST['id'];
    
 
    // Submitted user data
    $transaksiData = array(
        'kode_barang'  => $kode_barang,
        'qty' => $qty,
        'jumlah_harga' => $jumlah_harga,
       
    );
    
    // Store submitted data into session
    $sessData['postData'] = $transaksiData;
    $sessData['postData']['id'] = $id;
    
    // ID query string
    $idStr = !empty($id)?'?id='.$id:'';
    
    // If the data is not empty
    if(!empty($kode_barang) && !empty($qty) && !empty($jumlah_harga)){
            if(!empty($id)){
                // Update data
                $condition = array('id' => $id);
                $update = $db->update($tblName, $transaksiData, $condition);
                
                if($update){
                    $sessData['postData'] = '';
                    $sessData['status']['type'] = 'success';
                    $sessData['status']['msg']  = 'Data berhasil diperbarui.';
                }else{
                    $sessData['status']['type'] = 'error';
                    $sessData['status']['msg']  = 'Some problem occurred, please try again.';
                    
                    // Set redirect url
                    $redirectURL = 'transaksi_addEdit.php'.$idStr;
                }
            }else{
                // Insert data
                $insert = $db->insert($tblName, $transaksiData);
       
                
                if($insert){
                    $sessData['postData'] = '';
                    $sessData['status']['type'] = 'success';
                    $sessData['status']['msg']  = 'Data berhasil ditambahkan.';
                }else{

                    $sessData['status']['type'] = 'error';
                    $sessData['status']['msg']  = 'Some problem occurred, please try again.';
                    
                    // Set redirect url
                    $redirectURL = 'transaksi_addEdit.php';
                }
            }
     
    }else{
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg']  = 'Data tidak boleh kosong. Silahkan isi dengan lengkap!';
        
        // Set redirect url
        $redirectURL = 'transaksi_addEdit.php'.$idStr;
    }
    
    // Store status into the session
    $_SESSION['sessData'] = $sessData;

    // Redirect user
    header("Location: ".$redirectURL);
}elseif(($_REQUEST['action_type'] == 'delete') && !empty($_GET['id'])){
    // Delete data
    $condition = array('id' => $_GET['id']);
    $delete = $db->delete($tblName, $condition);
    if($delete){
        $sessData['status']['type'] = 'success';
        $sessData['status']['msg']  = 'Data berhasil dihapus!';
    }else{
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg']  = 'Some problem occurred, please try again.';
    }
    
    // Store status into the session
    $_SESSION['sessData'] = $sessData;
}

// Redirect the user
header("Location: ".$redirectURL);
exit();