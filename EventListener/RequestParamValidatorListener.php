<?php


namespace jarming\RequestParamsBundle\EventListener;


use App\AppClassFactory;
use jarming\RequestParamsBundle\ArgumentResolver\Validators\ValidatorGroup;
use jarming\RequestParamsBundle\ArgumentResolver\Validators\ValidatorsInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadataFactory;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadataFactoryInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestParamValidatorListener implements EventSubscriberInterface
{
    private $argumentMetadataFactory;

    public function __construct(ArgumentMetadataFactoryInterface $argumentMetadataFactory = null)
    {
        $this->argumentMetadataFactory = $argumentMetadataFactory ?? new ArgumentMetadataFactory();
    }

    public function controllerArguments(ControllerArgumentsEvent $event){
        $controller = $event->getController();
        $requestParamService = AppClassFactory::RequestParamService();
        $requestParams = $requestParamService->getRequestParams($controller);
        foreach ($this->argumentMetadataFactory->createArgumentMetadata($controller) as $metadata){
            if (isset($requestParams[$metadata->getName()])){
                $requestParam = $requestParams[$metadata->getName()];
                if ($requestParam->is_required){
                    if ($requestParamService->get($requestParam->request_name) === null){
                        return $this->response($event,new JsonResponse(["data"=>[],"code"=>-1,"codeMsg"=>"缺乏必要参数'{$requestParam->request_name}'"]));
                    }
                }
                if ($requestParam->validators){
                    if (!($requestParam->validators instanceof ValidatorGroup||$requestParam->validators instanceof ValidatorsInterface||is_array($requestParam->validators))){
                        throw new \Exception("request_param_validator: @RequestParam Annotation \"validators\" field need to extends \"".ValidatorGroup::class."\" or implements \"".ValidatorsInterface::class."\"");
                    }
                    $validator = $requestParam->validators;
                    if (is_array($validator)||$validator instanceof ValidatorGroup){
                        if (is_array($validator)){
                            $validatorList = $validator;
                        }else{
                            $validatorList = $validator->getValidatorsList();
                        }
                        foreach ($validatorList as $v){
                            if (!$v instanceof ValidatorsInterface){
                                throw new \Exception("request_param_validator:@RequestParam Annotation \"validators\" field subset need to implements \"".ValidatorsInterface::class."\"");
                            }
                            $value = $v->get($requestParam->request_name);
                            if (empty($value)){
                                if ($requestParam->is_required==true){
                                    return $this->response($event,new JsonResponse(["data"=>[],"code"=>-1,"codeMsg"=>"缺乏必要参数'{$requestParam->request_name}'"]));
                                }
                                // 值为空，且非必传时跳过验证
                                break;
                            }
                            $r = $v->verify($requestParam);
                            if (!$r) {
                                $codeMsg = "请求参数'{$requestParam->request_name}'字段格式不正确";
                                $code = -1;
                                $errMessage = $v->getErrorMessage();
                                $errCode = $v->getErrorCode();
                                if ($errMessage){
                                    $codeMsg = $errMessage;
                                }
                                if ($errCode){
                                    $code = $errCode;
                                }
                                return $this->response($event,new JsonResponse(["data"=>[],"code"=>$code,"codeMsg"=>$codeMsg]));
                            }
                            if ($r instanceof Response){
                                return $this->response($event,$r);
                            }
                        }
                    }elseif ($validator instanceof ValidatorsInterface){
                        $value = $validator->get($requestParam->request_name);
                        if (empty($value)){
                            if ($requestParam->is_required==true){
                                return $this->response($event,new JsonResponse(["data"=>[],"code"=>-1,"codeMsg"=>"缺乏必要参数'{$requestParam->request_name}'"]));
                            }
                            // 值为空，且非必传时跳过验证
                            continue;
                        }
                        $r = $validator->verify($requestParam);
                        if (!$r) {
                            $codeMsg = "请求参数'{$requestParam->request_name}'字段格式不正确";
                            $code = -1;
                            $errMessage = $validator->getErrorMessage();
                            $errCode = $validator->getErrorCode();
                            if ($errMessage){
                                $codeMsg = $errMessage;
                            }
                            if ($errCode){
                                $code = $errCode;
                            }
                            return $this->response($event,new JsonResponse(["data"=>[],"code"=>$code,"codeMsg"=>$codeMsg]));
                        }
                        if ($r instanceof Response){
                            return $this->response($event,$r);
                        }
                    }
                }
            }
        }
        return $event;
    }

    public function response(ControllerArgumentsEvent $event,$response){
        if ($response instanceof Response){
            $event->setController(function ($response){
                return $response;
            });
            $event->setArguments([$response]);
        }
        return false;
    }


    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER_ARGUMENTS=>[["controllerArguments"]],
        ];
    }
}
