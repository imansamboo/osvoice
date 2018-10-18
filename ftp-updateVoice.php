<?php
// Start the session
session_start();
function checkFieldName(){
    $j=0;
    foreach ($_GET['inputFileName'] as $x=>$y) {
        
        if($y==$_POST['newName']){
            $j++;
        }
    }
    return $j;
    
    
}

if (isset($_POST['submit-update'])&&isset($_POST['newName'])) {
    //update action
    if(checkFieldName()!=0){
        $errorUpload="emptyNameField";
        header('Location: http://parspayer.com/my/admin/addonmodules.php?module=osvoice&tab=uploadtest&errorUpload='.$errorUpload);
        exit();
    }
    $do=1;
    $id=$_POST['voice'];
    $newName=$_POST['newName'];
    header('Location: http://parspayer.com/my/admin/addonmodules.php?module=osvoice&tab=uploadtest&newName='.$newName.'&id='.$id.'&do='.$do);
    exit();
} else if (isset($_POST['submit-delete'])) {
    
    $do=0;
    $id=$_POST['voice'];
    header('Location: http://parspayer.com/my/admin/addonmodules.php?module=osvoice&tab=uploadtest&id='.$id.'&do='.$do);
    exit();
}else{
    echo   '
            <p width="30%" style="color:#721C24; background-color: #F6D5D8;" >'.$LANG['updateFail'].' <p>
            ';
        $uploadOk = 0;
}
?>