<?php
// Start the session
session_start();
if (isset($_POST['submit-update'])&&isset($_POST['newName'])) {
    //update action
    $do=1; 
    $id=$_POST['voice'];
    $newName=$_POST['newName'];
    header('Location: http://parspayer.com/my/admin/addonmodules.php?module=osvoice&tab=fileupload&newName='.$newName.'&id='.$id.'&do='.$do);
} else if (isset($_POST['submit-delete'])) {
    
    $do=0;
    $id=$_POST['voice'];
    header('Location: http://parspayer.com/my/admin/addonmodules.php?module=osvoice&tab=fileupload&id='.$id.'&do='.$do);
}else{
    echo "something is wrong";
}
?>