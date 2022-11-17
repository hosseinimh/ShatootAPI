<?php

require_once(__DIR__ . '/../../../config.php');
require_once(__DIR__ . '/../Helper.php');

class ShatootApi
{
    private $baseAddress;
    private $username;
    private $password;
    private $bearerToken = null;

    public function __construct()
    {
        $this->baseAddress = SHATOOT_HOST;
        $this->username = SHATOOT_USERNAME;
        $this->password = SHATOOT_PASSWORD;

        if (isset($_SESSION['bearer-token'])) {
            $this->bearerToken = $_SESSION['bearer-token'];
        } else if (!$this->login()) {
            throw new Exception('Shatoo API connect error.');
        }
    }

    public function getGoodsbyRemaindsInStocks($date)
    {
        $url = $this->baseAddress . '/Products/GoodsbyRemaindsInStocks';
        $postFields = [
            'date' => $date,
            'idstocks' => '',
            'idGoods' => '',
            'SelectStockByshatootSoft' => true,
            'ShowRemaindOtherStock' => true,
            'Skip' => 0,
            'Take' => 9999999
        ];

        return $this->postRequest($url, $postFields);
    }

    public function getGoodsbyRemaindsInStocksBySalePrice($date, $start = 0, $count = 9999999)
    {
        $url = $this->baseAddress . '/Products/GoodsbyRemaindsInStocksBySalePrice';
        $postFields = [
            'date' => $date,
            'idstocks' => '',
            'idGoods' => '',
            'SelectStockByshatootSoft' => false,
            'ShowRemaindOtherStock' => false,
            'Skip' => $start,
            'Take' => $count
        ];

        return $this->postRequest($url, $postFields);
    }

    public function getProducts()
    {
        $url = $this->baseAddress . '/Products';
        $postFields = [
            'JustWithRemaind' => false,
            'Skip' => 0,
            'Take' => 9999999
        ];

        return $this->postRequest($url, $postFields);
    }

    private function login()
    {
        try {
            $url = $this->baseAddress . '/shauth/login/' . $this->username . '/' . $this->password;
            $result = $this->getRequest($url);

            if ($result->access_Token) {
                $this->bearerToken = $result->access_Token;
                $_SESSION["bearer-token"] = $result->access_Token;

                return true;
            }
        } catch (Exception $e) {
            Helper::print($e->getMessage());
        }

        return false;
    }

    private function getRequest($url)
    {
        try {
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);

            $result = curl_exec($ch);
            $curl_errno = curl_errno($ch);

            curl_close($ch);

            if ($curl_errno > 0) {
                return null;
            }

            return $this->parseJsonData($result);
        } catch (Exception $e) {
            Helper::print($e->getMessage());
        }

        return null;
    }

    private function postRequest($url, $postFields)
    {
        try {
            if (is_null($this->bearerToken)) {
                throw new Exception('Brearer token is not set.');
            }

            $ch = curl_init($url);
            $postFields = json_encode($postFields);
            $authorization = "Authorization: Bearer " . $this->bearerToken;

            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', $authorization]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);

            $result = curl_exec($ch);
            $curl_errno = curl_errno($ch);

            curl_close($ch);

            if ($curl_errno > 0) {
                return null;
            }

            return $this->parseJsonData($result);
        } catch (Exception $e) {
            Helper::print($e->getMessage());
        }

        return null;
    }

    private function parseJsonData($data)
    {
        try {
            return json_decode($data)->data;
        } catch (Exception $e) {
            Helper::print($e->getMessage());
        }

        return null;
    }
}
