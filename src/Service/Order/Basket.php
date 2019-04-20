<?php

declare(strict_types = 1);

namespace Service\Order;

use Framework\Registry;
use Model;
use Service\Billing\Card;
use Service\Billing\IBilling;
use Service\Communication\Email;
use Service\Communication\ICommunication;
use Service\Communication\NotificationSender;
use Service\Communication\Sms;
use Service\Discount\Discounter;
use Service\Discount\IDiscount;
use Service\Discount\NullObject;
use Service\Discount\PromoCode;
use Service\Discount\VipDiscount;
use Service\User\ISecurity;
use Service\User\Security;
use SplObserver;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Basket implements \SplSubject
{
    /**
     * Сессионный ключ списка всех продуктов корзины
     */
    private const BASKET_DATA_KEY = 'basket';

    private $user;
    /**
     * @var SessionInterface
     */
    private $session;

    private $observers = [];

    /**
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;

        foreach (Registry::getDataConfig('order.listeners') as $listener){
            $this->attach(new $listener);
        }
    }

    /**
     * Добавляем товар в заказ
     *
     * @param int $product
     *
     * @return void
     */
    public function addProduct(int $product): void
    {
        $basket = $this->session->get(static::BASKET_DATA_KEY, []);
        if (!in_array($product, $basket, true)) {
            $basket[] = $product;
            $this->session->set(static::BASKET_DATA_KEY, $basket);
        }
    }

    /**
     * Проверяем, лежит ли продукт в корзине или нет
     *
     * @param int $productId
     *
     * @return bool
     */
    public function isProductInBasket(int $productId): bool
    {
        return in_array($productId, $this->getProductIds(), true);
    }

    /**
     * Получаем информацию по всем продуктам в корзине
     *
     * @return Model\Entity\Product[]
     */
    public function getProductsInfo(): array
    {
        $productIds = $this->getProductIds();
        return $this->getProductRepository()->search($productIds);
    }

    public function getDiscount(string $send)
    {
        switch ($send) {
            case 'promo':
                $strategy = new Discounter(new PromoCode('promo'));
                break;

            case 'vip':
                $strategy = new Discounter(new VipDiscount($this->user));
                break;

            default:
                $strategy = new Discounter(new NullObject());
        }

        return $strategy;
    }

    /**
     * Оформление заказа
     *
     * @return void
     */
    public function checkout(BasketBuilder $basketBuilder): void
    {
        // Здесь должна быть некоторая логика выбора способа платежа
        $basketBuilder->setBilling(new Card());

        // Здесь должна быть некоторая логика получения информации о скидки пользователя
        $basketBuilder->setDiscount($this->getDiscount('promo'));

        // Здесь должна быть некоторая логика получения способа уведомления пользователя о покупке
        $basketBuilder->setCommunication(new Email());

        $basketBuilder->setSecurity(new Security($this->session));

        $this->checkoutProcess($basketBuilder);
    }

    /**
     * Проведение всех этапов заказа
     *
     * @param IDiscount $discount,
     * @param IBilling $billing,
     * @param ISecurity $security,
     * @param ICommunication $communication
     * @return void
     */
    public function checkoutProcess(
        BasketBuilder $basketBuilder
    ): void {
        $totalPrice = 0;
        foreach ($this->getProductsInfo() as $product) {
            $totalPrice += $product->getPrice();
        }

        $discount = $basketBuilder->getDiscount();
        $totalPrice = $totalPrice - $totalPrice / 100 * $discount;

        $billing = $basketBuilder->getBilling();

        $billing->pay($totalPrice);

        $security = $basketBuilder->getSecurity();

        $user = $security->getUser();

        $communication = $basketBuilder->getCommunication();
        $communication->process($user, 'checkout_template');
    }

    /**
     * Фабричный метод для репозитория Product
     *
     * @return Model\Repository\Product
     */
    protected function getProductRepository(): Model\Repository\Product
    {
        return new Model\Repository\Product();
    }

    /**
     * Получаем список id товаров корзины
     *
     * @return array
     */
    private function getProductIds(): array
    {
        return $this->session->get(static::BASKET_DATA_KEY, []);
    }

    public function attach(SplObserver $observer)
    {
        if(!array_key_exists(get_class($observer), $this->observers)){
            $this->observers[get_class($observer)] = $observer;
        }
    }

    public function detach(SplObserver $observer)
    {
        unset($this->observers[get_class($observer)]);
    }

    public function notify()
    {
        foreach ($this->observers as $observer){
            $observer->update();
        }
    }
}
