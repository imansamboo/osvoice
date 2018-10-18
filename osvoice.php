<?php

use WHMCS\Database\Capsule;
use WHMCS\Session;
// Start the session
session_start();
/* WHMCS telephone Addon with GNU/GPL Licence
 * OnlineServer - http://www.onlineserver.ir
 * */
if (!defined("WHMCS"))
    die("&#1575;&#1605;&#1705;&#1575;&#1606; &#1583;&#1587;&#1578;&#1585;&#1587;&#1740; &#1605;&#1587;&#1578;&#1602;&#1740;&#1605; &#1576;&#1607; &#1575;&#1740;&#1606; &#1601;&#1575;&#1740;&#1604; &#1608;&#1580;&#1608;&#1583; &#1606;&#1583;&#1575;&#1585;&#1583;");
//session start
session_start();
function osvoice_config() {
    $configarray = array(
        "name" => "OS-Voice",
        "description" => "ماژول تماس تلفنی آنلاین سرور",
        "version" => "1",
        "author" => "Mizban Dade Pasargad",
        "language" => "persian",
    );
    return $configarray;
}

function osvoice_activate() {

    $query = "CREATE TABLE IF NOT EXISTS `mod_osvoice_messages` (`id` int(11) NOT NULL AUTO_INCREMENT,`sender` varchar(40) NOT NULL,`to` varchar(15) DEFAULT NULL,`text` text,`msgid` varchar(50) DEFAULT NULL,`status` varchar(10) DEFAULT NULL,`errors` text,`logs` text,`user` int(11) DEFAULT NULL,`datetime` datetime NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
    mysql_query($query);

    $query = "CREATE TABLE IF NOT EXISTS `mod_osvoice_settings` 
    (`id` int(11) NOT NULL AUTO_INCREMENT,`api` varchar(40) CHARACTER SET utf8 NOT NULL,
    `ftp_server` varchar(40) CHARACTER SET utf8 NOT NULL, `ftp_username` varchar(40) CHARACTER SET utf8 NOT NULL,
    `ftp_pass` varchar(40) CHARACTER SET utf8 NOT NULL,
    `apiparams` varchar(500) CHARACTER SET utf8 NOT NULL,`wantsmsfield` int(11) DEFAULT NULL,
    `gsmnumberfield` int(11) DEFAULT NULL,`dateformat` varchar(12) CHARACTER SET utf8 DEFAULT NULL,
    `version` varchar(6) CHARACTER SET utf8 DEFAULT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
    mysql_query($query);

    $query = "INSERT INTO `mod_osvoice_settings` (`api`, `apiparams`, `wantsmsfield`, `gsmnumberfield`,`dateformat`, `version`) VALUES ('', '', 0, 0,'%d.%m.%y','1.1.3');";
    mysql_query($query);

    $query = "CREATE TABLE IF NOT EXISTS `mod_osvoice_templates` (`id` int(11) NOT NULL AUTO_INCREMENT,`name` varchar(50) CHARACTER SET utf8 NOT NULL,`type` enum('client','admin') CHARACTER SET utf8 NOT NULL,`admingsm` varchar(255) CHARACTER SET utf8 NOT NULL,`template` varchar(240) CHARACTER SET utf8 NOT NULL,`variables` varchar(500) CHARACTER SET utf8 NOT NULL,`active` tinyint(1) NOT NULL,`extra` varchar(3) CHARACTER SET utf8 NOT NULL,`description` text CHARACTER SET utf8,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
    mysql_query($query);
    $query = "CREATE TABLE IF NOT EXISTS `mod_osvoice_upfile` (`id` int(11) NOT NULL AUTO_INCREMENT,
    `description` text CHARACTER SET utf8,`name` varchar(40) NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
    mysql_query($query);

    //Creating hooks
    require_once("phone.php");
    $class = new osvoice();
    $class->checkHooks();

    return array('status'=>'success','description'=>'ماژول ارسال پیامک فراپیامک با موفقیت فعال شد.');
}

function osvoice_deactivate() {

    $query = "DROP TABLE `mod_osvoice_templates`";
    mysql_query($query);
    $query = "DROP TABLE `mod_osvoice_settings`";
    mysql_query($query);
    $query = "DROP TABLE `mod_osvoice_messages`";
    mysql_query($query);
    $query = "DROP TABLE `mod_osvoice_upfile`";
    mysql_query($query);


    return array('status'=>'success','description'=>'ماژول با موفقیت غیر فعال شد.');
}

function osvoice_upgrade($vars) {
    $version = $vars['version'];

    switch($version){
        case "1":
        case "1.0.1":
            $sql = "ALTER TABLE `mod_osvoice_messages` ADD `errors` TEXT NULL AFTER `status` ;ALTER TABLE `mod_osvoice_templates` ADD `description` TEXT NULL ;ALTER TABLE `mod_osvoice_messages` ADD `logs` TEXT NULL AFTER `errors` ;";
            mysql_query($sql);
        case "1.1":
            $sql = "ALTER TABLE `mod_osvoice_settings` CHANGE `apiparams` `apiparams` VARCHAR( 500 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;";
            mysql_query($sql);
        case "1.1.1":
        case "1.1.2":
            $sql = "ALTER TABLE `mod_osvoice_settings` ADD `dateformat` VARCHAR(12) NULL AFTER `gsmnumberfield`;UPDATE `mod_osvoice_settings` SET dateformat = '%d.%m.%y';";
            mysql_query($sql);
        case "1.1.3":
        case "1.1.4":
            $sql = "ALTER TABLE `mod_osvoice_templates` CHANGE `name` `name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,CHANGE `type` `type` ENUM( 'client', 'admin' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,CHANGE `admingsm` `admingsm` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,CHANGE `template` `template` VARCHAR( 240 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,CHANGE `variables` `variables` VARCHAR( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,CHANGE `extra` `extra` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;";
            mysql_query($sql);
            $sql = "ALTER TABLE `mod_osvoice_settings` CHANGE `api` `api` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,CHANGE `apiparams` `apiparams` VARCHAR( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,CHANGE `dateformat` `dateformat` VARCHAR( 12 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,CHANGE `version` `version` VARCHAR( 6 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;";
            mysql_query($sql);
            $sql = "ALTER TABLE `mod_osvoice_messages` CHANGE `sender` `sender` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,CHANGE `to` `to` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,CHANGE `text` `text` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,CHANGE `msgid` `msgid` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,CHANGE `status` `status` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,CHANGE `errors` `errors` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,CHANGE `logs` `logs` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;";
            mysql_query($sql);

            $sql = "ALTER TABLE `mod_osvoice_templates` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
            mysql_query($sql);
            $sql = "ALTER TABLE `mod_osvoice_settings` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
            mysql_query($sql);
            $sql = "ALTER TABLE `mod_osvoice_messages` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
            mysql_query($sql);
        case "1.1.5":
        case "1.1.6":
        case "1.1.7":
            break;

    }

    $class = new osvoice();
    $class->checkHooks();
}

function osvoice_output($vars){

    $modulelink = $vars['modulelink'];
    $version = $vars['version'];
    $LANG = $vars['_lang'];
    putenv("TZ=Asia/Tehran");
    //Get name filed from table
    $voiceArray = [];

    $result = select_query("mod_osvoice_settings", "ftp_server, ftp_username, ftp_pass");
    while ($data = mysql_fetch_array($result)   ) {
        $ftp['constants']['address'] = $data['ftp_server'];
        $ftp['constants']['username'] = $data['ftp_username'];
        $ftp['constants']['password'] = $data['ftp_pass'];

    }
    if(mysql_num_rows($result)>0&&$ftp['constants']['address']!=''&&$ftp['constants']['username']!=''&&$ftp['constants']['password']!='') {
        //set up ftp connection
        define("FTP_SERVER", $ftp['constants']['address']);
        //define("FTP_PORT",    "");
        define("FTP_USER", $ftp['constants']['username']);
        define("FTP_PASS", $ftp['constants']['password']);
    }else {
        define("FTP_SERVER", "85.10.205.163");
        //define("FTP_PORT",    "");
        define("FTP_USER", "iman@parspayer.com");
        define("FTP_PASS", "31414142");
    }
    function login(){
        $conn_id = ftp_connect(FTP_SERVER/*,FTP_PORT*/);        // set up basic connection
        $login_result = ftp_login($conn_id, FTP_USER, FTP_PASS) or die("<h2>You do not have access to this ftp server!</h2>");   // login with username and password, or give invalid user message
        if ((!$conn_id) || (!$login_result)) {  // check connection
            // wont ever hit this, b/c of the die call on ftp_login
            echo "FTP connection has failed! <br />";
            echo "Attempted to connect to" . " " . FTP_SERVER . " for user " . " " . FTP_USER;
            return $conn_id;
        } else {
            //connection test
            //echo "Connected to". " " . FTP_SERVER . ", for user" . " " .  FTP_USER . " <br />";
            return $conn_id;
        }
    }
    $conn_id=login();


    $class = new osvoice();
    $tab = $_GET['tab'];
//evaluating error in file upload and input
    if(isset($_GET['errorUpload'])){

        if($_GET['errorUpload']=="emptyFileName"){
            echo   '
        <p width="30%" style="color:#721C24; background-color: #F6D5D8;" >'.$_GET['voiceName'].' '.$LANG['emptyFileName'].'<p>
        ';
        }elseif($_GET['errorUpload']=="emptyFileUpload"){
            echo   '
        <p width="30%" style="color:#721C24; background-color: #F6D5D8;" >'.$_GET['voiceName'].' '.$LANG['emptyFileUpload'].'<p>
        ';

        }elseif($_GET['errorUpload']=="other"){
            echo   '
        <p width="30%" style="color:#721C24; background-color: #F6D5D8;" >'.$_GET['voiceName'].' '.$LANG['other'].'<p>
        ';

        }elseif($_GET['errorUpload']=="largeFile"){
            echo   '
        <p width="30%" style="color:#721C24; background-color: #F6D5D8;" > '.$LANG['filemorethan'].'<p>
        ';

        }elseif($_GET['errorUpload']=="smallFile"){
            echo   '
        <p width="30%" style="color:#721C24; background-color: #F6D5D8;" > '.$LANG['fileempty'].'<p>
        ';

        }elseif($_GET['errorUpload']=="wrongFormat"){
            echo   '
        <p width="30%" style="color:#721C24; background-color: #F6D5D8;" >'.$LANG['onlymp3'].'<p>
        ';

        }elseif($_GET['errorUpload']=="emptyNameField"){
            echo   '
        <p width="30%" style="color:#721C24; background-color: #F6D5D8;" >'.$LANG['repeatedName'].'<p>
        ';

        }


    }
    //some test
    /*
    if($_GET['error']==1){
        echo "error equal to 1";
    }
    */
    //test for passing data from header
    if($_GET['upload']==1&&$_GET['error']!=1){
        if(isset($_GET['voiceName'])){
            echo   '
        <p width="30%" style="color:#1A5E36; background-color: #D4EDDA;" >'.$_GET['voiceName'].' '.$LANG['successSave'].'<p>
        ';
            $nameVoice= $_GET['voiceName'];
            //add to database
            Capsule::table("mod_osvoice_upfile")->insert(
                [
                    'description' =>$_GET['name'],
                    'name'=>$_GET['voiceName'],
                ]
            );
        }}elseif(isset($_GET['do'])){
        if($_GET['do']==0){
            /*
            //delete file from upload folder
            // Change directory
            chdir("../modules/addons/osvoice/uploads/");
            foreach (Capsule::table('mod_osvoice_upfile')
            ->where('id',$_GET['id'])->get() as $completeVoiceName)
            {
                unlink($completeVoiceName->description);
            }



            // come back to main directory
            chdir("../../../../admin");
            */

            //delete from ftp

            foreach (Capsule::table('mod_osvoice_upfile')
                         ->where('id',$_GET['id'])->get() as $completeVoiceName)
            {
                $destination_file=$completeVoiceName->description;
            }

            $delete = ftp_delete($conn_id, $destination_file);  // upload the file
            if (!$delete) {  // check upload status
                echo   '
            <p width="30%" style="color:#721C24; background-color: #F6D5D8;" >'.$LANG['sorrydelete'].' <p>
            ';
            } else {
                echo   '
                    <p width="30%" style="color:#1A5E36; background-color: #D4EDDA;" > 
                     '.$LANG['deleteok'].'  <p><br/>
                    ';
            }

            ftp_close($conn_id); // close the FTP stream


            // Get current directory
            //echo getcwd();
            //file_exists()
            //delete from database
            Capsule::table("mod_osvoice_upfile")
                ->where('id',$_GET['id'])
                ->delete();



            //test
            //echo "every thing is ok and do is delete";

        }elseif($_GET['do']==1 && $_GET['errorUpload']!="emptyNameField"){
            //echo "every thing is ok and do is update";
            //update database
            Capsule::table("mod_osvoice_upfile")
                ->where('id',$_GET['id'])
                ->update(
                    ['name'=>$_GET['newName']]
                );
        }else{

            //test
            //echo "your php file in line 124 doesn't work correctly ";
        }
    }
    echo '
    <div id="clienttabs" style="float: right">


    
<html dir="rtl">
       <br>
       <br>


        <ul>
            <li class="' . (($tab == "support")?"tabselected":"tab") . '"><a href="addonmodules.php?module=osvoice&amp;tab=support">'.$LANG['support'].'</a></li>
            <li class="' . (($tab == "messages")?"tabselected":"tab") . '"><a href="addonmodules.php?module=osvoice&amp;tab=messages">'.$LANG['messages'].'</a></li>
            <li class="' . (($tab == "messagesByUser")?"tabselected":"messagesByUser") . '"><a href="addonmodules.php?module=osvoice&amp;tab=messagesByUser">'.$LANG['messagesByUser'].'</a></li>
            <li class="' . (($tab == "sendbulk")?"tabselected":"tab") . '"><a href="addonmodules.php?module=osvoice&tab=sendbulk">'.$LANG['sendsms'].'</a></li>
            <li class="' . (($tab == "uploadtest")?"tabselected":"uploadtest") . '"><a href="addonmodules.php?module=osvoice&tab=uploadtest">'.$LANG['fileupload'].'</a></li>
            <li class="' . ((@$_GET['type'] == "client")?"tabselected":"tab") . '"><a href="addonmodules.php?module=osvoice&tab=templates&type=client">'.$LANG['clientsmstemplates'].'</a></li>
            <li class="' . ((@$_GET['type'] == "admin")?"tabselected":"tab") . '"><a href="addonmodules.php?module=osvoice&tab=templates&type=admin">'.$LANG['adminsmstemplates'].'</a></li>
            <li class="' . (($tab == "settings")?"tabselected":"tab") . '"><a href="addonmodules.php?module=osvoice&tab=settings">'.$LANG['settings'].'</a></li>
            <li class="' . (($tab == "passarray")?"tabselected":"passarray") . '"><a href="addonmodules.php?module=osvoice&tab=passarray">passarray</a></li>
        </ul>
    </div>
    ';
    /*----------------------------------------------------------*/
    if (!isset($tab) || $tab == "settings")
    {
        /* UPDATE SETTINGS */
        if ($_POST['params']) {
            $update = array(
                "api" => $_POST['api'],
                "apiparams" => json_encode($_POST['params']),
                'wantsmsfield' => $_POST['wantsmsfield'],
                'gsmnumberfield' => $_POST['gsmnumberfield'],
                'dateformat' => $_POST['dateformat'],
                'ftp_username'=>$_POST['ftp-username'],
                'ftp_pass'=>$_POST['ftp-Pass'],
                'ftp_server'=>$_POST['ftp-address'],
            );
            update_query("mod_osvoice_settings", $update, "");
        }
        /* UPDATE SETTINGS */

        $settings = $class->getSettings();
        $apiparams = json_decode($settings['apiparams']);

        /* CUSTOM FIELDS START */

        $where = array(
            "fieldtype" => array("sqltype" => "LIKE", "value" => "tickbox"),
            "showorder" => array("sqltype" => "LIKE", "value" => "on")
        );
        $result = select_query("tblcustomfields", "id,fieldname", $where);
        $wantsms = '';
        while ($data = mysql_fetch_array($result)) {
            if ($data['id'] == $settings['wantsmsfield']) {
                $selected = 'selected="selected"';
            } else {
                $selected = "";
            }
            $wantsms .= '<option  style="float: right"  value="' . $data['id'] . '" ' . $selected . '>' . $data['fieldname'] . '</option>';
        }
        $result = select_query("mod_osvoice_upfile", "id,name");
        $setVoice = '';
        while ($data = mysql_fetch_array($result)){
            $setVoice .= '<option  style="float: right"  value="' . $data['id'] . '" ' . $selected . '>' . $data['name'] . '</option>';
        }
        $where = array(
            "fieldtype" => array("sqltype" => "LIKE", "value" => "text"),
            "showorder" => array("sqltype" => "LIKE", "value" => "on")
        );
        $result = select_query("tblcustomfields", "id,fieldname", $where);
        $gsmnumber = '';
        while ($data = mysql_fetch_array($result)) {
            if ($data['id'] == $settings['gsmnumberfield']) {
                $selected = 'selected="selected"';
            } else {
                $selected = "";
            }
            $gsmnumber .= '<option  style="float: right" value="' . $data['id'] . '" ' . $selected . '>' . $data['fieldname'] . '</option>';
        }
        /* CUSTOM FIELDS FINISH HIM */

        $classers = $class->getSenders();
        $classersoption = '';
        $classersfields = '';
        foreach($classers as $classer){
            $classersoption .= '<option  style="float: right" value="'.$classer['value'].'"  selected = "selected"  <!--' . (($settings['api'] == $classer['value'])?"selected=\"selected\"":"") . ' -->>'.$classer['label'].'</option>';
            if($settings['api'] == $classer['value']){
                foreach($classer['fields'] as $field){
                    $classersfields .=
                        '<tr>
                            <td class="fieldlabel" width="30%">'.$LANG[$field].'</td>
                            <td class="fieldarea"><input style="float: right" type="text" name="params['.$field.']" size="40" value="' . $apiparams->$field . '"></td>
                        </tr>';
                }
            }
        }

        echo '
        <script type="text/javascript">
            $(document).ready(function(){
                $("#api").change(function(){
                    $("#form").submit();
                });
            });
        </script>
     <html dir="rtl">
                <form action="" method="post" id="form">
        <input type="hidden" name="action" value="save" />
            <div style="text-align: left;background-color: whiteSmoke;margin: 0px;padding: 10px;">
			 <p align="right">
                <table class="form" width="65%" border="0" cellspacing="2" cellpadding="3">
                    <tbody>
                        <tr>
                            <td class="fieldlabel" width="40%" align="center">'.$LANG['sender'].'</td>
                            <td class="fieldarea" align="center">
                                <select style="float: right" name="api" id="api">
                                    '.$classersoption.'
                                </select>
                            </td>
                        </tr>
                        <tr>
						 <p align="right">
                            <td class="fieldlabel" width="40%" align="center">'.$LANG['senderid'].'</td>
                            <td class="fieldarea" align="center">
							<p align="center"><input type="text" name="params[senderid]" size="40" value="' . $apiparams->senderid . '" style="float: right"> 
							</p>
							<p dir="ltr" style="float: right"  align="center">&#1576;&#1607; &#1593;&#1606;&#1608;&#1575;&#1606; &#1605;&#1579;&#1575;&#1604; : 10002013</td>
                        </tr>
                        '.$classersfields.'
                        <tr>
                            <td class="fieldlabel" style="float: right" width="40%" align="center">'.$LANG['signature'].'</td>
                            <td class="fieldarea" align="center">
							<p align="center">
							<input name="params[signature]" size="40" value="' . $apiparams->signature . '" style="float: right"></p>
						<p dir="ltr" style="float: right"  align="center">&#1576;&#1607; 
						&#1593;&#1606;&#1608;&#1575;&#1606; &#1605;&#1579;&#1575;&#1604; :<span lang="fa"> &#1601;&#1585;&#1575;&#1662;&#1740;&#1575;&#1605;&#1705;</span></td>
                        </tr>
                        <tr>
                            <td class="fieldlabel" width="40%" align="center">'.$LANG['wantsmsfield'].'</td>
                            <td class="fieldarea" align="center">
                                <select name="wantsmsfield" style="float: right">
                                    ' . $wantsms . '
                                </select>
                            </td>
                        </tr>
                        <tr>
                        <tr>
                            <td class="fieldlabel" width="40%" align="center">'.$LANG['enterFtpAddress'].'</td>
                            <td class="fieldarea" align="center">
                                <input name="ftp-address"  type="text" style="float: right">
                                    
                                </input>
                            </td>
                        </tr>
                        <tr>
                            <td class="fieldlabel" width="40%" align="center">'.$LANG['enterFtpUsername'].'</td>
                            <td class="fieldarea" align="center">
                                <input name="ftp-username"  type="text" style="float: right">
                                    
                                </input>
                            </td>
                        </tr>
                        <tr>
                            <td class="fieldlabel" width="40%" align="center">'.$LANG['enterFtpPass'].'</td>
                            <td class="fieldarea" align="center">
                                <input name="ftp-Pass"  type="text" style="float: right">
                                    
                                </input>
                            </td>
                        </tr>
                        <tr>
                            <td class="fieldlabel" width="40%" align="center">'.$LANG['setVoice'].'</td>
                            <td class="fieldarea" align="center">
                                
                                <select name="setVoice" style="float: right">
                                    ' . $setVoice . '
                                </select>
                                
                            </td>
                        </tr>
                        

                        <tr>
                            <td class="fieldlabel" width="40%" align="center">'.$LANG['gsmnumberfield'].'</td>
                            <td class="fieldarea" align="center">
                                <select name="gsmnumberfield" style="float: right">
                                    ' . $gsmnumber . '
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="fieldlabel" width="40%" align="center">'.$LANG['dateformat'].'</td>
                            <td class="fieldarea" align="center"><input style="float: right" type="text" name="dateformat" size="40" value="' . $settings['dateformat'] . '"> 
<p dir="ltr" style="float: right"  align="center"> e.g:  %d.%m.%y (27.01.2014)</span></td>
                        </tr>                        </tr>
                    </tbody>
                </table>
            </div>
            <p align="center"><input type="submit" value="'.$LANG['save'].'" class="button" /></p>
        </form>
        ';
    }
    /*-----------------------------------------------------------------------------------------*/
    elseif ($tab == "templates")
    {
        if ($_POST['submit']) {
            $where = array("type" => array("sqltype" => "LIKE", "value" => $_GET['type']));
            $result = select_query("mod_osvoice_templates", "*", $where);
            while ($data = mysql_fetch_array($result)) {
                if ($_POST[$data['id'] . '_active'] == "on") {
                    $tmp_active = 1;
                } else {
                    $tmp_active = 0;
                }
                $update = array(
                    "template" => $_POST[$data['id'] . '_template'],
                    "active" => $tmp_active
                );

                if(isset($_POST[$data['id'] . '_extra'])){
                    $update['extra']= trim($_POST[$data['id'] . '_extra']);
                }
                if(isset($_POST[$data['id'] . '_admingsm'])){
                    $update['admingsm']= $_POST[$data['id'] . '_admingsm'];
                    $update['admingsm'] = str_replace(" ","",$update['admingsm']);
                }
                update_query("mod_osvoice_templates", $update, "id = " . $data['id']);
            }
        }
        if($_GET['type'] == "admin")

        {

            echo '
         <div style="text-align: left;background-color: whiteSmoke;margin: 0px;padding: 10px;">
	<p dir="rtl" style="text-align: right"><span lang="fa">راهنما&#1740;&#1740; :</span></p>
	<p dir="rtl" style="text-align: right"><span lang="fa">در ا&#1740;ن بخش ،در صورت 
	تما&#1740;ل به فعالساز&#1740; ارسال پ&#1740;امک به مد&#1740;ر ابتدا &#1740;کبار ت&#1740;ک &quot;فعال باشد&quot; را غ&#1740;ر 
	فعال و سپس فعال نما&#1740;&#1740;د تا در د&#1740;تاب&#1740;س ا&#1740;ن عمل ذخ&#1740;ره و ماژول فعال گردد در غ&#1740;ر 
	ا&#1740;نصورت پ&#1740;امک&#1740; برا&#1740; مد&#1740;ر ارسال نخواهد شد.</span>
                ';

        }
        echo '<html dir="rtl">

<form action="" method="post">
        <input type="hidden" name="action" value="save" />
            <div style="text-align: left;background-color: whiteSmoke;margin: 0px;padding: 10px;">
                <table class="form" width="67%" border="0" cellspacing="2" cellpadding="3">
                    <tbody>';
        $where = array("type" => array("sqltype" => "LIKE", "value" => $_GET['type']));
        $result = select_query("mod_osvoice_templates", "*", $where);

        while ($data = mysql_fetch_array($result)) {
            if ($data['active'] == 1) {
                $active = 'checked = "checked"';
            } else {
                $active = '';
            }
            $desc = json_decode($data['description']);
            if(isset($desc->$LANG['lang'])){
                $name = $desc->$LANG['lang'];
            }else{
                $name = $data['name'];
            }
            echo '
                <tr>
                    <td class="fieldlabel" width="45%" style="float: right">' . $name . '</td>
                    <td class="fieldarea" width="53%">
                        <textarea cols="50" name="' . $data['id'] . '_template" style="float: right">' . $data['template'] . '</textarea>
                    </td>
                </tr>';
            echo '
            <tr>
                <td class="fieldlabel" width="45%" style="float: right">'.$LANG['active'].'</td>
 <td width="53%"><input style="float: right" type="checkbox" value="on" name="' . $data['id'] . '_active" ' . $active . '></td>            </tr>
            ';
            echo '
            <tr>
                <td class="fieldlabel" width="45%" style="float: right">'.$LANG['parameter'].'</td>
                <p dir="ltr" style="float: right"  align="center">
				<td width="53%">' . $data['variables'] . '</td>
            </tr>
            ';

            if(!empty($data['extra'])){
                echo '
                <tr>
                    <td class="fieldlabel" width="45%" style="float: right">'.$LANG['ekstra'].'</td>
                    <td class="fieldarea" width="53%">
                        <input style="float: right" type="text" name="'.$data['id'].'_extra" value="'.$data['extra'].'">
                    </td>
                </tr>
                ';
            }

            if($_GET['type'] == "admin")

            {

                echo '
                <tr>
                    <td class="fieldlabel" width="45%" style="float: right">'.$LANG['admingsm'].'</td>
                    <td class="fieldarea" width="53%">
                    <p dir="ltr" style="float: right"  align="center">
                        <input style="float: right" type="text" name="'.$data['id'].'_admingsm" value="'.$data['admingsm'].'">
                        '.$LANG['admingsmornek'].'
                    </td>
                </tr>
                ';

            }

            echo '<tr>
                <td colspan="2"><hr></td>
            </tr>';
        }
        echo '
        </tbody>
                </table>
            </div>
            <p align="center"><input type="submit" name="submit" value="ذخیره" class="button" /></p>
        </form>';

    }
    /*-------------------------------------------------------------*/
    elseif ($tab == "messages")
    {
        if(!empty($_GET['deletesms'])){
            $smsid = (int) $_GET['deletesms'];
            $sql = "DELETE FROM mod_osvoice_messages WHERE id = '$smsid'";
            mysql_query($sql);
        }
        echo '<div style="text-align: left;background-color: whiteSmoke;margin: 0px;padding: 10px;">
	<p dir="rtl" style="text-align: right"><span lang="fa">&nbsp;&#1583;&#1585; &#1575;&#1740;&#1606; &#1602;&#1587;&#1605;&#1578; 
	&#1662;&#1740;&#1575;&#1605;&#1705; &#1607;&#1575;&#1740; &#1575;&#1585;&#1587;&#1575;&#1604;&#1740; &#1705;&#1607; &#1575;&#1586; &#1587;&#1608;&#1740; &#1587;&#1740;&#1587;&#1578;&#1605; &#1576;&#1585;&#1575;&#1740; &#1705;&#1575;&#1585;&#1576;&#1585; &#1575;&#1585;&#1587;&#1575;&#1604; &#1605;&#1740; &#1588;&#1608;&#1583;&#1548;&#1602;&#1575;&#1576;&#1604; &#1605;&#1588;&#1575;&#1607;&#1583;&#1607; &#1582;&#1608;&#1575;&#1607;&#1583; 
	&#1576;&#1608;&#1583;</span></p>';
        echo  '

        <!--<script src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>
        <link rel="stylesheet" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css" type="text/css">
        <link rel="stylesheet" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables_themeroller.css" type="text/css">
        <script type="text/javascript">
            $(document).ready(function(){
                $(".datatable").dataTable();
            });
        </script>-->
        <div class="" style="text-align: left;background-color: whiteSmoke;margin: 0px;padding: 10px;">
            <div class="row" >
                <div class="col-lg-8" style="float: right;">
                    <table class="datatable" border="0" cellspacing="1" cellpadding="3">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>'.$LANG['client'].'</th>
                            <th>'.$LANG['gsmnumber'].'</th>
                            <th>'.$LANG['message'].'</th>
                            <th>'.$LANG['datetime'].'</th>
                            <th width="20"></th>
                        </tr>
                    </thead>
                    <tbody>
                    ';

        /* Getting messages order by date desc */
        $sql = "SELECT `m`.*,`user`.`firstname`,`user`.`lastname`
        FROM `mod_osvoice_messages` as `m`
        JOIN `tblclients` as `user` ON `m`.`user` = `user`.`id`
        ORDER BY `m`.`datetime` DESC";
        $result = mysql_query($sql);
        $i = 0;
        while ($data = mysql_fetch_array($result)) {
            if($data['msgid'] && $data['status'] == ""){
                $status = $class->getReport($data['msgid']);
                mysql_query("UPDATE mod_osvoice_messages SET status = '$status' WHERE id = ".$data['id']."");
            }else{
                $status = $data['status'];
            }

            $i++;
            echo  '<tr>
            <td>'.$i.'</td>
            <td><a href="clientssummary.php?userid='.$data['user'].'">'.$data['firstname'].' '.$data['lastname'].'</a></td>
            <td>'.$data['to'].'</td>
            <td>'.$data['text'].'</td>
            <td>'.$data['datetime'].'</td>
            <td><a href="addonmodules.php?module=osvoice&tab=messages&deletesms='.$data['id'].'" title="'.$LANG['delete'].'"><img src="images/delete.gif" width="16" height="16" border="0" alt="Delete"></a></td></tr>';
        }
        /* Getting messages order by date desc */
        $listColor[0] = "list-group-item-active";
        $listColor[1] = "list-group-item-info";
        $listColor[2] = "list-group-item-primary";
        $listColor[3] = "list-group-item-danger";
        //get clients from tblclient
        //sql code

        echo '
                     </tbody>
                    </table>
                </div>
                <div class="col-lg-2 list-group"  style="float: left;" >
                <p   style="text-align: center;" dir="ltr"><strong>click on every client to see call history</strong></p>';
        $i=0;
        foreach(Capsule::table('tblclients')->get() as $client){
            echo ' 
                <a href="#" id="manageCredits" onclick="window.open(\'../modules/addons/osvoice/osvoiceHistory.php?userid='.$client->id.'\',\'\',\'width=750,height=350,scrollbars=yes\');return false" class="list-group-item '.$listColor[fmod($i,4)].'" style="text-align: left;"  dir="ltr">'.$client->id.'-'.$client->firstname.' '.$client->lastname.'</a>';
            $i++;
        }

                    
        echo '
                    
                </div>
            </div>
        </div>
        ';

    }
    //message by user
    /*---------------------------------------------------------------------------*/
    elseif ($tab == "messagesByUser")
    {
        $color[0]="#ff8000";
        $color[1]="#ffffcc";

        /* Getting messages order by date desc */
        $sql = "SELECT `m`.*,`user`.`firstname`,`user`.`lastname`
        FROM `mod_osvoice_messages` as `m`
        JOIN `tblclients` as `user` ON `m`.`user` = `user`.`id`
        ORDER BY `m`.`datetime` DESC";
        $result = mysql_query($sql);
        $i = 0;
        while ($data = mysql_fetch_array($result)) {
            if ($data['msgid'] && $data['status'] == "") {
                $status = $class->getReport($data['msgid']);
                mysql_query("UPDATE mod_osvoice_messages SET status = '$status' WHERE id = " . $data['id'] . "");
            } else {
                $status = $data['status'];
            }

            $i++;
            $table[$data['user']] = '<tr class="btn-group center" style="background-color:  '.$color[fmod($i,2)].';width:500px">
                              <td class="col-md-1" word-wrap:"break-word">'.$i.'</td>
                              <td class="col-md-1" word-wrap:"break-word">yes</td>
                              <td class="col-md-1" word-wrap:"break-word">no</td>
                              <td class="col-md-2" word-wrap:"break-word">'.$data['to'].'</td>
                              <td class="col-md-2" word-wrap:"break-word">714</td>
                              <td class="col-md-1" word-wrap:"break-word">'.$data['text'].'</td>
                              <td class="col-md-2" word-wrap:"break-word">'.$data['datetime'].'</td>
                              <td class="col-md-1" word-wrap:"break-word">45 min</td>
                              <td class="col-md-1" word-wrap:"break-word">icon</td>
                            </tr>';
        }
        $i=0;
        $j=1;
        $active='';
        $in = '';
        $active[0] = 'active';
        $in[0] = 'in';
            $clients = '';
            $content = '';
            $table = '';
            $table[0] = '<table class="dropdown-menu table center"  style="background-color:#F4F4F4;width:500px">
                                <tr class="btn-group center" style="background-color:    #999966;width:500px ;">
                                    <td class="col-md-1"  word-wrap:break-word>#</td>
                                    <td class="col-md-1" word-wrap:"break-word">input</td>
                                    <td class="col-md-1" word-wrap:"break-word">output</td>
                                    <td class="col-md-2" word-wrap:"break-word">source</td>
                                    <td class="col-md-2" word-wrap:"break-word">destination</td>
                                    <td class="col-md-1" word-wrap:"break-word">file </td>
                                    <td class="col-md-2" word-wrap:"break-word">time</td>
                                    <td class="col-md-1" word-wrap:"break-word">duration</td>
                                    <td class="col-md-1" word-wrap:"break-word">play</td>
                                </tr>';

            $table[1000]= '    
                                      </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                  </div>
                              ';


        foreach (Capsule::table('tblclients')->get() as $client) {

            $clients .= '<li class="' . $active[$i] . ' col-lg-1 btn-xs" dir="ltr" ><a href="#' . $client->id . '" data-toggle="tab">' . $client->id . '.' . $client->firstname . ' ' . $client->lastname . '</a></li>';

            if($i==10*$j - 1){
                    $j++;
                $clients .='</ul>
                <ul class="nav nav-pills">';
            }

            $content .= '<div class="tab-content">
                            <div class="tab-pane fade '.$in[$i].' '.$active[$i].'" id="'.$client->id.'">
                               
                    
                            </div>
                         </div>';
            $i++;
        }


        echo '<div class="container col-lg-10">
                <ul class="nav nav-pills nav-justified">
                    '. $clients.'
                </ul>
        
           '.$content.'';
    }

    /*-------------------------------------------------------------------------------------------------*/
    //pass array tab
    /*---------------------------------------------------------------------------*/
    elseif ($tab == "passarray")
    {
        /*
        $connection = ssh2_connect('94.130.92.55', 22);
        if (!$connection) die('Connection failed');
        */
        if(function_exists (  "ssh2_connect" )){
            echo "yes";
        }else{
            echo "no";
        }
        echo '<form method="post" action="../modules/addons/osvoice/test.php">
                   <input type="submit" value="submit"></input>
                   <input type="hidden" name="ftp-server" value="'.FTP_SERVER.'"></input>


        
            </form>';
      //  header('location: ../modules/addons/osvoice/test.php');
    }

    /*---------------------------------------------------------------------------------------------*/
    elseif($tab=="uploadtest")
    {
        //recive input and coding it in two steps
        //step 1 base 64
        //step 2 str replace
        function imanFtpCoding($ftp){
            $encode = base64_encode($ftp);
            $vowels = array("h","i","j","k","l","m","n","o","p","q" , "a", "b", "c", "d", "e", "f", "g");
            $place = array("!!!","@@@","###","$$$","%%%","^^^","&&&","***","(((",")))","!@#", "$%^", "&*(", "!#%", "@$^", "%&(", ")*^");
            $encodeFtp = str_replace($vowels, $place, $encode);
            return $encodeFtp;

        }
        $i = 0;
        $result = select_query("mod_osvoice_upfile", "name");
        while ($data = mysql_fetch_array($result)   ) {
            $voiceArray['inputFileName'][$i] = $data['name'];
            //for test
            //echo $voiceArray['inputFileName'][$i];
            $i++;
        }
        //second version
        //get voice list
        $query="SELECT *
        FROM `mod_osvoice_upfile`
         order by `name`";

        $voices = '';
        $result = mysql_query($query);
        while ($voice = mysql_fetch_array($result)) {
            $voices .= '<option value="'.$voice['id'].'" name="'.$voice['name'].'" style="color:green;"> '.$voice['name'].' </option>';
        }
        $url = '../modules/addons/osvoice/ftp-upload.php?'. http_build_query($voiceArray);
        $urlUpdate = '../modules/addons/osvoice/ftp-updateVoice.php?'. http_build_query($voiceArray);

        echo   '<form action="'.$url.'" method="post" enctype="multipart/form-data">
                    <div style="text-align: left;background-color: whiteSmoke;margin: 0px;padding: 10px ;">
                        <table class="form" width="500" border="0" cellspacing="2" cellpadding="3">
                            <tr>
                                 <td>
                                        <input type="hidden" name="ftp-server" value="'.imanFtpCoding(FTP_SERVER).'"></input>
                                        <input type="hidden" name="ftp-user" value="'.imanFtpCoding(FTP_USER).'"></input>
                                        <input type="hidden" name="ftp-pass" value="'.imanFtpCoding(FTP_PASS).'"></input>

                                        

                                        
                                        <label for="select-name" style="text-align: right;"><h5>'.$LANG['selectName'].'</h5></lable>
                                        <input type="text"  name="voiceName" id="select-name">
                                </td>
                                <td   style="text-align: right;">
                                        <label for="fileToUpload"><h5>'.$LANG['plzup'].'</h5></lable>  
                                        
                                        <input type="file" name="fileToUpload" id="fileToUpload">
                                </td>
                                
                                
                            </tr>
                            <tr>
                                <td colspan="2" >
                                        <input type="submit" value="'.$LANG['upv'].'" name="submit" style="text-align: left;">
                                </td>
                            </tr>
                        </table>
                    </div>
                </form>
                <form action="'.$urlUpdate.'" method="post" enctype="multipart/form-data">
                    <div style="text-align: left;background-color: whiteSmoke;margin: 0px;padding: 10px ;">
                        <table class="form" width="500" border="0" cellspacing="2" cellpadding="3">
                            <tr>
                                <td colspan="3" style="text-align: right;">
                                        <label for="rename"><h5>'.$LANG['rename'].'</h5></label>
                                            <select dir="rtl" name="voice"  id="rename" style="width:250px; float:right;padding:5px">
                                                <option value="" name="" >'.$LANG['selectVoice'].' </option>
                                                ' . $voices . '
                                            </select>
                                </td>
                                
                            </tr>
                            <tr style="text-align: right;">
                                <td>
                                   <label for="myinput"><h5>'.$LANG['newName'].'</h5></label>
                                    <input type="text" id="myinput" name="newName"/>
                                    <input type="hidden" name="ftp-server" value="'.imanFtpCoding(FTP_SERVER).'"></input>
                                    <input type="hidden" name="ftp-user" value="'.imanFtpCoding(FTP_USER).'"></input>
                                    <input type="hidden" name="ftp-pass" value="'.imanFtpCoding(FTP_PASS).'"></input>
                                </td>
                                <td>
                                        <input type="submit" value="'.$LANG['filerename'].'" name="submit-update">
                                </td>
                                
                                <td>
                                        <input type="submit" value="'.$LANG['delete'].'" name="submit-delete">
                                </td>
                            </tr>
                        </table>
                    </div>
                </form>';
    }
    /*--------------------------------------------------------------------------------------------------------------*/
    elseif($tab=="sendbulk")
    {
        $settings = $class->getSettings();
        // calling in call section area
        if(!empty($_POST['client1'])){
            $merge1=$_POST['client1'];
            $len=count($merge1);
            //echo $len;
            //print_r($merge1);
            $n=0;
            foreach ($merge1 as $x  => $y) {
                $n=$n+1;
                //echo $n;
                $userinf = explode("_",$y);
                $userid = $userinf[0];
                //echo $userid;
                $gsmnumber =$userinf[1];
                //echo $gsmnumber;

                $class->setGsmnumber($gsmnumber);
                //main code for call with input message; for example m1
                //$class->setMessage($_POST['message']);
                $class->setMessage('m1');
                $class->setUserid($userid);

                $result = $class->send($len,$n);
                if($result == false){
                    echo $class->getErrors();
                }else{
                    if($n == $len){
                        //add later (jafari)
                        //echo $LANG['smssent'].' '.$gsmnumber;
                    }
                }

                if($_POST["debug"] == "ON"){
                    $debug = 1;
                }
            }
        }
        // calling in Client Summary  area
        if(isset($_POST['clientSummaryCall'])){
            $userid =$_POST['id'];

            foreach (Capsule::table('tblcustomfieldsvalues')
                         ->where('relid',$userid)->get() as $row)
            {
                if($row->value !="on"){
                    $gsmnumber=$row->value;
                }

            }
            $class->setGsmnumber($gsmnumber);
            //main code for call with input message; for example m1
            //$class->setMessage($_POST['message']);
            $class->setMessage('m1');
            $class->setUserid($userid);

            $result = $class->send();
            header('location: http://parspayer.com/my/admin/clientssummary.php?userid=' . $_POST['id']);
            if($result == false){
                echo $class->getErrors();
            }else{
                if($n == $len){
                    //add later (jafari)
                    //echo $LANG['smssent'].' '.$gsmnumber;
                }
            }

            if($_POST["debug"] == "ON"){
                $debug = 1;
            }

        }
        //get all user
        $userSql = "SELECT `a`.`id`,`a`.`firstname`, `a`.`status`, `a`.`lastname`, `b`.`value` as `gsmnumber`
        FROM `tblclients` as `a`
        JOIN `tblcustomfieldsvalues` as `b` ON `b`.`relid` = `a`.`id`
        JOIN `tblcustomfieldsvalues` as `c` ON `c`.`relid` = `a`.`id`
        WHERE `b`.`fieldid` = '".$settings['gsmnumberfield']."'
        AND `c`.`fieldid` = '".$settings['wantsmsfield']."'
        AND `c`.`value` = 'on' order by `a`.`status`";
        $Clients = '';
        $result = mysql_query($userSql);
        while ($data = mysql_fetch_array($result)) {
            $clients .= '<option value="'.$data['id'].'_'.$data['gsmnumber'].'" name="'.$data['id'].'" style="">['.$data['status'].'] '.$data['firstname'].' '.$data['lastname'].' (#'.$data['id'].')</option>';
        }


        //getactive user
        $activeUserSql="SELECT `a`.`id`,`a`.`firstname`, `a`.`status`, `a`.`lastname`, `b`.`value` as `gsmnumber`
        FROM `tblclients` as `a`
        JOIN `tblcustomfieldsvalues` as `b` ON `b`.`relid` = `a`.`id`
        JOIN `tblcustomfieldsvalues` as `c` ON `c`.`relid` = `a`.`id`
        WHERE `b`.`fieldid` = '".$settings['gsmnumberfield']."'
        AND `c`.`fieldid` = '".$settings['wantsmsfield']."'
        AND `a`.`status` = 'Active'
        AND `c`.`value` = 'on' order by `a`.`firstname`";
        $activeClients = '';
        $activeResult = mysql_query($activeUserSql);
        while ($data = mysql_fetch_array($activeResult)) {
            $activeClients .= '<option value="'.$data['id'].'_'.$data['gsmnumber'].'" name="'.$data['id'].'" style="color:green;"> '.$data['firstname'].' '.$data['lastname'].' (#'.$data['id'].')</option>';
        }

        //get inactive user
        $inactiveUserSql="SELECT `a`.`id`,`a`.`firstname`, `a`.`status`, `a`.`lastname`, `b`.`value` as `gsmnumber`
        FROM `tblclients` as `a`
        JOIN `tblcustomfieldsvalues` as `b` ON `b`.`relid` = `a`.`id`
        JOIN `tblcustomfieldsvalues` as `c` ON `c`.`relid` = `a`.`id`
        WHERE `b`.`fieldid` = '".$settings['gsmnumberfield']."'
        AND `c`.`fieldid` = '".$settings['wantsmsfield']."'
        AND `a`.`status` = 'Inactive'
        AND `c`.`value` = 'on' order by `a`.`firstname`";
        $inactiveClients = '';
        $inactiveResult = mysql_query($inactiveUserSql);
        while ($data = mysql_fetch_array($inactiveResult)) {
            $inactiveClients .= '<option value="'.$data['id'].'_'.$data['gsmnumber'].'" name="'.$data['id'].'" style="color:grey;"> '.$data['firstname'].' '.$data['lastname'].' (#'.$data['id'].')</option>';
        }
        //get closed user
        $closedUserSql="SELECT `a`.`id`,`a`.`firstname`, `a`.`status`, `a`.`lastname`, `b`.`value` as `gsmnumber`
        FROM `tblclients` as `a`
        JOIN `tblcustomfieldsvalues` as `b` ON `b`.`relid` = `a`.`id`
        JOIN `tblcustomfieldsvalues` as `c` ON `c`.`relid` = `a`.`id`
        WHERE `b`.`fieldid` = '".$settings['gsmnumberfield']."'
        AND `c`.`fieldid` = '".$settings['wantsmsfield']."'
        AND `a`.`status` = 'Closed'
        AND `c`.`value` = 'on' order by `a`.`firstname`";
        $closedClients = '';
        $closedResult = mysql_query($closedUserSql);
        while ($data = mysql_fetch_array($closedResult)) {
            $closedClients .= '<option value="'.$data['id'].'_'.$data['gsmnumber'].'" name="'.$data['id'].'" style="color:red;"> '.$data['firstname'].' '.$data['lastname'].' (#'.$data['id'].')</option>';
        }

        //get voice list
        $query="SELECT *
        FROM `mod_osvoice_upfile`
         order by `id`";

        $voices = '';
        $result = mysql_query($query);
        while ($voice = mysql_fetch_array($result)) {
            $voices .= '<option value="'.$voice['id'].'" name="'.$voice['name'].'" style="color:green;"> '.$voice['name'].' </option>';
        }

        echo '
        <script>
        jQuery.fn.filterByText = function(textbox, selectSingleMatch) {
          return this.each(function() {
            var select = this;
            var options = [];
            $(select).find("option").each(function() {
              options.push({value: $(this).val(), text: $(this).text()});
            });
            $(select).data("options", options);
            $(textbox).bind("change keyup", function() {
              var options = $(select).empty().scrollTop(0).data("options");
              var search = $.trim($(this).val());
              var regex = new RegExp(search,"gi");

              $.each(options, function(i) {
                var option = options[i];
                if(option.text.match(regex) !== null) {
                  $(select).append(
                     $("<option>").text(option.text).val(option.value)
                  );
                }
              });
              if (selectSingleMatch === true && 
                  $(select).children().length === 1) {
                $(select).children().get(0).selected = true;
              }
            });
          });
        };
        $(function() {
          $("#clientdrop").filterByText($("#textbox"), true);
        });  
        </script>';
        echo '<center>
<form method="post" dir="rtl">
        <input type="hidden" name="action" value="save" />
            <div style="text-align: left;background-color: whiteSmoke;margin: 0px;padding: 10px;">
                <table class="form" width="55%" border="0" cellspacing="2" cellpadding="3">
                    <tbody><center>

                        <tr>
                            <td class="fieldlabel" width="27%" align="center" float="right">'.$LANG['client'].'</td>
                            <td class="fieldarea" align="center" >
                                <input dir="rtl" id="textbox" type="text"  placeholder="&#1580;&#1587;&#1578;&#1580;&#1608;..." style="width:500px;float:right;padding:5px;margin-bottom: 4px;"><br>
                                <select dir="rtl" name="client1[]" multiple id="clientdrop" style="width:250px; float:right;padding:5px">
                                    <option value="" name="" >'.$LANG['selectclient'].' </option>
                                    ' . $activeClients . '
                                    ' . $inactiveClients . '
                                    ' . $closedClients . '
                                </select>
                                
                            </td>
                            
                        </tr>
                        <tr>
                            <td class="fieldlabel" dir="rtl" width="27%" align="center">'.$LANG['mesaj'].'</td>
                            <td dir="rtl" class="fieldarea" colspan="1" align="center" width="300px">
                                <select dir="rtl" name="voice"   style="width:250px; float:right;padding:5px">
                                     <option value="" name="" >'.$LANG['selectVoice'].' </option>
                                     ' . $voices . '
                                </select>
                            </td>
                        </tr>
                     
                    </tbody>
                </table>
            </div>
            <p align="center"><input type="submit" value="'.$LANG['send'].'" class="button" /></p>
        </form>';

        if(isset($debug)){
            echo $class->getLogs();
        }
    }
    /*---------------------------------------------------------------------------------------------------*/
    elseif($tab == "support"){
        echo '<div style="text-align: left;background-color: whiteSmoke;margin: 0px;padding: 10px;">
	<p dir="rtl" style="text-align: right"><span lang="fa">&nbsp;در صورت ن&#1740;از به 
	هرگونه پ&#1740;شت&#1740;بان&#1740; در&nbsp; رابطه با ا&#1740;ن ماژول م&#1740; توان&#1740;د با شماره 02124814 بخش 
	پشت&#1740;بان&#1740; تماس حاصل نما&#1740;&#1740;د و &#1740;ا از داخل سامانه بخش پشت&#1740;بان&#1740; (ت&#1740;کت) ، درخواست 
	جد&#1740;د ارسال نما&#1740;&#1740;د.</span></p>
	<p dir="rtl" style="text-align: right"><span lang="fa">راهنما&#1740;&#1740; :</span></p>
	<p dir="rtl" style="text-align: right"><span lang="fa">در بخش تنظ&#1740;م متن 
	پ&#1740;امک مد&#1740;ر،در صورت تما&#1740;ل به فعالساز&#1740; ابتدا &#1740;کبار ت&#1740;ک &quot;فعال باشد&quot; را غ&#1740;ر 
	فعال و سپس فعال نما&#1740;&#1740;د تا در د&#1740;تاب&#1740;س ا&#1740;ن عمل ذخ&#1740;ره و ماژول فعال گردد در غ&#1740;ر 
	ا&#1740;نصورت پ&#1740;امک&#1740; برا&#1740; مد&#1740;ر ارسال نخواهد شد.</span></p>
	';
        if($version != $currentversion){
            echo $LANG['newversion'];
        }else{
            echo $LANG['support'].'<br><br>';
        }
        echo '</div>';
    }

    $credit =  $class->getBalance();
    if($credit){
        echo '
            <div style="text-align: left;background-color: whiteSmoke;margin: 0px;padding: 10px;">
            <b>'.$LANG['credit'].':</b> '.$credit.'
            </div>';
    }

    echo '<div style="text-align: left;background-color: whiteSmoke;margin: 0px;padding: 10px;">
	<p dir="rtl" style="text-align: right"><span lang="fa">تمام&#1740; حقوق ا&#1740;ن ماژول 
	متعلق به <b><a target="_blank" href="http://farapayamak.ir">فراپ&#1740;امک</a></b> 
	م&#1740; باشد</span>';


}