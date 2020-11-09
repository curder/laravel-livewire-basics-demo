<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Class PostEdit
 *
 * @package App\Http\Livewire
 */
class PostEdit extends Component
{
    use WithFileUploads;

    public $title;
    public $content;
    public $photo;

    public $post;
    public $successMessage = '';
    public $tempUrl;

    public $rules = [
        'title' => 'required',
        'content' => 'required',
        'photo' => 'sometimes|image|max:5000',
    ];

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->title = $post->title;
        $this->content = $post->content;
    }

    public function updatedPhoto()
    {
        try {
            $this->tempUrl = $this->photo->temporaryUrl();
        } catch (\Exception $e) {
            $this->tempUrl = ''; // placeholder image
        }

        $this->validate();
    }

    public function submitForm() : void
    {
        $this->validate();

        $imageToShow = $this->post->photo ?? null;

        $this->post->update([
            'title' => $this->title,
            'content' => $this->content,
            'photo' => $this->photo ? $this->photo->store('photos', 'public') : $imageToShow,
        ]);

        $this->successMessage = 'Post was updated successfully!';
    }

    public function render()
    {
        return view('livewire.post-edit');
    }
}
