<?php
/**
 * Created by PhpStorm.
 * User: LatteCake
 * Email: solacowa@gmail.com
 * Date: 2020/4/3
 * Time: 22:05
 * File: Etower.php
 */

namespace yiier\crossBorderExpress\platforms;


use Cassandra\Date;
use GuzzleHttp\Client;
use nusoap_client;
use yiier\crossBorderExpress\Config;
use yiier\crossBorderExpress\contracts\Order;
use yiier\crossBorderExpress\contracts\OrderFee;
use yiier\crossBorderExpress\contracts\OrderResult;
use yiier\crossBorderExpress\contracts\Transport;
use yiier\crossBorderExpress\exceptions\ExpressException;

class EtowerPlatform extends Platform
{

    /**
     * default host
     */
    const HOST = 'https://cn.etowertech.com';

    const SUCCESS = "SUCCESS";

    /**
     * @var string
     */
    private $host;

    /**
     * @inheritDoc
     */
    public function getClient()
    {
        $headers = [
            'Content-Type' => 'application/json; charset=utf8',
        ];

        $client = new \GuzzleHttp\Client([
            'headers' => $headers,
            'timeout' => method_exists($this, 'getTimeout') ? $this->getTimeout() : 5.0,
        ]);

        $this->host = $this->config->get('host') ?: self::HOST;

        return $client;
    }

    /**
     * @inheritDoc
     */
    public function getTransportsByCountryCode(string $countryCode)
    {
        // TODO: Implement getTransportsByCountryCode() method.
    }

    /**
     * create order
     *
     * @param Order $order
     * @return OrderResult
     * @throws ExpressException
     */
    public function createOrder(Order $order): OrderResult
    {
        $uri = "/services/shipper/orders";
        $a = `[{"trackingNo":"ABC123456789001","consignmentId":"1","referenceNo":"20160311001","addressLine1":"200 Bumborah Point Rd","addressLine2":"St. Peters","addressLine3":"","city":"PORT BOTANY","country":"AU","description":"shoes ","nativeDescription":"包含中文字符","email":"test@gmail.com","facility":"230031","instruction":"","invoiceCurrency":"AUD","invoiceValue":100,"phone":"","platform":"","postcode":"2036","recipientCompany":"","recipientName":"Bob","serviceCode":"","serviceOption":"","sku":"T1818ZS39*1","state":"NSW","weightUnit":"","weight":0.776,"dimensionUnit":"","length":0,"width":0,"height":0,"volume":0,"shipperName":"walltech hello","shipperAddressLine1":"pudongxinqu","shipperAddressLine2":"","shipperAddressLine3":"","shipperCity":"","shipperState":"","shipperPostcode":"","shipperCountry":"","shipperPhone":"","recipientTaxId":"","authorityToLeave":"","incoterm":"","lockerService":"","extendData":{"nationalNumber":"","nationalIssueDate":"11/11/2017","cyrillicName":"Bob","imei":"","isImei":true,"vendorid":"64652016681","gstexemptioncode":"","abnnumber":"","sortCode":"","coveramount":12},"orderItems":[{"itemNo":"00001","sku":"T1818ZS39*1-1","description":"PHONE","nativeDescription":"包含中文字符","hsCode":"TT11","originCountry":"CHINA","unitValue":50,"itemCount":1,"weight":0,"productURL":"www.ebay.com"},{"itemNo":"00002","sku":"T1818ZS39*1-2","description":"APPLE","nativeDescription":"包含中文字符","hsCode":"TT11","originCountry":"CHINA","unitValue":25,"itemCount":1,"weight":0,"productURL":"www.ebay.com"},{"itemNo":"00003","sku":"T1818ZS39*1-3","description":"IPAD","nativeDescription":"包含中文字符","hsCode":"TT11","originCountry":"CHINA","unitValue":5,"itemCount":1,"weight":0,"productURL":"www.ebay.com"},{"itemNo":"00005","sku":"T1818ZS39*1-5","description":"SHOES","nativeDescription":"包含中文字符","hsCode":"TT11","originCountry":"CHINA","unitValue":20,"itemCount":1,"weight":0,"productURL":"www.ebay.com"}]},{"trackingNo":"ABC123456789002","referenceNo":"20160311002","consignmentId":"1","addressLine1":"200 Bumborah Point Rd","addressLine2":"St. Peters","addressLine3":"","city":"PORT BOTANY","country":"AU","description":"SHOES","email":"test@gmail.com","facility":"230031","instruction":"","invoiceCurrency":"AUD","invoiceValue":100,"phone":"","platform":"","postcode":"2036","recipientCompany":"","recipientName":"Bob","serviceOption":"","sku":"T1818ZS39*1","state":"NSW","weightUnit":"","weight":0.776,"dimensionUnit":"","length":0,"width":0,"height":0,"volume":0,"shipperName":"walltech hello","shipperAddressLine1":"pudongxinqu","shipperAddressLine2":"","shipperAddressLine3":"","shipperCity":"","shipperState":"","shipperPostcode":"","shipperCountry":"","shipperPhone":"","recipientTaxId":"","authorityToLeave":"","incoterm":"","lockerService":""},{"referenceNo":"20160311003","addressLine1":"200 Bumborah Point Rd","addressLine2":"St. Peters","addressLine3":"","city":"PORT BOTANY","country":"AU","description":"shoes ","email":"test@gmail.com","facility":"230031","instruction":"","invoiceCurrency":"AUD","invoiceValue":100,"phone":"","platform":"","postcode":"2036","recipientCompany":"","recipientName":"Bob","serviceOption":"","sku":"T1818ZS39*1","state":"NSW","weightUnit":"","weight":0.776,"dimensionUnit":"","length":0,"width":0,"height":0,"volume":0,"shipperName":"walltech hello","shipperAddressLine1":"pudongxinqu","shipperAddressLine2":"","shipperAddressLine3":"","shipperCity":"","shipperState":"","shipperPostcode":"","shipperCountry":"","shipperPhone":"","recipientTaxId":"","authorityToLeave":"","incoterm":"","lockerService":""},{"referenceNo":"20160429004","addressLine1":"200 Bumborah Point Rd","addressLine2":"St. Peters","addressLine3":"","city":"PORT BOTANY","country":"AU","description":"shoes ","email":"test@gmail.com","facility":"230031","instruction":"","invoiceCurrency":"AUD","invoiceValue":100,"phone":"","platform":"","postcode":"2036","recipientCompany":"","recipientName":"Bob","serviceOption":"","sku":"T1818ZS39*1","state":"NSW","weightUnit":"","weight":0.776,"dimensionUnit":"","length":0,"width":0,"height":0,"volume":0,"shipperName":"walltech hello","shipperAddressLine1":"pudongxinqu","shipperAddressLine2":"","shipperAddressLine3":"","shipperCity":"","shipperState":"","shipperPostcode":"","shipperCountry":"","shipperPhone":"","recipientTaxId":"","authorityToLeave":"","incoterm":"","lockerService":""},{"referenceNo":"20160429005","addressLine1":"200 Bumborah Point Rd","addressLine2":"St. Peters","addressLine3":"","city":"PORT BOTANY","country":"AU","description":"shoes ","email":"test@gmail.com","facility":"230031","instruction":"","invoiceCurrency":"AUD","invoiceValue":100,"phone":"","platform":"","postcode":"2036","recipientCompany":"","recipientName":"Jim","serviceOption":"","sku":"T1818ZS39*1","state":"NSW","weightUnit":"","weight":0.776,"dimensionUnit":"","length":0,"width":0,"height":0,"volume":0,"shipperName":"walltech hello","shipperAddressLine1":"pudongxinqu","shipperAddressLine2":"","shipperAddressLine3":"","shipperCity":"","shipperState":"","shipperPostcode":"","shipperCountry":"","shipperPhone":"","returnName":"Wholesale Logisitics","returnAddressLine1":"Unit 17, MIAC Building","returnAddressLine2":"1 international Drive","returnAddressLine3":"","returnCity":"TULLAMARINE","returnState":"VIC","returnPostcode":"3043","returnCountry":"AU","returnOption":"","recipientTaxId":"","authorityToLeave":"","incoterm":"","lockerService":""}]`;

        $waybill[] = $this->formatOrder($order);
        echo json_encode($waybill, true);
        $body = ['body' => $a, "headers" => $this->buildClientHeader("POST", $uri)];
        var_dump($body);
        $response = $this->client->post($this->host . $uri, $body);
        $result = $this->parseResult($response->getBody());
        $orderResult = new OrderResult();
        if (!empty($result) && strtoupper($result[0]["status"]) == self::SUCCESS) {
            $orderResult->expressAgentNumber = $result[0]['referenceNo'];
            $orderResult->expressNumber = $result[0]['orderId'];
            $orderResult->expressTrackingNumber = $result[0]['trackingNo'];
        } else {
            throw new ExpressException('订单提交返回失败', (array)$result);
        }
        $orderResult->data = json_encode($result, JSON_UNESCAPED_UNICODE);

        return $orderResult;
    }

    /**
     * @inheritDoc
     */
    public function getPrintUrl(string $orderNumber): string
    {
        // TODO: Implement getPrintUrl() method.
    }

    /**
     * @inheritDoc
     */
    public function getOrderFee(string $orderNumber): OrderFee
    {
        // TODO: Implement getOrderFee() method.
    }

    /**
     * @inheritDoc
     */
    public function getOrderAllFee(array $query = []): array
    {
        // TODO: Implement getOrderAllFee() method.
    }


    private function buildClientHeader(string $method, $path): array
    {

        $t = new \DateTime('now');
        $t = $t->setTimezone(new \DateTimeZone("GMT+8"));

        $wallTechDate = $t->format(\DateTime::RFC1123);
        $auth = $method . "\0x000A" . $wallTechDate . "\0x000A" . $this->config->get("host") . $path;
        $hash = base64_encode(hash_hmac('sha1', $auth, $this->config->get("key"), true));
        return [
            'Content-Type' => 'application/json; charset=utf8',
            "X-WallTech-Date" => $wallTechDate,
            'Authorization' => sprintf("WallTech %s:%s",
                $this->config->get("token"),
                $hash
            )
        ];
    }


    /**
     * Parse result
     *
     * @param $result
     * @return array
     * @throws ExpressException
     */
    protected function parseResult($result)
    {
        $arr = json_decode($result, true);
        if (empty($arr) || !isset($arr['status'])) {
            throw new \Exception('Invalid response: ' . $result, 400);
        }
        if (strtoupper($arr["status"]) != self::SUCCESS) {
            $message = $arr['errors'];
            if (!empty($arr['data'][0]['errors'])) {
                $message = $arr['data'][0]['errors'];
            }
            throw new ExpressException($message, $arr['errors']);
        }

        return $arr['data'];
    }

    protected function formatOrder(Order $orderClass)
    {
        foreach ($orderClass->goods as $key => $value) {
            $goods[$key] = [
                "itemNo" => "00001",
                "sku" => $value->sku,
                "description" => $value->description,
                "nativeDescription" => $value->cnDescription,
                "hsCode" => $value->hsCode,
                "originCountry" => "", // CHINA
                "unitValue" => $value->worth,
                "itemCount" => $value->quantity,
                "weight" => $value->weight,
                "productURL" => ""
            ];
        }

        return [
            'trackingNo' => $orderClass->transportCode,
            'consignmentId' => '',
            'referenceNo' => $orderClass->customerOrderNo,
            'addressLine1' => $orderClass->recipient->address,
            'addressLine2' => '',
            'addressLine3' => '',
            'city' => $orderClass->recipient->city,
            'country' => $orderClass->recipient->countryCode,
            'description' => $orderClass->package->description,
            'nativeDescription' => $orderClass->package->description,
            'email' => $orderClass->recipient->email,
            'facility' => "",
            'instruction' => "",
            'invoiceCurrency' => '',
            'batteryType' => $orderClass->withBattery,
            'invoiceValue' => 100,
            'phone' => $orderClass->recipient->phone,
            'platform' => "",
            'postcode' => $orderClass->recipient->zip,
            'recipientCompany' => $orderClass->recipient->company,
            'recipientName' => $orderClass->recipient->name,
            'serviceCode' => '',
            'serviceOption' => '',
            "sku" => "",
            "state" => "",
            "weightUnit" => "KG",
            "weight" => $orderClass->package->weight,
            "dimensionUnit" => "",
            "length" => $orderClass->package->length,
            "width" => $orderClass->package->width,
            "height" => $orderClass->package->height,
            "volume" => "",
            "shipperName" => $orderClass->shipper ? $orderClass->shipper->name : "",
            "shipperAddressLine1" => $orderClass->shipper ? $orderClass->shipper->address : "",
            "shipperAddressLine2" => "",
            "shipperAddressLine3" => "",
            "shipperCity" => $orderClass->shipper ? $orderClass->shipper->city : "",
            "shipperState" => $orderClass->shipper ? $orderClass->shipper->state : "",
            "shipperPostcode" => $orderClass->shipper ? $orderClass->shipper->zip : "",
            "shipperCountry" => $orderClass->shipper ? $orderClass->shipper->countryCode : "",
            "shipperPhone" => $orderClass->shipper ? $orderClass->shipper->phone : "",
            "recipientTaxId" => $orderClass->taxesNumber,
            "authorityToLeave" => "",
            "incoterm" => "",
            "lockerService" => "",
            "extendData" => [
                "nationalNumber" => "",
                "nationalIssueDate" => "11/11/2017",
                "cyrillicName" => "Bob",
                "imei" => "",
                "isImei" => true,
                "vendorid" => "64652016681",
                "gstexemptioncode" => "",
                "abnnumber" => "",
                "sortCode" => "",
                "coveramount" => 12
            ],
            "orderItems" => $goods
        ];
    }
}
