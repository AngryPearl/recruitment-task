<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Controller;

use PragmaGoTech\Interview\Model\LoanProposal;
use SQLite3;
use SQLite3Result;

class FeesController
{

    private LoanProposal $application;
    private SQLite3 $db;

    public function __construct(LoanProposal $application)
    {
        $this->application = $application;
        $this->db = new SQLite3('loan_fees.db');
    }

    public function fetchDbData(): array
    {
        $exactResultFee = $this->getExactFee()->fetchArray(SQLITE3_ASSOC);

        if (!$exactResultFee) {
            $result = $this->getFeeByRange();
            $resultArr[] = $result->fetchArray(SQLITE3_ASSOC);
            $resultArr[] = $result->fetchArray(SQLITE3_ASSOC);
            return $resultArr;
        } else {
            return $exactResultFee;
        }
    }

    private function getExactFee(): SQLite3Result
    {
        return match ($this->application->term()) {
            12 => $this->db->query("SELECT fee FROM fees_term_12 WHERE amount = " . $this->application->amount()),
            24 => $this->db->query("SELECT fee FROM fees_term_24 WHERE amount = " . $this->application->amount()),
        };
    }

    private function getFeeByRange(): SQLite3Result
    {
        return match ($this->application->term()) {
            12 => $this->db->query("SELECT amount, fee FROM fees_term_12 order by abs(amount - " . $this->application->amount() . ") limit 2"),
            24 => $this->db->query("SELECT amount, fee FROM fees_term_24 order by abs(amount - " . $this->application->amount() . ") limit 2"),
        };
    }
}