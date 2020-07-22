
let acp = function() {


let getAmmountUri = parseInt(document.querySelector('#ammountUri').value);
let getAmmountCredits = parseInt(document.querySelector('#ammountCreds').value);
let getAmmountEXP = parseInt(document.querySelector('#ammountExp').value);
let getAmmountHON = parseInt(document.querySelector('#ammountHon').value);
let getID = parseInt(document.querySelector('#id').value);
let getDivError = document.querySelector(".wrongID");
if (isNaN(getAmmountUri)) {
  getAmmountUri= 0;
}
if (isNaN(getAmmountCredits)) {
  getAmmountCredits = 0;
}
if (isNaN(getAmmountHON)) {
  getAmmountHON = 0;
}
if (isNaN(getAmmountEXP)) {
  getAmmountEXP= 0;
}

window.location =('./acphandler.php?&send=itemsActivated&&ID='+getID+'&&Uri='+getAmmountUri+'&&Creds='+getAmmountCredits+'&&Hon='+getAmmountHON+'&&Exp='+getAmmountEXP+'');
}
