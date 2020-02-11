<?php

declare(strict_types=1);

namespace N1215\EventSauceExample\Commands;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use N1215\EventSauceExample\CharacterId;
use N1215\EventSauceExample\UserId;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/** ユーザ所持キャラクター獲得コマンド */
final class AcquireUserCharacter implements SerializablePayload
{
    private UuidInterface $id;

    private UserId $userId;

    private CharacterId $characterId;

    private function __construct(UuidInterface $id, UserId $userId, CharacterId $characterId)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->characterId = $characterId;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getCharacterId(): CharacterId
    {
        return $this->characterId;
    }

    public static function new(UserId $userId, CharacterId $characterId): AcquireUserCharacter
    {
        return new self(
            Uuid::uuid4(),
            $userId,
            $characterId
        );
    }

    public static function fromPayload(array $payload): AcquireUserCharacter
    {
        return new self(
            Uuid::fromString($payload['id']),
            UserId::fromString($payload['userId']),
            CharacterId::fromInt((int)$payload['characterId'])
        );
    }

    public function toPayload(): array
    {
        return [
            'id' => $this->id->toString(),
            'userId' => $this->userId->toString(),
            'characterId' => $this->characterId->toInt(),
        ];
    }
}
