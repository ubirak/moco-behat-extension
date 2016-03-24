<?php

namespace Rezzza\MocoBehatExtension;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;

class MocoContext implements Context
{
    private $mocoWriter;

    private $mocoIp;

    private $mocoPort;

    public function __construct(MocoWriter $mocoWriter, $mocoIp, $mocoPort)
    {
        $this->mocoWriter = $mocoWriter;
        $this->mocoIp = $mocoIp;
        $this->mocoPort = $mocoPort;
    }

    /**
     * @BeforeScenario @moco
     */
    public function before(BeforeScenarioScope $scope)
    {
        if (false === @fsockopen($this->mocoIp, $this->mocoPort, $errno, $errstr, 3)) {
            throw new \Exception(
                sprintf('You should run moco by "bin/moco start -p %s -c %s"', $this->mocoPort, $this->mocoWriter->getJsonFile())
            );
        }
        $this->mocoWriter->reset();
    }
}
