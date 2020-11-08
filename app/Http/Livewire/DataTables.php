<?php
namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Class DataTables
 *
 * @package App\Http\Livewire
 */
class DataTables extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.data-tables', [
            'users' => User::paginate(10),
        ]);
    }

    public function paginationView()
    {
        return 'livewire.custom-pagination-links-view';
    }
}
