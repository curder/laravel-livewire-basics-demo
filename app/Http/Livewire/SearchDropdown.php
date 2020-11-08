<?php
namespace App\Http\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

/**
 * Class SearchDropdown
 *
 * @package App\Http\Livewire
 */
class SearchDropdown extends Component
{
    public $search;
    public $searchResults = [];
    public function updated($newValue)
    {
        if(strlen($this->search) < 3) {
            $this->searchResults = [];
            return;
        }

        $response = Http::get('https://itunes.apple.com/search/?term='.$this->search.'&limit=10');
        $this->searchResults = $response->json()[ 'results' ];
    }
    public function render()
    {
        return view('livewire.search-dropdown');
    }
}
