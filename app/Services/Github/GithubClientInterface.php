<?php

namespace App\Services\Github;

use App\Services\Github\DTO\GithubRepositoryDTO;

interface GithubClientInterface
{
    /**
     * @param string $repoOwnerUsername
     * @return GithubRepositoryDTO[]
     */
    public function getRepositories(string $repoOwnerUsername): array;
}
