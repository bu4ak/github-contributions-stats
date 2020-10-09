<?php

namespace App\Services\SVG\DTO;

class SvgBackgroundDTO
{
    private string $color;
    private int $width;
    private int $height;

    /**
     * @param string $color
     * @param int $width
     * @param int $height
     */
    public function __construct(string $color = 'white', int $width = 495, int $height = 195)
    {
        $this->color = $color;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

}
