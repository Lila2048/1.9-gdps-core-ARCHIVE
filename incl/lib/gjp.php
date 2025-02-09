<?php

include __DIR__ . "/XORCipher.php";

class gjpTools {
    public function DecodeGJP($gjp) {
        $xor = new XORCipher();
        return(base64_decode($xor->plaintext($gjp, 37526)));
    }

    public function MakeGJP($password) {
        $xor = new XORCipher();
        return(base64_encode($xor->cipher($password, 37526)));
    }

}
?>