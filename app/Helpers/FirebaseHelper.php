<?php

namespace App\Helpers;

use Kreait\Firebase\Messaging\CloudMessage;

class FirebaseHelper
{
    public function broadcastNotifications($tokens, $judul, $pesan, $type, $keluhanId)
    {
        $messaging = app('firebase.messaging');
        $data = [];
        foreach ($tokens as $token) {
            if ($token != null) {
                $message = CloudMessage::fromArray([
           'token' => 'e_HDrtFxTN6XBTScL_f5WF:APA91bG7AysQmQ-aR1n0dD3dRg8KU0suduvzVKwue_uw6EyNkK9gHmSqHZIZx74wX0fWC4kXPsLp7NlWdonx1OLh8s2Rh4A7ypR-ELkh5X3TyvTFmT-pg4-oULN6Uw61F9Jiy2DBTWRC',
                    'token' => $token,
                    'notification' => [
                        "body" => $pesan,
                        "title" => $judul,
                        "sound" => 'default'
                    ],
                    'android' => [
                        'ttl' => '3600s',
                        'priority' => 'normal',
                        'notification' => [
                            'title' => $judul,
                            'body' => $pesan,
                            'sound' => 'default'
                        ],
                    ],
                    'data' => ['title' => $judul, 'message' => $pesan, 'type' => $type, 'keluhan_id' => $keluhanId], // optional
                ]);
                array_push($data, $token);
                $messaging->send($message);
            }
        }

        return $data;
    }
}
