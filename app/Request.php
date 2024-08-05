<?php

namespace App;

use App\Storage\Repo;
use Illuminate\Support\Collection;

class Request
{
    public const string GIT_API_URL = 'https://api.github.com';

    function request(string $url): string|false
    {
        $request_url = self::GIT_API_URL . $url;

        $curl = curl_init($request_url);

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_USERPWD => env('GITHUB_USER') . ':' . env('GITHUB_TOKEN'),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'User-Agent: RepoLister',
            ],
        ]);

        $response = curl_exec($curl);
        $curl_error = curl_error($curl);

        curl_close($curl);

        if ($curl_error) {
            echo "curl error: " . $curl_error . PHP_EOL;
            return false;
        }

        return $response;
    }

    function repos(): Collection
    {
        $result = json_decode($this->request('/user/repos?type=all&per_page=100'), associative: true);

        if(is_array($result) && array_key_exists('message', $result) && $result['message'] === 'Requires authentication') {
            die('authentication failed' . PHP_EOL);
        }

        return Collection::make($result)
            ->map(fn(array $repo) => Repo::fromRequest($repo));
    }
}
