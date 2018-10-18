<?php
/* WHMCS SMS Addon with GNU/GPL Licence
 * AktuelHost - http://www.aktuelhost.com
 *
 * https://github.com/AktuelSistem/WHMCS-SmsModule
 *
 * Developed at Aktuel Sistem ve Bilgi Teknolojileri (www.aktuelsistem.com)
 * Licence: GPLv3 (http://www.gnu.org/licenses/gpl-3.0.txt)
 * */
/*------------------------------------------------------------my code*/
use WHMCS\Database\Capsule;
use WHMCS\Session;
use WHMCS\Ticket\Watchers;
// Print all client first names using a simple select.




/** @var stdClass $client */
/*************************************************************/
add_hook('ClientAreaHomepage', 1, function($vars) {
    $n = 10;
    $stream = fopen("https://onlineserver.ir/category/news/feed/","r");
    $string = stream_get_contents($stream);

    preg_match_all("#<a.*?>([^<]+)</a>#", $string, $foo);
    $i = 0;
    $aContent = array();
    foreach($foo[1] as $f){
    if(fmod($i,4) == 0){
    $aContent[] = $f;
    }
    $i++;
    }
    preg_match_all("#<p>([^<]+)\[.*?\]</p>#", $string, $foo);
    $i = 0;
    $pContent = array();
    foreach($foo[1] as $f){
    $pContent[] = $f;
    }

    preg_match_all('/\b(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)[-A-Z0-9+&@#\/%=~_|$?!:,.]*[A-Z0-9+&@#\/%=~_|$]/i', $string, $results, PREG_PATTERN_ORDER);
    $results = $results[0];
    $i = 0;
    $aHref = array();
    foreach ($results as $result){
    if($i< 24 && $i > 7){
    if(fmod($i-9,6) == 0){
    $aHref[] = $result;
    }
    }
    if($i >29){
    if(fmod($i-30,6) == 0){
    $aHref[] = $result;
    }
    }
    $i++;

    }
    function substrwords($text, $maxchar, $end='...')
    {
    if (strlen($text) > $maxchar || $text == '') {
    $words = preg_split('/\s/', $text);
    $output = '';
    $i = 0;
    while (1) {
    $length = strlen($output) + strlen($words[$i]);
    if ($length > $maxchar) {
    break;
    } else {
    $output .= " " . $words[$i];
    ++$i;
    }
    }
    $output .= $end;
    } else {
    $output = $text;
    }
    return $output;
    }
    $pContentEdit = array();
    foreach($pContent as $p){
        $pContentEdit[] = substrwords($p , 315);
    }
    global $smarty;

    $vars['pContentEdit'] = $pContentEdit;
    $vars['aHref'] = $aHref;
    $vars['aContent'] = $aContent;
    $smarty->assign('VARIABLE_COMES_FROM_HOOK', $vars);
    
    return $vars['pContentEdit'][4];
    
});
/*-------------------------------------------------------------------------*/


/*---------------------------------------------------------------------------------*/

add_hook('ClientAreaPage', 1, function($vars) {
    $stream = fopen("https://onlineserver.ir/category/news/feed/","r");
    $string = stream_get_contents($stream);

    preg_match_all("#<a.*?>([^<]+)</a>#", $string, $foo);
    $i = 0;
    $aContent = array();
    foreach($foo[1] as $f){
    if(fmod($i,4) == 0){
    $aContent[] = $f;
    }
    $i++;
    }
    preg_match_all("#<p>([^<]+)\[.*?\]</p>#", $string, $foo);
    $i = 0;
    $pContent = array();
    foreach($foo[1] as $f){
    $pContent[] = $f;
    }

    preg_match_all('/\b(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)[-A-Z0-9+&@#\/%=~_|$?!:,.]*[A-Z0-9+&@#\/%=~_|$]/i', $string, $results, PREG_PATTERN_ORDER);
    $results = $results[0];
    $i = 0;
    $aHref = array();
    foreach ($results as $result){
    if($i< 24 && $i > 7){
    if(fmod($i-9,6) == 0){
    $aHref[] = $result;
    }
    }
    if($i >29){
    if(fmod($i-30,6) == 0){
    $aHref[] = $result;
    }
    }
    $i++;

    }
    function substrword($text, $maxchar, $end='...')
    {
    if (strlen($text) > $maxchar || $text == '') {
    $words = preg_split('/\s/', $text);
    $output = '';
    $i = 0;
    while (1) {
    $length = strlen($output) + strlen($words[$i]);
    if ($length > $maxchar) {
    break;
    } else {
    $output .= " " . $words[$i];
    ++$i;
    }
    }
    $output .= $end;
    } else {
    $output = $text;
    }
    return $output;
    }
    $pContentEdit = array();
    foreach($pContent as $p){
        $pContentEdit[] = substrword($p , 315);
    }
    global $smarty;

    $vars['pContentEdit'] = $pContentEdit;
    $vars['aHref'] = $aHref;
    $vars['aContent'] = $aContent;
    $smarty->assign('VARIABLE_COMES_FROM_HOOK', $vars);
    
    return $vars['pContentEdit'][4];
});


/*---------------------------------------------------------------------------------*/


add_hook('AdminAreaClientSummaryPage', 1, function($vars) {
    $color[0]="#ff8000";
    $color[1]="#ffffcc";


    /* Getting messages order by date desc */
    $sql = "SELECT `m`.*,`user`.`firstname`,`user`.`lastname`
    FROM `mod_osvoice_messages` as `m`
    JOIN `tblclients` as `user` ON `m`.`user` = `user`.`id`
    ORDER BY `m`.`datetime` DESC";
    $result = mysql_query($sql);
    $i = 0;
    $url3 = 'http://parspayer.com/my/admin/addonmodules.php?module=osvoice&tab=sendbulk';
    foreach (Capsule::table('mod_osvoice_upfile')->get() as $voice) {
        $options .= '<option value="' . $voice->id . '">  ' . $voice->name . '  </option>';
    }
    foreach (Capsule::table('tblclients')->get() as $row) {
        if($row->id == $_GET['userid']){
            $number = $row->phonenumber;
        }
    }
    //href="../modules/addons/osvoice/osvoiceHistory.php?userid='.$_GET['userid'].'"
    $return = '';
    $return= '
            <script>function openCCDetails() {
                    var winl = (screen.width - 750) / 2;
                    var wint = (screen.height - 635) / 2;
                    winprops = \'height=635,width=750,top=\'+wint+\',left=\'+winl+\',scrollbars=yes\'
                    win = window.open(\'../modules/addons/osvoice/osvoiceHistory.php?userid='.$_GET['userid'].'\', \'ccdetails\', winprops)
                    if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
                    }</script>
            
            <div class="row client-summary-panels" style="margin-bottom: -500px;" >
                <div class="col-lg-5 col-sm-5">
                   <div class="clientssummarybox">
                        <div class="title">Call to '.$number.'</div>
                        <form action="'.$url3.'" method="post">
                            <input name="token" value="62df4ba3bf68797f1daff7162b8a8430d77fc7c8" type="hidden"> 
                            <input name="id" value="'.$_GET['userid'].'" type="hidden">
                            <div align="center">
                                <select name="messageID" class="form-control select-inline">
                                    <option value="0">Voice Name </option>
                                    '.$options.'
                                </select>
                       
                                <input type="submit" name="clientSummaryCall" value="Call" class="btn btn-success btn-sm">
                            
                                </input>
                            </div>
                        </form>
                            
        
                    </div>
                </div>
                 <div class="col-lg-7 col-sm-7">
                    <div align="center">
                       <div class="clientssummarybox">
                       
                       <div class="title">Call History</div>
                           <div class="dropdown">
                              <a href="#" id="manageCredits" onclick="openCCDetails();return false" class="btn btn-danger " type="button" id="" data-toggle="" aria-haspopup="true" aria-expanded="true">
                                Click here to see history of incoming and outgoing calls
                                
                              </a>
                              <table class="dropdown-menu table center" aria-labelledby="dropdownMenu1" style="background-color:#F4F4F4;width:500px">
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
    while ($data = mysql_fetch_array($result)) {
        if($data['msgid'] && $data['status'] == ""){
            $status = $class->getReport($data['msgid']);
            mysql_query("UPDATE mod_osvoice_messages SET status = '.$status.' WHERE id = ".$data['id']."");

        }else{
            $status = $data['status'];
        }
        if($_GET['userid']==$data['user']){
            $i++;
            $return.= '<tr class="btn-group center" style="background-color:  '.$color[fmod($i,2)].';width:500px">
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
    }
    $return .= '    
                                      </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                  </div>
                              ';
    return $return;
});



/*------------------------------------------------------------*/
if (!defined("WHMCS"))
    die("This file cannot be accessed directly");
require_once("phone.php");
$class = new osvoice();
$hooks = $class->getHooks();

foreach($hooks as $hook){
    add_hook($hook['hook'], 1, $hook['function'], "");
}
