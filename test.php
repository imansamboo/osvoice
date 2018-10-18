<?php
$str = "+98.912 347 9882";
$str = str_replace("+98.","0",$str);
echo $str;
$str = preg_replace('/\s+/', "", $str);
echo $str;

$url = "http://185.81.96.226:90/?action=m1&phone=09120621785";
        
$result = file_get_contents($url);
echo gethostbyname('www.imansamboo.com');
echo '<br>';

$ip = gethostbyname('www.google.com');
if ($ip == 'www.google.com') {
    echo '101';
    exit;
}

echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';

echo getcwd();
set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib');

include('phpseclib/Net/SSH2.php');
$ssh = new Net_SSH2('94.130.92.55', 22);
if (!$ssh->login('root', '7G0DH2DXxb')) {
    exit('Login Failed');
}

echo $ssh->exec('pwd');
echo $ssh->exec('ls -la');
echo $ssh->exec('touch iman.txt');
/*
echo "first";
if(function_exists (  "ssh2_connect" )){
    echo "yes";
}else{
    echo "no";
}
$conn = ssh2_connect('94.130.92.55', 22);
echo "yes";
if (ssh2_auth_password($conn, 'root', 'ptkWq51EzJ')) {
    echo "Authentication Successful!";
} else {
    die('Authentication Failed...');
}

*/
use WHMCS\Database\Capsule as Capsule;

/*
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
//echo "Connected successfully";
//getactive user
$sql = "SELECT id, firstname, lastname FROM tblclients";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
            $activeClients .= '<option value="'.$row['id'].'" name="'.$row['id'].'" style="color:green;"> '.$row['firstname'].' '.$row['lastname'].' (#'.$row['id'].')</option>';
    }
} else {
    echo "0 results";
}
echo $activeClients;
$conn->close();
*/
?>



