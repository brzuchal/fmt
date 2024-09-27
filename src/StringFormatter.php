<?php declare(strict_types=1);

namespace fmt;

use ArrayAccess;
use Stringable;

final class StringFormatter
{
    private ArgumentExtractor $argumentExtractor;

    public function __construct(
        private FormatProvider $formatProvider
    ) {
        $this->argumentExtractor = new ArgumentExtractor();
    }

    public function format(string|Stringable $formatSpec, ...$values): string
    {
        return $this->formatValues($formatSpec, $values);
    }

    public function formatValues(string|Stringable $formatSpec, array|ArrayAccess $values): string
    {
        $result = '';
        $formatSpecLength = \strlen($formatSpec);
        if ($formatSpecLength === 0) {
            return $result;
        }
        $offset = 0;
        foreach (FormatSpecificationParser::try((string) $formatSpec) as $spec) {
            if ($spec->getOffset() > 0) {
                $result .= \substr(
                    $formatSpec,
                    $offset,
                    $spec->getOffset() - $offset
                );
            }
            $result .= $this->formatArgument(
                $spec->getArg(),
                $values,
                $spec->getFmt()
            );
            $offset = $spec->getOffset() + $spec->getLength();
        }
        if ($offset === 0) {
            return $formatSpec;
        }
        if ($offset < $formatSpecLength) {
            $result .= \substr(
                $formatSpec,
                $offset
            );
        }

        return $result;
    }

    private function formatArgument(Argument $arg, ArrayAccess|array $values, ?string $fmt): string
    {
        return $this->formatValue(
            $this->argumentExtractor->extractValue(
                $arg,
                $values
            ),
            $fmt
        );
    }

    private function formatValue(mixed $value, ?string $fmt): string
    {
        if (!$this->formatProvider->provides($value)) {
            return '';
        }

        return $this->formatProvider->format(
            $this->formatProvider,
            $fmt,
            $value
        );
    }
}
