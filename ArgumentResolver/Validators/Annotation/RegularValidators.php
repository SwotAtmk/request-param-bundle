<?php


namespace jarming\RequestParamsBundle\ArgumentResolver\Validators\Annotation;


use jarming\RequestParamsBundle\Annotation\RequestParam;
use jarming\RequestParamsBundle\ArgumentResolver\Validators\AbstractValidators;

/**
 * 正则表达试验证器
 * Class RegularValidators
 * @package App\ArgumentResolver\Validators\Annotation
 * @Annotation
 */
class RegularValidators extends AbstractValidators
{

    /**
     * 正则表达试
     * @var string
     */
    public $pattern;

    /**
     * 结果取反
     * @var bool
     */
    public $result_negate=false;

    /**
     * @inheritDoc
     */
    public function verify(RequestParam $requestParam)
    {
        $value = $this->get($requestParam->request_name);
        $res = preg_match($this->pattern,$value);
        if ($this->result_negate){
            $res = !$res;
        }
        return (bool)$res;
    }
}
