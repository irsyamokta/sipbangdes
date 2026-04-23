<?php

namespace App\Services\CodeGenerators;

use App\Contracts\CodeGeneratorInterface;

class DotCodeGenerator implements CodeGeneratorInterface
{
    public function __construct(
        protected int $maxThirdLevel = 9
    ) {}

    public function generate(
        string $modelClass,
        string $column,
        string $prefix
    ): string {

        $lastCode = $modelClass::where($column, 'like', "{$prefix}.%")
            ->orderByDesc($column)
            ->value($column);

        if (!$lastCode) {
            return "{$prefix}.1.1";
        }

        $parts = explode('.', $lastCode);

        if (count($parts) !== 3) {
            throw new \RuntimeException("Format kode tidak valid");
        }

        [$p, $level2, $level3] = $parts;

        $level2 = (int) $level2;
        $level3 = (int) $level3;

        if ($level3 >= $this->maxThirdLevel) {
            $level2++;
            $level3 = 1;
        } else {
            $level3++;
        }

        return "{$prefix}.{$level2}.{$level3}";
    }
}