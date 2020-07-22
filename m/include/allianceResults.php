<?php
if (isset($_SESSION["result_ally"]) && $_SESSION["result_ally"] != "") {
  echo "<div class=\"popup_result\">";
  switch ($_SESSION["result_ally"]) {
    case 'noperms':
      echo "<p>You don't have permissions to do such action!</p>";
    break;
    case 'empty':
      echo "<p>You haven't entered name of the alliance you want to join!</p>";
    break;
    case 'allyfull':
      echo "<p>This alliance has currently maximum members.</p>";
    break;
    case 'alreadysent':
      echo "<p>You have already sent an application to this alliance.</p>";
    break;
    case 'appsent':
      echo "<p>Your application has been successfully sent.</p>";
    break;
    // end of applying to ally part
    case 'missingparam':
      echo "<p>You haven't entered all required parameters!</p>";
    break;
    case 'longtag':
      echo "<p>Alliance tag cannot be longer than 5 characters.</p>";
    break;
    case 'shorttag':
      echo "<p>Alliance tag cannot be shorter than 3 characters.</p>";
    break;
    case 'longname':
      echo "<p>Alliance name cannot be longer than 20 characters.</p>";
    break;
    case 'shortname':
      echo "<p>Alliance name cannot be shorter than 4 characters.</p>";
    break;
    case 'longdesc':
      echo "<p>Alliance description cannot be longer than 500 characters.</p>";
    break;
    case 'credits':
      echo "<p>You don't have enough credits to create an alliance!</p>";
    break;
    case 'sql':
      echo "<p>Unfortunately an internal server error has occured , try again or report this error on our forums!</p>";
    break;
    case 'successCreate':
      echo "<p>Alliance has been successfully registered in galactic database!</p>";
    break;
    case 'exists':
      echo "<p>Alliance with such tag or name already exists!</p>";
    break;
    case 'inally':
      echo "<p>You already are in an alliance!</p>";
    break;
    // end of create alliance part
    case 'noally':
      echo "<p>You don't have an alliance!</p>";
    break;
    case 'maxmembers':
      echo "<p>Alliance has already reached maximum members capacity (50)</p>";
    break;
    case 'usernotexist':
      echo "<p>Unfortunately this commander has retired and no longer participates in the conflict.</p>";
    break;
    case 'alreadyjoined':
      echo "<p>Unfortunately this commander has already joined another alliance.</p>";
    break;
    case 'accepted':
      echo "<p>Commander has been successfully accepted!</p>";
    break;
    // end of accept member part
    case 'allyRank':
      echo "<p>You cannot kick commander with higher or same alliance rank!</p>";
    break;
    case 'kicked':
      echo "<p>Commander has been successfully kicked</p>";
    break;
    // end of kick member part
    case 'notleader':
      echo "<p>You cannot change other commander ranks as you aren't the alliance leader!</p>";
    break;
    case 'success':
      echo "<p>The commanders alliance rank has been changed.</p>";
    break;
    // end of change rank part
    case 'notleader':
      echo "<p>You cannot disband the alliance if you aren't the leader!</p>";
    break;
    case 'successDisband':
      echo "<p>You cannot disband the alliance if you aren't the leader!</p>";
    break;
    // end of disband ally part
    case 'sameuser':
      echo "<p>You cannot promote yourself to leader.</p>";
    break;
    case 'noleader':
      echo "<p>You aren't the alliance leader!</p>";
    break;
    case 'successGiveup':
      echo "<p>You have successfully resigned on the leader position.</p>";
    break;
    // end of leader resignation
    case 'successLeave':
      echo "<p>You have successfully left the alliance!</p>";
    break;
    // end of leave ally part
    case 'successDeclinedApp':
      echo "<p>Commanders application has been successfully declined</p>";
    break;
    // end of decline app part
    case 'allynotfound':
      echo "<p>Requested alliance hasn't been found</p>";
    break;
    case 'alreadyrelation':
      echo "<p>Your alliance already has diplomatic relation with this alliance.</p>";
    break;
    case 'alreadypending':
      echo "<p>Your alliance has already sent diplomatic request to this alliance.</p>";
    break;
    case 'successSentDiplo':
      echo "<p>The diplomatic request has been successfully sent!</p>";
    break;
    // end of send diplo request
    case 'allynotexist':
      echo "<p>Unfortunately this alliance no longer exists.</p>";
    break;
    case 'wrongindex':
      echo "<p>You have entered wrong index, do not manipulate the URL!</p>";
    break;
    case 'successDeleteReq':
      echo "<p>The pending request has been successfully cancelled</p>";
    break;
    // end of end pending part
    case 'allydeleted':
      echo "<p>Unfortunately this alliance no longer exists.</p>";
    break;
    case 'successAccepted':
      echo "<p>Your alliance has now started diplomatic relations with this alliance.</p>";
    break;
    // end of accept diplo req
    case 'successDiploEnded':
      echo "<p>Diplomatic relations between both alliances have now been terminated.</p>";
    break;
    // end of delete diplomation
    case 'allydeletedReqDiplo':
      echo "<p>You have successfully declined this diplomatic request.</p>";
    break;
    // end of decline req diplomacy
    case 'nochange':
      echo "<p>At least one alliance info has to be changed!</p>";
    break;
    case 'alreadyexistname':
      echo "<p>Unfortunately this alliance name already exists.</p>";
    break;
    case 'alreadyexisttag':
      echo "<p>Unfortunately this alliance tag already exists.</p>";
    break;
    case 'special_chars':
      echo "<p>No special characters are allowed.</p>";
    break;
    case 'longshorttag':
      echo "<p>The alliance tag has to be at least 2 characters long and cannot be longer than 6 characters</p>";
    break;
    case 'longshortname':
      echo "<p>The alliance name has to be at least 2 characters long and cannot be longer than 20 characters</p>";
    break;
    case 'successNameChange':
      echo "<p>Alliance Tag/Name have been successfully changed!</p>";
    break;
    // end of rename ally part
    case 'successChangeDesc':
      echo "<p>Alliance description has been successfully changed!</p>";
    break;
  }
  echo '<button type="button" name="button_confirm_result" class="button_confirm_result">OK</button></div>';
  $_SESSION["result_ally"] = "";
}
 ?>
