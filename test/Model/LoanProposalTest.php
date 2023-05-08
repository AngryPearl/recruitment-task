<?php

namespace PragmaGoTech\Interview\Test\Model;

use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Model\LoanProposal;

class LoanProposalTest extends TestCase
{
    public function testLoanProposal()
    {
        $loanProposal = new LoanProposal(12, 20.0);

        $this->assertSame(12, $loanProposal->term());
        $this->assertSame(20.0, $loanProposal->amount());
    }
}