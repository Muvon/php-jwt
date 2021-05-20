<?php
namespace Muvon\JWT;

use Throwable;
use Elliptic\EC;

class ES256 {
  final protected function __construct() {}

  public static function create(string $pubkey, ?string $privkey = null) {
    $Obj = new static;

    $ec = new EC('secp256k1');
    $ecpubkey = $ec->keyFromPublic($pubkey, 'hex');
    $pubkey_full = $ecpubkey->getPublic(false, 'hex');
    $public_base64 = base64_encode(hex2bin('3056301006072a8648ce3d020106052b8104000a034200' . $pubkey_full));
    $Obj->pubkey = '-----BEGIN PUBLIC KEY-----' . PHP_EOL
      . $public_base64 . PHP_EOL
      . '-----END PUBLIC KEY-----' . PHP_EOL
    ;

    if ($privkey) {
      $priv_base64 = base64_encode(
        hex2bin(
          '30740201010420' . $privkey . 'a00706052b8104000aa144034200' . $pubkey_full
        )
      );
      $Obj->privkey = '-----BEGIN EC PRIVATE KEY-----' . PHP_EOL
        . $priv_base64 . PHP_EOL
        . '-----END EC PRIVATE KEY-----' . PHP_EOL
      ;
    }

    return $Obj;
  }

  public function encode(array $payload): string {
    return jwt_encode($payload, $this->privkey, 'ES256');
  }

  public function decode(string $token): array {
    return jwt_decode($token, $this->pubkey, ['algorithm' => 'ES256']);
  }

  public function verify(string $token) {
    try {
      $this->decode($token);
    } catch (Throwable $e) {
      return false;
    }

    return true;
  }
}
