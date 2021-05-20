<?php

use Muvon\JWT\ES256;
use PHPUnit\Framework\TestCase;

class ES256Test extends TestCase {
  protected ES256 $jwt;
  protected function setUp(): void {
    $public = '031587e192182b8a826d720bb81b944e2b979f7f27a149abd9b38bf00fb52cc218';
    $private = 'c98a3420971233ee39bcca3d120f8ead3d90695dfedb26d8609bb7c0b451b14d';
    $this->jwt = ES256::create(
      $public,
      $private
    );
  }

  public function testEncodeDecode() {
    $data = array(
      "data" => [
          "name" => "ZiHang Gao",
          "admin" => true
      ],
      "iss" => "http://example.org",
      "sub" => "1234567890",
    );
    $token = $this->jwt->encode($data);
    var_dump($token);exit;
    $this->assertIsString($token);
    $decoded = $this->jwt->decode($token);
    $this->assertIsArray($decoded);
    $this->assertEquals($data, $decoded);
    $this->assertTrue($this->jwt->verify($token));
  }
}