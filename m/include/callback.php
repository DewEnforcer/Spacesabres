<?php
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
      header("location: ../index.php");
      exit();
    }

    function bookItems ($email, $username, &$itemArray, $item, $date, $status, $verifySign, $currency, $price, $userID, $packContent) {
      $token = bin2hex(random_bytes(10));
      $subject = 'Payment confirmation';

      $message = "<div style='width: 100%; height: 100%; border: 2px solid grey; border-radius: 10px; margin: 15px 0; display:flex; background-color: rgb(50,50,50); flex-flow: column nowrap; align-items: center; justify-content: space-evenly; text-align: center; font-family: sans-serif; color: white;'><h2>Hello ".$username."</h2>";
      $message .= "<p>Thank you for purchasing ".$itemArray[$item]." , the items included in this pack should be booked on your account by now.</p>";
      $message .= "<p>This pack includes: ".$packContent.".</p>";
      $message .= "<p>Haven't recieved some or all of the included items? Save this e-mail along with paypal confirmation reciept and contact our support <a href=\"https://www.spacesabres.com/support/index.php?problem=payment\">here!</a></p>";
      $message .= "<hr style='width: 100%; border: 2px solid white;'><p style='margin-bottom: 30px;'>Payment ID: ".$token.", Payment date: ".$date.", Payment status: ".$status."</p></div>";

      $header = "From: Spacesabres <noreply@spacesabres.com>\r\n";
      $header .= "Content-type: text/html \r\n";

      mail($email, $subject, $message, $header);
      return $token;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "cmd=_notify-validate&" . http_build_query($_POST));
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response == "VERIFIED" && isset($_POST['custom'])) {
      require "dbh.inc.php";
      $FetcheduserID = $_POST["custom"];
      $sql = "SELECT Username, Email, credits, hyperid, natium, userID FROM users WHERE userID=?";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        // TODO: send error msg
        exit();
      }
      mysqli_stmt_bind_param($stmt, "s", $FetcheduserID);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $userInfo = mysqli_fetch_assoc($result);
      if (empty($userInfo) == TRUE) {
        // TODO: error msg;
        exit();
      }
      $price = $_POST["mc_gross"];
      $currency = $_POST["mc_currency"];
      $status = $_POST["payment_status"];
      $item = $_POST["item_name"];
      $date = $_POST["payment_date"];
      $verifySign = $_POST["verify_sign"];
      $username = $userInfo["Username"];
      $email = $userInfo["Email"];
      $credits = $userInfo["credits"];
      $hyperid = $userInfo["hyperid"];
      $natium = $userInfo["natium"];
      $userID = $userInfo["userID"];

      $sql = mysqli_query($conn, "SELECT hornet, spacefire, starhawk, peacemaker, centurion, fuel FROM userfleet WHERE userID=$userID");
      $userShips = mysqli_fetch_assoc($sql);
      $itemArray = ["pack_credits_starter"=>"Starter credit pack", "pack_credits_m"=>"Credit pack M", "pack_credits_l"=>"Credit pack L", "pack_credits_xl"=>"Credit pack XL", "pack_credits_xxl"=>"Credit pack XXL","pack_hyperid_starter"=>"Starter hyperid pack", "pack_hyperid_m"=>"Hyperid pack M", "pack_hyperid_l"=>"Hyperid pack L", "pack_hyperid_xl"=>"Hyperid pack XL", "pack_hyperid_xxl"=>"Hyperid pack XXL", "pack_natium"=>"Natium pack", "pack_ship_1"=>"Hornet nest pack", "pack_ship_2"=>"Spacefire pack", "pack_ship_3"=>"Starhawk pack", "pack_ship_4"=>"Peacemaker pack", "pack_ship_5"=>"Centurion pack", "pack_bundle_1"=>"Bounty hunter bundle", "pack_bundle_2"=>"Captain bundle", "pack_bundle_3"=>"Brigadier General bundle", "pack_bundle_4"=>"Hand of Midas bundle"];
      if ($status == "Completed" && $currency == "USD") {
        if ($item == "pack_credits_starter" && $price == "5") {
          $newCredits = $credits + 2500000; // value
          $sql = mysqli_query($conn, "UPDATE users SET credits=$newCredits WHERE userID=$userID");
          if ($sql !== FALSE) {
            $token = bookItems($email, $username, $itemArray, $item, $date, $status, $verifySign, $currency, $price, $userID, "2 500 000 Credits");
            $sql = "INSERT INTO paymentLogs (userID, paymentID, paymentVerify, paymentDate, paymentItem, paymentCurrency, paymentPrice, paymentEmail, paymentStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_bind_param($stmt, "isssssiss", $userID, $token, $verifySign, $date, $item, $currency, $price, $email, $status);
            mysqli_stmt_execute($stmt);
            exit();
            }
          }
          else if ($item == "pack_credits_m" && $price == "10") {
            $newCredits = $credits + 5750000; // value
            $sql = mysqli_query($conn, "UPDATE users SET credits=$newCredits WHERE userID=$userID");
            if ($sql !== FALSE) {
              $token = bookItems($email, $username, $itemArray, $item, $date, $status, $verifySign, $currency, $price, $userID, "5 750 000 Credits");
              $sql = "INSERT INTO paymentLogs (userID, paymentID, paymentVerify, paymentDate, paymentItem, paymentCurrency, paymentPrice, paymentEmail, paymentStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
              $stmt = mysqli_stmt_init($conn);
              mysqli_stmt_prepare($stmt, $sql);
              mysqli_stmt_bind_param($stmt, "isssssiss", $userID, $token, $verifySign, $date, $item, $currency, $price, $email, $status);
              mysqli_stmt_execute($stmt);
              exit();
          }
        }
        else if ($item == "pack_credits_l" && $price == "25") {
            $newCredits = $credits + 14400000; // value
            $sql = mysqli_query($conn, "UPDATE users SET credits=$newCredits WHERE userID=$userID");
            if ($sql !== FALSE) {
              $token = bookItems($email, $username, $itemArray, $item, $date, $status, $verifySign, $currency, $price, $userID, "14 400 000 Credits");
              $sql = "INSERT INTO paymentLogs (userID, paymentID, paymentVerify, paymentDate, paymentItem, paymentCurrency, paymentPrice, paymentEmail, paymentStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
              $stmt = mysqli_stmt_init($conn);
              mysqli_stmt_prepare($stmt, $sql);
              mysqli_stmt_bind_param($stmt, "isssssiss", $userID, $token, $verifySign, $date, $item, $currency, $price, $email, $status);
              mysqli_stmt_execute($stmt);
              exit();
          }
        }
        else if ($item == "pack_credits_xl" && $price == "50") {
            $newCredits = $credits + 32500000; // value
            $sql = mysqli_query($conn, "UPDATE users SET credits=$newCredits WHERE userID=$userID");
            if ($sql !== FALSE) {
              $token = bookItems($email, $username, $itemArray, $item, $date, $status, $verifySign, $currency, $price, $userID, "32 500 000 Credits");
              $sql = "INSERT INTO paymentLogs (userID, paymentID, paymentVerify, paymentDate, paymentItem, paymentCurrency, paymentPrice, paymentEmail, paymentStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
              $stmt = mysqli_stmt_init($conn);
              mysqli_stmt_prepare($stmt, $sql);
              mysqli_stmt_bind_param($stmt, "isssssiss", $userID, $token, $verifySign, $date, $item, $currency, $price, $email, $status);
              mysqli_stmt_execute($stmt);
              exit();
          }
        }
         else if ($item == "pack_credits_xxl" && $price == "100") {
            $newCredits = $credits + 75000000; // value
            $sql = mysqli_query($conn, "UPDATE users SET credits=$newCredits WHERE userID=$userID");
            if ($sql !== FALSE) {
              $token = bookItems($email, $username, $itemArray, $item, $date, $status, $verifySign, $currency, $price, $userID, "75 000 000 Credits");
              $sql = "INSERT INTO paymentLogs (userID, paymentID, paymentVerify, paymentDate, paymentItem, paymentCurrency, paymentPrice, paymentEmail, paymentStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
              $stmt = mysqli_stmt_init($conn);
              mysqli_stmt_prepare($stmt, $sql);
              mysqli_stmt_bind_param($stmt, "isssssiss", $userID, $token, $verifySign, $date, $item, $currency, $price, $email, $status);
              mysqli_stmt_execute($stmt);
              exit();
          }
        }
         else if ($item == "starter_hyperid_pack" && $price == "5") {
            $newCredits = $hyperid + 500000; // value
            $sql = mysqli_query($conn, "UPDATE users SET hyperid=$newCredits WHERE userID=$userID");
            if ($sql !== FALSE) {
              $token = bookItems($email, $username, $itemArray, $item, $date, $status, $verifySign, $currency, $price, $userID, "500 000 Hyperids");
              $sql = "INSERT INTO paymentLogs (userID, paymentID, paymentVerify, paymentDate, paymentItem, paymentCurrency, paymentPrice, paymentEmail, paymentStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
              $stmt = mysqli_stmt_init($conn);
              mysqli_stmt_prepare($stmt, $sql);
              mysqli_stmt_bind_param($stmt, "isssssiss", $userID, $token, $verifySign, $date, $item, $currency, $price, $email, $status);
              mysqli_stmt_execute($stmt);
              exit();
          }
        }
        else if ($item == "pack_hyperid_m" && $price == "10") {
            $newCredits = $hyperid + 1150000; // value
            $sql = mysqli_query($conn, "UPDATE users SET hyperid=$newCredits WHERE userID=$userID");
            if ($sql !== FALSE) {
              $token = bookItems($email, $username, $itemArray, $item, $date, $status, $verifySign, $currency, $price, $userID, "1 150 000 Hyperids");
              $sql = "INSERT INTO paymentLogs (userID, paymentID, paymentVerify, paymentDate, paymentItem, paymentCurrency, paymentPrice, paymentEmail, paymentStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
              $stmt = mysqli_stmt_init($conn);
              mysqli_stmt_prepare($stmt, $sql);
              mysqli_stmt_bind_param($stmt, "isssssiss", $userID, $token, $verifySign, $date, $item, $currency, $price, $email, $status);
              mysqli_stmt_execute($stmt);
              exit();
          }
        }
        else if ($item == "pack_hyperid_l" && $price == "25") {
            $newCredits = $hyperid + 2880000; // value
            $sql = mysqli_query($conn, "UPDATE users SET hyperid=$newCredits WHERE userID=$userID");
            if ($sql !== FALSE) {
              $token = bookItems($email, $username, $itemArray, $item, $date, $status, $verifySign, $currency, $price, $userID, "2 880 000 Hyperids");
              $sql = "INSERT INTO paymentLogs (userID, paymentID, paymentVerify, paymentDate, paymentItem, paymentCurrency, paymentPrice, paymentEmail, paymentStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
              $stmt = mysqli_stmt_init($conn);
              mysqli_stmt_prepare($stmt, $sql);
              mysqli_stmt_bind_param($stmt, "isssssiss", $userID, $token, $verifySign, $date, $item, $currency, $price, $email, $status);
              mysqli_stmt_execute($stmt);
              exit();
          }
        }
        else if ($item == "pack_hyperid_xl" && $price == "50") {
            $newCredits = $hyperid + 6500000; // value
            $sql = mysqli_query($conn, "UPDATE users SET hyperid=$newCredits WHERE userID=$userID");
            if ($sql !== FALSE) {
              $token = bookItems($email, $username, $itemArray, $item, $date, $status, $verifySign, $currency, $price, $userID, "6 500 000 Hyperids");
              $sql = "INSERT INTO paymentLogs (userID, paymentID, paymentVerify, paymentDate, paymentItem, paymentCurrency, paymentPrice, paymentEmail, paymentStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
              $stmt = mysqli_stmt_init($conn);
              mysqli_stmt_prepare($stmt, $sql);
              mysqli_stmt_bind_param($stmt, "isssssiss", $userID, $token, $verifySign, $date, $item, $currency, $price, $email, $status);
              mysqli_stmt_execute($stmt);
              exit();
          }
        }
        else if ($item == "pack_hyperid_xxl" && $price == "100") {
            $newCredits = $hyperid + 15000000; // value
            $sql = mysqli_query($conn, "UPDATE users SET hyperid=$newCredits WHERE userID=$userID");
            if ($sql !== FALSE) {
              $token = bookItems($email, $username, $itemArray, $item, $date, $status, $verifySign, $currency, $price, $userID, "15 000 000 Hyperids");
              $sql = "INSERT INTO paymentLogs (userID, paymentID, paymentVerify, paymentDate, paymentItem, paymentCurrency, paymentPrice, paymentEmail, paymentStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
              $stmt = mysqli_stmt_init($conn);
              mysqli_stmt_prepare($stmt, $sql);
              mysqli_stmt_bind_param($stmt, "isssssiss", $userID, $token, $verifySign, $date, $item, $currency, $price, $email, $status);
              mysqli_stmt_execute($stmt);
              exit();
          }
        }
        else if ($item == "pack_natium" && $price == "10") {
            $newCredits = $hyperid + 5000; // value
            $sql = mysqli_query($conn, "UPDATE users SET natium=$newCredits WHERE userID=$userID");
            if ($sql !== FALSE) {
              $token = bookItems($email, $username, $itemArray, $item, $date, $status, $verifySign, $currency, $price, $userID, "5 000 Natiums");
              $sql = "INSERT INTO paymentLogs (userID, paymentID, paymentVerify, paymentDate, paymentItem, paymentCurrency, paymentPrice, paymentEmail, paymentStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
              $stmt = mysqli_stmt_init($conn);
              mysqli_stmt_prepare($stmt, $sql);
              mysqli_stmt_bind_param($stmt, "isssssiss", $userID, $token, $verifySign, $date, $item, $currency, $price, $email, $status);
              mysqli_stmt_execute($stmt);
              exit();
          }
        }
        else if ($item == "pack_ship_1" && $price == "10") {
            $newVal = $userShips["hornet"] + 222; // value
            $sql = mysqli_query($conn, "UPDATE userfleet SET hornet=$newVal WHERE userID=$userID");
            if ($sql !== FALSE) {
              $token = bookItems($email, $username, $itemArray, $item, $date, $status, $verifySign, $currency, $price, $userID, "222 Hornets");
              $sql = "INSERT INTO paymentLogs (userID, paymentID, paymentVerify, paymentDate, paymentItem, paymentCurrency, paymentPrice, paymentEmail, paymentStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
              $stmt = mysqli_stmt_init($conn);
              mysqli_stmt_prepare($stmt, $sql);
              mysqli_stmt_bind_param($stmt, "isssssiss", $userID, $token, $verifySign, $date, $item, $currency, $price, $email, $status);
              mysqli_stmt_execute($stmt);
              exit();
          }
        }
        else if ($item == "pack_ship_2" && $price == "10") {
            $newVal = $userShips["spacefire"] + 160; // value
            $sql = mysqli_query($conn, "UPDATE userfleet SET spacefire=$newVal WHERE userID=$userID");
            if ($sql !== FALSE) {
              $token = bookItems($email, $username, $itemArray, $item, $date, $status, $verifySign, $currency, $price, $userID, "160 Spacefires");
              $sql = "INSERT INTO paymentLogs (userID, paymentID, paymentVerify, paymentDate, paymentItem, paymentCurrency, paymentPrice, paymentEmail, paymentStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
              $stmt = mysqli_stmt_init($conn);
              mysqli_stmt_prepare($stmt, $sql);
              mysqli_stmt_bind_param($stmt, "isssssiss", $userID, $token, $verifySign, $date, $item, $currency, $price, $email, $status);
              mysqli_stmt_execute($stmt);
              exit();
          }
        }
        else if ($item == "pack_ship_3" && $price == "10") {
            $newVal = $userShips["starhawk"] + 120; // value
            $sql = mysqli_query($conn, "UPDATE userfleet SET starhawk=$newVal WHERE userID=$userID");
            if ($sql !== FALSE) {
              $token = bookItems($email, $username, $itemArray, $item, $date, $status, $verifySign, $currency, $price, $userID, "120 Starhawks");
              $sql = "INSERT INTO paymentLogs (userID, paymentID, paymentVerify, paymentDate, paymentItem, paymentCurrency, paymentPrice, paymentEmail, paymentStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
              $stmt = mysqli_stmt_init($conn);
              mysqli_stmt_prepare($stmt, $sql);
              mysqli_stmt_bind_param($stmt, "isssssiss", $userID, $token, $verifySign, $date, $item, $currency, $price, $email, $status);
              mysqli_stmt_execute($stmt);
              exit();
          }
        }
        else if ($item == "pack_ship_4" && $price == "10") {
            $newVal = $userShips["peacemaker"] + 40; // value
            $sql = mysqli_query($conn, "UPDATE userfleet SET peacemaker=$newVal WHERE userID=$userID");
            if ($sql !== FALSE) {
              $token = bookItems($email, $username, $itemArray, $item, $date, $status, $verifySign, $currency, $price, $userID, "40 Peacemakers");
              $sql = "INSERT INTO paymentLogs (userID, paymentID, paymentVerify, paymentDate, paymentItem, paymentCurrency, paymentPrice, paymentEmail, paymentStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
              $stmt = mysqli_stmt_init($conn);
              mysqli_stmt_prepare($stmt, $sql);
              mysqli_stmt_bind_param($stmt, "isssssiss", $userID, $token, $verifySign, $date, $item, $currency, $price, $email, $status);
              mysqli_stmt_execute($stmt);
              exit();
          }
        }
        else if ($item == "pack_ship_5" && $price == "10") {
            $newVal = $userShips["centurion"] + 40; // value
            $sql = mysqli_query($conn, "UPDATE userfleet SET centurion=$newVal WHERE userID=$userID");
            if ($sql !== FALSE) {
              $token = bookItems($email, $username, $itemArray, $item, $date, $status, $verifySign, $currency, $price, $userID, "40 Centurions");
              $sql = "INSERT INTO paymentLogs (userID, paymentID, paymentVerify, paymentDate, paymentItem, paymentCurrency, paymentPrice, paymentEmail, paymentStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
              $stmt = mysqli_stmt_init($conn);
              mysqli_stmt_prepare($stmt, $sql);
              mysqli_stmt_bind_param($stmt, "isssssiss", $userID, $token, $verifySign, $date, $item, $currency, $price, $email, $status);
              mysqli_stmt_execute($stmt);
              exit();
          }
        }
        else if ($item == "pack_bundle_1" && $price == "5") {
            $newVal = $userShips["hornet"] + 60; // value
            $newFuel = $userShips["fuel"] + 10000;
            $newCredits = $userInfo["credits"] + 400000;
            $newHyperid = $userInfo["hyperid"] + 80000;
            $sql = mysqli_query($conn, "UPDATE userfleet SET hornet=$newVal, fuel=$newFuel WHERE userID=$userID");
            $sql1 = mysqli_query($conn, "UPDATE users SET credits=$newCredits, hyperid=$newHyperid WHERE userID=$userID");
            if ($sql !== FALSE && $sql1 !== FALSE) {
              $token = bookItems($email, $username, $itemArray, $item, $date, $status, $verifySign, $currency, $price, $userID, "60 Hornets, 10 000 units of fuel, 400 000 credits and 80 000 hyperids");
              $sql = "INSERT INTO paymentLogs (userID, paymentID, paymentVerify, paymentDate, paymentItem, paymentCurrency, paymentPrice, paymentEmail, paymentStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
              $stmt = mysqli_stmt_init($conn);
              mysqli_stmt_prepare($stmt, $sql);
              mysqli_stmt_bind_param($stmt, "isssssiss", $userID, $token, $verifySign, $date, $item, $currency, $price, $email, $status);
              mysqli_stmt_execute($stmt);
              exit();
          }
        }
        else if ($item == "pack_bundle_2" && $price == "15") {
            $newVal = $userShips["hornet"] + 100; // value
            $newVal2 = $userShips["spacefire"] + 100;
            $newVal3 = $userShips["starhawk"] + 20;
            $sql = mysqli_query($conn, "UPDATE userfleet SET hornet=$newVal, spacefire=$newVal2, starhawk=$newVal3 WHERE userID=$userID");
            if ($sql !== FALSE) {
              $token = bookItems($email, $username, $itemArray, $item, $date, $status, $verifySign, $currency, $price, $userID, "100 Hornets, 100 Spacefires and 20 Starhawks");
              $sql = "INSERT INTO paymentLogs (userID, paymentID, paymentVerify, paymentDate, paymentItem, paymentCurrency, paymentPrice, paymentEmail, paymentStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
              $stmt = mysqli_stmt_init($conn);
              mysqli_stmt_prepare($stmt, $sql);
              mysqli_stmt_bind_param($stmt, "isssssiss", $userID, $token, $verifySign, $date, $item, $currency, $price, $email, $status);
              mysqli_stmt_execute($stmt);
              exit();
          }
        }
        else if ($item == "pack_bundle_3" && $price == "30") {
            $newVal = $userInfo["credits"] + 2000000; // value
            $newVal2 = $userShips["peacemaker"] + 50;
            $newVal3 = $userShips["starhawk"] + 40;
            $sql1 = mysqli_query($conn, "UPDATE users SET credits=$newVal WHERE userID=$userID");
            $sql = mysqli_query($conn, "UPDATE userfleet SET peacemaker=$newVal2, starhawk=$newVal3, starhawk=$newVal3 WHERE userID=$userID");
            if ($sql !== FALSE) {
              $token = bookItems($email, $username, $itemArray, $item, $date, $status, $verifySign, $currency, $price, $userID, "2 000 000 Credits, 50 Peacemakers and 40 Starhawks");
              $sql = "INSERT INTO paymentLogs (userID, paymentID, paymentVerify, paymentDate, paymentItem, paymentCurrency, paymentPrice, paymentEmail, paymentStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
              $stmt = mysqli_stmt_init($conn);
              mysqli_stmt_prepare($stmt, $sql);
              mysqli_stmt_bind_param($stmt, "isssssiss", $userID, $token, $verifySign, $date, $item, $currency, $price, $email, $status);
              mysqli_stmt_execute($stmt);
              exit();
          }
        }
        else if ($item == "pack_bundle_4" && $price == "30") {
            $newVal = $userInfo["credits"] + 7000000; // value
            $newVal2 = $userInfo["hyperid"] + 1500000;
            $sql1 = mysqli_query($conn, "UPDATE users SET credits=$newVal, hyperid=$newVal2 WHERE userID=$userID");
            if ($sql !== FALSE) {
              $token = bookItems($email, $username, $itemArray, $item, $date, $status, $verifySign, $currency, $price, $userID, "7 000 000 credits and 1 500 000 hyperids");
              $sql = "INSERT INTO paymentLogs (userID, paymentID, paymentVerify, paymentDate, paymentItem, paymentCurrency, paymentPrice, paymentEmail, paymentStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
              $stmt = mysqli_stmt_init($conn);
              mysqli_stmt_prepare($stmt, $sql);
              mysqli_stmt_bind_param($stmt, "isssssiss", $userID, $token, $verifySign, $date, $item, $currency, $price, $email, $status);
              mysqli_stmt_execute($stmt);
              exit();
          }
        }



      } else {
        $token = bin2hex(random_bytes(10));
        $subject = 'Issue with payment #'.$token.'';

        $messageSupport = "<h2>Hey Dew , an issue with payment has been encountered (Payment has been verified), here are the details: </h2>";
        foreach ($_POST as $key => $value) {
          $messageSupport .= "$key => $value";
        }
        // ↓ for user
        $message = "<h2>Hello ".$username."</h2>";
        $message .= "<p>Unfortunately we have encountered an issue with your payment (ID #".$token.").";
        $message .= "<p>Please save this e-mail , along with paypal confirmation (if the payment has been completed) and contact our support via our form <a href=\"https://www.spacesabres.com/support/index.php?problem=payment\">here!</a> There we will hopefully be able to resolve this issue together :).</p>";
        $message .= "<hr><p>Payment ID: ".$token.", Payment date: ".$date.", Payment status: ".$status."</p>";

        $header = "From: Spacesabres <noreply@spacesabres.com>\r\n";
        $header .= "Content-type: text/html \r\n";
        mail("support.spacesabres@spacesabres.com", $subject, $messageSupport, $header);
        mail($email, $subject, $message, $header);
      }
      } else {
        $token = bin2hex(random_bytes(10));
        $subject = 'Issue with payment #'.$token.'';

        $messageSupport = "<h2>Hey Dew , an issue with payment has been encountered (payment might not be verified, or user has manipulated with userID), here are the details: </h2>";
        foreach ($_POST as $key => $value) {
          $messageSupport .= "$key => $value";
        }
        // ↓ for user
        $message = "<h2>Hello ".$username."</h2>";
        $message .= "<p>Unfortunately we have encountered an issue with your payment (ID #".$token.").";
        $message .= "<p>Please save this e-mail , along with paypal confirmation (if the payment has been completed) and contact our support via our form <a href=\"https://www.spacesabres.com/support/index.php?problem=payment\">here!</a> There we will hopefully be able to resolve this issue together :).</p>";

        $header = "From: Spacesabres <noreply@spacesabres.com>\r\n";
        $header .= "Content-type: text/html \r\n";
        mail("support.spacesabres@spacesabres.com", $subject, $messageSupport, $header);
        mail($email, $subject, $message, $header);
      }
 ?>
