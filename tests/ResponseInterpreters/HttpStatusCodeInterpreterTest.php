<?php

namespace Trucker\Tests\ResponseInterpreters;

use Guzzle\Http\Message\Response as GuzzleResponse;
use Illuminate\Container\Container;
use Prophecy\Prediction\CallTimesPrediction;
use Trucker\Responses\Interpreters\HttpStatusCodeInterpreter;
use Trucker\Responses\Response;
use Trucker\Tests\TruckerTestCase;

class HttpStatusCodeInterpreterTest extends TruckerTestCase
{
    public function testSuccessResponse()
    {
        $response = $this->mockResponse(200);
        $interpreter = $this->getInterpreter();
        $this->assertTrue($interpreter->success($response), 'Response should have been successful');

        //test array of http codes
        $interpreter = $this->getInterpreter([
            'trucker::response.http_status.success' => [200, 201],
        ]);
        $this->assertTrue($interpreter->success($response), 'Response should have been successful');

        //test wildcard http codes
        $interpreter = $this->getInterpreter([
            'trucker::response.http_status.success' => '2*',
        ]);
        $this->assertTrue($interpreter->success($response), 'Response should have been successful');
    }

    public function testNotFoundResponse()
    {
        $response = $this->mockResponse(404);
        $interpreter = $this->getInterpreter();
        $this->assertTrue($interpreter->notFound($response), 'Response should have been not found');

        //test array of http codes
        $interpreter = $this->getInterpreter([
            'trucker::response.http_status.not_found' => [404, 405],
        ]);
        $this->assertTrue($interpreter->notFound($response), 'Response should have been not found');

        //test wildcard http codes
        $interpreter = $this->getInterpreter([
            'trucker::response.http_status.not_found' => '4*',
        ]);
        $this->assertTrue($interpreter->notFound($response), 'Response should have been not found');
    }

    public function testInvalidResponse()
    {
        $response = $this->mockResponse(422);
        $interpreter = $this->getInterpreter();
        $this->assertTrue($interpreter->invalid($response), 'Response should have been invalid');

        //416 isn't really invalid, but we need to test with something
        //test array of http codes
        $interpreter = $this->getInterpreter([
            'trucker::response.http_status.invalid' => [422, 416],
        ]);
        $this->assertTrue($interpreter->invalid($response), 'Response should have been invalid');

        //test wildcard http codes
        $interpreter = $this->getInterpreter([
            'trucker::response.http_status.invalid' => '42*',
        ]);
        $this->assertTrue($interpreter->invalid($response), 'Response should have been invalid');
    }

    public function testErrorResponse()
    {
        $response = $this->mockResponse(500);
        $interpreter = $this->getInterpreter();
        $this->assertTrue($interpreter->error($response), 'Response should have been error');

        //test array of http codes
        $interpreter = $this->getInterpreter([
            'trucker::response.http_status.error' => [500, 503],
        ]);
        $this->assertTrue($interpreter->error($response), 'Response should have been error');

        //test wildcard http codes
        $interpreter = $this->getInterpreter([
            'trucker::response.http_status.error' => '5*',
        ]);
        $this->assertTrue($interpreter->error($response), 'Response should have been error');
    }

    private function getInterpreter(array $overwriteConfig = [])
    {
        $swapWith = array_merge([
            'trucker::response.http_status.success' => '200',
            'trucker::response.http_status.not_found' => '404',
            'trucker::response.http_status.invalid' => '422',
            'trucker::response.http_status.error' => '500',
        ], $overwriteConfig);
        $this->swapConfig($swapWith);

        return new HttpStatusCodeInterpreter($this->app);
    }

    private function mockResponse($statusCode)
    {
        $response = $this->prophesize(GuzzleResponse::class);
        $response
            ->getStatusCode()
            ->should(new CallTimesPrediction(3))
            ->willReturn($statusCode);

        $container = $this->prophesize(Container::class);

        return new Response($container->reveal(), $response->reveal());
    }
}
