<?php

use App\Models\Country;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

function get_default_lang()
{
    return Config::get('app.locale');
}

function get_user_country($request, $country_name = null)
{
    try {
        //code...
    } catch (\Exception $e) {
        $srealIP = "196.132.131.238";
        $realIP = $request->ip();
        $url = "http://api.ipapi.com/$srealIP?access_key=0c2cc3a014f062d25fa953da376b43a1";
        $country = Http::get($url);

        $country_name = trans("lang." . $country['country_name']);
        $data = Country::where('name', "LIKE", "%$country_name%")->orWhere('name', "LIKE", "%" . $country['country_name'] . "%")->with('cities')->first();
        if ($data) {
            return $country;
        }
    }
    return false;
}

function uploadImage($folder, $image)
{
    //$image->store( $folder);
    $filename = $image->hashName();
    $path2 = public_path("images/".$folder);
    $image->move($path2, $filename);
    $path = 'images/' . $folder . '/' . $filename;
    return $path;
}

function sendmessage($token, $title, $body)
{
    $token = $token;
    $from = "AAAA1n08IHY:APA91bHiBDlu0I5QY70SI7mfbqbnKf5F22onNGh5mDWS2xUL6kyGbH2pNId93y1yNHZNc5JmFSIPEYYv-2HN15Gk2wb8Pi7f7BluPCEzJ0z4w2o9vVEI414g-u1_LHHg8fxGlr32CHFA";
    $msg = array(
            'body'     => $body,
            'title'    => $title,
            'receiver' => 'erw',
            'icon'     => "https://salon-eljoker.com/images/joker.jpg",/*Default Icon*/
            'vibrate'	=> 1,
            'sound'		=> "http://commondatastorage.googleapis.com/codeskulptor-demos/DDR_assets/Kangaroo_MusiQue_-_The_Neverwritten_Role_Playing_Game.mp3",
            );

    $fields = array(
                'to'        => $token,
                'notification'  => $msg
            );

    $headers = array(
                'Authorization: key=' . $from,
                'Content-Type: application/json'
            );
    //#Send Reponse To FireBase Server
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
