<?php

namespace Tests\Feature;

use App\Http\Livewire\CommentsSection;
use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Class CommentsSectionTest
 *
 * @package Tests\Feature
 */
class CommentsSectionTest extends TestCase
{
    use RefreshDatabase;

    protected $post;

    public function setUp(): void
    {
        parent::setUp();
        $this->post = Post::create([
            'title' => 'My First Post',
            'content' => 'Content here',
        ]);
    }

    /** @test */
    public function examples_page_contains_posts() : void
    {
        $this->get('/examples')
             ->assertSee($this->post->title);
    }

    /** @test */
    public function post_page_contains_comments_livewire_component() : void
    {
        $this->get(route('posts.show', $this->post))
            ->assertSeeLivewire('comments-section');
    }

    /** @test */
    public function valid_comment_can_be_posted() : void
    {
        Livewire::test(CommentsSection::class)
            ->set('post', $this->post)
            ->set('comment', 'This is my comment')
            ->call('postComment')
            ->assertSee('Comment was posted!')
            ->assertSee('This is my comment');
    }

    /** @test */
    public function comment_is_required() : void
    {
        Livewire::test(CommentsSection::class)
            ->set('post', $this->post)
            ->call('postComment')
            ->assertHasErrors('comment', 'required')
            ->assertSee('The comment field is required');
    }

    /** @test */
    public function comment_requires_min_characters() : void
    {
        Livewire::test(CommentsSection::class)
                ->set('post', $this->post)
                ->set('comment', '123')
                ->call('postComment')
                ->assertHasErrors('comment', 'min')
                ->assertSee('The comment must be at least 4 characters.');
    }
}
