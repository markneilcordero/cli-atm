<?php
require_once 'index.php'; // Include the class file

use PHPUnit\Framework\TestCase;

class ATMTest extends TestCase
{
    private $atm;

    protected function setUp(): void
    {
        $this->atm = new ATM();
    }

    // Test userAccount method
    public function testUserAccount()
    {
        $expected = [
            ["1234567890123456", "1111", 2500],
            ["2345678901234567", "1234", 1800],
            ["4000123456789010", "4567", 5000],
            ["6789012309489093", "8909", 25100],
        ];
        $this->assertEquals($expected, $this->atm->userAccount());
    }

    // Test startOperation method
    public function testStartOperation()
    {
        // Set up automatic input for '1' as user input
        $this->setInput('1');
        $this->expectOutputRegex('/WELCOME TO.*MNC BANK/');
        $this->assertEquals('1', $this->atm->startOperation());
    }

    // Test enterPin method
    public function testEnterPin()
    {
        // Test with correct PIN
        $this->setInput('1111');
        $userAccount = $this->atm->userAccount();
        $this->assertEquals(0, $this->atm->enterPin($userAccount));

        // Test with incorrect PIN
        $this->setInput('9999');
        $this->expectOutputRegex('/INCORRECT PIN ENTERED/');
        $this->assertEquals(-1, $this->atm->enterPin($userAccount));
    }

    // Test balanceInquiry method
    public function testBalanceInquiry()
    {
        $userAccount = $this->atm->userAccount();
        $this->expectOutputRegex('/BALANCE INQUIRY.*2500/');
        $this->atm->balanceInquiry($userAccount[0][2]);
    }

    // Test cashWithdrawal method
    public function testCashWithdrawal()
    {
        $userAccount = $this->atm->userAccount()[0][2];

        // Test withdrawal with insufficient funds
        $this->setInput('5000'); // Try to withdraw more than available balance
        $this->expectOutputRegex('/INSUFFICIENT FUNDS ERROR/');
        $this->atm->cashWithdrawal($userAccount);

        // Test withdrawal with sufficient funds
        $this->setInput('1000'); // Withdraw within available balance
        $this->expectOutputRegex('/Withdrawal successful/');
        $this->atm->cashWithdrawal($userAccount);
    }

    // Test depositCash method
    public function testDepositCash()
    {
        $userAccount = $this->atm->userAccount()[0][2];

        // Test deposit with negative amount
        $this->setInput('-100');
        $this->expectOutputRegex('/INVALID DEPOSIT AMOUNT/');
        $this->atm->depositCash($userAccount);

        // Test deposit with valid amount
        $this->setInput('500');
        $this->expectOutputRegex('/Deposit successful/');
        $this->atm->depositCash($userAccount);
    }

    // Test transferCash method
    public function testTransferCash()
    {
        $userAccount = $this->atm->userAccount()[0];
        $recipientAccount = $this->atm->userAccount();

        // Test transfer to own account
        $this->setInput('1234567890123456');
        $this->expectOutputRegex('/CANNOT TRANSFER TO OWN ACCOUNT/');
        $this->atm->transferCash($userAccount, $recipientAccount);

        // Test transfer to non-existing account
        $this->setInput('1234567890123458'); // Non-existing account number
        $this->expectOutputRegex('/RECIPIENT ACCOUNT NOT FOUND/');
        $this->atm->transferCash($userAccount, $recipientAccount);
    }

    // Test changePin method
    public function testChangePin()
    {
        $userAccount = $this->atm->userAccount()[0][1];

        // Test with invalid new PIN length
        $this->setInput('1111'); // Current PIN
        $this->setInput('123'); // New PIN (invalid length)
        $this->expectOutputRegex('/INVALID PIN LENGTH ERROR/');
        $this->atm->changePin($userAccount);

        // Test with valid new PIN length
        $this->setInput('1111'); // Current PIN
        $this->setInput('4321'); // New PIN
        $this->expectOutputRegex('/PIN successfully changed/');
        $this->atm->changePin($userAccount);
    }

    // Helper method to set automatic input stream
    private function setInput($input)
    {
        $stream = fopen("php://stdin", "r");
        fwrite($stream, $input . PHP_EOL);
        rewind($stream);
        stream_set_blocking($stream, 1);
        fclose($stream);
    }
}
