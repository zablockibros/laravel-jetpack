<?php

namespace Tests\Unit\Models;

use App\Models\Field;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FieldTest extends TestCase
{
    /**
     * @var Field
     */
    protected $field;

    /**
     * @before
     */
    public function setUp()
    {
        parent::setUp();

        $this->field = create(Field::class);
    }

    /**
     * @test
     */
    public function itGetsEmptyColumn()
    {
        $this->assertEmpty($this->field->test_column);
    }

    /**
     * @test
     */
    public function itGetsStringColumn()
    {
        $this->field->columns = [
            'test_column' => 'value',
        ];
        $this->field->save();

        $this->assertEquals('value', $this->field->test_column);
    }

    /**
     * @test
     */
    public function itSetsStringColumn()
    {
        $this->field->test_column = 'value';
        $this->field->save();

        $this->assertEquals('value', $this->field->test_column);
        $this->assertEquals('value', $this->field->columns['test_column']);
    }
}
