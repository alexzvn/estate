<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddPhoneBlackListViaApiTest extends TestCase
{
    protected static $wasSetup = false;

    protected static $examplePhone = [];

    public function setUp() : void
    {
        parent::setUp();

        if (static::$wasSetup === false && static::$wasSetup = true) {
            static::$examplePhone = $this->generatePhone(5);
        }
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_add_phone_list()
    {
        $response = $this->postJson('api/blacklist/add',
            $this->getExamplePhone()
        );

        $response->assertJson([
            'success' => true,
            'phoneToAdd' => 5
        ]);
    }

    public function test_add_duplicated_phone_list()
    {
        $response = $this->postJson('api/blacklist/add',
            $this->getExtraExamplePhone()
        );

        $response->assertJson([
            'success' => true,
            'phoneToAdd' => 5
        ]);
    }

    public function test_add_nothing_to_list()
    {
        $this->postJson('api/blacklist/add')
        ->assertJson([
            'success' => true,
            'phoneToAdd' => 0
        ]);
    }

    private function getExamplePhone()
    {
        return static::$examplePhone;
    }

    private function getExtraExamplePhone()
    {
        return array_merge(
            static::$examplePhone,
            $this->generatePhone(5)
        );
    }

    
    protected function generatePhone(int $times = 1)
    {
        while ($times--) {
            $phone[] = [
                'agencyName'  => 'random name',
                'phoneNumber' => '0' . random_int(100000000, 999999999),
                'region'      => 'Quảng Nam',
                'category'    => 'Mua bán',
                'url'         => 'https://nha.chotot.com'
            ];
        }

        return $phone;
    }
}
