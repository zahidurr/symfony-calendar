<?php

declare(strict_types=1);

namespace App\Tests\PHPUnit\Factory;

use App\Dto\Cart\CartInput;
use App\Dto\Cart\CartItemInput;
use App\Entity\Cart;
use App\Factory\Cart\CartFactory;
use App\Repository\CartItemRepository;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Security;

/**
 * @coversDefaultClass \App\Factory\Cart\CartFactory
 */
class CartFactoryTest extends TestCase
{
    /**
     * @var CartFactory
     */
    private CartFactory $cartFactory;


    protected function setUp(): void
    {
        $this->markTestSkipped('must be revisited.');return;
        $cartItemRepository = $this->getMockBuilder(CartItemRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['findOneBy'])
            ->getMock();

        $security = $this->getMockBuilder(Security::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getUser'])
            ->getMock();

        $this->cartFactory = new CartFactory($security);
    }

    /**
     * @covers ::__construct
     * @covers ::createFromDto
     */
    public function testCreateFromDto(): void
    {
        $cartInput = $this->createCartInput();

        $result = $this->cartFactory->createFromDto($cartInput);

        $this->checkCartAssert($result);
    }

    /**
     * @covers ::__construct
     * @covers ::updateFromDto
     */
    public function testUpdateFromDto(): void
    {
        $cart = new Cart();

        $cartInput = $this->createCartInput();

        $result = $this->cartFactory->updateFromDto($cartInput, $cart);

        $this->checkCartAssert($result);
    }

    private function createCartInput(): CartInput
    {
        $cartItemInput = new CartItemInput();
        $cartItemInput->productId = Uuid::uuid4();
        $cartItemInput->agreementId = Uuid::uuid4();
        $cartItemInput->agreementDataId = Uuid::uuid4();

        $cartInput = new CartInput();
        $item = array(
            $cartItemInput
        );
        $cartInput->cartItems = $item;

        return $cartInput;
    }

    private function checkCartAssert($result): void
    {
        $this->assertSame(Cart::STATUS_OPEN, $result->getStatus());
        $this->assertSame('brl', $result->getCurrency());
        $this->assertSame(Cart::CHANNEL_ONLINE, $result->getChannel());
        $this->assertSame(45000, $result->getTotalAmountWithVatForPayment());
    }
}
