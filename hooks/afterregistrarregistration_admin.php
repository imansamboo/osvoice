<?php
$hook = array(
    'hook' => 'AfterRegistrarRegistration',
    'function' => 'AfterRegistrarRegistration_admin',
    'type' => 'admin',
    'extra' => '',
    'defaultmessage' => 'دامنه {domain} با موفقیت ثبت شد.',
    'variables' => '{domain}'
);
if(!function_exists('AfterRegistrarRegistration_admin')){
    function AfterRegistrarRegistration_admin($args){
        $class = new osvoice();
        $template = $class->getTemplateDetails(__FUNCTION__);
        if($template['active'] == 0){
            return null;
        }
        $settings = $class->getSettings();
        if(!$settings['api'] || !$settings['apiparams'] || !$settings['gsmnumberfield'] || !$settings['wantsmsfield']){
            return null;
        }
        $admingsm = explode(",",$template['admingsm']);

        $template['variables'] = str_replace(" ","",$template['variables']);
        $replacefrom = explode(",",$template['variables']);
        $replaceto = array($args['params']['sld'].".".$args['params']['tld']);
        $message = str_replace($replacefrom,$replaceto,$template['template']);

        foreach($admingsm as $gsm){
            if(!empty($gsm)){
                $class->setGsmnumber( trim($gsm));
                $class->setUserid(0);
                $class->setMessage($message);
                $class->send();
            }
        }
    }
}

return $hook;