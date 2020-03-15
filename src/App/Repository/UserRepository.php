<?php

namespace App\Repository;

use App\Entity\User;
use PDO;

/**
 * Class UserRepository
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UserRepository
{
    /** @var PDO */
    private $connection;


    /**
     * Constructor.
     *
     * @param \PDO $connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Finds a user by its id.
     *
     * @param string $email
     *
     * @return User|null
     */
    public function findOneById(int $id): ?User
    {
        $sql = "SELECT id, email, `password`, name, birthday, active FROM user WHERE id=:id LIMIT 1";

        $statement = $this->connection->prepare($sql);

        $statement->execute(['id' => $id]);

        if (false === $data = $statement->fetch(PDO::FETCH_ASSOC)) {
            return null;
        }

        $user = new User();

        $this->hydrate($user, $data);

        return $user;
    }

    /**
     * Finds all the users.
     *
     * @return User[]
     */
    public function findAll(): array
    {
        $sql = "SELECT id, email, `password`, name, birthday, active FROM user ORDER BY id";

        $statement = $this->connection->query($sql);

        $users = [];
        while (false !== $data = $statement->fetch(PDO::FETCH_ASSOC)) {
            $user = new User();

            $this->hydrate($user, $data);

            $users[] = $user;
        }

        return $users;
    }

    protected function hydrate(User $user, array $data)
    {
        $user->setId($data['id']);
        $user
            ->setEmail($data['email'])
            ->setPassword($data['password'])
            ->setName($data['name'])
            ->setBirthday($data['birthday'] ? new \DateTime($data['birthday']) : null)
            ->setActive('1' === $data['active']);
    }
}
