<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Order;
use Livewire\Livewire;
use App\Http\Livewire\PollingExample;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PollingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function examples_contains_polling_example_livewire_component() : void
    {
        $this->get('examples')->assertSeeLivewire('polling-example');
    }

    /** @test */
    public function poll_sums_orders_correctly() : void
    {
        Order::create([ 'price' => 20]);
        Order::create([ 'price' => 20]);

        Livewire::test(PollingExample::class)
                ->call('getRevenue')
                ->assertSet('revenue', 40)
                ->assertSee('$40');

        Order::create([ 'price' => 20]);

        Livewire::test(PollingExample::class)
                ->call('getRevenue')
                ->assertSet('revenue', 60)
                ->assertSee('$60');
    }
}
