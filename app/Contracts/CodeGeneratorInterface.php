<?php

namespace App\Contracts;

interface CodeGeneratorInterface
{
    public function generate(
        string $modelClass,
        string $column,
        string $prefix
    ): string;
}
