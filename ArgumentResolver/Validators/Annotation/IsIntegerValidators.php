<?php


namespace jarming\RequestParamsBundle\ArgumentResolver\Validators\Annotation;

use jarming\RequestParamsBundle\Annotation\RequestParam;
use jarming\RequestParamsBundle\ArgumentResolver\Validators\AbstractValidators;

/**
 * Class IsInteger
 * @package App\ArgumentResolver\Validators\Annotation
 * @Annotation
 */
class IsIntegerValidators extends AbstractValidators
{

    public function verify(RequestParam $requestParam)
    {
        $value = $this->get($requestParam->request_name);
        if (!is_numeric($value)){
            $this->errorMessage = "参数'{$requestParam->request_name}'必须为一个整数";
            return false;
        }
        if(floor($value)!=$value){
            $this->errorMessage = "参数'{$requestParam->request_name}'必须为一个整数";
            return false;
        }
        return true;
    }
}
