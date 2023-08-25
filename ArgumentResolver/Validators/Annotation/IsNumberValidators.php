<?php


namespace jarming\RequestParamsBundle\ArgumentResolver\Validators\Annotation;

use jarming\RequestParamsBundle\Annotation\RequestParam;
use jarming\RequestParamsBundle\ArgumentResolver\Validators\AbstractValidators;

/**
 * Class IsNumberValidators
 * @package App\ArgumentResolver\Validators\Annotation
 * @Annotation
 */
class IsNumberValidators extends AbstractValidators
{
    public $maxDecimalScale=0;

    public function verify(RequestParam $requestParam)
    {
        $value = $this->get($requestParam->request_name);
        if (empty($value)||!is_numeric($value)){
            $this->errorMessage = "参数'{$requestParam->request_name}'必须为一个数字";
            return false;
        }
        // 验证小数位
        if ($this->maxDecimalScale>0){
            $decimals = $this->numberOfDecimals($value);
            if ($decimals>$this->maxDecimalScale){
                $this->errorMessage = "需要保留".$this->maxDecimalScale."位小数";
                return false;
            }
        }

        return true;
    }
    private function numberOfDecimals($value)
    {
        if ((int)$value == $value)
        {
            return 0;
        }
        else if (! is_numeric($value))
        {
            return 0;
        }

        return strlen($value) - strrpos($value, '.') - 1;
    }
}
