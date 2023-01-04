<?php

namespace Partitech\FixUnSerialize\Tests;

use Partitech\FixUnSerialize\UnSerializeService;
use PHPUnit\Framework\TestCase;

class FixUnSerializeTest extends TestCase
{
    const VALID_STRING = 'a:2:{s:4:"test";s:4:"test";s:5:"test2";s:5:"test2";}';
    const INVALID_STRING = 'a:2:{s:123456:"test";s:4:"test";s:5:"test2";s:5:"test2";}';

    private UnSerializeService $unserializeService;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->unserializeService = new UnSerializeService();
    }

    public function testIsValidTrue()
    {
        $this->assertTrue($this->unserializeService->isValid(self::VALID_STRING));
    }

    public function testIsValidFalse()
    {
        $this->assertNotTrue($this->unserializeService->isValid(self::INVALID_STRING));
    }

    public function testFixIfInvalidWithValidString()
    {
        $this->assertEquals(
            self::VALID_STRING,
            $this->unserializeService->fixIfInvalid(self::VALID_STRING)
        );
    }

    public function testFixIfInvalidWithInvalidString()
    {
        $this->assertEquals(
            self::VALID_STRING,
            $this->unserializeService->fixIfInvalid(self::INVALID_STRING)
        );
    }

    public function testFixLength()
    {
        $data = [
            's:5000:"test2";',
            5,
            'test2'
        ];
        $expected = 's:5:"test2";';
        $this->assertEquals(
            $expected,
            $this->unserializeService->fixLength($data)
        );
    }

    public function testFixValid()
    {
        $this->assertEquals(
            self::VALID_STRING,
            $this->unserializeService->fix(self::VALID_STRING)
        );
    }

    public function testFixInvalid()
    {
        $this->assertEquals(
            self::VALID_STRING,
            $this->unserializeService->fix(self::INVALID_STRING)
        );
    }

    public function testUnserializeValid()
    {
        $this->assertEquals(
            unserialize(self::VALID_STRING),
            $this->unserializeService->unserialize(self::VALID_STRING)
        );
    }

    public function testUnserializeInvalid()
    {
        $this->assertEquals(
            unserialize(self::VALID_STRING),
            $this->unserializeService->unserialize(self::INVALID_STRING)
        );
    }
}