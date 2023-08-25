<?php


namespace jarming\RequestParamsBundle\ArgumentResolver\Validators\Annotation;


use jarming\RequestParamsBundle\Annotation\RequestParam;
use jarming\RequestParamsBundle\ArgumentResolver\Validators\AbstractValidators;

/**
 * Class NotNullValidators
 * @package App\ArgumentResolver\Validators\Annotation
 * @Annotation
 */
class NotNullValidators extends AbstractValidators
{

    public function verify(RequestParam $requestParam)
    {
        $v = $this->get($requestParam->request_name);
        if (empty($v)){
            return false;
        }
        return true;
    }
}
