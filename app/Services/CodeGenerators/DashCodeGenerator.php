<?php

namespace App\Services\CodeGenerators;

use App\Contracts\CodeGeneratorInterface;

class DashCodeGenerator implements CodeGeneratorInterface
{
    public function __construct(
        protected int $pad = 4
    ) {}

    public function generate(
        string $modelClass,
        string $column,
        string $prefix
    ): string {

        $lastCode = $modelClass::where($column, 'like', "{$prefix}-%")
            ->orderByDesc($column)
            ->value($column);

        if (!$lastCode) {
            return $prefix . '-' . str_pad(1, $this->pad, '0', STR_PAD_LEFT);
        }

        $number = (int) str_replace("{$prefix}-", '', $lastCode);

        $next = $number + 1;

        return $prefix . '-' . str_pad($next, $this->pad, '0', STR_PAD_LEFT);
    }
}