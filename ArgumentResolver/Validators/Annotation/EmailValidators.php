<?php


namespace jarming\RequestParamsBundle\ArgumentResolver\Validators\Annotation;



/**
 * Class EmailValidators
 * @package App\ArgumentResolver\Validators\Annotation
 * @Annotation
 */
class EmailValidators extends RegularValidators
{

    public $pattern="/[\\w!#$%&'*+\/\=?^_`{|}~-]+(?:\\.[\\w!#$%&'*+\/\=?^_`{|}~-]+)*@(?:[\\w](?:[\\w-]*[\\w])?\\.)+[\\w](?:[\\w-]*[\\w])?/";
}
