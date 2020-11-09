<?php

namespace App\Http\Livewire;

use App\Models\Tag;
use Livewire\Component;

/**
 * Class TagsComponent
 *
 * @package App\Http\Livewire
 */
class TagsComponent extends Component
{
    public $tags;

    protected $listeners = [
        'tagAdded',
        'tagRemoved',
    ];

    public function mount()
    {
        $this->tags = json_encode(Tag::pluck('name'));
    }

    public function tagAdded($tag)
    {
        Tag::create(['name' => $tag]);

        $this->emit('tagAddedFromBackend', $tag);
    }

    public function tagRemoved($tag)
    {
        $tag = Tag::whereName($tag)->first();

        $tag->delete();

        $this->emit('tagRemovedFromBackend', $tag->name);
    }

    public function render()
    {
        return view('livewire.tags-component');
    }
}
