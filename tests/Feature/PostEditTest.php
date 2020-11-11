<?php

namespace Tests\Feature;

use App\Http\Livewire\PostEdit;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Class PostEditTest
 *
 * @package Tests\Feature
 */
class PostEditTest extends TestCase
{
    use RefreshDatabase;

    public $post;

    public function setUp() : void
    {
        parent::setUp();
        $this->post = Post::create([
            'title' => 'My First Post',
            'content' => 'Post Content',
        ]);
    }
    /** @test */
    public function posts_edit_page_contains_post_edit_livewire_component()
    {
        $this->get(route('posts.edit', $this->post))
            ->assertSeeLivewire('post-edit');
    }

    /** @test */
    public function posts_edit_page_form_works()
    {
        Livewire::test(PostEdit::class, ['post' => $this->post])
            ->set('title', 'new Title')
            ->set('content', 'new Content')
            ->call('submitForm')
            ->assertSee('Post was updated successfully!');
    }

    /** @test */
    public function posts_edit_page_upload_works_for_images()
    {
        Storage::fake('public');

        $photo = UploadedFile::fake()->image('photo.jpg');

        Livewire::test(PostEdit::class, ['post' => $this->post])
                ->set('title', 'new Title')
                ->set('content', 'new Content')
                ->set('photo', $photo)
                ->call('submitForm')
                ->assertSee('Post was updated successfully!');

        $this->post->refresh();

        $this->assertNotNull($this->post->photo);
        Storage::disk('public')->assertExists($this->post->photo);
    }

    /** @test */
    public function posts_edit_page_upload_does_not_work_for_not_image()
    {
        Storage::fake('public');

        $pdf = UploadedFile::fake()->create('photo.pdf', 1000);

        Livewire::test(PostEdit::class, ['post' => $this->post])
                ->set('title', 'new Title')
                ->set('content', 'new Content')
                ->set('photo', $pdf)
                ->call('submitForm')
                ->assertHasErrors(['photo' => 'image']);

        $this->post->refresh();

        $this->assertNull($this->post->photo);
        Storage::disk('public')->assertMissing($this->post->photo);
    }

}
