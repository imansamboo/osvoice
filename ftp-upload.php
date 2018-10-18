<?php

/**
 * Created by PhpStorm.
 * User: iman
 * Date: 5/24/18
 * Time: 10:14 AM
 */
class FtpController{
    private $ftpServer = "85.10.205.163";
    private $ftpUser = "iman@parspayer.com";
    private $ftpPass  = "31414142";
    public function ftpConnect()
    {
        $conn_id = ftp_connect($this->ftpServer);        // set up basic connection
        $login_result = ftp_login($conn_id, $this->ftpUser, $this->ftpPass) or die("<h2>You do not have access to this ftp server!</h2>");   // login with username and password, or give invalid user message
        if ((!$conn_id) || (!$login_result)) {  // check connection
            // wont ever hit this, b/c of the die call on ftp_login
            echo "FTP connection has failed! <br />";
            echo "Attempted to connect to" . " " . $this->ftpServer . " for user " . " " . $this->ftpUser;
            return false;
        } else {
            return $conn_id;
        }
    }
    public function ftpCheckName($name)
    {
        $ftp = $this->ftpConnect();
        ftp_pasv($ftp, TRUE); //Passive Mode is better for this
        $contents_on_server = ftp_nlist($ftp,"server_monitoring/"); //Returns an array of filenames from the specified directory on success or FALSE on error.
        // Test if file is in the ftp_nlist array
        if (in_array($name, $contents_on_server)) {
            //echo "I found ".$file." in directory : ";
            $result=true;
        }
        else {
            //echo $file." not found in directory : ";
            $result=false;
        };
        ftp_close($ftp); // close the FTP stream
        return $result;
    }
    public function ftpRandomName($name)
    {
        if ($this->ftpCheckName($name)||$this->checkPersian()) {
            //renaming

            do {
                $key = '';
                $keys = array_merge(range(0, 9), range('a', 'z'));

                for ($i = 0; $i < 9; $i++) {
                    $key .= $keys[array_rand($keys)];
                }
            } while ($this->ftpCheckName($key . '.' . 'mp3'));

            return $key . '.' . 'mp3';
        } else {
            return $name;
        }
    }
    public function ftpUpload($newName)
    {
        $ftp = $this->ftpConnect();
        $targetFile = "server_monitoring/";
        $targetFile .= $newName;
        $localFile=$_FILES["file_section_down"]["tmp_name"];
        $upload = ftp_put($ftp, $targetFile, $localFile, FTP_ASCII);  // upload the file
        ftp_close($ftp); // close the FTP stream
        return $upload;
    }
    public function ftpDelete($name)
    {
        $ftp = $this->ftpConnect();
        $delete = ftp_delete($ftp, "server_monitoring/" . $name);  // upload the file
        ftp_close($ftp); // close the FTP stream
        return $delete;
    }
    public function checkPersian()
    {
        //take voice name
        $file = basename($_FILES["file_section_down"]["name"]);
        //create regix format
        $regix = '/^[\w\-\s]+(\.mp3)$/i';
        if(/*compariton regix format with input file name*/(bool)preg_match($regix,$file)){
            return false;
        }else{
            return true;
        }
    }
}