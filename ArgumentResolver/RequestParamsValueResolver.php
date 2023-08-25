<?php

namespace jarming\RequestParamsBundle\ArgumentResolver;


use jarming\RequestParamsBundle\Annotation\RequestParam;
use App\AppClassFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class RequestParamsValueResolver implements ArgumentValueResolverInterface
{

    /**
     * @inheritDoc
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        $requestParams = AppClassFactory::RequestParamService()->getRequestParams($request);
        if (!$requestParams) {
            return false;
        }

        if (isset($requestParams[$argument->getName()])&&$requestParams[$argument->getName()] instanceof RequestParam){
            $requestParam = $requestParams[$argument->getName()];
            if ($requestParam->param_name ){
                if($requestParam->param_name != $argument->getName()) return false;
            }
            return true;
//            if ($requestParam->is_required == false){
//                return true;
//            }
//            if (in_array($requestParam->content_type,[null,RequestParam::REQUEST_CONTENT_TYPE_QUERY,RequestParam::REQUEST_CONTENT_TYPE_BODY])){
//                return  ($request->query->has($requestParam->request_name)||$request->request->has($requestParam->request_name));
//            }elseif ($requestParam->content_type == RequestParam::REQUEST_CONTENT_TYPE_JSON){
//                $data = AppClassFactory::RequestParamService()->getData($request);
//                return isset($data[$requestParam->request_name]);
//            }
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $requestParamService = AppClassFactory::RequestParamService();
        $requestParams = $requestParamService->getRequestParams($request);
        if (!$requestParams) {
            yield null;
            return null;
        }
        $requestParam = $requestParams[$argument->getName()];
        switch (true){
            case $requestParam->content_type == RequestParam::REQUEST_CONTENT_TYPE_QUERY:
                yield $request->query->get($requestParam->request_name,$requestParamService->getDefault($argument,$requestParam));
                break;
            case $requestParam->content_type == RequestParam::REQUEST_CONTENT_TYPE_BODY:
                yield $request->request->get($requestParam->request_name,$requestParamService->getDefault($argument,$requestParam));
                break;
            case $requestParam->content_type == RequestParam::REQUEST_CONTENT_TYPE_JSON:
                $data = $requestParamService->getData($request);
                yield $data[$requestParam->request_name]??$requestParamService->getDefault($argument,$requestParam);
                break;
            case $requestParam->content_type == null:
                $r = $request->get($requestParam->request_name);
                if ($r !== null) {
                    yield $request->get($requestParam->request_name,$requestParamService->getDefault($argument,$requestParam));
                    break;
                }
                $data = $requestParamService->getData($request);
                yield $data[$requestParam->request_name]??$requestParamService->getDefault($argument,$requestParam);
                break;
            default:
                yield null;
                break;
        }
    }

}
