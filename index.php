<?php
require_once('vendor/autoload.php');
require_once('vendor/stripe/stripe-php/init.php');

if (isset($_POST['sbt'])) {
    $err = '';
    $flag = 0;
    if (isset($_POST['card_holder_name']) && $_POST['card_holder_name'] != "") {
        $card_holder_name = $_POST['card_holder_name'];
    } else {
        $flag = 1;
        $err .= 'Card holder name should not be blank.<br/>';
    }
    if (isset($_POST['card_no']) && $_POST['card_no'] != "") {
        $card_no = $_POST['card_no'];
    } else {
        $flag = 1;
        $err .= 'Card number should not be blank.<br/>';
    }
    if (isset($_POST['month']) && $_POST['month'] != "") {
        $month = $_POST['month'];
    } else {
        $flag = 1;
        $err .= 'Month should not be blank.<br/>';
    }
    if (isset($_POST['year']) && $_POST['year'] != "") {
        $year = $_POST['year'];
    } else {
        $flag = 1;
        $err .= 'Year should not be blank.<br/>';
    }
    if (isset($_POST['cvv']) && $_POST['cvv'] != "") {
        $cvv = $_POST['cvv'];
    } else {
        $flag = 1;
        $err .= 'CVV should not be blank.<br/>';
    }
    if (isset($_POST['amount']) && $_POST['amount'] != "") {
        $amount = $_POST['amount'];
    } else {
        $flag = 1;
        $err .= 'Amount should not be blank.<br/>';
    }

    if ($flag == 0) {

        try {
            \Stripe\Stripe::setApiKey("YOUR_SECRET_KEY_HERE");

            //       Creating Token
            $token = \Stripe\Token::create(array("card" => array(
                            "number" => $card_no,
                            "exp_month" => $month,
                            "exp_year" => $year,
                            "cvc" => $cvv
            )));

            //       Charge customer
            $charge = \Stripe\Charge::create(array(
                        'amount' => ($amount * 100), // Amount in cents!
                        'currency' => 'usd',
                        'description' => 'Description of your charge',
                        'source' => $token['id'],
            ));
            echo "<pre>";
            print_r($charge);
            echo "</pre>";
            exit();
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network problem, perhaps try again.
            echo $e->getMessage();
        } catch (\Stripe\Error\InvalidRequest $e) {
            // You screwed up in your programming. Shouldn't happen!
            echo $e->getMessage();
        } catch (\Stripe\Error\Api $e) {
            // Stripe's servers are down!
            echo $e->getMessage();
        } catch (\Stripe\Error\Card $e) {
            // Card was declined.
            echo $e->getMessage();
        }
    } else {
        echo $err;
    }
}
?>

<html>
    <head>
        <title></title>
    </head>
    <body>
        <h3>Payment Form</h3>
        <form action="" method="POST">
            <input type="text" name="card_holder_name" placeholder="Card Holder Name"><br/><br/>
            <input type="text" name="card_no" placeholder="Card Number" maxlength="16"><br/><br/>
            <select name="month">
                <?php
                for ($i = 1; $i < 13; $i++) {
                    echo '<option value="' . $i . '">' . $i . '</option>';
                }
                ?>
            </select>
            <select name="year">
                <?php
                for ($i = date('Y'); $i < (date('Y') + 30); $i++) {
                    echo '<option value="' . $i . '">' . $i . '</option>';
                }
                ?>
            </select><br/><br/>
            <input type="password" name="cvv" placeholder="CVV Code" maxlength="3"><br/><br/>
            <input type="number" name="amount" placeholder="Amount" min="1"><br/><br/>
            <input type="submit" name="sbt" value="Pay">
        </form>
    </body>
</html>