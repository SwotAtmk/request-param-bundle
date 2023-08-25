<?php

namespace jarming\RequestParamsBundle\Annotation;

/**
 * 请求参数装配
 * Class RequestParam
 * @package jarming\RequestParamsBundle\Annotation
 * @Annotation
 */
class RequestParam
{
    const REQUEST_CONTENT_TYPE_QUERY = "REQUEST_CONTENT_TYPE_QUERY"; // 请求参数在query中
    const REQUEST_CONTENT_TYPE_BODY = "REQUEST_CONTENT_TYPE_BODY"; // 请求参数在body中
    const REQUEST_CONTENT_TYPE_JSON = "REQUEST_CONTENT_TYPE_JSON"; // 请求参数在json中

    /**
     * @var string 请求参数名称
     */
    public $request_name;

    /**
     * @var string 控制器方法参数映射的字段名称
     */
    public $param_name;

    /**
     * @var string 请求参数内容类型
     */
    public $content_type;

    /**
     * @var bool 是否必填参数
     */
    public $is_required=false;

    /**
     * 参数默认值，当参数不存在时给它一个默认值
     */
    public $default=null;

    /**
     * 装配验证器，它可以是一个实现了"jarming\RequestParamsBundle\ArgumentResolver\Validators\ValidatorsInterface"
     * 或"jarming\RequestParamsBundle\ArgumentResolver\Validators\ValidatorGroup"接口的实例，
     * 也可以是实现"jarming\RequestParamsBundle\ArgumentResolver\Validators\ValidatorsInterface"接口的数组
     */
    public $validators=null;

    /**
     * 额外操作参数
     */
    public $options=[];

    /**
     * 标签名称
     */
    public $label;

    /**
     * 备注
     */
    public $remarks=null;
}
