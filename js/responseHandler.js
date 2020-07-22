$(document).ready(function(){
  $(document).on("click", "#reply", function() {
    let toreplymsg = $("#msgTextContainer").text();
    let htmlmsg = "<p style='margin: 0px 10px; margin-top: 50px; font-family: sans-serif; font-size: 15px;'>"+toreplymsg+"</p>";
    let getFrom = $("#from").text();
    let getSubject= $("#subject").text();
    let splitFrom = getFrom.split(": ");
    let splitSubject = getSubject.split(": ");
    $("#msgText").html(htmlmsg);
    let form = "<form class=message id='replymsg' action=internalInbox.php method=post style='margin: 10px 10px;'><input type=text name=user id='user' style='margin-top: 10px;  background-color: #989898;  color: white;  width: 30%;  box-sizing: border-box;  padding-left: 5px;' value="+splitFrom[1]+"><br><input type=text name=subject id='subject' value='RE:"+splitSubject[1]+"' style='margin-top: 10px;  background-color: #989898;  color: white;  width: 30%;  box-sizing: border-box;  padding-left: 5px;'><br><br><textarea name=msg id=msg rows=8 cols=80 maxlength='600' style='resize:none;margin-top: 10px;  background-color: #989898;  color: white;  width: 100%;  box-sizing: border-box;  padding-left: 5px;'></textarea><br><button type=submit name=submit-msg style='height: 2%;display: block;  background-color: #87CEFA;  border: none;  color: #282828;  padding: 5px 10px;  text-decoration: none;  cursor: pointer;  font-family: sans-serif;'>Submit your message!</button><br></form>"
    $("#msgText").append(form);
    $("#msgText").append("<img src='../image/graphics/closeMsg.png' id='closemsg' alt='close' style='position:absolute; right:1.5%;top:1.5%; width:7%;'>");
  });
});
