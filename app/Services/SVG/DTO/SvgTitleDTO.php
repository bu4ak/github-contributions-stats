<?php

namespace App\Services\SVG\DTO;

class SvgTitleDTO
{
    private string $text;
    private int $xCoordinate;
    private int $yCoordinate;

    /**
     * @param string $text
     * @param int $xCoordinate
     * @param int $yCoordinate
     */
    public function __construct(string $text, int $xCoordinate = 25, int $yCoordinate = 35)
    {
        $this->text = ucwords($text);
        $this->xCoordinate = $xCoordinate;
        $this->yCoordinate = $yCoordinate;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return int
     */
    public function getXCoordinate(): int
    {
        return $this->xCoordinate;
    }

    /**
     * @return int
     */
    public function getYCoordinate(): int
    {
        return $this->yCoordinate;
    }
}
