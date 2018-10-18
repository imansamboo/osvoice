<?php
use WHMCS\Database\Capsule;
/*
$hook = array(
    'hook' => 'AdminAreaClientSummaryPage',
    'function' => 'SummaryPage',
    'description' => array(
        'turkish' => ' mesaj gönderir',
        'english' => 'After order accepted'
    ),
    'type' => 'client',
    'extra' => '',
    'defaultmessage' => '{lastname} عزیز, سفارش شما به شماره {orderid} تایید شد.',
    'variables' => '{firstname},{lastname},{orderid}'
);
if(!function_exists('SummaryPage')){
    function SummaryPage($args){

        $class = new osvoice();
        $template = $class->getTemplateDetails(__FUNCTION__);

        if($template['active'] == 0){
            return null;
        }
        $settings = $class->getSettings();
        if(!$settings['api'] || !$settings['apiparams'] || !$settings['gsmnumberfield'] || !$settings['wantsmsfield']){
            return null;
        }
        
    $url3 = 'http://parspayer.com/my/admin/addonmodules.php?module=osvoice&tab=sendbulk';
    foreach (Capsule::table('mod_osvoice_upfile')->get() as $voice) {
        $options .= '<option value="' . $voice->id . '">  ' . $voice->name . '  </option>';
    }
    foreach (Capsule::table('tblclients')->get() as $row) {
        if($row->id == $_GET['userid']){
            $number = $row->phonenumber;
        }
    }
    
    
    return '
            
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
                              <button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                Click here to see history of incoming and outgoing calls
                                <span class="caret"></span>
                              </button>
                              <table class="dropdown-menu table center" aria-labelledby="dropdownMenu1" style="background-color:#F4F4F4;width:500px">
                                <tr class="btn-group center" style="background-color:    #999966;width:500px ;position:fixed;">
                                    <td class="col-md-1"  word-wrap:break-word>#</td>
                                    <td class="col-md-1" word-wrap:"break-word">input</td>
                                    <td class="col-md-1" word-wrap:"break-word">output</td>
                                    <td class="col-md-2" word-wrap:"break-word">source</td>
                                    <td class="col-md-2" word-wrap:"break-word">destination</td>
                                    <td class="col-md-2" word-wrap:"break-word">date</td>
                                    <td class="col-md-1" word-wrap:"break-word">time</td>
                                    <td class="col-md-1" word-wrap:"break-word">duration</td>
                                    <td class="col-md-1" word-wrap:"break-word">play</td>
                                </tr>
                                <tr class="btn-group center" style="background-color:  #ffffcc;width:500px">
                                    <td class="col-md-1" word-wrap:"break-word">1</td>
                                    <td class="col-md-1" word-wrap:"break-word">yes</td>
                                    <td class="col-md-1" word-wrap:"break-word">no</td>
                                    <td class="col-md-2" word-wrap:"break-word">09120621785</td>
                                    <td class="col-md-2" word-wrap:"break-word">714</td>
                                    <td class="col-md-2" word-wrap:"break-word">1996/23/07</td>
                                    <td class="col-md-1" word-wrap:"break-word">12:51</td>
                                    <td class="col-md-1" word-wrap:"break-word">21 min</td>
                                    <td class="col-md-1" word-wrap:"break-word">icon</td>
                                </tr>
                                <tr class="btn-group center" style="background-color:  coral;width:500px">
                                    <td class="col-md-1">1</td>
                                    <td class="col-md-1">yes</td>
                                    <td class="col-md-1">no</td>
                                    <td class="col-md-2">09120621785</td>
                                    <td class="col-md-2">714</td>
                                    <td class="col-md-2">1996/23/07</td>
                                    <td class="col-md-1">12:51</td>
                                    <td class="col-md-1">21 min</td>
                                    <td class="col-md-1">icon</td>
                                </tr>
                                <tr class="btn-group center" style="background-color:  #ffffcc;width:500px">
                                    <td class="col-md-1">1</td>
                                    <td class="col-md-1">yes</td>
                                    <td class="col-md-1">no</td>
                                    <td class="col-md-2">09120621785</td>
                                    <td class="col-md-2">714</td>
                                    <td class="col-md-2">1996/23/07</td>
                                    <td class="col-md-1">12:51</td>
                                    <td class="col-md-1">21 min</td>
                                    <td class="col-md-1">icon</td>
                                </tr>
                                <tr class="btn-group center" style="background-color:  coral;width:500px">
                                    <td class="col-md-1">1</td>
                                    <td class="col-md-1">yes</td>
                                    <td class="col-md-1">no</td>
                                    <td class="col-md-2">09120621785</td>
                                    <td class="col-md-2">714</td>
                                    <td class="col-md-2">1996/23/07</td>
                                    <td class="col-md-1">12:51</td>
                                    <td class="col-md-1">21 min</td>
                                    <td class="col-md-1">icon</td>
                                </tr>
                                
                              </table>
                            </div>
                            
                        </div>
                    </div>
                </div>
          </div>
          ';
    };
}

        
        /*------------------------------------------------------------------*/
/*
        $class = new osvoice();
        $template = $class->getTemplateDetails(__FUNCTION__);
        if($template['active'] == 0){
            return null;
        }
        $settings = $class->getSettings();
        if(!$settings['api'] || !$settings['apiparams'] || !$settings['gsmnumberfield'] || !$settings['wantsmsfield']){
            return null;
        }

        $userSql = "SELECT `a`.`id`,`a`.`firstname`, `a`.`lastname`, `b`.`value` as `gsmnumber`
        FROM `tblclients` as `a`
        JOIN `tblcustomfieldsvalues` as `b` ON `b`.`relid` = `a`.`id`
        JOIN `tblcustomfieldsvalues` as `c` ON `c`.`relid` = `a`.`id`
        WHERE `a`.`id` IN (SELECT userid FROM tblorders WHERE id = '".$args['orderid']."')
        AND `b`.`fieldid` = '".$settings['gsmnumberfield']."'
        AND `c`.`fieldid` = '".$settings['wantsmsfield']."'
        AND `c`.`value` = 'on'
        LIMIT 1";

        $result = mysql_query($userSql);
        $num_rows = mysql_num_rows($result);
        if($num_rows == 1){
            $UserInformation = mysql_fetch_assoc($result);

            $template['variables'] = str_replace(" ","",$template['variables']);
            $replacefrom = explode(",",$template['variables']);
            $replaceto = array($UserInformation['firstname'],$UserInformation['lastname'],$args['orderid']);
            $message = str_replace($replacefrom,$replaceto,$template['template']);


            $class->setGsmnumber($UserInformation['gsmnumber']);
            $class->setUserid($UserInformation['id']);
            $class->setMessage($message);
            $class->send();
        }
    }
}
*/
//return $hook;

