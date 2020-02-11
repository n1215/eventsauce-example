<?php

declare(strict_types=1);

namespace N1215\EventSauceExample;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\AggregateRootTestCase;
use N1215\EventSauceExample\Commands\EnhanceUserCharacter;
use N1215\EventSauceExample\Events\UserCharacterAcquired;
use N1215\EventSauceExample\Events\UserCharacterEnhanced;
use N1215\EventSauceExample\Exceptions\AlreadyMaxLevelException;

/**
 * ユーザ所持キャラクターのテスト
 */
class UserCharacterTest extends AggregateRootTestCase
{
    /**
     * テスト対象の集約のIDを返す
     * AggregateRootTestCaseのための設定1
     */
    protected function newAggregateRootId(): AggregateRootId
    {
        return UserCharacterId::fromString('dummy_user_character_id_1');
    }

    /**
     * テスト対象の集約の完全就職クラス名を返す
     * AggregateRootTestCaseのための設定2
     */
    protected function aggregateRootClassName(): string
    {
        return UserCharacter::class;
    }

    /**
     * when()の入力を処理する。コマンドを渡して集約のメソッドを呼ぶ処理
     * AggregateRootTestCaseのための設定3
     * @param object $command
     * @throws AlreadyMaxLevelException
     */
    protected function handle(object $command): void
    {
        /** @var UserCharacter $userCharacter */
        $userCharacter = $this->repository->retrieve($this->aggregateRootId);

        if ($command instanceof EnhanceUserCharacter) {
            $userCharacter->performEnhance($command);
        }

        $this->repository->persist($userCharacter);
    }

    /**
     * @testdox 強化が成功する
     */
    public function test_enhance(): void
    {
        $userCharacterId = $this->aggregateRootId();
        assert($userCharacterId instanceof UserCharacterId);

        $this
            ->given(
                new UserCharacterAcquired($userCharacterId, UserId::fromString('dummy_user_1'), CharacterId::fromInt(1))
            )
            ->when(EnhanceUserCharacter::new($userCharacterId, 1000))
            ->then(new UserCharacterEnhanced($userCharacterId, 1000));
    }

    /**
     * @testdox 最大レベルのとき強化が失敗する
     */
    public function test_enhance_fails_when_max_level(): void
    {
        $userCharacterId = $this->aggregateRootId();
        assert($userCharacterId instanceof UserCharacterId);

        $this
            ->given(
                new UserCharacterAcquired(
                    $userCharacterId, UserId::fromString('dummy_user_1'), CharacterId::fromInt(1)
                ),
                new UserCharacterEnhanced($userCharacterId, 1000000)
            )
            ->when(EnhanceUserCharacter::new($userCharacterId, 1000))
            ->expectToFail(new AlreadyMaxLevelException('最大レベルです'));
    }
}