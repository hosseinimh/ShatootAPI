<?php

require_once(__DIR__ . '/../../config.php');

class ShatootApi
{
    private string $baseAddress;
    private string $username;
    private string $password;
    private ?string $bearerToken = null;

    public function __construct()
    {
        $this->baseAddress = SHATOOT_HOST;
        $this->username = SHATOOT_USERNAME;
        $this->password = SHATOOT_PASSWORD;

        if (!$this->login()) {
            throw new Exception('Shatoo API connect error.');
        }
    }

    public function getGoodsbyRemaindsInStocks(string $date)
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

    public function getGoodsbyRemaindsInStocksBySalePrice(string $date)
    {
        $url = $this->baseAddress . '/Products/GoodsbyRemaindsInStocksBySalePrice';
        $postFields = [
            'date' => $date,
            'idstocks' => '',
            'idGoods' => '',
            'SelectStockByshatootSoft' => false,
            'ShowRemaindOtherStock' => false,
            'Skip' => 0,
            'Take' => 9999999
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

    private function login(): mixed
    {
        try {
            $url = $this->baseAddress . '/shauth/login/' . $this->username . '/' . $this->password;
            $result = $this->getRequest($url);

            if ($result->access_Token) {
                $this->bearerToken = $result->access_Token;

                return true;
            }
        } catch (Exception) {
        }

        return false;
    }

    private function getRequest(string $url): mixed
    {
        try {
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

            $result = curl_exec($ch);

            curl_close($ch);

            return $this->parseJsonData($result);
        } catch (Exception) {
        }

        return null;
    }

    private function postRequest(string $url, array $postFields): mixed
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

            $result = curl_exec($ch);

            curl_close($ch);

            return $this->parseJsonData($result);
        } catch (Exception) {
        }

        return null;
    }

    private function parseJsonData(string|bool $data): mixed
    {
        try {
            if (is_bool($data)) {
                return null;
            }

            return json_decode($data)->data;
        } catch (Exception) {
        }

        return null;
    }
}
