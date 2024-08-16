<?php

namespace Tests\Feature\Livewire\Partials;

use App\Livewire\Partials\Navbar;
use App\Ecommerce\Cart\CookieCart;
use Livewire\Livewire;
use Tests\TestCase;
use Mockery as m;

class NavbarTest extends TestCase
{
    public function testItRenderSuccessfully(): void
    {
        Livewire::test(Navbar::class)
            ->assertViewIs('livewire.partials.navbar')
            ->assertStatus(200);
    }

    public function testItCanUpdateCartCount(): void
    {
        Livewire::test(Navbar::class)
            ->assertSet('totalCount', 0)
            ->call('updateCartCount', 1)
            ->assertSet('totalCount', 1);
    }

    public function testItCanUpdateCartCountFromAnInitializedState(): void
    {
        // Set
        $cookieCart = $this->instance(CookieCart::class, m::mock(CookieCart::class));

        // Expectations
        $cookieCart->expects()
            ->getCartItems()
            ->andReturn([
                ['id' => 1, 'quantity' => 1],
                ['id' => 2, 'quantity' => 1],
            ]);

        // Actions
        Livewire::test(Navbar::class)
            ->assertSet('totalCount', 2)
            ->call('updateCartCount', 1)
            ->assertSet('totalCount', 1);
    }
}
