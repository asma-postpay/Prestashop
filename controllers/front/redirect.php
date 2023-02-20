<?php

use Postpay\Postpay;

class postpayRedirectModuleFrontController extends ModuleFrontController {


    public $url = 'https://sandbox.postpay.io/checkouts';

    public function postProcess() {
        $theCart                        = $this->context->cart;
        $cartId                         = $theCart->id;
        Context::getContext()->currency = new Currency((int) Context::getContext()->cart->id_currency);
        Context::getContext()->language = new Language((int) Context::getContext()->customer->id_lang);
        $secureKey                      = Context::getContext()->customer->secure_key;
        $paymentStatus                  = Configuration::get('PS_OS_PREPARATION');
        $moduleName = $this->module->displayName;
        $currencyId = (int) Context::getContext()->currency->id;
        $amount     = (float) $theCart->getOrderTotal(true, Cart::BOTH);


        $this->module->validateOrder($cartId, $paymentStatus, $amount, $moduleName, '', array(), $currencyId, false, $secureKey);

        $orderId     = Order::getOrderByCartId($cartId);
        $orderObject = new Order((int) $orderId);
        $payLoadData = $this->getPayLoadData($orderObject, $cartId  ,$orderId );
        $this->restoreCart($cartId);
        try {
            $url      = $this->generatePaymentURL($payLoadData);

            Tools::redirect($url);
        } catch (Exception $ex) {
            
            if (version_compare(_PS_VERSION_, '1.7.0', '>')) {
                $this->errors[] = $ex->getMessage();
                $this->redirectWithNotifications('index.php?controller=order');
            } else {
                $message = $ex->getMessage();
                array_push($this->errors, $this->module->l($message));
                $this->context->smarty->assign('errors', array($message));
                return $this->setTemplate('error.tpl');
            }
        }
    }

    protected function getPayLoadData($orderObject, $cartId , $orderId) {
        /*$address   = new Address((int) ($orderObject->id_address_delivery));
        $customer  = new Customer((int) ($address->id_customer));
        $phone     = $address->phone_mobile;
        $secureKey = $customer->secure_key;
        $fName     = $customer->firstname;
        $lname     = $customer->lastname;
        $email     = $customer->email;
        //$orderRef  = $orderObject->reference;
        if (empty($phone)) {
            $phone = $address->phone;
        }

        $customerAddress = array(
            "Address" => $address->address1 . ' , ' . $address->address2,
        );

        $amount          = $orderObject->total_paid;
        $returnURL       = $this->context->link->getModuleLink('postpay',
                'confirmation'
        );
         $apiKey     = Configuration::get('fatora_token');

        $curlData = [
            'token'=> $apiKey,
            'orderId'=> $orderId,
            'CustomerName'       => "$fName $lname",
            'customerPhone'     => $phone,
            'CustomerEmail'      => $email,
            'customerCountry'    => $customerAddress,
            'currencyCode' => Context::getContext()->currency->iso_code,
            'amount'       => $amount,
            'SuccessUrl'        => $returnURL,
            'FailureURL'           => $returnURL,
            'lang'           => Context::getContext()->language->iso_code, 

        ];*/
        $returnURL       = $this->context->link->getModuleLink('postpay',
            'confirmation'
        );
        return [
            'order_id' => $orderId,
            'total_amount' =>5,
            'tax_amount' => 2,
            'currency' =>'AED',
            "shipping" => [
                "id" => "shipping-01",
                "name" => "Express Delivery",
                "amount" => 2000,
                "address" => [
                    "first_name" => "John",
                    "last_name" => "Doe",
                    "phone" => "+971 50 000 0000",
                    "alt_phone" => "800 239",
                    "line1" => "The Gate District, DIFC",
                    "line2" => "Level 4, Precinct Building 5",
                    "city" => "Dubai",
                    "state" => "Dubai",
                    "country" => "AE",
                    "postal_code" => "00000"
                ]],
            "billing_address" => [
                "first_name" => "John",
                "last_name" => "Doe",
                "phone" => "+971 50 000 0000",
                "alt_phone" => "800 239",
                "line1" => "The Gate District, DIFC",
                "line2" => "Level 4, Precinct Building 5",
                "city" => "Dubai",
                "state" => "Dubai",
                "country" => "AE",
                "postal_code" => "00000"],
            "customer" => [
                "id" => "customer-01",
                "email" => "john@postpay.io",
                "first_name" => "John",
                "last_name" => "Doe",
                "gender" => "male",
                "account" => "guest",
                "date_of_birth" => "1990-01-20",
                "date_joined" => "2019-08-26T09:28:14.790Z"
            ],

            'items' => array([
                "reference"=> "sku-01",
                "name" => "Wild Flower Eau de Parfum (50ml)",
                "description" => "It’s a windswept bouquet with a spring awakening.",
                "unit_price" => 84000,
                "qty" => 1
            ]),
            'merchant' => [
                'confirmation_url' => $returnURL,
                'cancel_url' =>$returnURL
            ],
            //'metadata' => Metadata::build($method),
            'num_instalments' => 1
        ];
    }

    public function restoreCart($cartId) {
        $oldCart                        = new Cart($cartId);
        $duplication                    = $oldCart->duplicate();
        $this->context->cookie->id_cart = $duplication['cart']->id;
        $context                        = $this->context;
        $context->cart                  = $duplication['cart'];
        CartRule::autoAddToCart($context);
        $this->context->cookie->write();
    }

    public function curl_post($url, array $post = NULL, array $options = array())
    {
        $auth = ['id_14aebbb6901e4758a87891e3ddd041fa', 'sk_test_9dbc57f82ca4932d5c37e7f75c18f2ef755f5bbf'];
        $defaults = array(
            CURLOPT_POST           => 1,
            CURLOPT_HEADER         => 0,
            CURLOPT_URL            => $url,
            CURLOPT_FRESH_CONNECT  => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE   => 1,
            CURLOPT_TIMEOUT        => 500,
            CURLOPT_POSTFIELDS     => http_build_query($post)
        );

        $headr = array();
        $headr[] = 'Content-type: application/json';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_USERPWD, implode(':', $auth));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $bodyStr = '';
        if (201 == $httpCode) {
            $headerSize = curl_getinfo( $ch , CURLINFO_HEADER_SIZE );
            $headerStr = substr( $result , 0 , $headerSize );
            $bodyStr = substr( $result , $headerSize );
        }
        curl_close($ch);
        return $bodyStr ;
    }

    public function generatePaymentURL($payLoadData){

        $postpay = new Postpay([
            'merchant_id' => 'id_14aebbb6901e4758a87891e3ddd041fa',
            'secret_key' => 'sk_test_9dbc57f82ca4932d5c37e7f75c18f2ef755f5bbf',
        ]);

        try {
            $response = $postpay->post('/checkouts', $payLoadData);
        } catch (RESTfulException $e) {
            echo $e->getErrorCode();
            exit;
        }

        return $response->json()['redirect_url'];
        /*$response = $this->curl_post( $this->url , $payLoadData );
        $result = json_decode($response,true);
        $paymentUrl = $result['redirect_url'];
        return $paymentUrl;*/

    }

}
