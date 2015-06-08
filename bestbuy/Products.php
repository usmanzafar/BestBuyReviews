<?php
/**
 * Created by Usman.
 * User: Usman
 * Date: 11/3/14
 * Time: 3:18 PM
 */

namespace App\BestBuy;


class Products extends BestBuyAPI {
    protected $sku;
    protected $upc;
    protected $endpoint;

    public function __construct(){
        parent::__construct();
        $this->setURLEndPoint('products');
    }
    public function getSKU($data=null){
        if(!is_null($data))
        {
            $products = $data['products'];
            if (count($products)==1)
                return $products[0]['sku'];
            else
            {
                $skuColllection = array();
                foreach($products as $product){
                    $skuColllection[] = $product['sku'];
                }
                return $skuColllection;
            }
        }
        else
        {
            return $this->sku;
        }

    }
    public function setSKU($sku){
        $this->sku = $sku;
    }
    public function getUPC(){
        return $this->upc;
    }
    public function setUPC($upc){
        $this->upc = $upc;
    }

        public function getProductByUPC($upc=null){
            if(is_null($upc))
                $upc = $this->getUPC();

            $this->setEndPointParameters('upc',$upc);
            $this->setShowAttributes('name');
            $this->setShowAttributes('sku');
            $request = $this->send();

            return $request;
        }
    public function getSKUByUPC($upc){

        $data = $this->getProductByUPC($upc);

        $sku = $this->getSKU($data);
        return $sku;
    }
} 