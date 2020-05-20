<?php

namespace App\Services;

use App\User;
use App\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function __construct()
    {
        $this->userToCardJoin = DB::table('users')->join('cards', 'users.id', '=', 'cards.user_id');
    }

    public function get_user_by_card_id($cardId)
    {
        return $this->userToCardJoin
            ->select('users.*', 'cards.card_id')
            ->where('cards.card_id', $cardId)
            ->first();
    }

    public function get_user_pin_by_card_id($cardId)
    {
        $user = $this->userToCardJoin
            ->select('users.*', 'cards.card_id')
            ->where('cards.card_id', $cardId)
            ->first();

        return $user->pin;
    }

    public function create($createUserModel)
    {
        $user =  User::create($createUserModel);
        return $user->id;
    }
}
