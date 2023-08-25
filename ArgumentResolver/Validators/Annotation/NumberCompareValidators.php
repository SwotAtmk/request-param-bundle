<?php


namespace jarming\RequestParamsBundle\ArgumentResolver\Validators\Annotation;

use jarming\RequestParamsBundle\Annotation\RequestParam;
use jarming\RequestParamsBundle\ArgumentResolver\Validators\AbstractValidators;

/**
 * 数值比较
 * Class NumberCompareValidators
 * @package App\ArgumentResolver\Validators\Annotation
 * @Annotation
 */
class NumberCompareValidators extends AbstractValidators
{

    public $value;

    /**
     * =、>=、<=、>、<
     * @var string
     */
    public $criteria="=";

    /**
     * @inheritDoc
     */
    public function verify(RequestParam $requestParam)
    {
        if (!in_array($this->criteria,["=",">=","<=",">","<"])){
            throw new \Exception("比较符号不合法");
        }

        if (!is_numeric($this->value)){
            throw new \Exception("比较值应该为一个数字");
        }

        $v = $this->get($requestParam->request_name);
        $s = bcsub($v,$this->value,4);
        switch (true){
            case $this->criteria == "=":
                return $s == 0;
            case $this->criteria == ">=":
                return $s >= 0;
            case $this->criteria == "<=":
                return $s<=0;
            case $this->criteria ==">":
                return $s>0;
            case $this->criteria == "<":
                return $s<0;
            default:
                throw new \Exception("比较符号不合法");
        }
    }
}
