<?php declare(strict_types=1);

namespace fmt\FormatParser;

use Exception;

final class ParserFailure extends Exception
{
    private int $offset;

    public function __construct($message = "", $code = 0, int $offset = 0)
    {
        parent::__construct($message, $code);
        $this->offset = $offset;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }
}
