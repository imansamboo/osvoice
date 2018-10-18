<?php
use WHMCS\Database\Capsule;
// Start the session
session_start();

if(empty($_POST['voiceName'])||!isset($_POST['voiceName'])/*||empty($_POST['fileToUpload'])||!isset($_POST['fileToUpload'])*/){
    if(empty($_POST['voiceName'])){
        $errorUpload="emptyFileName";
    }/*elseif(!isset($_POST['fileToUpload'])){
        $errorUpload="emptyFileUpload";
    }*/else{
        $errorUpload="other";
    }
    
    header('Location: http://parspayer.com/my/admin/addonmodules.php?module=osvoice&tab=fileupload&errorupload='.$errorUpload);
    
}else{

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    //test
    //echo "esme file" . $_FILES["fileToUpload"]["name"];
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $uploadedFileName = $_FILES["fileToUpload"]["name"];
    // Check if 
    
    
    
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "mp3"  ) {
        echo "Sorry, only mp3  files are allowed.";
        $uploadOk = 0;
    }
    //Check if file already exist rename it
    if (file_exists($target_file)) {
        //renaming
        function random_filename()
        {
            do {
                $key = '';
                $keys = array_merge(range(0, 9), range('a', 'z'));
        
                for ($i = 0; $i < 9; $i++) {
                    $key .= $keys[array_rand($keys)];
                }
            } while (file_exists($target_dir . '/' . $key . '.' . 'mp3' ));
        
            return $key . '.' . 'mp3';
        }
        $uploadedFileName=random_filename();
        $target_file=$target_dir . '/' .$uploadedFileName;
        echo "ranaming: " .$uploadedFileName;
        echo $target_file;
        
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)&&!empty($_POST["voiceName"])) {
            echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
            
                //echo $name;
                //custumer input name
                $voiceName=$_POST["voiceName"];
                
        
            //some tests for evaluating session 
            //header('Location: test.php');
            //redirect to upload voice page and pas name of file to it
            header('Location: http://parspayer.com/my/admin/addonmodules.php?module=osvoice&tab=fileupload&name='.$uploadedFileName.'&voiceName='.$voiceName);
       
           
        } else {
            echo "Sorry, there was an error uploading your file.";
        }

    }
    }
?>
