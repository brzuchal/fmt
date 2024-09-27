<?php declare(strict_types=1);

namespace fmt;

use Generator;
use fmt\FormatParser\ParserFailure;

final class FormatSpecificationParser
{
    private const START_CHAR = '{';
    private const END_CHAR = '}';
    private const MASK = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890_';
    const FORMAT_DELIMITER = ':';
public static ?string $formatSpec = null;
    /**
     * @return FormatSpecification[]|Generator
     * @throws ParserFailure
     */
    public static function try(string $formatSpec): Generator
    {
        self::$formatSpec = $formatSpec;
        $offset = 0;
        $argc = 0;
        $length = \strlen($formatSpec);
        if ($length == 0) {
            return;
        }
        while (false !== ($pos = \strpos($formatSpec, self::START_CHAR, $offset))) {
            $argpos = $pos + 1;
            self::verifyArgumentNotStartingAtEof(
                $argpos,
                $length
            );
            $nextChar = $formatSpec[$argpos];

            if ($nextChar == self::START_CHAR) {
                $offset += 2;
                continue;
            }

            $arg = ($nextChar == self::END_CHAR) ?
                new Argument\PositionalArgument($argc) :
                self::checkPositionalArgumentAt($formatSpec, $pos, $length) ??
                self::checkNamedArgumentAt($formatSpec, $pos, $length);

            if ($arg === null) {
                $offset++;
                continue;
            }
            $fmt = null;
            self::checkArgumentNotFinishedAtEof($argpos, $arg, $length);
            // TODO: replace Argument->getLength() with $length to minimize FCALLs
            $newOffset = $argpos + $arg->getLength();
            if ($formatSpec[$newOffset] === self::FORMAT_DELIMITER) {
                $endPosition = \strpos($formatSpec, self::END_CHAR, $newOffset + 1);
                if ($endPosition > $newOffset) {
                    $fmt = \substr($formatSpec, $newOffset + 1, $endPosition - $newOffset - 1);
                }
            }

            if ($arg) {
                yield $field = new FormatSpecification(
                    $pos,
                    $arg->getLength() + 2 + ($fmt ? 1 + \strlen($fmt) : 0),
                    $arg,
                    $fmt
                );
                $argc++;
                $offset = $field->getOffset() + $field->getLength();
                continue;

            }
            throw new ParserFailure(
                "Unexpected char \"{$formatSpec[$pos + 1]}\", expected argument replacement",
                1,
                $offset
            );
        }
    }

    protected static function checkPositionalArgumentAt(string $subject, int $pos, int $max): ?Argument
    {
        $argpos = $pos + 1;
        $arglen = 0;
        while (($argpos + $arglen) < $max &&
//            (\is_numeric($subject[$argpos + $arglen]) || $subject[$argpos + $arglen] === self::END_CHAR) &&
            \is_numeric($subject[$argpos + $arglen]) &&
            $arglen < 100) {
            // TODO: throw on max length reached
            $arglen++;
        }
        if (!$arglen) {
            return null;
        }

        return new Argument\PositionalArgument(
            (int) \substr($subject, $argpos, $arglen)
        );
    }

    protected static function checkNamedArgumentAt(string $subject, int $pos, int $max): ?Argument
    {
        $argpos = $pos + 1;
        $arglen = \strspn(
            $subject,
            self::MASK,
            $argpos,
            256
        );
        if (!$arglen) {
            return null;
        }

        return new Argument\NamedArgument(
            \substr($subject, $argpos, $arglen)
        );
    }

    protected static function createPositionalArgument(int $position): Argument\PositionalArgument
    {
        return new Argument\PositionalArgument(
            $position
        );
    }

    protected static function verifyArgumentNotStartingAtEof(int $argpos, int $length): void
    {
        if ($argpos < $length) {
            return;
        }
        throw new ParserFailure(
            'Unindexed positional argument finish at eof',
            2,
            $argpos
        );
    }

    protected static function checkArgumentNotFinishedAtEof(int $argpos, Argument $argument, int $length): void
    {
        if ($argpos + $argument->getLength() < $length) {
            return;
        }
        throw new ParserFailure(
            'Argument finish at eof',
            2,
            $argpos + $argument->getLength()
        );
    }

    protected static function checkArgumentNotEnclosed(string $subject, int $argpos, Argument $argument): void
    {
        $argumentClosingChar = $subject[$argpos + $argument->getLength()];
        if ($argumentClosingChar == self::END_CHAR) {
            return;
        }
        throw new ParserFailure(
            'Argument not closed by ' . self::END_CHAR,
            2,
            $argpos + $argument->getLength()
        );
    }
}
