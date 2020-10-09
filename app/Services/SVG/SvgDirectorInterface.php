<?php

namespace App\Services\SVG;

interface SvgDirectorInterface
{
    public function buildCard(): string;

    public function buildFallbackCard(): string;
}
