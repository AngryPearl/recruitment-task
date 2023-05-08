<?php

namespace PragmaGoTech\Interview\Test;

use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\database\Migration;
use PragmaGoTech\Interview\FeeCalculator;
use PragmaGoTech\Interview\Model\LoanProposal;

class FeeCalculatorTest extends TestCase
{

    private FeeCalculator $feeCalculator;

    /** @before */
    public function setUp(): void
    {
        Migration::migrate();
        $this->feeCalculator = new FeeCalculator();
    }

    public function testExactFeeAmount()
    {
        $loanProposal = new LoanProposal(12, 1000);
        $this->assertSame(50.0, $this->feeCalculator->calculate($loanProposal));

        $loanProposal = new LoanProposal(24, 2000);
        $this->assertSame(100.0, $this->feeCalculator->calculate($loanProposal));
    }

    public function testRangeFeeCalculate()
    {
        $loanProposal = new LoanProposal(12, 2500);
        $this->assertSame(90.0, $this->feeCalculator->calculate($loanProposal));

        $loanProposal = new LoanProposal(12, 4400);
        $this->assertSame(110.0, $this->feeCalculator->calculate($loanProposal));

        $loanProposal = new LoanProposal(12, 19250);
        $this->assertSame(385.0, $this->feeCalculator->calculate($loanProposal));

        $loanProposal = new LoanProposal(24, 11500);
        $this->assertSame(460.0, $this->feeCalculator->calculate($loanProposal));

        $loanProposal = new LoanProposal(24, 2750);
        $this->assertSame(115.0, $this->feeCalculator->calculate($loanProposal));
    }

    public function testRangeFeeDecimalAmountCalculate()
    {
        $loanProposal = new LoanProposal(12, 1301.25);
        $this->assertSame(63.75, $this->feeCalculator->calculate($loanProposal));
    }

}