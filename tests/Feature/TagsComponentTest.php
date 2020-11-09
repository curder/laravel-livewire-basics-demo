<?php

namespace Tests\Feature;

use App\Http\Livewire\TagsComponent;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Class TagsComponentTest
 *
 * @package Tests\Feature
 */
class TagsComponentTest extends TestCase
{
    use RefreshDatabase;

    protected $tagA;
    protected $tagB;

    public function setUp(): void
    {
        parent::setUp();

        $this->tagA = Tag::create(['name' => 'tag a']);
        $this->tagB = Tag::create(['name' => 'tag b']);
    }

    /** @test */
    public function examples_page_contains_tags_component_livewire_component()
    {
        $this->get('/examples')->assertSeeLivewire('tags-component');
    }

    /** @test */
    public function loads_existing_tags_correctly()
    {
        Livewire::test(TagsComponent::class)
            ->assertSet('tags', json_encode([$this->tagA->name, $this->tagB->name]));
    }

    /** @test */
    public function adds_tags_correctly()
    {
        Livewire::test(TagsComponent::class)
                ->emit('tagAdded', 'tag c')
                ->assertEmitted('tagAddedFromBackend', 'tag c');

        $this->assertDatabaseHas('tags', [
            'name' => 'tag c',
        ]);
    }

    /** @test */
    public function removes_tags_correctly()
    {
        Livewire::test(TagsComponent::class)
                ->emit('tagRemoved', $this->tagB->name);

        $this->assertDatabaseMissing('tags', [
            'name' => $this->tagB->name,
        ]);
    }
}
