<?php

declare(strict_types=1);

namespace N1215\EventSauceExample\Commands;

use N1215\EventSauceExample\UserCharacterId;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * ユーザ所持キャラクター獲得コマンド
 */
final class EnhanceUserCharacter
{
    private UuidInterface $id;

    private UserCharacterId $userCharacterId;

    private int $experience;

    private function __construct(UuidInterface $id, UserCharacterId $userCharacterId, int $experience)
    {
        $this->id = $id;
        $this->userCharacterId = $userCharacterId;
        $this->experience = $experience;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getUserCharacterId(): UserCharacterId
    {
        return $this->userCharacterId;
    }

    public function getExperience(): int
    {
        return $this->experience;
    }

    public static function new(UserCharacterId $userCharacterId, int $experience): EnhanceUserCharacter
    {
        return new self(
            Uuid::uuid4(),
            $userCharacterId,
            $experience
        );
    }
}
