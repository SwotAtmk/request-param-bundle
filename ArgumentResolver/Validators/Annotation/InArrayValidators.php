<?php


namespace jarming\RequestParamsBundle\ArgumentResolver\Validators\Annotation;


use jarming\RequestParamsBundle\Annotation\RequestParam;
use jarming\RequestParamsBundle\ArgumentResolver\Validators\AbstractValidators;

/**
 * Class InArrayValidators
 * @package App\ArgumentResolver\Validators\Annotation
 * @Annotation
 */
class InArrayValidators extends AbstractValidators
{

    public $haystack;

    public $strict=false;

    /**
     * @inheritDoc
     */
    public function verify(RequestParam $requestParam)
    {
        $v = $this->get($requestParam->request_name);
        return in_array($v,$this->haystack,$this->strict);
    }
}
