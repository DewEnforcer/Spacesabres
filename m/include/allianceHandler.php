<?php
session_start();
if (isset($_SESSION["sid"])) {
  require "dbh.inc.php";
  $session = $_SESSION["sid"];
  $sql = mysqli_query($conn, "SELECT userID, userclan, credits, ingameNick FROM users WHERE sessionID='$session'");
  if (mysqli_num_rows($sql)>0) {
    $userInfo = mysqli_fetch_assoc($sql);
    // takes array from sql fetch, decompiles it for use
    // always select info in order -clanid, members, permissions rest is optionable, log, apps, appDesc, diplo, diploReq, diploPend, totalMembers
    //                                  0     1         2
    function decompileInfo(&$array, $index, $section) {
      $arrayDecompiled = [];
      $i = 0;
      foreach ($array as $key) {
        if (strpos($key, "{") === FALSE) {
          array_push($arrayDecompiled, $key);
        } else {
          $unesrialized = unserialize($key);
          array_push($arrayDecompiled, $unesrialized);
        }
        if ($i == 2) {
          checkPermission($index, $unesrialized, $section);
        }
        $i++;
      }
      return $arrayDecompiled;
    }
    function checkPermission ($index, &$permissionsArray, $section) {
      if ($index != "none" && $section != "none") {
        if ($permissionsArray[0][$index] != 1) {
          $_SESSION["result_ally"] = "noperms";
          header("location: ../internal$section.php");
          exit();
        }
      }
    }
    // takes modified decompiled array, and compiles it back , ready for sql
    function compileInfo(&$array) {
      $arrayCompiled = [];
      foreach ($array as $key) {
        if (is_array($key) === TRUE) {
          $serialized = serialize($key);
          array_push($arrayCompiled, $serialized);
        } else {
          array_push($arrayCompiled, $key);
        }
      }
      return $arrayCompiled;
    }

    if (isset($_POST["btn_submit_app"]) && isset($_POST["user_apply_text"]) && isset($_POST["ally_name"])) {
      if ($userInfo["userclan"] == "none") {

        if ($_POST["ally_name"] == "") {
          $_SESSION["result_ally"] = "empty";
          header("location: ../internalJoinally.php");
          exit();
        }

        $sql = "SELECT clanID, clanMembers, clanMembersPerms, clanLog, clanApps, clanAppDesc FROM userclans WHERE clanName=?";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "s", $_POST["ally_name"]);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $clanInfo = mysqli_fetch_assoc($result);

        if ($clanInfo["clanID"] > 0) {
          if ($_POST["user_apply_text"] == "") {
            $_POST["user_apply_text"] == "Commander hasn't submitted any additional information.";
          }
        } else {
          header("location: ../internalAlliances.php");
          exit();
        }
        $decompiledParams = decompileInfo($clanInfo, "none", "none");
          if (count($decompiledParams[2]) >= 50) {
            $_SESSION["result_ally"] = "allyfull";
            header("location: ../internalJoinally.php");
            exit();
          }
          if (array_search($userInfo["userID"], $decompiledParams[4]) > -1 ) {
            $_SESSION["result_ally"] = "alreadysent";
            header("location: ../internalJoinally.php?success");
            exit();
          }
          $text = htmlspecialchars($_POST["user_apply_text"]);
          array_push($decompiledParams[4], $userInfo["userID"]);
          array_push($decompiledParams[5], $text);
          $compiledParams = compileInfo($decompiledParams);

          $sql = "UPDATE userclans SET clanApps=?, clanAppDesc=? WHERE clanID=?";
          $stmt = mysqli_stmt_init($conn);
          mysqli_stmt_prepare($stmt, $sql);
          mysqli_stmt_bind_param($stmt, "ssi", $compiledParams[4], $compiledParams[5], $decompiledParams[0]);
          mysqli_stmt_execute($stmt);

          $_SESSION["result_ally"] = "appsent";
          header("location: ../internalJoinally.php");
          exit();
        } else {
          $_SESSION["result_ally"] = "nofound";
          header("location: ../internalJoinally.php");
          exit();
        }
    } elseif (isset($_POST["btn_create_ally"]) && isset($_POST["ally_create_desc"]) && isset($_POST["ally_tag_create"]) && isset($_POST["ally_name_create"])) {
      if ($userInfo["userclan"] == "none") {
        $name = $_POST["ally_name_create"];
        $tag = $_POST["ally_tag_create"];
        $desc = $_POST["ally_create_desc"];

        if (empty($name) != FALSE || empty($tag) != FALSE || empty($desc) != FALSE) {
          $_SESSION["result_ally"] = "missingparam";
          header("location: ../internalJoinally.php");
          exit();
        }

        if (strlen($tag)>5) {
          $_SESSION["result_ally"] = "longtag";
          header("location: ../internalJoinally.php");
          exit();
        }
        if (strlen($tag)<3) {
          $_SESSION["result_ally"] = "shorttag";
          header("location: ../internalJoinally.php");
          exit();
        }
        if (strlen($tag)>20) {
          $_SESSION["result_ally"] = "longname";
          header("location: ../internalJoinally.php");
          exit();
        }
        if (strlen($name)<4) {
          $_SESSION["result_ally"] = "shortname";
          header("location: ../internalJoinally.php");
          exit();
        }
        if (strlen($tag)>500) {
          $_SESSION["result_ally"] = "longdesc";
          header("location: ../internalJoinally.php");
          exit();
        }
        if ($userInfo["credits"] < 750000) {
          $_SESSION["result_ally"] = "credits";
          header("location: ../internalJoinally.php");
          exit();
        }
        $newCredits = $userInfo["credits"]-750000;

        $sql = "SELECT clanID FROM userclans WHERE clanTag=? OR clanName=?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          $_SESSION["result_ally"] = "sql";
          header("location: ../internalJoinally.php");
          exit();
        }
        mysqli_stmt_bind_param($stmt, "ss", $tag, $name);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $clanCheck = mysqli_fetch_assoc($result);

        if ($clanCheck["userID"] <= 0) {
          $arrayMembers = [$userInfo["userID"]];
          $arrayPerms = [[1,1,1,1,1,1,1,1,5]];

          $newMembers = serialize($arrayMembers);
          $newPerms = serialize($arrayPerms);

          function checkIds($conn, $randID) {
            $sql = "SELECT * FROM users";
            $result = mysqli_query($conn, $sql);

            while($row = mysqli_fetch_assoc($result)) {
              if ($row['userID'] == $randID) {
                $idExists = true;
                break;
              } else {
                $idExists = false;
              }
            }

            return $idExists;
          }

          function generateID($conn) {
            $keyLength = 12;
            $str = "1234567890";
            $randID = substr(str_shuffle($str), 0 , $keyLength);
            $checkId = checkIds($conn, $randID);

            while ($checkId == true) {
              $randID = substr(str_shuffle($str), 0 , $keyLength);
              $checkId = checkIds($conn, $randID);
            }

            return $randID;
          }
          $date = date("U");
          $clanID = generateID($conn);
          $sql = "INSERT INTO userclans (clanID, clanName, clanTag, clanLeader, clanDetail, clanMembers, clanMembersPerms, createDate) VALUES (?,?,?,?,?,?,?,?)";
          $stmt = mysqli_stmt_init($conn);
          if (!mysqli_stmt_prepare($stmt, $sql)) {
            $_SESSION["result_ally"] = "sql";
            header("location: ../internalJoinally.php");
            exit();
          }
          mysqli_stmt_bind_param($stmt, "issssssi", $clanID, $name, $tag, $userInfo["ingameNick"], $desc, $newMembers, $newPerms, $date);
          mysqli_stmt_execute($stmt);

          $sql = "UPDATE users SET credits=?, userclan=? WHERE userID=$userInfo[userID]";
          $stmt = mysqli_stmt_init($conn);
          if (!mysqli_stmt_prepare($stmt, $sql)) {
            $_SESSION["result_ally"] = "sql";
            header("location: ../internalJoinally.php");
            exit();
          }
          mysqli_stmt_bind_param($stmt, "is", $newCredits, $tag);
          mysqli_stmt_execute($stmt);

          $_SESSION["result_ally"] = "successCreate";
          header("location: ../internalAlliance.php"); //  updatnout na stránku ali once done
          exit();
        } else {
          $_SESSION["result_ally"] = "exists";
          header("location: ../internalJoinally.php");
          exit();
        }
      } else {
        $_SESSION["result_ally"] = "inally";
        header("location: ../internalAlliance.php"); //  updatnout na stránku ali once done
        exit();
      }
    } elseif (isset($_GET["action"]) && $_GET["action"] == "accept" && isset($_GET["index"])) {
      $index = $_GET["index"];

      if ($userInfo["userclan"] == "none") {
        $_SESSION["result_ally"] = "noally";
        header("location: ../internalAlliances.php");
        exit();
      }

      $sql = mysqli_query($conn, "SELECT clanID, clanMembers, clanMembersPerms, clanLog, clanApps, clanAppDesc, clanID, clanTag, totalMembers FROM userclans WHERE clanTag='$userInfo[userclan]'");
      $clanInfo = mysqli_fetch_assoc($sql);

      $decompiledParams = decompileInfo($clanInfo, 5, "AllianceMembers");
      $totalMembers = count($decompiledParams[1]);
      if ($totalMembers >= 50) {
        $_SESSION["result_ally"] = "maxmembers";
        header("location: ../internalAllianceMembers.php");
        exit();
      }

      $appUserID= $decompiledParams[4][$index];
      $sql = mysqli_query($conn, "SELECT userclan, ingameNick FROM users WHERE userID=$appUserID");
      if (mysqli_num_rows($sql) <= 0) {
        array_splice($applicationsDesc, $index, 1);
        array_splice($applications, $index, 1);

        $serializedMembers = serialize($membersList);
        $serializedDescs = serialize($applicationsDesc);

        $sql = mysqli_query($conn, "UPDATE userclans SET clanApps='$serializedApps', clanAppDesc='$serializedDescs' WHERE clanID=$clanInfo[clanID]");
        $_SESSION["result_ally"] = "usernotexist";
        header("location: ../internalAllianceMembers.php");
        exit();
      }

      $appUserClan = mysqli_fetch_assoc($sql);
      if ($appUserClan["userclan"] != "none") {
        array_splice($applicationsDesc, $index, 1);
        array_splice($applications, $index, 1);

        $serializedMembers = serialize($membersList);
        $serializedDescs = serialize($applicationsDesc);

        $sql = mysqli_query($conn, "UPDATE userclans SET clanApps='$serializedApps', clanAppDesc='$serializedDescs' WHERE clanID=$clanInfo[clanID]");
        $_SESSION["result_ally"] = "alreadyjoined";
        header("location: ../internalAllianceMembers.php");
        exit();
      }
      $newPerms = [0,0,0,0,0,0,0,0,1];
      $logText = "<div>Commander $appUserClan[ingameNick] has joined the alliance. (".date("d.m.Y").")</div>";
      array_push($decompiledParams[1], $appUserID);
      array_splice($decompiledParams[5], $index, 1);
      array_splice($decompiledParams[4], $index, 1);
      array_push($decompiledParams[2], $newPerms);
      array_unshift($decompiledParams[3], $logText);
      $newTotalMembers = $clanInfo["totalMembers"]+1;
      $compiledParams = compileInfo($decompiledParams);

      $sql = mysqli_query($conn, "UPDATE userclans SET clanMembers='$compiledParams[1]', clanMembersPerms='$compiledParams[2]', clanApps='$compiledParams[4]', clanAppDesc='$compiledParams[5]', totalMembers=$newTotalMembers, clanLog='$compiledParams[3]' WHERE clanID=$clanInfo[clanID]");
      $sql1 = mysqli_query($conn, "UPDATE users SET userclan='$clanInfo[clanTag]' WHERE userID=$appUserID");
      if ($sql !== FALSE && $sql1 !== FALSE) {
        $_SESSION["result_ally"] = "accepted";
        header("location: ../internalAllianceMembers.php");
        exit();
      }

    } elseif (isset($_GET["action"]) && $_GET["action"] == "kick" && isset($_GET["index"])) {
      $index = $_GET["index"];

      if ($userInfo["userclan"] == "none") {
        $_SESSION["result_ally"] = "noally";
        header("location: ../internalAlliances.php");
        exit();
      }

      $sql = mysqli_query($conn, "SELECT clanMembers, clanMembersPerms, clanLog, clanID, clanTag FROM userclans WHERE clanTag='$userInfo[userclan]'");
      $clanInfo = mysqli_fetch_assoc($sql);

      $membersList = unserialize($clanInfo["clanMembers"]);
      $permissions = unserialize($clanInfo["clanMembersPerms"]);
      $logs = unserialize($clanInfo["clanLog"]);
      $getPermissionUser = array_search($userInfo["userID"], $membersList);
      $permissionUser = $permissions[$getPermissionUser];
      $permissionKickedUser = $permissions[$index];

      if ($permissionUser[0] == 0) {
        $_SESSION["result_ally"] = "noperms";
        header("location: ../internalAllianceMembers.php");
        exit();
      }

      if ($permissionUser[8] <= $permissionKickedUser[8]) {
        $_SESSION["result_ally"] = "allyRank";
        header("location: ../internalAllianceMembers.php");
        exit();
      }

      $totalMembers = count($membersList);

      $appUserID= $membersList[$index];
      $sql = mysqli_query($conn, "SELECT ingameNick FROM users WHERE userID=$appUserID");
      $kickedUserNick = mysqli_fetch_assoc($sql);

      $logText = "<div>Commander $kickedUserNick[ingameNick] has been kicked from the alliance by $userInfo[ingameNick]. (".date("d.m.Y").")</div>";
      array_splice($membersList, $index);
      array_splice($permissions, $index);
      array_unshift($logs, $logText);

      $serializedMembers = serialize($membersList);
      $serializedPerms = serialize($permissions);
      $newTotalMembers = $totalMembers-1;
      $serializedLogs = serialize($logs);
      
      $sql = mysqli_query($conn, "UPDATE userclans SET clanMembers='$serializedMembers', clanMembersPerms='$serializedPerms', totalMembers=$newTotalMembers, clanLog='$serializedLogs' WHERE clanID=$clanInfo[clanID]");
      $sql1 = mysqli_query($conn, "UPDATE users SET userclan='none' WHERE userID=$appUserID");
      if ($sql !== FALSE && $sql1 !== FALSE) {
        $_SESSION["result_ally"] = "kicked";
        header("location: ../internalAllianceMembers.php");
        exit();
      }



    } elseif (isset($_POST["btn_submit_rank_change"]) && isset($_POST["index_rank"]) && isset($_POST["rank_selection"])) {
      if ($userInfo["userclan"] == "none") {
        $_SESSION["result_ally"] = "noally";
        header("location: ../internalAlliances.php");
        exit();
      }

      $index = $_POST["index_rank"];
      $rank = $_POST["rank_selection"];

      $sql = mysqli_query($conn, "SELECT clanMembers, clanMembersPerms FROM userclans WHERE clanTag='$userInfo[userclan]'");
      $clanInfo = mysqli_fetch_assoc($sql);

      $membersList = unserialize($clanInfo["clanMembers"]);
      $permissions = unserialize($clanInfo["clanMembersPerms"]);
      $getPermissionUser = array_search($userInfo["userID"], $membersList);
      $permissionUser = $permissions[$getPermissionUser];
      if ($permissionUser[8] != 5) {
        $_SESSION["result_ally"] = "notleader";
        header("location: ../internalAllianceMembers.php");
        exit();
      }
      switch ($rank) {
        case '1':
          $newPermsArray = [0,0,0,0,0,0,0,0,1];
        break;
        case '2':
          $newPermsArray = [1,1,0,0,0,0,1,0,2];
        break;
        case '3':
          $newPermsArray = [1,1,1,0,0,1,1,0,3];
        break;
        case '4':
          $newPermsArray = [1,1,1,1,1,1,1,0,4];
        break;
      }

      $changeRankUserID = $membersList[$index];

      $permissions[$index] = $newPermsArray;

      $serializedPermissions = serialize($permissions);
      $sql = "UPDATE userclans SET clanMembersPerms=? WHERE clanTag=?";
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "ss", $serializedPermissions, $userInfo["userclan"]);
      mysqli_stmt_execute($stmt);

        $_SESSION["result_ally"] = "success";
        header("location: ../internalAllianceMembers.php");
        exit();

    } elseif (isset($_GET["action"]) && $_GET["action"] == "disband") {
      if ($userInfo["userclan"] == "none") {
        $_SESSION["result_ally"] = "noally";
        header("location: ../internalAlliances.php");
        exit();
      }

      $sql = mysqli_query($conn, "SELECT clanMembers, clanMembersPerms, clanID FROM userclans WHERE clanTag='$userInfo[userclan]'");
      $clanInfo = mysqli_fetch_assoc($sql);

      $membersList = unserialize($clanInfo["clanMembers"]);
      $permissions = unserialize($clanInfo["clanMembersPerms"]);
      $getPermissionUser = array_search($userInfo["userID"], $membersList);
      $permissionUser = $permissions[$getPermissionUser];
      if ($permissionUser[8] != 5) {
        $_SESSION["result_ally"] = "notleader";
        header("location: ../internalAllianceMembers.php");
        exit();
      }

      foreach ($membersList as $key) {
        $sql = mysqli_query($conn, "UPDATE users SET userclan='none' WHERE userID=$key");
      }

      $sql = mysqli_query($conn, "DELETE FROM userclans WHERE clanID=$clanInfo[clanID]");
      if ($sql !== FALSE) {
        $_SESSION["result_ally"] = "successDisband";
        header("location: ../internalAlliances.php");
        exit();
      }
    } elseif (isset($_POST["btn_submit_give_leader"]) && isset($_POST["user_selection"])) {
      if ($userInfo["userclan"] == "none") {
        $_SESSION["give_leader"] = "noally";
        header("location: ../internalAlliances.php");
        exit();
      }
      $index = $_POST["user_selection"]-1;
      $sql = mysqli_query($conn, "SELECT clanMembers, clanMembersPerms, clanLog FROM userclans WHERE clanTag='$userInfo[userclan]'");
      $clanInfo = mysqli_fetch_assoc($sql);

      $membersList = unserialize($clanInfo["clanMembers"]);
      $permissions = unserialize($clanInfo["clanMembersPerms"]);
      $logs = unserialize($clanInfo["clanLog"]);
      $getPermissionUser = array_search($userInfo["userID"], $membersList);
      $permissionUser = $permissions[$getPermissionUser];
      $newLeaderID = $membersList[$index];
      if ($newLeaderID == $userInfo["userID"]) {
        $_SESSION["give_leader"] = "sameuser";
        header("location: ../internalAllianceMembers.php");
        exit();
      }
      $sql = mysqli_query($conn, "SELECT ingameNick FROM users WHERE userID=$newLeaderID");
      $newLeaderNick = mysqli_fetch_assoc($sql);
      if ($permissionUser[8] != 5) {
        $_SESSION["give_leader"] = "noleader";
        header("location: ../internalAllianceMembers.php");
        exit();
      }
      $logText = "<div>Commander $userInfo[ingameNick] has given up his leadership to $newLeaderNick[ingameNick]. (".date("d.m.Y").")</div>";
      $permissions[$getPermissionUser] = [1,1,1,1,1,1,1,0,4];
      $permissions[$index] = [1,1,1,1,1,1,1,1,5];
      array_unshift($logs, $logText);
      $serializedLogs = serialize($logs);
      $serializedMembers = serialize($membersList);
      $serializedPerms = serialize($permissions);

      $sql = mysqli_query($conn, "UPDATE userclans SET clanMembersPerms='$serializedPerms', clanMembers='$serializedMembers', clanLog='$serializedLogs' WHERE clanTag='$userInfo[userclan]'");
      if ($sql !== FALSE) {
        $_SESSION["give_leader"] = "successGiveup";
        header("location: ../internalAllianceMembers.php");
        exit();
      }
    } elseif (isset($_GET["action"]) && $_GET["action"] == "leave" ) {
      if ($userInfo["userclan"] == "none") {
        $_SESSION["result_ally"] = "noally";
        header("location: ../internalAlliances.php");
        exit();
      }
      $sql = mysqli_query($conn, "SELECT clanMembers, clanMembersPerms, clanLog, totalMembers FROM userclans WHERE clanTag='$userInfo[userclan]'");
      $clanInfo = mysqli_fetch_assoc($sql);

      $membersList = unserialize($clanInfo["clanMembers"]);
      $logs = unserialize($clanInfo["clanLog"]);
      $permissions = unserialize($clanInfo["clanMembersPerms"]);
      $getPermissionUser = array_search($userInfo["userID"], $membersList);

      array_splice($membersList, $getPermissionUser, 1);
      array_splice($permissions, $getPermissionUser, 1);
      $totalMembers = $clanInfo["totalMembers"]-1;
      $newLog = "<div>$userInfo[ingameNick] has left the alliance. (".date("d.m.Y").")</div>";
      array_unshift($logs, $newLog);

      $serializedLogs = serialize($logs);
      $serializedMembers = serialize($membersList);
      $serializedPerms = serialize($permissions);

      $sql = mysqli_query($conn, "UPDATE userclans SET clanMembersPerms='$serializedPerms', clanMembers='$serializedMembers', clanLog='$serializedLogs', totalMembers=$totalMembers WHERE clanTag='$userInfo[userclan]'");
      $sql1 = mysqli_query($conn, "UPDATE users SET userclan='none' WHERE userID=$userInfo[userID]");
      if ($sql !== FALSE) {
        $_SESSION["result_ally"] = "successLeave";
        header("location: ../internalAlliances.php");
        exit();
      }
    } elseif (isset($_POST["action"]) && $_POST["action"] == "show_app" && isset($_POST["index"])) {
      if ($userInfo["userclan"] == "none") {
        $_SESSION["result_ally"] = "noally";
        header("location: ../internalAlliances.php");
        exit();
      }
      $index = $_POST["index"];
      $sql = mysqli_query($conn, "SELECT clanMembers, clanMembersPerms, clanAppDesc FROM userclans WHERE clanTag='$userInfo[userclan]'");
      $clanInfo = mysqli_fetch_assoc($sql);

      $membersList = unserialize($clanInfo["clanMembers"]);
      $permissions = unserialize($clanInfo["clanMembersPerms"]);
      $applicationsDesc = unserialize($clanInfo["clanAppDesc"]);
      $getPermissionUser = array_search($userInfo["userID"], $membersList);
      $userPermissions = $permissions[$getPermissionUser];
      if ($userPermissions[5] != 1) {
        echo "noperms";
        exit();
      }

      echo $applicationsDesc[$index];
      exit();
    } elseif (isset($_GET["action"]) && $_GET["action"] == "decline" && isset($_GET["index"])) {
      if ($userInfo["userclan"] == "none") {
        $_SESSION["result_ally"] = "noally";
        header("location: ../internalAlliances.php");
        exit();
      }
      $index = $_GET["index"];
      $sql = mysqli_query($conn, "SELECT clanMembers, clanMembersPerms, clanApps, clanAppDesc, clanID FROM userclans WHERE clanTag='$userInfo[userclan]'");
      $clanInfo = mysqli_fetch_assoc($sql);

      $membersList = unserialize($clanInfo["clanMembers"]);
      $applications = unserialize($clanInfo["clanApps"]);
      $applicationsDesc = unserialize($clanInfo["clanAppDesc"]);
      $permissions = unserialize($clanInfo["clanMembersPerms"]);
      $getPermissionUser = array_search($userInfo["userID"], $membersList);
      $permissionUser = $permissions[$getPermissionUser];

      if ($permissionUser[5] == 0) {
        $_SESSION["result_ally"] = "noperms";
        header("location: ../internalAllianceMembers.php");
        exit();
      }

      array_splice($applications, $index, 1);
      array_splice($applicationsDesc, $index, 1);

      $serializedApps = serialize($applications);
      $serializedDescs = serialize($applicationsDesc);
      $sql = mysqli_query($conn, "UPDATE userclans SET clanApps='$serializedApps', clanAppDesc='$serializedDescs' WHERE clanID=$clanInfo[clanID]");
      if ($sql !== FALSE) {
        $_SESSION["result_ally"] = "successDeclinedApp";
        header("location: ../internalAllianceMembers.php");
        exit();
      }

    } elseif (isset($_POST["btn_submit_diplo"]) && isset($_POST["ally_name_diplo"]) && isset($_POST["diplo_type"])) {
      if ($userInfo["userclan"] == "none") {
        $_SESSION["result_ally"] = "noally";
        header("location: ../internalAlliances.php");
        exit();
      }
      $allyName = $_POST["ally_name_diplo"];
      $sql = mysqli_query($conn, "SELECT clanMembers, clanMembersPerms, clanDiplo, clanDiploPending, clanID FROM userclans WHERE clanTag='$userInfo[userclan]'");
      $clanInfo = mysqli_fetch_assoc($sql);

      $membersList = unserialize($clanInfo["clanMembers"]);
      $permissions = unserialize($clanInfo["clanMembersPerms"]);
      $getPermissionUser = array_search($userInfo["userID"], $membersList);
      $diplomacy = unserialize($clanInfo["clanDiplo"]);
      $permissionUser = $permissions[$getPermissionUser];
      $diplomacyPending = unserialize($clanInfo["clanDiploPending"]);

      if ($permissionUser[1] != 1) {
        $_SESSION["result_ally"] = "noperms";
        header("location: ../internalAllianceDiplo.php");
        exit();
      }

      $sql = "SELECT clanDiplo, clanDiploReq,  clanID FROM userclans WHERE clanName=?";
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "s", $allyName);
      mysqli_stmt_execute($stmt);

      $result = mysqli_stmt_get_result($stmt);
      $allyDiplo = mysqli_fetch_assoc($result);

      if ($allyDiplo["clanID"] < 1) {
        $_SESSION["result_ally"] = "allynotfound";
        header("location: ../internalAllianceDiplo.php");
        exit();
      }

      $diplomacyOthers = unserialize($allyDiplo["clanDiplo"]);
      $diplomacyReqOthers = unserialize($allyDiplo["clanDiploReq"]);

      foreach ($diplomacyOthers as $key) {
        if (array_search($clanInfo["clanID"], $key) > -1) {
          $_SESSION["result_ally"] = "alreadyrelation";
          header("location: ../internalAllianceDiplo.php");
          exit();
        }
      }
      foreach ($diplomacyReqOthers as $key) {
        if (array_search($clanInfo["clanID"], $key) > -1) {
          $_SESSION["result_ally"] = "alreadypending";
          header("location: ../internalAllianceDiplo.php");
          exit();
        }
      }

      array_push($diplomacyPending, [$allyDiplo["clanID"], $_POST["diplo_type"]]);
      array_push($diplomacyReqOthers, [$clanInfo["clanID"], $_POST["diplo_type"]]);
      $serializedDiploReq = serialize($diplomacyReqOthers);
      $serializedPendings = serialize($diplomacyPending);

      $sql = "UPDATE userclans SET clanDiploReq=? WHERE clanID=?";
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "si", $serializedDiploReq, $allyDiplo["clanID"]);
      mysqli_stmt_execute($stmt);

      $sql = "UPDATE userclans SET clanDiploPending=? WHERE clanID=?";
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "si", $serializedPendings, $clanInfo["clanID"]);
      mysqli_stmt_execute($stmt);

      $_SESSION["result_ally"] = "successSentDiplo";
      header("location: ../internalAllianceDiplo.php");
      exit();
    } elseif (isset($_GET["action"]) && $_GET["action"] == "cancelreq" && isset($_GET["index"])) {
      if ($userInfo["userclan"] == "none") {
        $_SESSION["result_ally"] = "noally";
        header("location: ../internalAlliances.php");
        exit();
      }

      $sql = mysqli_query($conn, "SELECT clanMembers, clanMembersPerms, clanDiploPending, clanID FROM userclans WHERE clanTag='$userInfo[userclan]'");
      $clanInfo = mysqli_fetch_assoc($sql);
      $index = $_GET["index"];

      $membersList = unserialize($clanInfo["clanMembers"]);
      $permissions = unserialize($clanInfo["clanMembersPerms"]);
      $getPermissionUser = array_search($userInfo["userID"], $membersList);
      $permissionUser = $permissions[$getPermissionUser];
      $diplomacyPending = unserialize($clanInfo["clanDiploPending"]);

      if ($permissionUser[3] != 1) {
        $_SESSION["result_ally"] = "noperms";
        header("location: ../internalAllianceDiplo.php");
        exit();
      }

      $allyID = $diplomacyPending[$index][0];

      $sql = mysqli_query($conn, "SELECT clanDiploReq FROM userclans WHERE clanID=$allyID");
      $allyOtherID = mysqli_fetch_assoc($sql);

      if (mysqli_num_rows($sql) == 0) {
        array_splice($diplomacyPending, $index, 1);
        $serializedPendings = serialize($diplomacyPending);

        $sql = mysqli_query($conn, "UPDATE userclans SET clanDiploPending='$serializedPendings' WHERE clanID=$clanInfo[userID]");
        if ($sql !== FALSE) {
          $_SESSION["result_ally"] = "allynotexist";
          header("location: ../internalAllianceDiplo.php");
          exit();
        }
      }

      $diplomacyReqOthers = unserialize($allyOtherID["clanDiploReq"]);
      foreach ($diplomacyReqOthers as $key) {
        $key = array_search($clanInfo["clanID"], $key);
        if ($key > -1) {
          break;
        }
      }

      if (array_key_exists($index, $diplomacyReqOthers) == FALSE) {
        $_SESSION["result_ally"] = "wrongindex";
        header("location: ../internalAllianceDiplo.php");
        exit();
      }

      array_splice($diplomacyPending, $index, 1);
      $serializedPendings = serialize($diplomacyPending);
      array_splice($diplomacyReqOthers, $key, 1);
      $serializedReqsOthers = serialize($diplomacyReqOthers);

      $sql = mysqli_query($conn, "UPDATE userclans SET clanDiploPending='$serializedPendings' WHERE clanID=$clanInfo[clanID]");
      $sql1 = mysqli_query($conn, "UPDATE userclans SET clanDiploReq='$serializedReqsOthers' WHERE clanID=$allyID");

      if ($sql !== FALSE && $sql1 !== FALSE) {
        $_SESSION["result_ally"] = "successDeleteReq";
        header("location: ../internalAllianceDiplo.php");
        exit();
      }

    } elseif (isset($_GET["action"]) && $_GET["action"] == "acceptDiplo" && isset($_GET["index"])) {
      $index = $_GET["index"];

      if ($userInfo["userclan"] == "none") {
        $_SESSION["result_ally"] = "noally";
        header("location: ../internalAlliances.php");
        exit();
      }

      $sql = mysqli_query($conn, "SELECT clanMembers, clanMembersPerms, clanDiploReq, clanDiplo, clanID FROM userclans WHERE clanTag='$userInfo[userclan]'");
      $clanInfo = mysqli_fetch_assoc($sql);
      $membersList = unserialize($clanInfo["clanMembers"]);
      $permissions = unserialize($clanInfo["clanMembersPerms"]);
      $getPermissionUser = array_search($userInfo["userID"], $membersList);
      $permissionUser = $permissions[$getPermissionUser];
      $diplomacyRequest = unserialize($clanInfo["clanDiploReq"]);
      $diplomacy = unserialize($clanInfo["clanDiplo"]);

      if ($permissionUser[3] != 1) {
        $_SESSION["result_ally"] = "noperms";
        header("location: ../internalAllianceDiplo.php");
        exit();
      }

      $allyID = $diplomacyRequest[$index][0];

      if (array_key_exists($index, $diplomacyRequest) == FALSE) {
        $_SESSION["result_ally"] = "wrongindex";
        header("location: ../internalAllianceDiplo.php");
        exit();
      }

      $sql = mysqli_query($conn, "SELECT clanDiplo, clanDiploPending FROM userclans WHERE clanID=$allyID");
      if (mysqli_num_rows($sql) == 0) {
        array_splice($diplomacyRequest, $index, 1);
        $serializedRequests = serialize($diplomacyRequest);

        $sql = mysqli_query($conn, "UPDATE userclans SET clanDiploReq='$serializedRequests' WHERE clanID=$clanInfo[clanID]");
        if ($sql !== FALSE) {
          $_SESSION["result_ally"] = "allydeleted";
          header("location: ../internalAllianceDiplo.php");
          exit();
        } else {
          $_SESSION["sql"] = "sql";
          header("location: ../internalAllianceDiplo.php");
          exit();
        }
      }
      $allyInfo = mysqli_fetch_assoc($sql);

      $allyDiplomacy = unserialize($allyInfo["clanDiplo"]);
      $allyPending = unserialize($allyInfo["clanDiploPending"]);

      $keyclan = 0;
      foreach ($allyPending as $key) {
        if (array_search($clanInfo["clanID"], $key) < -1) {
          $check = false;
        } else {
          $check = true;
          $type = $key[1];
          break;
        }
        $keyclan++;
      }
      if ($check != true) {
        array_splice($diplomacyRequest, $index, 1);
        $serializedRequests = serialize($diplomacyRequest);
        exit();
        $sql = mysqli_query($conn, "UPDATE userclans SET clanDiploReq='$serializedRequests' WHERE clanID=$clanInfo[clanID]");
        if ($sql !== FALSE) {
          $_SESSION["result_ally"] = "allydeleted";
          header("location: ../internalAllianceDiplo.php");
          exit();
        } else {
          $_SESSION["sql"] = "sql";
          header("location: ../internalAllianceDiplo.php");
          exit();
        }
      }

      array_push($diplomacy, [$allyID, $type]);
      array_push($allyDiplomacy, [$clanInfo["clanID"], $type]);
      array_splice($diplomacyRequest, $index, 1);
      array_splice($allyPending, $keyclan, 1);

      $serializedDiplomacy = serialize($diplomacy);
      $serializedAllyDiplo = serialize($allyDiplomacy);
      $serializedReqs = serialize($diplomacyRequest);
      $serializedPends = serialize($allyPending);

      $sql = mysqli_query($conn, "UPDATE userclans SET clanDiplo='$serializedDiplomacy', clanDiploReq='$serializedReqs' WHERE clanID=$clanInfo[clanID]");
      $sql1 = mysqli_query($conn, "UPDATE userclans SET clanDiplo='$serializedAllyDiplo', clanDiploPending='$serializedPends' WHERe clanID=$allyID");

      if ($sql !== FALSE && $sql1 !== FALSE) {
        $_SESSION["result_ally"] = "successAccepted";
        header("location: ../internalAllianceDiplo.php");
        exit();
      } else {
        $_SESSION["sql"] = "sql";
        header("location: ../internalAllianceDiplo.php");
        exit();
      }
    } elseif (isset($_GET["action"]) && $_GET["action"] == "cancelNormal" && isset($_GET["index"])) {
      $index = $_GET["index"];

      if ($userInfo["userclan"] == "none") {
        $_SESSION["result_ally"] = "noally";
        header("location: ../internalAlliances.php");
        exit();
      }

      $sql = mysqli_query($conn, "SELECT clanMembers, clanMembersPerms, clanDiplo, clanID FROM userclans WHERE clanTag='$userInfo[userclan]'");
      $clanInfo = mysqli_fetch_assoc($sql);
      $membersList = unserialize($clanInfo["clanMembers"]);
      $permissions = unserialize($clanInfo["clanMembersPerms"]);
      $getPermissionUser = array_search($userInfo["userID"], $membersList);
      $permissionUser = $permissions[$getPermissionUser];
      $diplomacy = unserialize($clanInfo["clanDiplo"]);

      if ($permissionUser[3] != 1) {
        $_SESSION["result_ally"] = "noperms";
        header("location: ../internalAllianceDiplo.php");
        exit();
      }

      $allyID = $diplomacy[$index][0];

      if (array_key_exists($index, $diplomacy) == FALSE) {
        $_SESSION["result_ally"] = "wrongindex";
        header("location: ../internalAllianceDiplo.php");
        exit();
      }

      $sql = mysqli_query($conn, "SELECT clanDiplo FROM userclans WHERE clanID=$allyID");
      if (mysqli_num_rows($sql) == 0) {
        array_splice($diplomacy, $index, 1);
        $serializedDiplo = serialize($diplomacy);

        $sql = mysqli_query($conn, "UPDATE userclans SET clanDiplo='$serializedDiplo' WHERE clanID=$clanInfo[clanID]");
        if ($sql !== FALSE) {
          $_SESSION["result_ally"] = "allydeleted";
          header("location: ../internalAllianceDiplo.php");
          exit();
        } else {
          $_SESSION["sql"] = "sql";
          header("location: ../internalAllianceDiplo.php");
          exit();
        }
      }
      $allyInfo = mysqli_fetch_assoc($sql);

      $allyDiplomacy = unserialize($allyInfo["clanDiplo"]);

      $keyclan = 0;
      foreach ($allyDiplomacy as $key) {
        if (array_search($clanInfo["clanID"], $key) < -1) {
          $check = false;
        } else {
          $check = true;
          $type = $key[1];
          break;
        }
        $keyclan++;
      }
      if ($check != true) {
        array_splice($diplomacy, $index, 1);
        $serializedDiplo = serialize($diplomacy);
        exit();
        $sql = mysqli_query($conn, "UPDATE userclans SET clanDiplo='$serializedDiplo' WHERE clanID=$clanInfo[clanID]");
        if ($sql !== FALSE) {
          $_SESSION["result_ally"] = "allydeleted";
          header("location: ../internalAllianceDiplo.php");
          exit();
        } else {
          $_SESSION["sql"] = "sql";
          header("location: ../internalAllianceDiplo.php");
          exit();
        }
      }

      array_splice($diplomacy, $index, 1);
      array_splice($allyDiplomacy, $keyclan, 1);

      $serializedDiplomacy = serialize($diplomacy);
      $serializedAllyDiplo = serialize($allyDiplomacy);

      $sql = mysqli_query($conn, "UPDATE userclans SET clanDiplo='$serializedDiplomacy' WHERE clanID=$clanInfo[clanID]");
      $sql1 = mysqli_query($conn, "UPDATE userclans SET clanDiplo='$serializedAllyDiplo' WHERE clanID=$allyID");

      if ($sql !== FALSE && $sql1 !== FALSE) {
        $_SESSION["result_ally"] = "successDiploEnded";
        header("location: ../internalAllianceDiplo.php");
        exit();
      } else {
        $_SESSION["sql"] = "sql";
        header("location: ../internalAllianceDiplo.php");
        exit();
      }
    } else if (isset($_GET["action"]) && $_GET["action"]=="declineDiplo" && isset($_GET["index"])) {
      $index = $_GET["index"];

      if ($userInfo["userclan"] == "none") {
        $_SESSION["result_ally"] = "noally";
        header("location: ../internalAlliances.php");
        exit();
      }

      $sql = mysqli_query($conn, "SELECT clanMembers, clanMembersPerms, clanDiploReq, clanID FROM userclans WHERE clanTag='$userInfo[userclan]'");
      $clanInfo = mysqli_fetch_assoc($sql);
      $membersList = unserialize($clanInfo["clanMembers"]);
      $permissions = unserialize($clanInfo["clanMembersPerms"]);
      $getPermissionUser = array_search($userInfo["userID"], $membersList);
      $permissionUser = $permissions[$getPermissionUser];
      $diplomacyRequest = unserialize($clanInfo["clanDiploReq"]);

      if ($permissionUser[3] != 1 && $permissionUser[1] != 1) {
        $_SESSION["result_ally"] = "noperms";
        header("location: ../internalAllianceDiplo.php");
        exit();
      }

      $allyID = $diplomacyRequest[$index][0];

      if (array_key_exists($index, $diplomacyRequest) == FALSE) {
        $_SESSION["result_ally"] = "wrongindex";
        header("location: ../internalAllianceDiplo.php");
        exit();
      }

      $sql = mysqli_query($conn, "SELECT clanDiploPending FROM userclans WHERE clanID=$allyID");
      if (mysqli_num_rows($sql) == 0) {
        array_splice($diplomacyRequest, $index, 1);
        $serializedRequests = serialize($diplomacyRequest);

        $sql = mysqli_query($conn, "UPDATE userclans SET clanDiploReq='$serializedRequests' WHERE clanID=$clanInfo[clanID]");
        if ($sql !== FALSE) {
          $_SESSION["result_ally"] = "allydeleted";
          header("location: ../internalAllianceDiplo.php");
          exit();
        } else {
          $_SESSION["sql"] = "sql";
          header("location: ../internalAllianceDiplo.php");
          exit();
        }
      }
      $allyInfo = mysqli_fetch_assoc($sql);
      $allyPending = unserialize($allyInfo["clanDiploPending"]);

      $keyclan = 0;
      foreach ($allyPending as $key) {
        if (array_search($clanInfo["clanID"], $key) < -1) {
          $check = false;
        } else {
          $check = true;
          $type = $key[1];
          break;
        }
        $keyclan++;
      }
      if ($check != true) {
        array_splice($diplomacyRequest, $index, 1);
        $serializedRequests = serialize($diplomacyRequest);
        exit();
        $sql = mysqli_query($conn, "UPDATE userclans SET clanDiploReq='$serializedRequests' WHERE clanID=$clanInfo[clanID]");
        if ($sql !== FALSE) {
          $_SESSION["result_ally"] = "allydeleted";
          header("location: ../internalAllianceDiplo.php");
          exit();
        } else {
          $_SESSION["sql"] = "sql";
          header("location: ../internalAllianceDiplo.php");
          exit();
        }
      }

      array_splice($diplomacyRequest, $index, 1);
      array_splice($allyPending, $keyclan, 1);

      $serializedReqs = serialize($diplomacyRequest);
      $serializedPends = serialize($allyPending);

      $sql = mysqli_query($conn, "UPDATE userclans SET clanDiploReq='$serializedReqs' WHERE clanID=$clanInfo[clanID]");
      $sql1 = mysqli_query($conn, "UPDATE userclans SET clanDiploPending='$serializedPends' WHERE clanID=$allyID");

      if ($sql !== FALSE && $sql1 !== FALSE) {
        $_SESSION["result_ally"] = "allydeletedReqDiplo";
        header("location: ../internalAllianceDiplo.php");
        exit();
      } else {
        $_SESSION["sql"] = "sql";
        header("location: ../internalAllianceDiplo.php");
        exit();
      }
    } elseif (isset($_POST["btn_submit_new_ally_name"]) && isset($_POST["new_tag_ally"]) && isset($_POST["new_name_ally"])) {
      if ($userInfo["userclan"] == "none") {
        $_SESSION["result_ally"] = "noally";
        header("location: ../internalAlliances.php");
        exit();
      }

      $sql = mysqli_query($conn, "SELECT clanID, clanMembers, clanMembersPerms , clanLog, clanName, clanTag FROM userclans WHERE clanTag='$userInfo[userclan]'");
      $clanInfo = mysqli_fetch_assoc($sql);

      $decompiledParams = decompileInfo($clanInfo, 7, "Alliance");

      $newTag = htmlspecialchars($_POST["new_tag_ally"]);
      $newName = htmlspecialchars($_POST["new_name_ally"]);

      $check = 0;
      if ($newTag == $decompiledParams[5]) {
        $check++;
        $news1 = "";
      } else {
        $news1 = "Alliance tag has been changed to $newTag";
      }
      if ($newName == $decompiledParams[4]) {
        $check++;
        $news2 = ".";
      } else {
        $news2 = "alliance name has been changed to $newName";
      }

      if ($check == 2) {
        $_SESSION["result_ally"] = "nochange";
        header("location: ../internalAlliance.php");
        exit();
      }
      $sql = mysqli_query($conn, "SELECT clanTag, clanName FROM userclans WHERE clanTag='$userInfo[userclan]'");
      $userClanCheck = mysqli_fetch_assoc($sql);

      $sql = "SELECT clanID FROM userclans WHERE clanName=?";
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "s", $newName);
      mysqli_stmt_execute($stmt);

      $result = mysqli_stmt_get_result($stmt);
      $checkClanName = mysqli_fetch_assoc($result);

      $sql = "SELECT clanID FROM userclans WHERE clanTag=?";
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "s", $newTag);
      mysqli_stmt_execute($stmt);

      $result = mysqli_stmt_get_result($stmt);
      $checkClanTag = mysqli_fetch_assoc($result);

      if ($checkClanName["clanID"] > 0) {
        if ($newName != $userClanCheck["clanName"]) {
          $_SESSION["result_ally"] = "alreadyexistname";
          header("location: ../internalAlliance.php");
          exit();
        }
      }
      if ($checkClanTag["clanID"] > 0) {
        if ($newTag != $userClanCheck["clanTag"]) {
          $_SESSION["result_ally"] = "alreadyexisttag";
          header("location: ../internalAlliance.php");
          exit();
        }
      }

        if (preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬-]/', $newTag) || preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬-]/', $newName))
        {
          $_SESSION["result_ally"] = "special_chars";
          header("location: ../internalAlliance.php");
          exit();
        }

      if (strlen($newTag)> 6 || strlen($newTag)< 2) {
        $_SESSION["result_ally"] = "longshorttag";
        strlen($newTag);
        header("location: ../internalAlliance.php");
        exit();
      }
      if (strlen($newName) > 20 || strlen($newName) < 2) {
        $_SESSION["result_ally"] = "longshortname";
        header("location: ../internalAlliance.php");
        exit();
      }



      $log = "ATTENTION: $news $news2.";
      array_unshift($decompiledParams[3], $log);
      $compiled = compileInfo($decompiledParams);
      $sql = "UPDATE userclans SET clanLog=?, clanName=?, clanTag=? WHERE clanID=?";
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "ssss", $compiled[3], $newName, $newTag, $decompiledParams[0]);
      mysqli_stmt_execute($stmt);

      foreach ($decompiledParams[1] as $key) {
        $sql = "UPDATE users SET userclan=? WHERE userID=?";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "si", $newTag, $key);
        mysqli_stmt_execute($stmt);
      }

      $_SESSION["result_ally"] = "successNameChange";
      header("location: ../internalAlliance.php");
      exit();

    } elseif (isset($_POST["btn_submit_desc_new"]) && isset($_POST["new_desc_ally"])) {
      if ($userInfo["userclan"] == "none") {
        $_SESSION["result_ally"] = "noally";
        header("location: ../internalAlliances.php");
        exit();
      }

      $sql = mysqli_query($conn, "SELECT clanID, clanMembers, clanMembersPerms , clanLog, clanName, clanTag FROM userclans WHERE clanTag='$userInfo[userclan]'");
      $clanInfo = mysqli_fetch_assoc($sql);

      $decompiledParams = decompileInfo($clanInfo, 7, "Alliance");

      $newDesc = htmlspecialchars($_POST["new_desc_ally"]);

      if (preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬-]/', $newTag) || preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬-]/', $newDesc)) {
        $_SESSION["result_ally"] = "special_chars";
        header("location: ../internalAlliance.php");
        exit();
      }

      $sql = "UPDATE userclans SET clanDetail=? WHERE clanID=?";
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "ss", $newDesc, $decompiledParams[0]);
      mysqli_stmt_execute($stmt);

      $_SESSION["result_ally"] = "successChangeDesc";
      header("location: ../internalAlliance.php");
      exit();
    } else {
      header("location: ../internalAlliances.php");
      exit();
    }

  } else {
    session_unset();
    session_destroy();
    header("location: ../index.php?error=10");
    exit();
  }
} else {
  header("location: ../index.php?error=10");
  exit();
}
 ?>
