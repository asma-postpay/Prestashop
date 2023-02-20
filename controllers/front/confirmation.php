<?php

class postpayConfirmationModuleFrontController extends ModuleFrontController {

    public $checkStatusURL = 'https://maktapp.credit/v3/CheckStatus';

    public function postProcess() {
        die('works');
        exit;
        try {

            $cartId = $_GET['orderId'];
            $faildDescription=$_GET['Failerdescription'];
            $orderId     = Order::getOrderByCartId($cartId);
            $orderObject = new Order((int) $orderId);
            $orderRef    = $orderObject->reference;
            $json      = $this->checkPaymentStatus($orderId);
            $address   = new Address((int) ($orderObject->id_address_delivery));
            $customer  = new Customer((int) ($address->id_customer));
            $secureKey = $customer->secure_key;

            if ($cartId && $json === 1 && empty($faildDescription)) {
                $moduleId          = $this->module->id;
                $history           = new OrderHistory();
                $history->id_order = (int) $orderObject->id;
                $history->changeIdOrderState(Configuration::get('PS_OS_PAYMENT'), (int) ($orderObject->id)); //order status=4
                $history->save();
                $this->context->cart->delete();
                Tools::redirect('index.php?controller=order-confirmation&id_cart=' . $cartId . '&id_module=' . $moduleId . '&id_order=' . $orderId . '&key=' . $secureKey);
            }
            elseif (!empty($faildDescription))
            {
                Tools::redirect('index.php?controller=history');
            }
            else {
                $message = 'Oops, you are accessing worng order ...';
            }
        } catch (Exception $ex) {
            $history           = new OrderHistory();
            $history->id_order = (int) $orderObject->id;
            $history->changeIdOrderState(Configuration::get('PS_OS_ERROR'), (int) ($orderObject->id)); //order status=4
            $history->save();
            $message           = $ex->getMessage() . Tools::getValue('data_message');
        }

        if (version_compare(_PS_VERSION_, '1.7.0', '>')) {
            $this->errors[] = $message;
            $this->redirectWithNotifications('index.php?controller=order');
        } else {
            array_push($this->errors, $this->module->l($message));
            $this->context->smarty->assign('errors', array($message));
            return $this->setTemplate('error.tpl');
        }
    }

    public function checkPaymentStatus($orderId)
    {
        $apiKey     = Configuration::get('fatora_token');
        $data = array(
            'token'           => $apiKey,
            'orderId'         => $orderId,
        );

        $response = $this->curl_post( $this->checkStatusURL , $data );
        $data_json_decode = json_decode($response);
        $result = $data_json_decode->{'result'};
        return $result;
    }

    public function curl_post($url, array $post = NULL, array $options = array())
    {
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
        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        if( ! $result = curl_exec($ch))
        {
            trigger_error(curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }


}
