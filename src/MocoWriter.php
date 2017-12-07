<?php

namespace Ubirak\MocoBehatExtension;

class MocoWriter
{
    private $payload = [];

    private $jsonFile;

    private $hostname;

    private $port;

    public function __construct($jsonFile, $hostname, $port)
    {
        $this->jsonFile = $jsonFile;
        $this->hostname = $hostname;
        $this->port = $port;
    }

    public function mockHttpCall(array $matchedRequest, array $mockedResponse, array $events = [])
    {
        $entry = [
            'request' => $matchedRequest,
            'response' => $mockedResponse,
        ];

        if (0 < count($events)) {
            $entry['on'] = $events;
        }

        $this->payload[] = $entry;
    }

    public function writeForMoco($maxAttempt = 10, $tempoInMs = 200)
    {
        file_put_contents($this->jsonFile, json_encode($this->payload));
        // We need to wait for moco detecting the fixtures file changed
        // If not, we can perform a request on old configFile
        sleep(1);
        $this->waitForMoco($maxAttempt, $tempoInMs);
    }

    public function reset($maxAttempt = 10, $tempoInMs = 200)
    {
        $this->payload = [];
        // To avoid false positive by having the next scenario using moco response of the previous scenario
        $this->writeForMoco($maxAttempt, $tempoInMs);
    }

    private function waitForMoco($maxAttempt, $tempoInMs)
    {
        $attempts = 0;
        $ip = gethostbyname($this->hostname);
        $up = false;
        while ($attempts < $maxAttempt && false === $up) {
            $socket = @stream_socket_client('tcp://'.$ip.':'.$this->port, $errno, $errstr, 5);

            if (false === $socket) {
                usleep($tempoInMs * 1000);
                ++$attempts;
            } else {
                $up = true;
                @fclose($socket);
            }
        }

        if ($maxAttempt <= $attempts) {
            throw new \Exception(
                sprintf('Cannot connect to moco on %s : %s. Ensure to run moco with "bin/moco start -p %s -c %s"',
                    $ip,
                    $errstr,
                    $this->port,
                    $this->jsonFile
                )
            );
        }
    }
}
