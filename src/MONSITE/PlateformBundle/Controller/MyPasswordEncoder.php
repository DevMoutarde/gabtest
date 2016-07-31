<?php

use Symfony\Component\Security\Core\Encoder\BasePasswordEncoder;

class MyPasswordEncoder extends BasePasswordEncoder
{
    public function encodePassword($raw)
    {
        echo "encryptage";
        return base64_encode( pack( 'H*' , md5("password") ) );
    }

    public function isPasswordValid($encoded, $raw)
    {
        return $this->comparePasswords($encoded, $this->encodePassword($raw, $salt));
    }
}