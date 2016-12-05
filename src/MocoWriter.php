<?php

namespace Rezzza\MocoBehatExtension;

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
            "request" => $matchedRequest,
            "response" => $mockedResponse
        ];

        if (0 < count($events)) {
            $entry['on'] = $events;
        }

        $this->payload[] = $entry;
    }

    public function writeForMoco()
    {
        file_put_contents($this->jsonFile, json_encode($this->payload));
        // We need to wait for moco detecting the fixtures file changed
        // If not, we can perform a request on old configFile
        sleep(1);
        $this->waitForMoco();
    }

    public function reset()
    {
        $this->payload = [];
        // To avoid false positive by having the next scenario using moco response of the previous scenario
        $this->writeForMoco();
    }

    private function waitForMoco()
    {
        $attempts = 0;
        $max = 10;
        $ip = gethostbyname($this->hostname);
        while (false === @stream_socket_client('tcp://'.$ip.':'.$this->port, $errno, $errstr, 5) && ($attempts < $max)) {
            usleep(200000); // 200ms
            $attempts++;
        }

        if ($max <= $attempts) {
            throw new \Exception(
                sprintf('You should run moco by "bin/moco start -p %s -c %s"', $this->port, $this->jsonFile)
            );
        }
    }
}
