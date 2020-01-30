<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
class SendOTP {
    function __construct() {

    }
    public function sendSMS($OTP, $mobileNumber){
        $isError = 0;
        $errorMessage = true;
        $xml_data ='<?xml version="1.0"?>
        <smslist>
        <sms>
        <user>msttxn</user>
        <password>64111e2573XX</password>
        <message>Welcome to MySocialtab , Your OPT is : '.$OTP.'</message>
        <mobiles>'.$mobileNumber.'</mobiles>
        <senderid>MSTTXN</senderid>
        </sms>
        </smslist>';
        $url = "https://sms.smsmenow.in/sendsms.jsp?"; 
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        //print_r($output); die;
        curl_close($ch);
    }
}