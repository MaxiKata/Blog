<?php


namespace Blog\App\Entity;


class Session
{
    private $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function setCookie($id, $value)
    {
        $data = $this->encrypt($value, $this->key);
        setcookie($id, $data, time()+86400);
    }

    public function getKey()
    {
        $password = new User();
        $password->setPassword($this->key);

        return $password;
    }

    public function getCookie($id)
    {
        $edata = filter_input(INPUT_COOKIE, $id);
        $data = $this->decrypt($edata, $this->key);

        return $data;
    }

    public function destroyCookie()
    {
        $datas = filter_input_array(INPUT_COOKIE);

        foreach($datas as $method=>$data){
            setcookie($method, '', time()-86400);
        }
    }

    private function encrypt($data, $password)
    {
        // Set a random salt
        $salt = openssl_random_pseudo_bytes(16);

        $salted = '';
        $dx = '';
        // Salt the key(32) and iv(16) = 48
        while (strlen($salted) < 48) {
            $dx = hash('sha256', $dx.$password.$salt, true);
            $salted .= $dx;
        }

        $key = substr($salted, 0, 32);
        $iv  = substr($salted, 32,16);

        $encrypted_data = openssl_encrypt($data, 'AES-256-CBC', $key, true, $iv);
        return base64_encode($salt . $encrypted_data);
    }

    private function decrypt($edata, $password) {
        $data = base64_decode($edata);
        $salt = substr($data, 0, 16);
        $ct = substr($data, 16);

        $rounds = 3; // depends on key length
        $data00 = $password.$salt;
        $hash = array();
        $hash[0] = hash('sha256', $data00, true);
        $result = $hash[0];
        for ($i = 1; $i < $rounds; $i++) {
            $hash[$i] = hash('sha256', $hash[$i - 1].$data00, true);
            $result .= $hash[$i];
        }
        $key = substr($result, 0, 32);
        $iv  = substr($result, 32,16);

        return openssl_decrypt($ct, 'AES-256-CBC', $key, true, $iv);
    }
}