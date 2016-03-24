<?php

namespace Rezzza\MocoBehatExtension;

class MocoWriter
{
    private $payload = [];

    private $jsonFile;

    public function __construct($jsonFile)
    {
        $this->jsonFile = $jsonFile;
    }

    public function mockHttpCall(array $matchedRequest, array $mockedResponse)
    {
        $this->payload[] = [
            "request" => $matchedRequest,
            "response" => $mockedResponse
        ];
    }

    public function writeForMoco()
    {
        file_put_contents($this->jsonFile, json_encode($this->payload));
        sleep(1);
    }

    public function reset()
    {
        $this->payload = [];
        $this->writeForMoco();
    }

    public function getJsonFile()
    {
        return $this->jsonFile;
    }
}
