<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    /**
     * \App\Repositories\UserRepository
     *
     * @var mixed
     */
    private $userRepository;

    /**
     * __construct
     *
     * @param  \App\Repositories\UserRepository $userRepository
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getBy($conditions)
    {
        return $this->userRepository->getBy($conditions);
    }

    public function getOne($id)
    {
        return $this->userRepository->getOne($id);
    }
}
