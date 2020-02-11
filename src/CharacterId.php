<?php

declare(strict_types=1);

namespace N1215\EventSauceExample;

/**
 * キャラクターのマスタID
 * @package N1215\EventsauceTest
 */
final class CharacterId
{
    private int $id;

    private function __construct(int $id)
    {
        $this->id = $id;
    }

    public function toInt(): int
    {
        return $this->id;
    }

    public static function fromInt(int $id): CharacterId
    {
        return new static($id);
    }
}
