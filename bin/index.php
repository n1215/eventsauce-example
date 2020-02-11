<?php
declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use EventSauce\EventSourcing\InMemoryMessageRepository;
use EventSauce\EventSourcing\SynchronousMessageDispatcher;
use N1215\EventSauceExample\Consumers\DumpEvents;
use N1215\EventSauceExample\UserCharacter;
use N1215\EventSauceExample\UserCharacterId;
use N1215\EventSauceExample\UserId;
use N1215\EventSauceExample\CharacterId;
use N1215\EventSauceExample\Commands\AcquireUserCharacter;
use N1215\EventSauceExample\Commands\EnhanceUserCharacter;
use EventSauce\EventSourcing\ConstructingAggregateRootRepository;

// AggregateRootRepositoryのデフォルト実装を使う
$userCharacterRepository = new ConstructingAggregateRootRepository(
    // 対象の集約のクラス
    UserCharacter::class,
    // MessageRepositoryのインメモリ実装
    // Doctrineによる実装あり： eventsauce/doctrine-message-repository
    new InMemoryMessageRepository(),
    // MessageDispatcherの同期処理による実装
    // RabbitMQ用の実装あり： eventsauce/rabbitmq-bundle-bindings
    new SynchronousMessageDispatcher(new DumpEvents())
);


// 初期化
$newUserCharacter = UserCharacter::initiate();
$userId = UserId::fromString('dummy_user_1');
$characterId = CharacterId::fromInt(1);

$newUserCharacter->performAcquire(AcquireUserCharacter::new($userId, $characterId));

// 永続化
$userCharacterRepository->persist($newUserCharacter);

// 再取得
$newUserCharacterId = $newUserCharacter->aggregateRootId();
assert($newUserCharacterId instanceof UserCharacterId);
/** @var UserCharacter $persistedUserCharacter */
$persistedUserCharacter = $userCharacterRepository->retrieve($newUserCharacterId);

// 強化
$experience = 100;
$persistedUserCharacter->performEnhance(EnhanceUserCharacter::new($newUserCharacterId, $experience));

// 永続化
$userCharacterRepository->persist($persistedUserCharacter);
