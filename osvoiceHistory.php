<?php
use WHMCS\Database\Capsule as Capsule;
//$userId = $_GET['userid'];

$servername = "localhost";
$username = "parspaye_whmcs";
$password = "o*R1d(rrRD80";
$dbname = 'parspaye_whmcs';

// Create connection
$conn = new mysqli($servername, $username, $password,$dbname );

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, firstname, lastname FROM tblclients";
$tblclientResult = $conn->query($sql);

$sql = "SELECT `m`.*,`user`.`firstname`,`user`.`lastname`
    FROM `mod_osvoice_messages` as `m`
    JOIN `tblclients` as `user` ON `m`.`user` = `user`.`id`
    ORDER BY `m`.`datetime` DESC";
$tblmessageResult = $conn->query($sql);
foreach ($tblmessageResult as $select){
    if($select['user'] == $_GET['userid']){
        $selectedClient = $select;
    }

}

$echo[0] = '<!DOCTYPE html>
                <html lang="en">
                  <head>
                    <meta charset="utf-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                
                    <title>WHMCS - OSVOICE HISTORY</title>
                
                    <link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600" rel="stylesheet" />
                    <link href="../../../admin/templates/blend/css/all.min.css?v=4e725a" rel="stylesheet" />
                    <script type="../../../text/javascript" src="/my/admin/templates/blend/js/scripts.min.js?v=4e725a"></script>
                    </head>
                    <body class="popup-body">
                        <div class="popup-content-area">
                            <table width="100%" bgcolor="#ffffff" cellpadding="15"><tr><td>
                    
                                <h2>OSVOICE HISTORY</h2>
                    
                                <p>You can see history of incoming and output calls with client: <strong>'.$selectedClient['firstname'].' '.$selectedClient['lastname'].'</strong> </p></td></tr></table>
                                <br />
                    
                  
                    
                    
                    <div class="tablebg">
                    <table id="sortabletbl1" class="datatable" width="100%" border="0" cellspacing="1" cellpadding="3">
                    <tr><th>#</th><th>input</th><th>output</th><th >source</th><th>destination</th>
                     <th>file</th><th>time</th><th>duration</th><th >play</th>
                    </tr>
                    ';
    echo $echo[0];
$i=0;
$j=1;
foreach ($tblmessageResult as $row){
    if($row['user'] == $_GET['userid']){
        $echo[1][$i] = '<tr style="text-align: center"><td>'.$j++.'</td><td>yes</td><td>no</td><td>'.$row['to'].'</td><td>217</td><td>'.$row['text'].'</td><td>'.$row['datetime'].'</td>
                    
                    <td>25 min</td><td>icon</td></tr>';
        echo $echo[1][$i];
        $i++;
    }

}
echo '</table>
                        </div>
                        </div>
                    </body>
                </html>';
?>

