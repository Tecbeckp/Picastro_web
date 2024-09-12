<?php

namespace App\Traits;

trait MailTrait
{
    function sendMail($data)
    {
        $curl = curl_init();
        $emailData = array(
            'personalizations' => array(
                array(
                    'to' => array(
                        array(
                            'email' => $data['to']
                        )
                    ),
                    'subject' => "[Picastro] - " . $data['subject']
                )
            ),
            'from' => array(
                'email' => $data['from'] ?? 'support@picastroapp.com',
                'name' => 'Picastro'
            ),
            'content' => array(
                array(
                    'type' => 'text/html',
                    'value' => $data['html']
                )
            ),

        );
        if (isset($data['attachments'])) {
            $emailData['attachments'] = $data['attachments'];
        }

        $data = json_encode($emailData);

        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . config('mail.sendgrid_key')
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.sendgrid.com/v3/mail/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return ['error' => $err, 'res' => $response, 'data' => $data];
        } else {
            $res = json_decode($response);
            if (isset($res->errors) && count($res->errors) > 0) {
                return ['error' => "Mail not sent", 'res' => $res->errors];
            }
            return ['success' => 'Email sent successfully!'];
        }
    }
}
