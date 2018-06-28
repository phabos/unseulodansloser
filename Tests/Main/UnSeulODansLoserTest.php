<?php

class UnSeulODansLoserTest extends PHPUnit\Framework\TestCase
{
    public $twitterOAuthMock = null;

    public function setUp()
    {
        $this->twitterOAuthMock = $this->getMockBuilder(Abraham\TwitterOAuth\TwitterOAuth::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->getMock();
    }

    public function testStub()
    {
        $stub = $this->twitterOAuthMock;
        $stub->method('get')
             ->willReturn('foo');

        $u = new Main\UnSeulODansLoser($stub, [], 'test');
        $this->assertEquals('foo', $stub->get('url', []));
    }

    public function testIncrementTotal()
    {
        $stub = $this->twitterOAuthMock;

        $u = new Main\UnSeulODansLoser($stub, [], 'test');
        $this->assertEquals($u->getTotalReTweet(), 0);
    }

    public function testLaunch()
    {
        $stub = $this->twitterOAuthMock;
        $stub->method('get')
             ->willReturn('foo');

        $stub->method('post')
             ->willReturn('bar');

        $u = new Main\UnSeulODansLoser($stub, [], 'test');
        $u->launch();
        $this->expectOutputString('Total de lOOser today: #0');
    }

    public function testLaunchWithResponse()
    {
        $apiResponse = new stdClass();
        $apiResponse->search_metadata = new stdClass();
        $apiResponse->search_metadata->count = 2;
        $response1 = new stdClass();
        $response1->text = 'toto #looser some random text';
        $response1->id_str = '1';
        $response2 = new stdClass();
        $response2->text = 'tata #looser';
        $response2->id_str = '2';
        $apiResponse->statuses = [
            $response1,
            $response2
        ];

        $stub = $this->twitterOAuthMock;
        $stub->method('get')
             ->willReturn($apiResponse);

        $stub->method('post')
             ->willReturn('bar');

        $u = new Main\UnSeulODansLoser($stub, [], '/#looser/i');
        $u->launch();
        $this->expectOutputString('Total de lOOser today: #2');
    }

    public function testLaunchBadRegexpResponse()
    {
        $apiResponse = new stdClass();
        $apiResponse->search_metadata = new stdClass();
        $apiResponse->search_metadata->count = 2;
        $response1 = new stdClass();
        $response1->text = 'toto #looser some random text';
        $response1->id_str = '1';
        $response2 = new stdClass();
        $response2->text = 'tata #looser';
        $response2->id_str = '2';
        $apiResponse->statuses = [
            $response1,
            $response2
        ];

        $stub = $this->twitterOAuthMock;
        $stub->method('get')
             ->willReturn($apiResponse);

        $stub->method('post')
             ->willReturn('bar');

        $u = new Main\UnSeulODansLoser($stub, [], '/#loser/i');
        $u->launch();
        $this->expectOutputString('Total de lOOser today: #0');
    }

    public function testLaunchMixedGoodTextAndBadTextResponse()
    {
        $apiResponse = new stdClass();
        $apiResponse->search_metadata = new stdClass();
        $apiResponse->search_metadata->count = 2;
        $response1 = new stdClass();
        $response1->text = 'toto qksn,nmlk,s lkqnsd #looser some random text';
        $response1->id_str = '1';
        $response2 = new stdClass();
        $response2->text = 'tata #loser';
        $response2->id_str = '2';
        $apiResponse->statuses = [
            $response1,
            $response2
        ];

        $stub = $this->twitterOAuthMock;
        $stub->method('get')
             ->willReturn($apiResponse);

        $stub->method('post')
             ->willReturn('bar');

        $u = new Main\UnSeulODansLoser($stub, [], '/#looser/i');
        $u->launch();
        $this->expectOutputString('Total de lOOser today: #1');
    }

    public function testLaunchWithALotOfResponse()
    {
        $apiResponse = new stdClass();
        $apiResponse->search_metadata = new stdClass();
        $apiResponse->search_metadata->count = 2;
        $response1 = new stdClass();
        $response1->text = 'toto qksn,nmlk,s lkqnsd #looser some random text';
        $response1->id_str = '1';
        $response2 = new stdClass();
        $response2->text = 'tata #looser';
        $response2->id_str = '2';
        $response3 = new stdClass();
        $response3->text = '#looser qsk,qmlk kqs';
        $response3->id_str = '3';
        $apiResponse->statuses = [
            $response1,
            $response2,
            $response3
        ];

        $stub = $this->twitterOAuthMock;
        $stub->method('get')
             ->willReturn($apiResponse);

        $stub->method('post')
             ->willReturn('bar');

        $u = new Main\UnSeulODansLoser($stub, [], '/#looser/i');
        $u->launch();
        $this->expectOutputString('Total de lOOser today: #3');
    }
}
