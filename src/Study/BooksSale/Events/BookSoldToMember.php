<?php

declare(strict_types=1);

namespace Francken\Study\BooksSale\Events;

use Broadway\Serializer\Serializable as SerializableInterface;
use Francken\Study\BooksSale\BookId;
use Francken\Domain\Members\MemberId;
use Francken\Domain\Serializable;

final class BookSoldToMember implements SerializableInterface
{
    use Serializable;

    private $bookId;
    private $buyersId;

    public function __construct(BookId $id, MemberId $buyersId)
    {
        $this->bookId = $id;
        $this->buyersId = $buyersId;
    }

    public function bookId() : BookId
    {
        return $this->bookId;
    }

    public function buyersId() : MemberId
    {
        return $this->buyersId;
    }

    protected static function deserializationCallbacks()
    {
        return ['bookId' => [BookId::class, 'deserialize'],
                'buyersId' => [MemberId::class, 'deserialize'], ];
    }
}
