<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Livewire\Component;

/**
 * Class PollingExample
 *
 * @package App\Http\Livewire
 */
class PollingExample extends Component
{
    public $revenue;

    public function mount() : void
    {
        $this->revenue = $this->getRevenue();
    }

    public function getRevenue() : int
    {
        $this->revenue = Order::sum('price');

        return $this->revenue;
    }

    public function render()
    {
        return view('livewire.polling-example');
    }
}
