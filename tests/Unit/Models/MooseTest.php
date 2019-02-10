<?php

namespace Tests\Unit\Models;

use App\Models\Moose;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MooseTest extends TestCase
{
    /**
     * @var Moose
     */
    protected $moose;

    /**
     * setup
     */
    public function setUp()
    {
        parent::setUp();

        $this->moose = create(Moose::class);
    }

    /**
     * @test
     */
    public function itHasDefaultValues()
    {
        $this->assertEquals('default', $this->moose->test_string);
        $this->assertEquals(1, $this->moose->test_int);
        $this->assertTrue($this->moose->test_bool);
        $this->assertEquals(['key' => 'val'], $this->moose->test_array);
    }
}
