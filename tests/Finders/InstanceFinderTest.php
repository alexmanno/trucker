<?php

namespace Trucker\Tests\Finders;

use Trucker\Tests\Helpers\GuzzleTestingTrait;
use Trucker\Tests\Stubs\User;
use Trucker\Tests\TruckerTestCase;

class InstanceFinderTest extends TruckerTestCase
{
    use GuzzleTestingTrait;

    public function testFind()
    {
        $this->setupIndividualTest($this->getTestOptions());
        extract($this->getTestOptions());

        $found = User::find(1234);

        //get objects to assert on
        $history = $this->getHttpClientHistory();
        $request = $history->getLastRequest();
        $response = $history->getLastResponse();

        $this->makeGuzzleAssertions('GET', $base_uri, $uri);

        //assert that the HTTP RESPONSE is what is expected
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals($response_body, $response->getBody(true));
        $this->assertInstanceOf(User::class, $found);
        $this->assertEquals(1234, $found->id);
        $this->assertEquals('jdoe@noboddy.com', $found->email);
        $this->assertEquals('John Doe', $found->name);
    }

    public function testFindWithGetParams()
    {
        $this->setupIndividualTest($this->getTestOptions());
        extract($this->getTestOptions());

        $found = User::find(1234, $queryParams);

        //get objects to assert on
        $history = $this->getHttpClientHistory();
        $request = $history->getLastRequest();
        $response = $history->getLastResponse();

        $this->makeGuzzleAssertions('GET', $base_uri, $uri, $queryParams);

        //assert that the HTTP RESPONSE is what is expected
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals($response_body, $response->getBody(true));
        $this->assertInstanceOf(User::class, $found);
        $this->assertEquals('jdoe@noboddy.com', $found->email);
        $this->assertEquals('John Doe', $found->name);
    }

    /**
     * Helper function to get commonly used testing data.
     *
     * @return array
     */
    private function getTestOptions()
    {
        //some vars for our test
        $data = [];
        $data['uri'] = '/users/1234';
        $data['base_uri'] = 'http://example.com';
        $data['queryParams'] = ['foo' => 'bar', 'biz' => 'bang'];
        $data['response_body'] = json_encode(
            [
                'id' => 1234,
                'name' => 'John Doe',
                'email' => 'jdoe@noboddy.com',
            ]
        );

        return $data;
    }

    /**
     * Function to mock a request for us and
     * expect test data from our getTestOptions() function.
     *
     * @param array $options
     */
    private function setupIndividualTest(array $options = [])
    {
        extract($options);

        //mock the response we expect
        $this->mockHttpResponse(
            //
            //config overrides & return client
            //
            $this->initGuzzleRequestTest([
                'trucker::request.base_uri' => $base_uri,
            ]),
            //
            //expcted status
            //
            200,
            //
            //HTTP response headers
            //
            [
                'Location' => $base_uri . '/' . $uri,
                'Content-Type' => 'application/json',
            ],
            //
            //response to return
            //
            $response_body
        );
    }
}
