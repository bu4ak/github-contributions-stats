<?php

namespace App\Services\Github\DTO;

class GithubRepositoryDTO
{
    private string $name;
    private string $language;
    private string $stars;

    /**
     * SvgRepositoryDTO constructor.
     * @param string $name
     * @param string $language
     * @param string $stars
     */
    public function __construct(string $name, string $language, string $stars)
    {
        $this->name = $name;
        $this->language = $language;
        $this->stars = $stars;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @return string
     */
    public function getStars(): string
    {
        return $this->stars;
    }
}
