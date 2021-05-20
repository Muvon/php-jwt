<?php

use Muvon\JWT\ES256;
use PHPUnit\Framework\TestCase;

class ES256Test extends TestCase {
  protected ES256 $jwt;
  protected function setUp(): void {
    $public = '03fce314340b325d4949f4dd385ac81adcdc3f89a087b99abae96088606d201274';
    $private = 'ba6bd7087653b7883a99cd3c8d385e30a188aa3204120e838072762f667e07de';
    $this->jwt = ES256::create(
      $public,
      $private
    );
  }

  public function testEncodeDecode() {
    $data = ['hello', 'world'];
    $token = $this->jwt->encode($data);
    $this->assertIsString($token);
    $decoded = $this->jwt->decode($token);
    $this->assertIsArray($decoded);
    $this->assertEquals($data, $decoded);
    $this->assertTrue($this->jwt->verify($token));
  }
}