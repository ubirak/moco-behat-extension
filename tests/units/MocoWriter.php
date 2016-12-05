<?php

namespace Rezzza\MocoBehatExtension\Tests\Units;

use mageekguy\atoum;

class MocoWriter extends atoum
{
    public function test_it_should_write_request_and_response()
    {
        $this
            ->given(
                $this->newTestedInstance('fixtures.json', '127.0.0.1', '8888'),
                $this->function->file_put_contents = true,
                $this->function->stream_socket_client = true,
                $this->testedInstance->mockHttpCall(['uri' => '/coucou'], ['status' => '404'])
            )
            ->when(
                $this->testedInstance->writeForMoco()
            )
            ->then
                ->function('file_put_contents')
                    ->wasCalledWithArguments('fixtures.json', json_encode([['request' => ['uri' => '/coucou'], 'response' => ['status' => '404']]]))
                    ->once()
        ;
    }

    public function test_it_should_reset_payload_to_moco()
    {
        $this
            ->given(
                $this->newTestedInstance('fixtures.json', '127.0.0.1', '8888'),
                $this->function->file_put_contents = true,
                $this->function->stream_socket_client = true,
                $this->testedInstance->mockHttpCall(['uri' => '/coucou'], ['status' => '404'])
            )
            ->when(
                $this->testedInstance->reset()
            )
            ->then
                ->function('file_put_contents')
                    ->wasCalledWithArguments('fixtures.json', json_encode([]))
                    ->once()
        ;
    }
}
