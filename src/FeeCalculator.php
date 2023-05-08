<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview;

use PragmaGoTech\Interview\Controller\FeesController;
use PragmaGoTech\Interview\Model\LoanProposal;

class FeeCalculator implements FeeCalculatorInterface
{

    public function calculate(LoanProposal $application): float
    {
        $feesController = new FeesController($application);
        $result = $feesController->fetchDbData();

        $row1 = $result[0] ?? $result['fee'];
        $row2 = $result[1] ?? null;

        if ($row2) {
            $resultFee = $row1['fee'] + ($application->amount() - $row1['amount']) * (($row2['fee'] - $row1['fee']) / ($row2['amount'] - $row1['amount']));
        } else {
            $resultFee = $row1;
        }

        $roundedUpSum = ceil(($application->amount() + $resultFee) / 5.0) * 5;
        return $roundedUpSum - $application->amount();
    }
}
