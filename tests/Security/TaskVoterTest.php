<?php

namespace App\Tests\Security;

use App\Entity\Task;
use App\Entity\User;
use App\Security\TaskVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class TaskVoterTest extends TestCase
{
    private function createUser(int $id)
    {
        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn($id);
        $user->method('getRoles')->willReturn(['ROLE_USER']);

        return $user;
    }

    private function createAdmin(int $id)
    {
        $admin = $this->createMock(User::class);
        $admin->method('getId')->willReturn($id);
        $admin->method('getRoles')->willReturn(['ROLE_ADMIN']);

        return $admin;
    }

    private function createTaskByUser($user = null)
    {
        $task = $this->createMock(Task::class);
        $task->method('getUser')->willReturn($user);

        return $task;
    }
    public function provideCases()
    {
        yield 'N\'est pas de type Task' => [
            'manage',
            null,
            $this->createUser(1),
            TaskVoter::ACCESS_ABSTAIN
        ];

        yield 'N\'est pas de type User' => [
            'manage',
            new Task(),
            null,
            TaskVoter::ACCESS_DENIED
        ];

        yield 'L\'utilisateur peut gérer sa propre tâche' => [
            'manage',
            $this->createTaskByUser($this->createUser(1)),
            $this->createUser(1),
            TaskVoter::ACCESS_GRANTED
        ];

        yield 'L\'utilisateur ne peut pas gérer les tâches anonymes' => [
            'manage',
            new Task(),
            $this->createUser(1),
            TaskVoter::ACCESS_DENIED
        ];

        yield 'L\'administrateur peut gérer les tâches anonymes' => [
            'manage',
            new Task(),
            $this->createAdmin(1),
            TaskVoter::ACCESS_GRANTED
        ];

        yield 'L\'administrateur ne peut gérer les tâches des autres utilisateurs' => [
            'manage',
            $this->createTaskByUser($this->createUser(1)),
            $this->createAdmin(1),
            TaskVoter::ACCESS_DENIED
        ];
    }
    
    /**
     * @dataProvider provideCases
     */
    public function testVote(
        string $attribute,
        $task,
        $user,
        $expectedVote
    ) {
        $voter = new TaskVoter();

        $token = new AnonymousToken('secret', 'anonymous');
        if ($user) {
            $token = new UsernamePasswordToken(
                $user, 'credentials', 'memory'
            );
        }

        $this->assertSame(
            $expectedVote,
            $voter->vote($token, $task, [$attribute])
        );
    }
}