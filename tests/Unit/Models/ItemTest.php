<?php

namespace Tests\Unit\Models;

use App\Models\Item;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemTest extends TestCase
{
    /**
     * @var Item
     */
    protected $item;

    /**
     * setup
     */
    public function setUp()
    {
        parent::setUp();

        $this->item = create(Item::class);

        $this->item->setFieldAttributes([
            'field_col'  => null,
            'sample_row' => null,
        ]);
    }

    /**
     * @test
     */
    public function itHasEmptyFieldColumn()
    {
        $value = $this->item->field_col;

        $this->assertEmpty($value);
    }

    /**
     * @test
     */
    public function itCanSetColumnValue()
    {
        $this->item->field_col = 'test';

        $this->assertEquals('test', $this->item->field_col);
    }

    /**
     * @test
     */
    public function itCanSaveColumnValue()
    {
        $this->item->field_col = 'test';
        $this->item->save();

        $this->assertEquals('test', $this->item->field_col);

        $this->item->refresh();

        $this->assertEquals('test', $this->item->field_col);
    }

    /**
     * @test
     */
    public function itCanSaveNestedColumnValueByArray()
    {
        $this->item->sample_row = [
            'value' => 'test',
        ];
        $this->item->save();

        $this->assertDatabaseHas('fields', [
            'fieldable_type' => get_class($this->item),
            'fieldable_id'   => $this->item->id,
            'name'           => 'default',
        ]);

        $this->assertEquals('test', $this->item->sample_row->value);

        $this->item->refresh();

        $this->assertEquals('test', $this->item->sample_row->value);
    }

    /**
     * @test
     */
    public function itCanBelongToAnItem()
    {
        $item = create(Item::class);

        $this->assertEmpty($this->item->itemable);

        // save one
        $this->item->itemable()->associate($item);

        $this->assertInstanceOf(Item::class, $this->item->itemable);
    }

    /**
     * @test
     */
    public function itCanHaveOneItem()
    {
        $item = create(Item::class);

        $this->assertEmpty($this->item->item()->first());

        // save one
        $this->item->item()->save($item);

        $this->assertInstanceOf(Item::class, $this->item->item()->first());
    }

    /**
     * @test
     */
    public function itCanSaveMultipleItems()
    {
        $item1 = create(Item::class);
        $item2 = create(Item::class);

        $this->assertCount(0, $this->item->items()->get());

        // save one
        $this->item->items()->save($item1);

        $this->assertCount(1, $this->item->items()->get());

        // save two
        $this->item->items()->save($item2);

        $this->assertCount(2, $this->item->items()->get());
    }

    /**
     * @test
     */
    public function itCanHaveManyItems()
    {
        $item1 = create(Item::class);
        $item2 = create(Item::class);

        $this->assertCount(0, $this->item->manyItems()->get());

        // save one
        $this->item->manyItems()->attach($item1->id);

        $this->assertCount(1, $this->item->manyItems()->get());

        // save two
        $this->item->manyItems()->attach($item2->id);

        $this->assertCount(2, $this->item->manyItems()->get());
    }

    /**
     * @test
     */
    public function itCanBeByManyItems()
    {
        $item1 = create(Item::class);
        $item2 = create(Item::class);

        $this->assertCount(0, $this->item->byManyItems()->get());

        // save one
        $this->item->byManyItems()->attach($item1->id);

        $this->assertCount(1, $this->item->byManyItems()->get());

        // save two
        $this->item->byManyItems()->attach($item2->id);

        $this->assertCount(2, $this->item->byManyItems()->get());
    }
}
