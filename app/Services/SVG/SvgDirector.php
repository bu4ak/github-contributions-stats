<?php

namespace App\Services\SVG;

use App\Services\Github\DTO\GithubRepositoryDTO;
use App\Services\Github\GithubClientInterface;
use App\Services\SVG\DTO\SvgBackgroundDTO;
use App\Services\SVG\DTO\SvgTitleDTO;
use Illuminate\Http\Request;

class SvgDirector implements SvgDirectorInterface
{
    protected Request $request;

    protected SvgBuilderInterface $builder;

    protected GithubClientInterface $githubClient;

    /**
     * @param Request $request
     * @param SvgBuilderInterface $svgBuilder
     * @param GithubClientInterface $githubClient
     */
    public function __construct(Request $request, SvgBuilderInterface $svgBuilder, GithubClientInterface $githubClient)
    {
        $this->request = $request;
        $this->builder = $svgBuilder;
        $this->githubClient = $githubClient;
    }

    public function buildCard(): string
    {
        $username = $this->request->get('username', '');

        $this->builder->setBackground(new SvgBackgroundDTO());
        $this->builder->setTitle(new SvgTitleDTO("Top repos contributed to by $username"));
        foreach ($this->githubClient->getRepositories($username) as $repository) {
            $this->builder->addRepo($repository);
        }

        return $this->builder->build();
    }

    public function buildFallbackCard(): string
    {
        $this->builder->setBackground(new SvgBackgroundDTO());
        $this->builder->setTitle(new SvgTitleDTO("Something went wrong"));
        $this->builder->addRepo(new GithubRepositoryDTO('Please report an issue http://url/com', '', '-'));

        return $this->builder->build();
    }
}
