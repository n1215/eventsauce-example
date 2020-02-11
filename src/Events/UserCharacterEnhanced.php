<?php

declare(strict_types=1);

namespace N1215\EventSauceExample\Events;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use N1215\EventSauceExample\UserCharacterId;

/**
 * ユーザ所持キャラクター強化イベント
 */
final class UserCharacterEnhanced implements SerializablePayload
{
    private UserCharacterId $userCharacterId;

    /**
     * 経験値
     */
    private int $experience;

    public function __construct(UserCharacterId $userCharacterId, int $experience)
    {
        $this->userCharacterId = $userCharacterId;
        $this->experience = $experience;
    }

    /**
     * @return UserCharacterId
     */
    public function getUserCharacterId(): UserCharacterId
    {
        return $this->userCharacterId;
    }

    public function getExperience(): int
    {
        return $this->experience;
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new self(
            UserCharacterId::fromString($payload['userCharacterId']),
            (int)$payload['experience'],
        );
    }

    public function toPayload(): array
    {
        return [
            'userCharacterId' => $this->userCharacterId->toString(),
            'experience' => $this->experience,
        ];
    }
}
