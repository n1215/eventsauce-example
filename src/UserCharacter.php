<?php

declare(strict_types=1);

namespace N1215\EventSauceExample;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;
use EventSauce\EventSourcing\AggregateRootId;
use N1215\EventSauceExample\Commands\AcquireUserCharacter;
use N1215\EventSauceExample\Commands\EnhanceUserCharacter;
use N1215\EventSauceExample\Events\UserCharacterAcquired;
use N1215\EventSauceExample\Events\UserCharacterEnhanced;
use N1215\EventSauceExample\Exceptions\AlreadyMaxLevelException;
use N1215\EventSauceExample\Exceptions\UnpublishedCharacterException;
use Ramsey\Uuid\Uuid;

/**
 * ユーザ所持キャラクター（集約）
 */
final class UserCharacter implements AggregateRoot
{
    use AggregateRootBehaviour;

    private UserId $userId;

    private CharacterId $characterId;

    /** 経験値 */
    private int $experience = 0;

    public static function initiate(): UserCharacter
    {
        $userCharacterId = UserCharacterId::fromString(Uuid::uuid4()->toString());
        return new static($userCharacterId);
    }

    /**
     * @param AcquireUserCharacter $command
     * @throws UnpublishedCharacterException
     */
    public function performAcquire(AcquireUserCharacter $command): void
    {
        $this->checkIfCharacterPublished($command->getCharacterId());

        assert($this->aggregateRootId instanceof UserCharacterId);
        $this->recordThat(
            new UserCharacterAcquired(
                $this->aggregateRootId,
                $command->getUserId(),
                $command->getCharacterId()
            )
        );
    }

    /**
     * キャラクターが公開されているかどうかをチェック
     * @param CharacterId $characterId
     * @throws UnpublishedCharacterException
     */
    private function checkIfCharacterPublished(CharacterId $characterId): void
    {
        // ロジックはダミー
        if ($characterId->toInt() !== 1) {
            throw new UnpublishedCharacterException('非公開のキャラクターです');
        }
    }

    /**
     * キャラクター獲得イベントを適用
     * @param UserCharacterAcquired $event
     */
    protected function applyUserCharacterAcquired(UserCharacterAcquired $event): void
    {
        $this->userId = $event->getUserId();
        $this->characterId = $event->getCharacterId();
    }

    /**
     * @param EnhanceUserCharacter $command
     * @throws AlreadyMaxLevelException
     */
    public function performEnhance(EnhanceUserCharacter $command): void
    {
        if (!$this->aggregateRootId->equals($command->getUserCharacterId())) {
            throw new \InvalidArgumentException();
        }

        if ($this->isMaxLevel()) {
            throw new AlreadyMaxLevelException('最大レベルです');
        }

        $this->recordThat(
            new UserCharacterEnhanced(
                $command->getUserCharacterId(),
                $command->getExperience()
            )
        );
    }

    private function isMaxLevel(): bool
    {
        return $this->experience >= 1000000;
    }

    protected function applyUserCharacterEnhanced(UserCharacterEnhanced $event): void
    {
        $this->experience += $event->getExperience();
    }
}
