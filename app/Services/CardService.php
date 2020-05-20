<?php

namespace App\Services;

use App\Card;


class CardService
{
    public function create($cardId, $userId)
    {
        return Card::create( [
            'user_id' => $userId,
            'card_id' => $cardId,
        ]);
    }
}
