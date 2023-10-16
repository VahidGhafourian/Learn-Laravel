<?php

namespace App\Notifications\Channels;

use Ghasedak\Exceptions\ApiException;
use Ghasedak\Exceptions\HttpException;
use Ghasedak\GhasedakApi;
use Illuminate\Notifications\Notification;

// TODO: Service ghasedak kar nakard. poshtibani zaeif.
class GhasedakChannel {

    public function send($notifiable, Notification $notification) {
//        if (method_exists($notification, 'toGhasedakSms')) {
//            throw new \Exception('toGhasedakSms not found');
//        }

        $data = $notification->toGhasedakSms($notifiable);
        $message = $data['text'];
        $receptor = $data['number'];
//        $apiKey = config('services.ghasedak.key');
//        try {
//            $lineNumber = "30005006008483";
//            $api = new GhasedakApi("uAoRQfoFR3yUE6NMQ4wll10ekRIR07SVLvBbFeOdG6A");
//            $api->SendSimple($receptor, $message, $lineNumber);
//        }
//        catch (ApiException | HttpException $e) {
//            throw $e;
//        }
        $sms_username = 'vahidghafourianam@gmail.com';
        $sms_password =  'vahid 1378';
        $from_number = 123;
        $to_number = $receptor;

        //$date="29/12/2014 17:24"; //Date example
        //list($day, $month, $year, $hour, $minute) = split('[/ :]', $date);

        //The variables should be arranged according to your date format and so the separators
        //$timestamp = mktime($hour, $minute, 0, $month, $day, $year);

        // $sendDate = array($timestamp);

        $client = new \SoapClient("https://parsasms.com/webservice/v2.asmx?WSDL");

        $params = array(
            'Username' 	=> $sms_username,
            'Password' 	=> $sms_password,
            'SenderNumbers' => '30005006008483',
            'recipientNumbers'=> ['09388468581'],
            //'sendDate'=> $sendDate,
            'messageBodies' => $message,
            'messageClasses' => 1
        );

//        $results = $client->SendSMS2( $params );
//        dd($params);
//        print_r($results);
//        dd($results);
//
//        $params = array(
//            'username' 	=> $sms_username,
//            'password' 	=> $sms_password,
//            'MessageIDs' => $results,
//        );
//
//        $results = $client->GetMessageStatus( $params );
//
//        print_r($results);


    }
}
