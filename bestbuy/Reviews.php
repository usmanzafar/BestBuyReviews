<?php
/**
 * Created by Usman Zafar.
 * User: Usman
 * Date: 11/4/14
 * Time: 11:25 AM
 */

namespace App\BestBuy;


class Reviews extends BestBuyAPI{
    public function __construct(){
        parent::__construct();
        $this->setURLEndPoint('reviews');
    }
    public function getReviewsBySKU($sku=null){

        if (    (is_null($sku) or empty($sku))  )
            return false;

        $this->setEndPointParameters('sku',$sku);

        $request = $this->send();

        if(isset($request['reviews']))
            return  $request['reviews'];
        else
            return false;

    }
} 