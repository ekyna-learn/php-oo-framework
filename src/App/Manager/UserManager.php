<?php

namespace App\Manager;

use App\Entity\User;
use PDO;

/**
 * Class UserManager
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UserManager
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
     * Persists the given user into the database.
     *
     * @param User $user
     */
    public function persist(User $user): void
    {
        if (0 < $user->getId()) {
            $this->update($user);

            return;
        }

        $this->insert($user);
    }

    /**
     * Inserts the user into the database.
     *
     * @param User $user
     */
    protected function insert(User $user): void
    {
        // Build the sql query
        $sql =
            "INSERT INTO user(email, `password`, name, birthday, active) " .
            "VALUES (:email, :password, :name, :birthday, :active);";

        // Prepare the query
        $query = $this->connection->prepare($sql);

        // Bind values
        $query->bindValue('email', $user->getEmail());
        $query->bindValue('password', $user->getPassword());
        $query->bindValue('name', $user->getName());
        $query->bindValue('active', $user->isActive(), \PDO::PARAM_BOOL);

        // DateTime must be converted to string, or null
        $birthday = $user->getBirthday();
        $query->bindValue('birthday', $birthday ? $birthday->format('Y-m-d') : null);

        // Execute insert
        $query->execute();

        // Get last inserted id
        $id = $this->connection->lastInsertId();

        // Set the user id
        $user->setId($id);
    }

    /**
     * Updates the user into the database.
     *
     * @param User $user
     */
    protected function update(User $user): void
    {
        // Build the sql query
        $sql =
            "UPDATE user " .
            "SET email=:email, `password`=:password, name=:name, birthday=:birthday, active=:active " .
            "WHERE id=:id LIMIT 1";

        // Prepare the query
        $query = $this->connection->prepare($sql);

        // Bind values
        $query->bindValue('email', $user->getEmail());
        $query->bindValue('password', $user->getPassword());
        $query->bindValue('name', $user->getName());
        $query->bindValue('active', $user->isActive(), \PDO::PARAM_BOOL);

        // DateTime must be converted to string, or null
        $birthday = $user->getBirthday();
        $query->bindValue('birthday', $birthday ? $birthday->format('Y-m-d') : null);

        $query->bindValue('id', $user->getId(), \PDO::PARAM_INT);

        // Execute the update query
        $query->execute();
    }

    /**
     * Removes the given user from the database.
     *
     * @param User $user
     */
    public function remove(User $user): void
    {
        // Without id, use should not be stored in the database
        if (0 >= $user->getId()) {
            // Just abort
            return;
        }

        // Build the sql query
        $sql = "DELETE FROM user WHERE id=:id LIMIT 1;";

        // Prepare the query
        $query = $this->connection->prepare($sql);

        // Execute the query, passing the user id
        $query->execute(['id' => $user->getId()]);

        // Unset the user id
        $user->setId(null);
    }
}
