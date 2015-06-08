<?php
namespace App\BestBuy;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
class BestBuyAPI {
    protected $apiKey;
    protected $response;
    protected $data;
    protected $url;
    protected $endpointParameters = array();
    protected $attributeParameters = array();


    public function __construct(){
        $this->apiKey       = $_ENV['BESTBUY.API_KEY'];
        $this->response     = $_ENV['BESTBUY.FORMAT'];
        $this->prepareURL();
    }
    public function getURL(){
            return $this->url;
    }
    public function setURL($url){
        $this->url = $url;
    }
    public function prepareURL(){
        $this->url          = $_ENV['BESTBUY.BASE_DOMAIN'].'/'.$_ENV['BESTBUY.VERSION'].'/';
    }
    public function setURLEndPoint($endpoint){

        $this->url .=$endpoint;
    }

    public function setEndPointParameters($key,$value){
        $this->endpointParameters[$key] = $value;

    }
    public function getEndPointParameters(){
        $params = $this->endpointParameters;
        $paramData = '';
        $counter = 1;

        foreach($params as $key => $value){
            if($counter==1)
                $paramData = $key.'='.$value;
            else
                {
                    $paramData  .='&'.$key.'='.$value;
                }
            $counter++;
        }
        return $paramData;
    }

    public function setShowAttributes($attribute){
        $this->attributeParameters[]= $attribute;
    }
    public function getShowAttributes(){
        $params = $this->attributeParameters;
        $paramData = '';
        $counter = 1;

        if(count($params)>0){
            foreach($params as $attribute){
                if($counter==1)
                    $paramData = $attribute;
                else
                {
                    $paramData  .=','.$attribute;
                }
                $counter++;
            }
            return $paramData;
        }
        else
            return false;

    }

    public function prepareRequest(){
        $url = $this->getURL();
        $url .= '('.$this->getEndPointParameters().')';
        $url .= '?format='.$this->response;
        $url .= '&apiKey='.$this->apiKey;

        if(!empty($this->attributeParameters))
            $url .= '&show='.$this->getShowAttributes();
        return $url;
    }
    protected function send(){
        $url =  $this->prepareRequest();
        //echo $url.'<br>';

        $client = new Client();



        try {
                $response = $client->get($url);
                $code = $response->getStatusCode();


                if($code != 200)
                    \Log::error('BestBuy Error:'.$code.$response->getReasonPhrase());
                else
                {
                    $data = $response->json();
                    $this->data = $data;

                    return $data;
                }
        }
        catch (RequestException $e) {
                echo $e->getRequest();
            if ($e->hasResponse()) {
                echo $e->getResponse();
                \Log::error('BestBuy Error:'.$e->getResponse());
            }
        }



    }

}