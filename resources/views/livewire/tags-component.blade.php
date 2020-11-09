<div class="w-1/2 border px-4 py-2"
     wire:ignore
     x-data
     x-init="
    new Taggle($el, {
        tags: {{ $tags }},
        onTagAdd: function(e, tag) {
            Livewire.emit('tagAdded', tag)
        },
        onTagRemove: function(e, tag) {
            Livewire.emit('tagRemoved', tag)
        },
    })

    Livewire.on('tagAddedFromBackend', tag => {
        console.log('A tag was added with the tag name is: ' + tag);
    })

    Livewire.on('tagRemovedFromBackend', tag => {
        console.log('A tag was removed with the tag name is: ' + tag);
    })
"></div>
