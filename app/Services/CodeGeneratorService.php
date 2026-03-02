<?php

namespace App\Services;

class CodeGeneratorService
{
    public function generate(
        string $modelClass,
        string $column,
        string $prefix,
        int $pad = 4
    ): string {
        $lastCode = $modelClass::where($column, 'like', "{$prefix}-%")
            ->orderByDesc($column)
            ->value($column);

        if (!$lastCode) {
            return $prefix . '-' . str_pad(1, $pad, '0', STR_PAD_LEFT);
        }

        $number = (int) str_replace("{$prefix}-", '', $lastCode);

        $next = $number + 1;

        return $prefix . '-' . str_pad($next, $pad, '0', STR_PAD_LEFT);
    }

    public function generateDotCode(
        string $modelClass,
        string $column,
        string $prefix,
        int $maxThirdLevel = 9
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

        if ($level3 >= $maxThirdLevel) {
            $level2++;
            $level3 = 1;
        } else {
            $level3++;
        }

        return "{$prefix}.{$level2}.{$level3}";
    }
}
