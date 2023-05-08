<?php

namespace PragmaGoTech\Interview\Test\Controller;

use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Controller\FeesController;
use PragmaGoTech\Interview\database\Migration;
use PragmaGoTech\Interview\Model\LoanProposal;
use SQLite3;

class FeesControllerTest extends TestCase
{

    private LoanProposal $loan12ExactFeeAmount;
    private LoanProposal $loan12RangeFeeAmount;
    private LoanProposal $loan24ExactFeeAmount;
    private LoanProposal $loan24RangeFeeAmount;
    private LoanProposal $loan12RangeDecimalFeeAmount;

    /** @before */
    public function setUp(): void
    {
        Migration::migrate();

        $this->loan12ExactFeeAmount = new LoanProposal(12, 1000);
        $this->loan12RangeFeeAmount = new LoanProposal(12, 1300);
        $this->loan24ExactFeeAmount = new LoanProposal(24, 2000);
        $this->loan24RangeFeeAmount = new LoanProposal(24, 2400);
        $this->loan12RangeDecimalFeeAmount = new LoanProposal(12, 1301.25);
    }

    public function testNoErrorsAfterMigration()
    {
        $db = new SQLite3('loan_fees.db');

        $this->assertSame('not an error', $db->lastErrorMsg());
    }

    public function testRangeFeeDecimalAmount()
    {
        $feesController = new FeesController($this->loan12RangeDecimalFeeAmount);
        $result = $feesController->fetchDbData();
        $this->assertSame(50, $result[0]['fee']);
        $this->assertSame(90, $result[1]['fee']);
    }

    public function testExactFeeAmount()
    {
        $feesController = new FeesController($this->loan12ExactFeeAmount);
        $result = $feesController->fetchDbData();
        $this->assertSame(50, $result['fee']);

        $feesController = new FeesController($this->loan24ExactFeeAmount);
        $result = $feesController->fetchDbData();
        $this->assertSame(100, $result['fee']);
    }

    public function testRangeFeeAmount()
    {
        $feesController = new FeesController($this->loan12RangeFeeAmount);
        $result = $feesController->fetchDbData();
        $this->assertSame(50, $result[0]['fee']);
        $this->assertSame(90, $result[1]['fee']);

        $feesController = new FeesController($this->loan24RangeFeeAmount);
        $result = $feesController->fetchDbData();
        $this->assertSame(100, $result[0]['fee']);
        $this->assertSame(120, $result[1]['fee']);
    }

}

