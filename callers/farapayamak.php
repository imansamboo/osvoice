<?php

class farapayamak extends osvoice {
      function __construct($message,$gsmnumber){
        $this->message = $this->utilmessage($message);
        $this->gsmnumber = $this->utilgsmnumber($gsmnumber);
    }
	
    function send($len,$n){if ($this->gsmnumber == "numbererror") {
            $log[] = ("Number format error." . $this->gsmnumber);
            $error[] = ("Number format error." . $this->gsmnumber);
           
            
            return null;
        }
        //$a=$len;
        //$b=$n;
        //echo $a;
        //echo $b;
        $params = json_decode($this->params);

        $url = "http://185.81.96.226:90/?" . "action=" . urlencode($this->message) . "&phone=".$this->gsmnumber;
        
        $result = file_get_contents($url);
        $return = $result;
        
       // echo $return;
        //$log[] = ("Sunucudan dönen cevap: " . $result);

       // $result = explode("|", $result);
       // if ($result[0] == "OK") {
        //    $log[] = ("Message sent.");
        //} else {
            
         //   $log[] = ("پیام شما ارسال نشد و کد خطا بدین شرح می باشد : $return");
           
         //   $error[] = ("پیامک شما با موفقیت ارسال شد و نتیجه به شرح ذیل می باشد : $return");
           
            
       // }
    
        //return array(
         //   'log' => $log,
         //   'error' => $error,
         //   'msgid' => $result[1],
       // );
        
        
       
    }

        function balance(){
        $params = $this->getParams();

        if($params->user && $params->pass){
            $url = "http://api.payamak-panel.com/get/getcredit.ashx?username=$params->user&password=$params->pass";
            $result = file_get_contents($url);
            $result = explode(" ",$result);
            return $result[1];
        }else{
            return null;
        }
    }

    function report($msgid){
        return null;
    }

    //You can spesifically convert your gsm number. See netgsm for example
    function utilgsmnumber($number){
        return $number;
    }
    //You can spesifically convert your message
    function utilmessage($message){
        return $message;
    }
}

return array(
    'value' => 'farapayamak',
    'label' => 'OSVOICE',
    'fields' => array(
        'user','pass'
    )
);
