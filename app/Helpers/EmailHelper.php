<?php


namespace App\Helpers;

use CURLFile;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Log;

class EmailHelper
{
    public static function FromEmail()
    {
        return config('mail.mailers.smtp.username');
    }

    public static function FromName(): string
    {
        return env('APP_NAME');
    }

    public static function sendMail($to, $subject, $body, $attachments = [])
    {
        try {
            $mailData = array('subject' => $subject, 'to' => '["' . $to . '"]', 'mail_body' => $body,  'from_email' => self::FromEmail(), 'from_name' => self::FromName());
            if (!empty($attachments)) {
                if (count($attachments) > 0) {
                    $files = [];
                    foreach ($attachments as $file) {
                        $files = new CURLFile($file);
                    }
                    $mailData['attachments[]'] = $files;
                }
            }
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://beckgroup.io/mail_api.php',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $mailData,
            ));

            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($curl);
            $r = curl_error($curl);
            curl_close($curl);
            // dd($response, $r);
            return json_decode($response, true);
        } catch (Exception $e) {
            return ['msg' => $e->getMessage(), 'e' => $e->getTraceAsString()];
        }
    }




    public static function testMail($to)
    {
        self::sendMail($to, "Test API Mail", '<h2>This is dummy text to test email ' . now()->toDayDateTimeString() . '</h2>');
    }
}
