<?php

// Start session
session_start();

// Load and initialize database class
require_once 'DBcon.class.php';
$db = new DBcon();

$tblName = 'barang';

// Set default redirect url
$redirectURL = 'index.php';

if(isset($_POST['barangSubmit'])){
    // Get submitted data
    $kode_barang   = $_POST['kode_barang'];
    $nama_barang  = $_POST['nama_barang'];
    $stok  = $_POST['stok'];
    $harga_barang  = $_POST['harga_barang'];
    $id     = $_POST['id'];
    $gambar_barang = $_FILES["gambar_barang"]["name"];
    $gambar_barangSementara = $_FILES['gambar_barang']['tmp_name'];
    
    // tentukan lokasi file akan dipindahkan
    $dirUpload = "terupload/";

    // $uploadOk = 1;
    // $target_file = $dirUpload . basename($_FILES["gambar_barang"]["name"]);
    // $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    
    // $check = getimagesize($gambar_barangSementara);
    //     if($check !== false) {
    //         echo "File is an image - " . $check["mime"] . ".";
    //         $sessData2['postData'] = '';
    //         $sessData2['status2']['type2'] = 'success';
    //         $sessData2['status2']['msg2']  = "File is an image - " . $check["mime"] . ".";
    //         $uploadOk = 1;
    //     } else {
    //         echo "File is not an image.";
    //         $sessData2['status2']['type2'] = 'error';
    //         $sessData2['status2']['msg2']  = "File is not an image.";
            
    //         // Set redirect url
    //         $redirectURL = 'addEdit.php';
            
    //         $uploadOk = 0;
    //     }
    //     // Check if file already exists
    //     if (file_exists($target_file)) {
    //         $sessData2['status2']['type2'] = 'error';
    //         $sessData2['status2']['msg2']  = "Sorry, file already exists.";
            
    //         // Set redirect url
    //         $redirectURL = 'addEdit.php';
    //         $uploadOk = 0;
    //     }
    //     // Allow certain file formats
    //     if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
    //     $sessData2['status2']['type2'] = 'error';
    //     $sessData2['status2']['msg2']  = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    //     $uploadOk = 0;
        
    //     // Set redirect url
    //     $redirectURL = 'addEdit.php';
        
    //     }

        // Check if $uploadOk is set to 0 by an error
        // if ($uploadOk == 0) {
        //     $sessData2['status2']['type2'] = 'error';
        //     $sessData2['status2']['msg2']  = "Sorry, your file was not uploaded.";
            
        // // if everything is ok, try to upload file
        // } else {
        //     if (move_uploaded_file($gambar_barangSementara, $target_file)) {
        //     $sessData2['postData'] = '';
        //     $sessData2['status2']['type2'] = 'success';
        //     $sessData2['status2']['msg2']  = "The file ". htmlspecialchars( basename( $_FILES["gambar_barang"]["name"])). " has been uploaded.";
        //     } else {
        //     $sessData2['status2']['type2'] = 'error';
        //     $sessData2['status2']['msg2']  = "Sorry, there was an error uploading your file.";
        //     }
        // }

    // pindahkan file
    $terupload = move_uploaded_file($gambar_barangSementara, $dirUpload.$gambar_barang);

    // if ($terupload) {
    //     echo "Upload berhasil!<br/>";
    //     // echo "Link: <a href='".$dirUpload.$gambar_barang."'>".$gambar_barang."</a>";
    // } else {
    //     echo "Upload Gagal!";
    // }


 
    // Submitted user data
    $barangData = array(
        'kode_barang'  => $kode_barang,
        'nama_barang' => $nama_barang,
        'stok' => $stok,
        'harga_barang' => $harga_barang,
        'gambar_barang' => $gambar_barang,
       
    );
    
    // Store submitted data into session
    $sessData['postData'] = $barangData;
    $sessData['postData']['id'] = $id;
    
    // ID query string
    $idStr = !empty($id)?'?id='.$id:'';
    
    // If the data is not empty
    // if(!empty($kode_barang) && !empty($nama_barang) && !empty($stok) && !empty($harga_barang) && !empty($gambar_barang)){
    if(!empty($kode_barang) && !empty($nama_barang) && !empty($stok) && !empty($harga_barang)){
            if(!empty($id)){
                // Update data
                $condition = array('id' => $id);
                $update = $db->update($tblName, $barangData, $condition);
                
                if($update){
                    $sessData['postData'] = '';
                    $sessData['status']['type'] = 'success';
                    $sessData['status']['msg']  = 'Data berhasil diperbarui.';
                }else{
                    $sessData['status']['type'] = 'error';
                    $sessData['status']['msg']  = 'Some problem occurred, please try again.';
                    
                    // Set redirect url
                    $redirectURL = 'addEdit.php'.$idStr;
                }
            }else{
                // Insert data
                $insert = $db->insert($tblName, $barangData);
       
                
                if($insert){
                    $sessData['postData'] = '';
                    $sessData['status']['type'] = 'success';
                    $sessData['status']['msg']  = 'Data berhasil ditambahkan.';
                }else{

                    $sessData['status']['type'] = 'error';
                    $sessData['status']['msg']  = 'Some problem occurred, please try again.';
                    
                    // Set redirect url
                    $redirectURL = 'addEdit.php';
                }
            }
        
    }else{
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg']  = 'Data tidak boleh kosong. Silahkan isi dengan lengkap!';
        
        // Set redirect url
        $redirectURL = 'addEdit.php'.$idStr;
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