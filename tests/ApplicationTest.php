<?php

use App\Services\UserService;
use App\Services\CardService;
use Illuminate\Support\Facades\Validator;

class ApplicationTest extends TestCase
{
    /**
     * Test calling the /card/scan route will return 
     */
    public function test_200_returned_when_card_scanned_successfully()
    {
        app()->bind(UserService::class, function () {
            $userService = Mockery::mock(\App\Services\UserService::class);
            $userService->shouldReceive("get_user_by_card_id")->andReturn(["foo" => "bar"]);
            return $userService;
        });

        $response = $this->post('/card/scan', ["card_id" => "4242424242424242"]);
        $response->assertResponseOk();
    }

    public function test_register_calls_user_service_create_and_calls_card_service_create_if_validation_passes()
    {
        $validator = Mockery::mock(\Illuminate\Validation\Validator::class);
        $validator->shouldReceive('fails')
            ->andReturn(false);
        Validator::shouldReceive('make')->andReturn($validator);

        app()->bind(UserService::class, function () {
            $userService = Mockery::mock(UserService::class);
            $userService->shouldReceive("create")->andReturn(1);
            return $userService;
        });

        app()->bind(CardService::class, function () {
            $cardService = Mockery::mock(CardService::class);
            $cardService->shouldReceive("create")->andReturn(true);
            return $cardService;
        });

        $response = $this->post('/user/register', [
            'employee_id' => '1234',
            'name' => 'John Doe',
            'email' => 'j.doe@example.com',
            'mobile_num' => '1234567890',
            'pin' => '1234',
            'card_id' => 'aaa'
        ]);

        $response->assertResponseStatus(201);
    }

    public function test_200_returned_when_pin_entered_matches_user_card_scanned_and_validation_passes()
    {
        $validator = Mockery::mock(\Illuminate\Validation\Validator::class);
        $validator->shouldReceive('fails')
            ->andReturn(false);
        Validator::shouldReceive('make')->andReturn($validator);

        app()->bind(UserService::class, function () {
            $userService = Mockery::mock(\App\Services\UserService::class);
            $userService->shouldReceive("get_user_pin_by_card_id")->andReturn(1234);
            return $userService;
        });

        $response = $this->post('/card/verify', ['card_id' => 'aaa', 'pin' => 1234]);

        $response->assertResponseOk();
    }

    // public function test_422_returned_when_card_id_not_provided()
    // {
    //     app()->bind(UserService::class, function () {
    //         $userService = Mockery::mock(\App\Services\UserService::class);
    //         $userService->shouldReceive("get_user_by_card_id")->andReturn(null);
    //         return $userService;
    //     });

    //     $response = $this->post('/card/scan');
    //     $response->assertResponseStatus(422);
    // }

    // public function test_422_returned_when_card_id_is_too_short()
    // {
    //     app()->bind(UserService::class, function () {
    //         $userService = Mockery::mock(\App\Services\UserService::class);
    //         $userService->shouldReceive("get_user_by_card_id")->andReturn(null);
    //         return $userService;
    //     });

    //     $response = $this->post('/card/scan', ["card_id" => "aaa"]);
    //     $response->assertResponseStatus(422);
    // }
}
