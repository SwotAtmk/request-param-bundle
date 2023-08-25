<?php

namespace jarming\RequestParamsBundle\ArgumentResolver\Validators\Annotation;

use jarming\RequestParamsBundle\Annotation\RequestParam;
use jarming\RequestParamsBundle\ArgumentResolver\Validators\AbstractValidators;

/**
 * Class DefaultValidators
 * @package App\ArgumentResolver\Validators
 * @Annotation
 */
class DefaultValidators extends AbstractValidators
{

    public function verify(RequestParam $requestParam)
    {
        $requestName = $requestParam->request_name;
        $request = $this->getRequest();
        if ($requestParam->content_type == RequestParam::REQUEST_CONTENT_TYPE_QUERY){
            if ($request->query->has($requestName)){
                return true;
            }
            $this->errorMessage = "该字段在query中不存在";
            return false;
        }elseif ($requestParam->content_type == RequestParam::REQUEST_CONTENT_TYPE_BODY){
            if ($request->request->has($requestName)){
                return true;
            }
            $this->errorMessage = "该字段在body中不存在";
            return false;
        }elseif ($requestParam->content_type == RequestParam::REQUEST_CONTENT_TYPE_JSON){
            $d = $this->getData($request);
            if (isset($d[$requestName])){
                return true;
            }
            $this->errorMessage = "该字段在json串的解析中不存在";
            return false;
        }
        return true;
    }

}
