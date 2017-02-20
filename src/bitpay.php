<?php
namespace codesign\bitpay;

use GuzzleHttp\Client;
//use GuzzleHttp\Exception\GuzzleException;

class bitpay {
    protected $testServer =  "http://bitpay.ir/payment-test/gateway-send";
    protected $normalServer =   "https://bitpay.ir/payment/gateway-send";

    protected $server;

    protected $testVerify =   "http://bitpay.ir/payment-test/gateway-result-second";
    protected $normalVerify =   "https://bitpay.ir/payment/gateway-result-second";

    protected $verifyUrl;

    protected $testPayUrl = "http://bitpay.ir/payment-test/gateway-";
    protected $normalPayUrl = "https://bitpay.ir/payment/gateway-";

    protected $payUrl;

    protected $client;

    protected $config = [];

    public function __construct()
    {
        $this->client   =   new Client(['defaults' => ['verify' => false]]);
    }

    public function boot()
    {
        $this->config[] = ['api'    => config('bitpay.api')];
        $this->setServer();
    }

    protected function check()
    {
        $response = $this->client->post($this->server, $this->config);
        return $response->getBody()->getContents();
    }

    public function redirect()
    {
        $response =$this->check();
        //return $response;
        if($response >= 1 && is_numeric($response)) {
            $this->payUrl = $this->payUrl . $response;
            return header("Location: ". $this->payUrl);
        }

        return response()->json($response);
    }

    public function setAmount($amount)
    {
        $this->config[] = ['amount' =>  $amount];
    }

    public function setCallbackUrl($url)
    {
        $this->config[]  =   ['redirect'    =>  urlencode($url)];
    }

    public function setName($name)
    {
        $this->config[] = ['name'   => $name];
    }

    public function setEmail($email)
    {
        $this->config[] = ['email'  =>  $email];
    }

    public function setDescription($description)
    {
        $this->config[] = ['description'    =>  $description];
    }

    public function setFactorId($factorId)
    {
        $this->config[] = ['factorId'   =>  $factorId];
    }

    protected function setServer() {
        $server = config('bitpay.gate');
        switch ($server) {
            case 'normal':
                $this->server   =   $this->normalServer;
                $this->verifyUrl  =   $this->normalVerify;
                $this->payUrl   =   $this->normalPayUrl;
                break;
            case 'test':
                $this->server   =   $this->testServer;
                $this->verifyUrl  =   $this->testVerify;
                $this->payUrl   =   $this->testPayUrl;
                break;
            case 'default':
                $this->server   =   $this->testServer;
                $this->verifyUrl  =   $this->testVerify;
                $this->payUrl   =   $this->testPayUrl;
                break;
        }
    }
}