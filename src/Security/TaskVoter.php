<?php

namespace App\Security;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TaskVoter extends Voter
{
    const MANAGE = 'manage';

    protected function supports(string $attribute, $task)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::MANAGE])) {
            return false;
        }

        if (!$task instanceof Task) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $task, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::MANAGE:
                return $this->canManage($task, $user);
                break;
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canManage(Task $task, User $user)
    {
        if ($task->getUser() == $user) {
            return true;
        }

        if (null == $task->getUser() && $user->getRoles() == ['ROLE_ADMIN']) {
            return true;
        }

        return false;
    }
}
