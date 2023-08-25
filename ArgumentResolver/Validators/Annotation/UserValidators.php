<?php


namespace jarming\RequestParamsBundle\ArgumentResolver\Validators\Annotation;


use jarming\RequestParamsBundle\Annotation\RequestParam;
use App\AppRepositoryFactory;
use jarming\RequestParamsBundle\ArgumentResolver\Validators\AbstractValidators;
use App\Entity\User;

/**
 * Class UserValidators
 * @package App\ArgumentResolver\Validators\Annotation
 * @Annotation
 */
class UserValidators extends AbstractValidators
{

    public function verify(RequestParam $requestParam)
    {
        $uid = $this->get($requestParam->request_name);
        if (empty($uid)){
            $this->errorMessage="User Id不能为空";
            return false;
        }
        $user = AppRepositoryFactory::UserRepository()->find($uid);
        if (!$user instanceof User){
            $this->errorMessage="用户不存在";
            return false;
        }

        return true;
    }
}
