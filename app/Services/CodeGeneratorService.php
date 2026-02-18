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
}
