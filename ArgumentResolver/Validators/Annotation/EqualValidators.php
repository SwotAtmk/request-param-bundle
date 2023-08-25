<?php


namespace jarming\RequestParamsBundle\ArgumentResolver\Validators\Annotation;


use jarming\RequestParamsBundle\Annotation\RequestParam;
use jarming\RequestParamsBundle\ArgumentResolver\Validators\AbstractValidators;

/**
 * Class EqualValidators
 * @package App\ArgumentResolver\Validators\Annotation
 * @Annotation
 */
class EqualValidators extends AbstractValidators
{

    public $eqValue;

    public $strict=false;

    /**
     * @inheritDoc
     */
    public function verify(RequestParam $requestParam)
    {
        $v = $this->get($requestParam->request_name);
        if ($this->strict){
            return $this->eqValue === $v;
        }else{
            return $this->eqValue == $v;
        }

    }
}
