<?php


namespace jarming\RequestParamsBundle\ArgumentResolver\Validators;


use App\AppClassFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractValidators implements ValidatorsInterface
{

    /**
     * 错误消息配置
     * @var string
     */
    public $errorMessage;

    /**
     * 错误码
     */
    public $errorCode;

    protected function renderResult($data, $code = null, $codeMsg = null){
        return new JsonResponse([
            "data"=>$data,
            "code"=>$code,
            "codeMsg"=>$codeMsg
        ]);
    }

    protected function getData(Request $request){
        static $t_request,$t_data;
        if ($t_request == $request && $t_data){
            return $t_data;
        }
        $data = [];
        if ($request->getContentType() == "json"){
            $content = $request->getContent();
            $data = json_decode($content,true);
            foreach ($data as $key=>$value){
                $data[$key] = $value;
            }
        }
        $t_data = $data;
        $t_request = $request;
        return $t_data;
    }

    protected function getRequest(){
        return AppClassFactory::CurrentRequest();
    }

    public function get($name,$default=null){
        $request = $this->getRequest();
        $value = $request->get($name);
        if (!$value){
            $data = $this->getData($request);
            if (!isset($data[$name])){
                return $default;
            }
            $value = $data[$name];
        }
        return $value;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
}
