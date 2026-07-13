<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SignatureController extends Controller
{
    public static function Sign($script)
    {
    $PrivateKey = resource_path('roblox\key.pem');
    $signature = "";
    openssl_sign($script, $signature, file_get_contents($PrivateKey), OPENSSL_ALGO_SHA1);

    return base64_encode($signature);
    }

    public static function ClientTicket($uid, $jid, $usr, $charap) {
        /*date_default_timezone_set('Europe/Belgrade');
        $time = date("m/d/Y h:m:s A", time());


        $sign1 = implode("\n", [
            $uid, 
            $usr, 
            $charap, 
            $jid, 
            $time
        ]);

        
        $sign2 = implode("\n", [
            $uid, 
            $jid, 
            $time
        ]);
        
        $sign1Hash = sha1($sign1);
        $sign2Hash = sha1($sign2);
        
        $sign1Base64 = base64_encode($sign1Hash);
        $sign2Base64 = base64_encode($sign2Hash);
        
        $ticket = "{$time};{$sign1Base64};{$sign2Base64}";
        
        return $ticket;*/

        $ticket = $uid . "\n" . $jid . "\n" . date('n\/j\/Y\ g\:i\:s\ A');
        $sig = self::Sign(base64_encode($ticket));
        $ticket2 = $uid . "\n" . $usr . "\n" . $charap . "\n". $jid . "\n" . date('n\/j\/Y\ g\:i\:s\ A'); // Shalom aleichem roblox
        $sig2 = self::Sign(base64_encode($ticket2));
        $final = date('n\/j\/Y\ g\:i\:s\ A') . ";" . $sig2 . ";" . $sig;
        return($final);

    }
     /*public static function ClientTicket($uid, $jid, $usr, $charap) {
        date_default_timezone_set('Europe/Belgrade');
        $time = date("m/d/Y h:m:s A", time());
        $privatekey = file_get_contents(resource_path('/roblox/key.pem'));
        $ticket = $uid . "\n" . $jid . "\n" . $time;
        openssl_sign($ticket, $sig, $privatekey, OPENSSL_ALGO_SHA1);

        $sig = base64_encode($sig);
        $ticket2 = $uid . "\n" . $usr . "\n" . $charap . "\n" . $jid . "\n" . $time;

        openssl_sign($ticket2, $sig2, $privatekey, OPENSSL_ALGO_SHA1);

        $sig2 = base64_encode($sig2);
        $final = $time . ";" . $sig2 . ";" . $sig;
        
        return ($final);
    } */
}
