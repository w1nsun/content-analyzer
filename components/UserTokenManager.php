<?php

namespace app\components;

use app\models\User;

class UserTokenManager
{
    const MAX_ITERATIONS = 10000;

    /**
     * @var User
     */
    protected $user;

    /**
     * UserTokenManager constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function updateToken()
    {
        $token = null;
        $i = 0;

        do {
            $token = $this->generate();
            $owner = User::findIdentityByAccessToken($token);
            $i++;
        } while($owner && $i < self::MAX_ITERATIONS);

        if ($owner && $i >= self::MAX_ITERATIONS) {
            \Yii::warning(sprintf('Error generate token for user ID: %s', $this->user->id));
            throw new \Exception('Token not generated!');
        }

        $this->user->access_token = $this->generate();

        if ($this->user->update(false, ['access_token'])) {
            return true;
        }

        return false;
    }

    protected function cryptoRandSecure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
    }

    protected function generate($length = 64)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet) - 1;
        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[$this->cryptoRandSecure(0, $max)];
        }
        return $token;
    }
}