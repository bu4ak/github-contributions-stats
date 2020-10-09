<?php

namespace App\Services\SVG;

use App\Services\Github\DTO\GithubRepositoryDTO;
use App\Services\SVG\DTO\SvgBackgroundDTO;
use App\Services\SVG\DTO\SvgTitleDTO;

interface SvgBuilderInterface
{
    /**
     * @return string
     */
    public function build(): string;

    /**
     * @param SvgBackgroundDTO $backgroundDTO
     */
    public function setBackground(SvgBackgroundDTO $backgroundDTO): void;

    /**
     * @param SvgTitleDTO $titleDTO
     */
    public function setTitle(SvgTitleDTO $titleDTO): void;

    /**
     * @param \App\Services\Github\DTO\GithubRepositoryDTO $repositoryDTO
     * @return void
     */
    public function addRepo(GithubRepositoryDTO $repositoryDTO): void;
}
