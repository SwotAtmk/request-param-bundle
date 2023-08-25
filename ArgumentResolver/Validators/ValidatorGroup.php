<?php


namespace jarming\RequestParamsBundle\ArgumentResolver\Validators;


/**
 * 验证器组，用于多个验证器实现
 * Class ValidatorGroup
 * @Annotation
 */
class ValidatorGroup
{

    public $validatorsList = [];

    /**
     * @return ValidatorsInterface[]
     */
    public function getValidatorsList()
    {
        return $this->validatorsList;
    }

    public function addValidators(ValidatorsInterface $validators){
        $this->validatorsList[] = $validators;

        return $this;
    }

    public function removeValidators($index){
        unset($this->validatorsList[$index]);

        return $this;
    }

    public function initValidators(){
        $this->validatorsList = [];

        return $this;
    }
}
