<?php

namespace Ubirak\MocoBehatExtension;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterScenarioScope;

class MocoContext implements Context
{
    private $mocoWriter;

    public function __construct(MocoWriter $mocoWriter)
    {
        $this->mocoWriter = $mocoWriter;
    }

    /**
     * @AfterScenario @moco
     */
    public function before(AfterScenarioScope $scope)
    {
        $this->mocoWriter->reset();
    }
}
