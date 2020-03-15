<?php

namespace Test\Http;

use Test\TestCase;

/**
 * Class ResponseTest
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ResponseTest extends TestCase
{
    protected $class = 'Http\Response';

    public function test_constants_and_properties(): void
    {
        $this->assertConstant('HTTP_OK', self::PUBLIC, 200);
        $this->assertConstant('HTTP_NOT_FOUND', self::PUBLIC, 404);
    }

    public function test_properties(): void
    {
        $this->assertProperty('content', self::PRIVATE);
        $this->assertProperty('code', self::PRIVATE);
    }

    public function test_methods(): void
    {
        $this->assertMethod('getContent', self::PUBLIC, [], 'string');
        $this->assertMethod('getCode', self::PUBLIC, [], 'int');
    }

    public function test_constructor(): void
    {
        $response = $this->createResponse('Test');

        $rp = $this->getReflectionProperty('content');
        $rp->setAccessible(true);
        $this->assertEquals(
            'Test', $rp->getValue($response),
            sprintf("%s:%s is not initialized properly.", $this->class, 'content')
        );

        $rp =$this->getReflectionProperty('code');
        $rp->setAccessible(true);
        $this->assertEquals(
            200, $rp->getValue($response),
            sprintf("%s:%s is not initialized properly (200 by default).", $this->class, 'code')
        );
    }

    public function test_content(): void
    {
        $response = $this->createResponse();
        $response->setContent('Test');
        $this->assertEquals('Test', $response->getContent());
    }

    public function test_code(): void
    {
        $response = $this->createResponse();
        $response->setCode(404);
        $this->assertEquals(404, $response->getCode());
    }

    /** @return \Http\Response */
    private function createResponse(string $content = '')
    {
        $this->skipIfClassDoesNotExist();

        return new $this->class($content);
    }
}
