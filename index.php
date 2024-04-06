<?php
class ATM
{
    public function userAccount()
    {
        $account = array(
            array("1234567890123456", "1111", 2500),
            array("2345678901234567", "1234", 1800),
            array("4000123456789010", "4567", 5000),
        );
        return $account;
    }

    public function startOperation()
    {
        $this->printWhiteSpace();
        echo "**********************\n";
        echo "*     WELCOME TO     *\n";
        echo "*      MNC BANK      *\n";
        echo "**********************\n";
        echo "Please select an option:\n";
        echo "1. Login\n";
        echo "2. Exit\n\n";
        $option = readline("Option:");
        return $option;
    }

    public function endOperation()
    {
        $this->printWhiteSpace();
        echo "***********************\n";
        echo "* THANK YOU FOR USING *\n";
        echo "*      MNC BANK       *\n";
        echo "***********************\n";
        echo "Have a great day!\n";
    }

    public function enterPin(&$userAccount)
    {
        $this->printWhiteSpace();
        echo "**********************\n";
        echo "*   ENTER PIN MENU   *\n";
        echo "**********************\n";
        echo "Please enter your PIN.\n";
        $pin = readline("PIN:");
        for ($y = 0; $y < count($userAccount); $y++)
        {
            if ($userAccount[$y][1] == $pin)
            {
                return $y;
            }
        }
        return -1;
    }

    public function incorrectPin()
    {
        $this->printWhiteSpace();
        echo "*************************\n";
        echo "* INCORRECT PIN ENTERED *\n";
        echo "*************************\n";
        echo "The PIN you entered is incorrect. Please try again.\n\n";
        $option = readline("Press any key to continue.");
    }

    public function printWhiteSpace()
    {
        for ($x = 0; $x < 100; $x++)
        {
            echo "\n";
        }
    }

    public function loginMenu()
    {
        $this->printWhiteSpace();
        echo "**********************\n";
        echo "*      LOGIN MENU    *\n";
        echo "**********************\n";
        echo "Please select an option:\n";
        echo "1. Balance Inquiry\n";
        echo "2. Cash Deposit\n";
        echo "3. Deposit\n";
        echo "4. Transfer\n";
        echo "5. Change PIN\n";
        echo "6. Logout\n\n";
        $option = readline("Option:");
        return $option;
    }

    public function balanceInquiry(&$userAccount)
    {
        $this->printWhiteSpace();
        echo "**********************\n";
        echo "*   BALANCE INQUIRY  *\n";
        echo "**********************\n";
        echo "Your current account balance is: " . $userAccount . "\n\n";
        $option = readline("Press any key to return to the main menu.");
    }

    public function cashWithdrawal(&$userAccount)
    {
        $this->printWhiteSpace();
        echo "**********************\n";
        echo "*   CASH WITHDRAWAL  *\n";
        echo "**********************\n";
        echo "Enter the amount to withdraw:\n";
        $amount = readline("Amount:");
        $amount = intval($amount);
        if ($amount < $userAccount)
        {
            $this->printWhiteSpace();
            $userAccount -= $amount;
            echo "Withdrawal successful. Please take your cash.\n\n";
            $option = readline("Press any key to return to the main menu.");
        }
        else
        {
            $this->printWhiteSpace();
            echo "****************************\n";
            echo "* INSUFFICIENT FUNDS ERROR *\n";
            echo "****************************\n";
            echo "Sorry, you have insufficient funds to complete this transaction.\n\n";
            $option = readline("Press any key to continue.");
        }
    }

    public function depositCash(&$userAccount)
    {
        $this->printWhiteSpace();
        echo "**********************\n";
        echo "*       DEPOSIT      *\n";
        echo "**********************\n";
        echo "Insert your cash or checks into the deposit slot.\n\n";
        $amount = readline("Amount:");
        $amount = intval($amount);
        if ($amount > 0)
        {
            $this->printWhiteSpace();
            $userAccount += $amount;
            echo "Deposit successful. Your funds will be available shortly.\n\n";
            $option = readline("Press any key to return to the main menu.");
        }
        else
        {
            $this->printWhiteSpace();
            echo "**************************\n";
            echo "* INVALID DEPOSIT AMOUNT *\n";
            echo "**************************\n";
            echo "Sorry, the deposit amount cannot be zero or negative. Please enter a valid deposit amount.\n\n";
            $option = readline("Press any key to continue.");
        }
    }

    public function transferCash(&$userAccount, &$recipientAccount)
    {
        $this->printWhiteSpace();
        echo "**********************\n";
        echo "*      TRANSFER      *\n";
        echo "**********************\n";
        echo "Enter the account number to transfer to:\n\n";
        $recipientAccountNumber = readline("Account Number:");
        $recipientFound = False;
        for ($x = 0; $x < count($recipientAccount); $x++)
        {
            if ($userAccount[0] == $recipientAccountNumber)
            {
                $this->printWhiteSpace();
                echo "**********************************\n";
                echo "* CANNOT TRANSFER TO OWN ACCOUNT *\n";
                echo "**********************************\n";
                echo "Sorry, you cannot transfer money to your own account.\n\n";
                $option = readline("Press any key to continue.");
                return;
            }
            elseif ($recipientAccountNumber == $recipientAccount[$x][0])
            {
                $recipientFound = True;
                break;
            }
        }

        if ($recipientFound)
        {
            $this->printWhiteSpace();
            echo "Enter the amount to transfer:\n\n";
            $amount = readline("Amount:");
            $amount = intval($amount);
            $recipientAccount[$x][2] += $amount;
            $userAccount[2] -= $amount;
            $this->printWhiteSpace();
            echo "Transfer successful.\n\n";
            $option = readline("Press any key to return to the main menu.");
        }
        else
        {
            $this->printWhiteSpace();
            echo "*******************************\n";
            echo "* RECIPIENT ACCOUNT NOT FOUND *\n";
            echo "*******************************\n";
            echo "Sorry, the recipient account number you entered was not found or does not exist.\n\n";
            $option = readline("Press any key to continue.");
        }
    }

    public function changePin(&$userAccount)
    {
        $this->printWhiteSpace();
        echo "**********************\n";
        echo "*     CHANGE PIN     *\n";
        echo "**********************\n";
        echo "Enter your current PIN:\n\n";
        $currentPin = readline("Current PIN:");
        if ($currentPin == $userAccount)
        {
            echo "Enter your new PIN:\n\n";
            $newPin = readline("New PIN:");
            if (strlen($newPin) == 4)
            {
                $userAccount = $newPin;
                echo "PIN successfully changed.\n";
                $option = readline("Press any key to return to the main menu.\n");
            }
            else
            {
                $this->printWhiteSpace();
                echo "****************************\n";
                echo "* INVALID PIN LENGTH ERROR *\n";
                echo "****************************\n\n";
                echo "Sorry, the new PIN must be at least 4 digits long. Please enter a valid PIN.\n\n";
                $option = readline("Press any key to continue.");
            }
        }
    }
}
$atm = new ATM();
$account = $atm->userAccount();
while (True)
{
    $option = $atm->startOperation();
    if ($option == 2)
    {
        $atm->endOperation();
        break;
    }
    elseif ($option == 1)
    {
        $pin = $atm->enterPin($account);
        if ($pin == -1)
        {
            $atm->incorrectPin();
        }
        else
        {
            while (True)
            {
                $loginMenu = $atm->loginMenu();
                if ($loginMenu == 1)
                {
                    $atm->balanceInquiry($account[$pin][2]);
                }
                elseif ($loginMenu == 2)
                {
                    $atm->cashWithdrawal($account[$pin][2]);
                }
                elseif ($loginMenu == 3)
                {
                    $atm->depositCash($account[$pin][2]);
                }
                elseif ($loginMenu == 4)
                {
                    $atm->transferCash($account[$pin], $account);
                }
                elseif ($loginMenu == 5)
                {
                    $atm->changePin($account[$pin][1]);
                }
                elseif ($loginMenu == 6)
                {
                    break;
                }
            }
        }
    }
}
?>