<?php

declare(strict_types=1);

namespace N1215\EventSauceExample\Consumers;

use EventSauce\EventSourcing\Consumer;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

/**
 * イベントをダンプ
 */
final class DumpEvents implements Consumer
{
    public function handle(Message $message)
    {
        /** @var SerializablePayload $event */
        $event = $message->event();

        echo PHP_EOL . '`' . get_class($event) . '` occurred.' . PHP_EOL;

        var_dump(
            'event handled.',
            array_merge(
                [
                    'aggregateRootId' => $message->aggregateRootId()->toString(),
                    'aggregateRootVersion' => $message->aggregateVersion(),
                ],
                $event->toPayload(),
            )
        );
    }
}
