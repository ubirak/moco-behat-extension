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

    public function test_it_should_write_events_if_present()
    {
        $this
            ->given(
                $this->newTestedInstance('fixtures.json', '127.0.0.1', '8888'),
                $this->function->file_put_contents = true,
                $this->function->stream_socket_client = true,
                $this->testedInstance->mockHttpCall(['uri' => '/coucou'], ['status' => '404'], ['complete' => 'ok'])
            )
            ->when(
                $this->testedInstance->writeForMoco()
            )
            ->then
                ->function('file_put_contents')
                    ->wasCalledWithArguments('fixtures.json', json_encode([
                        ['request' => ['uri' => '/coucou'], 'response' => ['status' => '404'], 'on' => ['complete' => 'ok']],
                    ]))
                    ->once()
        ;
    }

    public function test_it_should_try_to_connect_when_first_tries_fail()
    {
        $this
            ->given(
                $this->newTestedInstance('fixtures.json', '127.0.0.1', '8888'),
                $this->function->file_put_contents = true,
                $this->function->stream_socket_client = false,
                $this->function->stream_socket_client[5] = true,
                $this->testedInstance->mockHttpCall(['uri' => '/coucou'], ['status' => '404'], ['complete' => 'ok'])
            )
            ->when(
                $this->testedInstance->writeForMoco(5, 1)
            )
            ->then
                ->function('stream_socket_client')
                    ->wasCalled()
                    ->exactly(5)
        ;
    }

    public function test_it_should_lead_to_exception_when_cannot_connect()
    {
        $this
            ->given(
                $this->newTestedInstance('fixtures.json', '127.0.0.1', '8888'),
                $this->function->file_put_contents = true,
                $this->function->stream_socket_client = false,
                $this->testedInstance->mockHttpCall(['uri' => '/coucou'], ['status' => '404'], ['complete' => 'ok'])
            )
            ->exception(function () {
                $this->testedInstance->writeForMoco(10, 1);
            })
                ->message->contains('Cannot connect to moco')
            ->then
                ->function('stream_socket_client')
                    ->wasCalled()
                    ->exactly(10)
        ;
    }
}
