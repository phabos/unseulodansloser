<?php

namespace Main;

use Abraham\TwitterOAuth\TwitterOAuth;

class UnSeulODansLoser implements IUnSeulODansLoser
{
    private $connection = null;
    private $search = [];
    private $total = 0;
    private $results = [];
    private $searchPattern = '';

    public function __construct(TwitterOAuth $connection, array $search, string $searchPattern)
    {
        $this->connection = $connection;
        $this->search = $search;
        $this->searchPattern = $searchPattern;
    }

    private function search()
    {
        $results = $this->connection->get('search/tweets', $this->search);
        $this->reverseResults($results);
    }

    private function reverseResults($results)
    {
        if (isset($results->search_metadata->count) && $results->search_metadata->count) {
            $this->results = array_reverse($results->statuses);
        }
    }

    private function reTweet(\stdClass $result)
    {
        preg_match($this->searchPattern, $result->text, $looserMatch);
        if (count($looserMatch) > 0) {
            $this->connection->post('statuses/retweet', [
                'id' => $result->id_str
            ]);
            $this->incrementCount();
        }
    }

    private function incrementCount()
    {
        $this->total++;
    }

    public function getTotalReTweet(): int
    {
        return $this->total;
    }

    public function launch()
    {
        $this->search();
        foreach ($this->results as $result) {
            $this->reTweet($result);
        }

        echo 'Total de lOOser today: #' . $this->getTotalReTweet();
    }
}
