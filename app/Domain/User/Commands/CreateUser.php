<?php


namespace App\Domain\User\Commands;

use App\Aggregates\UserAggregate;
use Spatie\Shop\Support\EventSourcing\Attributes\AggregateUuid;
use Spatie\Shop\Support\EventSourcing\Attributes\HandledBy;

#[HandledBy(UserAggregate::class)]
class CreateUser
{
    public function __construct(
        #[AggregateUuid] public string $cartUuid,
        public string $cartItemUuid,
        public Product $product,
        public int $amount,
    ) {}
}
