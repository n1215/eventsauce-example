<?php

declare(strict_types=1);

namespace N1215\EventSauceExample;

use EventSauce\EventSourcing\AggregateRootId;

/**
 * ユーザID
 */
final class UserId implements AggregateRootId
{
    private string $id;

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public function toString(): string
    {
        return $this->id;
    }

    public static function fromString(string $id): UserId
    {
        return new static($id);
    }
}
