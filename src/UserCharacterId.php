<?php

declare(strict_types=1);

namespace N1215\EventSauceExample;

use EventSauce\EventSourcing\AggregateRootId;

/**
 * Class UserCharacterId
 * @package N1215\EventsauceTest
 */
final class UserCharacterId implements AggregateRootId
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

    public static function fromString(string $id): UserCharacterId
    {
        return new static($id);
    }

    public function equals(UserCharacterId $userCharacterId): bool
    {
        return $this->id === $userCharacterId->toString();
    }
}
