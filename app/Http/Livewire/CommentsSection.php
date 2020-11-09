<?php

namespace App\Http\Livewire;

use App\Models\Post;
use App\Models\Comment;
use Livewire\Component;

/**
 * Class CommentsSection
 *
 * @package App\Http\Livewire
 */
class CommentsSection extends Component
{
    public $post;
    public $comment = '';
    protected $rules = [
        'comment' => 'required|min:4',
        'post' => 'required'
    ];
    public $successMessage = '';

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function postComment() : void
    {
        sleep(1);

        $this->validate();

        Comment::create([
            'post_id' => $this->post->id,
            'username' => 'Guest',
            'content' => $this->comment,
        ]);

        $this->comment = '';
        $this->post = Post::find($this->post->id);

        $this->successMessage = 'Comment was posted!';
    }

    public function render()
    {
        return view('livewire.comments-section');
    }
}
