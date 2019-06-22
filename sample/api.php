<?php

use Maestroprog\CreditCalculator\Calculator;
use Maestroprog\CreditCalculator\Period;

header('Content-Type: text/json');

require_once '../src/Calculator.php';
require_once '../src/Payment.php';
require_once '../src/PaymentPeriod.php';
require_once '../src/Period.php';

$result = [];

$period = (int)($_GET['period'] ?? 12);
$amount = (int)($_GET['credit'] ?? 100000);
$credit = $amount;
$percent = (float)($_GET['percent'] ?? 20);
$perMonth1 = (float)($_GET['per-month'] ?? 70000);

$calculator = new Calculator(
    $amount,
    $percent,
    $mainPeriod = new Period(
        new \DateTimeImmutable(),
        new \DateTimeImmutable($period . ' month')
    ),
    new \Maestroprog\CreditCalculator\CreditOptions(0)
);

$minimalPayment = $calculator->getMinimalPayment($credit, $mainPeriod);
$paymentSchedule = $calculator->paymentSchedule($perMonth1);
foreach ($paymentSchedule->getPayments() as $payment) {
    $result['payments'][] = [
        'date' => $payment->getDate()->format('Y-m-d'),
        'amount' => $payment->getAmount(),
        'minimal_payment' => $minimalPayment,
        'percent_amount' => $payment->getPercentAmount(),
        'debt_repayment_amount' => $payment->getDebtRepaymentAmount()
    ];
}
$calculator->applyPaymentPeriod($paymentSchedule);

$result['remaining_amount'] = $calculator->getCredit();
$result['over_amount'] = $paymentSchedule->getOverPay();

echo json_encode($result);
