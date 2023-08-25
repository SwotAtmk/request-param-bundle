<?php


namespace jarming\RequestParamsBundle\ArgumentResolver\Validators\Annotation;



/**
 * 手机号验证器
 * Class PhoneValidators
 * @package App\ArgumentResolver\Validators\Annotation
 * @Annotation
 */
class PhoneValidators extends RegularValidators
{

    public $pattern='/^(13[0-9]|14[01456879]|15[0-35-9]|16[2567]|17[0-8]|18[0-9]|19[0-35-9])\d{8}$/';
}
