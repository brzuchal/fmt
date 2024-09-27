<?php declare(strict_types=1);

namespace fmt\Argument;

use fmt\Argument;

final class NamedArgument implements Argument
{
    use ArgumentWithLength;
    public string $name;
    
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->length = \strlen($name);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
