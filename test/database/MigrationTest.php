<?php

namespace PragmaGoTech\Interview\Test\database;

use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\database\Migration;
use SQLite3;

class MigrationTest extends TestCase
{

    /** @before */
    public function setUp(): void
    {
        Migration::migrate();
    }

    public function testNoErrorsAfterMigration()
    {
        $db = new SQLite3('loan_fees.db');

        $this->assertSame('not an error', $db->lastErrorMsg());
    }

    public function testColumnsAfterMigration()
    {
        $db = new SQLite3('loan_fees.db');

        $result = $db->query('SELECT * FROM fees_term_12');
        $this->assertSame(3, $result->numColumns());

        $result = $db->query('SELECT * FROM fees_term_24');
        $this->assertSame(3, $result->numColumns());
    }
}
