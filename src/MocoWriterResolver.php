<?php

namespace Rezzza\MocoBehatExtension;

use Behat\Behat\Context\Argument\ArgumentResolver;

class MocoWriterResolver implements ArgumentResolver
{
    private $mocoWriter;

    public function __construct(MocoWriter $mocoWriter)
    {
        $this->mocoWriter = $mocoWriter;
    }

    public function resolveArguments(\ReflectionClass $classReflection, array $arguments)
    {
        $constructor = $classReflection->getConstructor();
        if ($constructor === null) {
            return $arguments;
        }

        $parameters = $constructor->getParameters();

        foreach ($parameters as $parameter) {
            if (null !== $parameter->getClass() && $parameter->getClass()->name === MocoWriter::class) {
                $arguments[$parameter->name] = $this->mocoWriter;
            }
        }

        return $arguments;
    }
}
