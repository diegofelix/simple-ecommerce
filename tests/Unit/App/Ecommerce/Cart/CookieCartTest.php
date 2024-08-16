<?php

namespace Tests\Unit\App\Ecommerce\Cart;

use App\Models\Product;
use App\Ecommerce\Cart\Cookie;
use App\Ecommerce\Cart\CookieCart;
use Mockery as m;
use Tests\TestCase;

class CookieCartTest extends TestCase
{
    public function testShouldGetCartItemsFromCookie(): void
    {
        // Set
        $product = m::mock(Product::class);
        $cookie = m::mock(Cookie::class);
        $cart = new CookieCart($product, $cookie);
        $model = Product::factory()->make(['id' => 20]);
        $cartItems = [
            [
                'product_id' => 20,
                'name' => $model->name,
                'image' => $model->images[0],
                'quantity' => 2.0,
                'unit_price' => $model->price,
                'total_price' => $model->price
            ]
        ];

        // Expectations
        $cookie->expects()
            ->get()
            ->andReturn($cartItems);

        // Actions
        $result = $cart->getCartItems(20);

        // Assertions
        $this->assertSame($cartItems, $result);
    }

    public function testShouldNotAddInvalidProductId(): void
    {
        // Set
        $product = m::mock(Product::class);
        $cookie = m::mock(Cookie::class);
        $cart = new CookieCart($product, $cookie);

        // Expectations
        $cookie->expects()
            ->get()
            ->andReturn([]);

        $product->expects()
            ->query()
            ->andReturnSelf();

        $product->expects()
            ->find(20, ['id', 'name', 'price', 'images'])
            ->andReturnNull();

        // Actions
        $cartItems = $cart->addItemToCart(20);

        // Assertions
        $this->assertSame(0, $cartItems);
    }

    public function testShouldAddProduct(): void
    {
        // Set
        $product = m::mock(Product::class);
        $cookie = m::mock(Cookie::class);
        $cart = new CookieCart($product, $cookie);
        $model = Product::factory()->make(['id' => 20]);

        // Expectations
        $cookie->expects()
            ->get()
            ->andReturn([]);

        $product->expects()
            ->query()
            ->andReturnSelf();

        $product->expects()
            ->find(20, ['id', 'name', 'price', 'images'])
            ->andReturn($model);

        $cookie->expects()
            ->queue([
                [
                    'product_id' => 20,
                    'name' => $model->name,
                    'image' => $model->images[0],
                    'quantity' => 1,
                    'unit_price' => $model->price,
                    'total_price' => $model->price
                ]
            ]);

        // Actions
        $cartItemsCount = $cart->addItemToCart(20);

        // Assertions
        $this->assertSame(1, $cartItemsCount);
    }

    public function testShouldAddProductWithQuantity(): void
    {
        // Set
        $product = m::mock(Product::class);
        $cookie = m::mock(Cookie::class);
        $cart = new CookieCart($product, $cookie);
        $model = Product::factory()->make(['id' => 20]);

        // Expectations
        $cookie->expects()
            ->get()
            ->andReturn([]);

        $product->expects()
            ->query()
            ->andReturnSelf();

        $product->expects()
            ->find(20, ['id', 'name', 'price', 'images'])
            ->andReturn($model);

        $cookie->expects()
            ->queue([
                [
                    'product_id' => 20,
                    'name' => $model->name,
                    'image' => $model->images[0],
                    'quantity' => 10,
                    'unit_price' => $model->price,
                    'total_price' => $model->price
                ]
            ]);

        // Actions
        $cartItemsCount = $cart->addItemToCart(20, 10);

        // Assertions
        $this->assertSame(1, $cartItemsCount);
    }

    public function testShouldUpdateProductOnCookie(): void
    {
        // Set
        $product = m::mock(Product::class);
        $cookie = m::mock(Cookie::class);
        $cart = new CookieCart($product, $cookie);
        $model = Product::factory()->make(['id' => 20]);

        // Expectations
        $cookie->expects()
            ->get()
            ->twice()
            ->andReturn([
                [
                    'product_id' => 20,
                    'name' => $model->name,
                    'image' => $model->images[0],
                    'quantity' => 2.0,
                    'unit_price' => $model->price,
                    'total_price' => $model->price
                ]
            ]);

        $cookie->expects()
            ->queue([
                [
                    'product_id' => 20,
                    'name' => $model->name,
                    'image' => $model->images[0],
                    'quantity' => 3.0,
                    'unit_price' => $model->price,
                    'total_price' => $model->price * 3
                ]
            ]);

        // Actions
        $cartItemsCount = $cart->addItemToCart(20);

        // Assertions
        $this->assertSame(1, $cartItemsCount);
    }

    public function testShouldRemoveItemFromCart(): void
    {
        // Set
        $product = m::mock(Product::class);
        $cookie = m::mock(Cookie::class);
        $cart = new CookieCart($product, $cookie);
        $model = Product::factory()->make(['id' => 20]);

        // Expectations
        $cookie->expects()
            ->get()
            ->andReturn([
                [
                    'product_id' => 20,
                    'name' => $model->name,
                    'image' => $model->images[0],
                    'quantity' => 1,
                    'unit_price' => $model->price,
                    'total_price' => $model->price
                ]
            ]);

        $cookie->expects()
            ->queue([]);

        // Actions
        $cartItems = $cart->removeCartItem(20);

        // Assertions
        $this->assertCount(0, $cartItems);
    }

    public function testShouldNotRemoveInvalidItem(): void
    {
        // Set
        $product = m::mock(Product::class);
        $cookie = m::mock(Cookie::class);
        $cart = new CookieCart($product, $cookie);
        $model = Product::factory()->make(['id' => 20]);

        // Expectations
        $cookie->expects()
            ->get()
            ->andReturn([
                [
                    'product_id' => 20,
                    'name' => $model->name,
                    'image' => $model->images[0],
                    'quantity' => 1,
                    'unit_price' => $model->price,
                    'total_price' => $model->price
                ]
            ]);

        $cookie->expects()
            ->queue([
                [
                    'product_id' => 20,
                    'name' => $model->name,
                    'image' => $model->images[0],
                    'quantity' => 1,
                    'unit_price' => $model->price,
                    'total_price' => $model->price
                ]
            ]);

        // Actions
        $cartItems = $cart->removeCartItem(15);

        // Assertions
        $this->assertCount(1, $cartItems);
    }

    public function testShouldIncrementCartItem(): void
    {
        // Set
        $product = m::mock(Product::class);
        $cookie = m::mock(Cookie::class);
        $cart = new CookieCart($product, $cookie);
        $model = Product::factory()->make(['id' => 20]);

        // Expectations
        $cookie->expects()
            ->get()
            ->andReturn([
                [
                    'product_id' => 20,
                    'name' => $model->name,
                    'image' => $model->images[0],
                    'quantity' => 2.0,
                    'unit_price' => $model->price,
                    'total_price' => $model->price * 2
                ]
            ]);

        $cookie->expects()
            ->queue([
                [
                    'product_id' => 20,
                    'name' => $model->name,
                    'image' => $model->images[0],
                    'quantity' => 3.0,
                    'unit_price' => $model->price,
                    'total_price' => $model->price * 3
                ]
            ]);

        // Actions
        $cartItems = $cart->incrementQuantityToCartItem(20);

        // Assertions
        $this->assertSame(3.0, $cartItems[0]['quantity']);
        $this->assertSame($model->price * 3, $cartItems[0]['total_price']);
    }

    public function testShouldNotIncrementInvalidProduct(): void
    {
        // Set
        $product = m::mock(Product::class);
        $cookie = m::mock(Cookie::class);
        $cart = new CookieCart($product, $cookie);
        $model = Product::factory()->make(['id' => 20]);

        // Expectations
        $cookie->expects()
            ->get()
            ->andReturn([
                [
                    'product_id' => 20,
                    'name' => $model->name,
                    'image' => $model->images[0],
                    'quantity' => 2.0,
                    'unit_price' => $model->price,
                    'total_price' => $model->price * 2
                ]
            ]);

        $cookie->expects()
            ->queue([
                [
                    'product_id' => 20,
                    'name' => $model->name,
                    'image' => $model->images[0],
                    'quantity' => 2.0,
                    'unit_price' => $model->price,
                    'total_price' => $model->price * 2
                ]
            ]);

        // Actions
        $cartItems = $cart->incrementQuantityToCartItem(15);

        // Assertions
        $this->assertSame(2.0, $cartItems[0]['quantity']);
    }

    public function testShouldDecrementCartItemQuantity(): void
    {
        // Set
        $product = m::mock(Product::class);
        $cookie = m::mock(Cookie::class);
        $cart = new CookieCart($product, $cookie);
        $model = Product::factory()->make(['id' => 20]);

        // Expectations
        $cookie->expects()
            ->get()
            ->andReturn([
                [
                    'product_id' => 20,
                    'name' => $model->name,
                    'image' => $model->images[0],
                    'quantity' => 2.0,
                    'unit_price' => $model->price,
                    'total_price' => $model->price * 2
                ]
            ]);

        $cookie->expects()
            ->queue([
                [
                    'product_id' => 20,
                    'name' => $model->name,
                    'image' => $model->images[0],
                    'quantity' => 1.0,
                    'unit_price' => $model->price,
                    'total_price' => $model->price
                ]
            ]);

        // Actions
        $cartItems = $cart->decrementQuantityToCartItem(20);

        // Assertions
        $this->assertSame(1.0, $cartItems[0]['quantity']);
        $this->assertSame($model->price, $cartItems[0]['total_price']);
    }

    public function testShouldCalculateCartTotalPrice(): void
    {
        // Set
        $product = m::mock(Product::class);
        $cookie = m::mock(Cookie::class);
        $cart = new CookieCart($product, $cookie);
        $model = Product::factory()->make(['id' => 20]);
        $cartItems = [
            [
                'product_id' => 20,
                'name' => $model->name,
                'image' => $model->images[0],
                'quantity' => 1.0,
                'unit_price' => $model->price,
                'total_price' => $model->price
            ]
        ];

        // Actions
        $totalPrice = $cart->calculateTotalPrice($cartItems);

        // Assertions
        $this->assertEquals($model->price, $totalPrice);
    }

    public function testShouldClearCartItems(): void
    {
        // Set
        $product = m::mock(Product::class);
        $cookie = m::mock(Cookie::class);
        $cart = new CookieCart($product, $cookie);

        // Expectations
        $cookie->expects()->forget();

        // Actions
        $cart->clearCartItems();
    }

}
