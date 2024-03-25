<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Search;

use App\Store\Domain\Entity\Search\ValueObject\SearchPhrasesId;

class SearchPhrases
{
    private SearchPhrasesId $id;
    private string $phrases;

    public function __construct(SearchPhrasesId $id, string $phrases)
    {
        $this->id = $id;
        $this->phrases = $phrases;
    }

    public function getId(): SearchPhrasesId
    {
        return $this->id;
    }

    public function getPhrases(): string
    {
        return $this->phrases;
    }
}
