<?php


namespace jarming\RequestParamsBundle\ArgumentResolver\Validators\Annotation;


use jarming\RequestParamsBundle\Annotation\RequestParam;
use jarming\RequestParamsBundle\ArgumentResolver\Validators\AbstractValidators;

/**
 * Class LengthValidators
 * @package App\ArgumentResolver\Validators\Annotation
 * @Annotation
 */
class LengthValidators extends AbstractValidators
{
    public $minLength=0;

    public $maxLength=-1;

    /**
     * 是否包含临界值
     * @var bool
     */
    public $isIncludeCriticalValue=true;

    public function verify(RequestParam $requestParam)
    {
        $value = $this->get($requestParam->request_name);
        $length = $this->getLength($value);
        $r = true;
        if ($this->isIncludeCriticalValue){
            if ($this->maxLength != -1){
                $r = $length <= $this->maxLength;
            }

            $r = $r && $length >= $this->minLength;
        }else{
            if ($this->maxLength != -1){
                $r = $length < $this->maxLength;
            }

            $r = $r && $length > $this->minLength;
        }
        return $r;
    }

    public function getLength($value){
        if (is_numeric($value)){
            $value = (string)$value;
        }
        if (is_string($value)){
            return strlen($value);
        }elseif (is_array($value)){
            return count($value);
        }

        throw new \Exception(get_class($this).":param value must is string or array");
    }
}
