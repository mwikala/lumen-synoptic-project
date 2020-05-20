<?php

namespace App\Http\Controllers;

use App\User;
use App\Card;
use App\Services\CardService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserService $userService, CardService $cardService)
    {
        $this->userService = $userService;
        $this->cardService = $cardService;
        $this->userToCardJoin = DB::table('users')->join('cards', 'users.id', '=', 'cards.user_id');
    }

    /**
     * Register a new user
     * 
     * @param Request $req
     * 
     * @return \App\User $user
     */
    public function register(Request $req)
    {
        $this->validate($req, [
            'employee_id' => ['required', 'unique:users,employee_id'],
            'name' => 'required',
            'email' => 'required',
            'mobile_num' => 'required',
            'pin' => ['required', 'digits:4'],
            'card_id' => ['required', 'size:16']
        ]);

        $createUserCommand = [
            'employee_id' => $req->input('employee_id'),
            'name' => $req->input('name'),
            'email' => $req->input('email'),
            'mobile_num' => $req->input('mobile_num'),
            'pin' => $req->input('pin')
        ];

        $newUserId = $this->userService->create($createUserCommand);
        $card = $this->cardService->create($req->input('card_id'), $newUserId);

        if (!$newUserId || !$card)
            return response()->json(['message' => 'error_storing_user_or_card'], 500);

        return response(null, 201);
    }

    /**
     * Handle card scan and return user
     *
     * @param Request $req
     *
     * @return \App\User $user
     */
    public function card_scanned(Request $req)
    {
        $this->validate($req, [
            'card_id' => ['required', 'size:16']
        ]);

        $cardId = $req->input('card_id');
        $user = $this->userService->get_user_by_card_id($cardId);

        if (!$user) {
            return response()->json(['message' => 'card_not_recognised'], 404);
        }

        return response()->json(['message' => 'successfully_scanned', 'user' => $user]);
    }

    /**
     * Verify Login using Card ID & Pin
     * 
     * @param Request $req
     * 
     * @return \App\User $user
     */
    public function verify_login(Request $req)
    {
        $this->validate($req, [
            'card_id' => ['required', 'size:16', 'exists:cards,card_id'],
            'pin' => ['required', 'digits:4']
        ]);

        $userPin = $this->userService->get_user_pin_by_card_id($req->input('card_id'));

        if (!$this->is_pin_correct($req->input('pin'), $userPin)) return response()->json(['message' => 'pin_incorrect'], 401);

        return response()->json(['message' => 'login_verified']);
    }

    /**
     * Verify a Inputted Pin Matches the Pin from Database
     * 
     * @param $inputtedPin, $userPin
     * 
     * @return Boolean
     */
    private function is_pin_correct($inputtedPin, $userPin)
    {
        if ($inputtedPin != $userPin) return false;
        return true;
    }
}
