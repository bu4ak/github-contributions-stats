<?php

namespace App\Services\Github;

use App\Services\Github\DTO\GithubRepositoryDTO;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Contracts\Cache\Repository as Cache;

use function GuzzleHttp\Promise\settle;

class GithubClient implements GithubClientInterface
{
    protected Client $client;
    protected Cache $cache;
    protected string $accessToken;
    protected int $cacheMinutes;

    /**
     * @param Client $client
     * @param Cache $cache
     * @param string $accessToken
     * @param int $cacheMinutes
     */
    public function __construct(Client $client, Cache $cache, string $accessToken, int $cacheMinutes = 15)
    {
        $this->client = $client;
        $this->cache = $cache;
        $this->accessToken = $accessToken;
        $this->cacheMinutes = $cacheMinutes;
    }

    /**
     * {@inheritDoc}
     */
    public function getRepositories(string $repoOwnerUsername): array
    {
        if (empty(trim($repoOwnerUsername))) {
            return [];
        }

        $promises = [];
        $result = [];

        foreach ($this->getPullRequests($repoOwnerUsername) as $item) {
            if ($item['author_association'] !== 'OWNER' && !isset($result[$item['repository_url']])) {
                if ($this->cache->has($item['repository_url'])) {
                    $body = $this->cache->get($item['repository_url']);
                    $decodedBody = json_decode($body, true);

                    $result[$decodedBody['full_name']] = new GithubRepositoryDTO(
                        $decodedBody['full_name'],
                        $decodedBody['language'] ?? '-',
                        $decodedBody['stargazers_count']
                    );
                } else {
                    $promises[] = $this->client->getAsync(
                        $item['repository_url'] . '?access_token=' . $this->accessToken
                    );
                }
            }
        }

        $responses = settle($promises)->wait();

        foreach ($responses as $response) {
            $body = (string)$response['value']->getBody();
            $data = json_decode($body, true);
            $ttl = Carbon::now()->addMinutes($this->cacheMinutes);
            $this->cache->put($data['url'], $body, $ttl);

            $result[$data['full_name']] = new GithubRepositoryDTO(
                $data['full_name'],
                $data['language'] ?? '-',
                $data['stargazers_count']
            );
        }
        uasort(
            $result,
            function (GithubRepositoryDTO $a, GithubRepositoryDTO $b) {
                return $b->getStars() - $a->getStars();
            }
        );

        return $result;
    }

    protected function getPullRequests(string $repoOwnerUsername)
    {
        $data = [
            'q' => "is:pr author:$repoOwnerUsername archived:false is:merged",
            'per_page' => '100',
            'page' => '1',
        ];
        $cacheKey = implode('', $data);
        $ttl = Carbon::now()->addMinutes($this->cacheMinutes);
        $function = function () use ($data) {
            $response = $this->client->request('GET', 'https://api.github.com/search/issues', ['query' => $data]);
            return json_decode($response->getBody(), true)['items'];
        };

        return $this->cache->remember($cacheKey, $ttl, $function);
    }
}
