<?php

declare(strict_types=1);

namespace N1215\EventSauceExample\Events;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use N1215\EventSauceExample\CharacterId;
use N1215\EventSauceExample\UserCharacterId;
use N1215\EventSauceExample\UserId;

/**
 * ユーザ所持キャラクター獲得イベント
 */
final class UserCharacterAcquired implements SerializablePayload
{
    /**
     * ユーザ所持キャラクターID
     */
    private UserCharacterId $userCharacterId;

    /**
     * キャラクターを取得したユーザのID
     */
    private UserId $userId;

    /**
     * キャラクターのマスタID
     */
    private CharacterId $characterId;

    public function __construct(UserCharacterId $userCharacterId, UserId $userId, CharacterId $characterId)
    {
        $this->userCharacterId = $userCharacterId;
        $this->userId = $userId;
        $this->characterId = $characterId;
    }

    public function getUserCharacterId(): UserCharacterId
    {
        return $this->userCharacterId;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getCharacterId(): CharacterId
    {
        return $this->characterId;
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new self(
            UserCharacterId::fromString($payload['userCharacterId']),
            UserId::fromString($payload['userId']),
            CharacterId::fromInt((int)$payload['characterId'])
        );
    }

    public function toPayload(): array
    {
        return [
            'userCharacterId' => $this->userCharacterId->toString(),
            'userId' => $this->userId->toString(),
            'characterId' => $this->characterId->toInt(),
        ];
    }
}
