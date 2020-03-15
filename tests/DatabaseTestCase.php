<?php

namespace Test;

/**
 * Class DatabaseTestCase
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class DatabaseTestCase extends TestCase
{
    protected function setUp(): void
    {
        Database::getConnection()->beginTransaction();
    }

    protected function tearDown(): void
    {
        Database::getConnection()->rollBack();
    }
}
