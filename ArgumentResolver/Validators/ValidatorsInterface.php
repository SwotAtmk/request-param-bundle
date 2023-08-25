<?php

namespace jarming\RequestParamsBundle\ArgumentResolver\Validators;


use jarming\RequestParamsBundle\Annotation\RequestParam;

/**
 * Interface ValidatorsInterface
 * @package App\ArgumentResolver\Validators
 */
interface ValidatorsInterface
{
    /**
     * @param RequestParam $requestParam
     * @return mixed
     */
    public function verify(RequestParam $requestParam);

    public function getErrorMessage();

    public function getErrorCode();
}
