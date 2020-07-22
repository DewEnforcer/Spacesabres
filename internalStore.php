<?php
  require "./include/accessSecurity.php";
 ?>
 <!DOCTYPE html>
 <html>
   <head>
     <meta charset="utf-8">
     <meta name="description" content="This is an example of a meta description. This will often show up in search results">
     <meta name=viewport content="width=device-width, initial-scale=1">
     <?php include "include/font.php"; ?>
     <link rel="stylesheet" href="../css/stylegame.css">
     <link rel="stylesheet" href="../css/stylePayment.css">
     <script src="../js/jquery-3.4.1.min.js" charset="utf-8"></script>
     <script src="../js/payment.js"></script>
     <script src="../js/gameinfo.js"></script>
     <script src="../js/backgroundmanager.js"></script>
     <script src="../js/search-player.js"></script>
     <title>SpaceSabres||Payment</title>
   </head>
   <body>
       <header>
         <?php require "include/header.php"; ?>
   </header>

     <main>
       <section class="searchPopup">

       </section>
       <section class="payment_container">
         <div class="header_payment">
           <ul class="nav_payment">
             <li class="link_currencies">Currencies</li>
             <li class="link_spaceships">Spaceships</li>
             <li class="link_bundles">Bundles</li>
           </ul>
         </div>
         <div class="payment_pack_container">
           <div class="payment_pack" id="1">
             <h3>Starter credit pack</h3>
             <span>Price: 5€</span>
             <p>This pack contains 2 500 000 Credits, it's not much, but it will get you couple of good old hornets in the shipyards.</p>
             <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
               <input type="hidden" name="cmd" value="_s-xclick">
               <input type="hidden" name="hosted_button_id" value="BDK3PVRU4RZH8">
               <input type="hidden" name="custom" value="<?php echo $userInfo["userID"]; ?>">
               <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
               <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
             </form>

           </div>
           <div class="payment_pack" id="2">
             <h3>Credit pack M</h3>
             <span>Price: 10€</span>
             <p>This pack contains 5 750 000 Credits, giving you 15% more in total compared to starter credit pack!</p>
             <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
               <input type="hidden" name="cmd" value="_s-xclick">
               <input type="hidden" name="hosted_button_id" value="RFF55LZXZS3X4">
               <input type="hidden" name="custom" value="<?php echo $userInfo["userID"]; ?>">
               <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
               <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
             </form>
           </div>
           <div class="payment_pack" id="3">
             <h3>Credit pack L</h3>
             <span>Price: 25€</span>
             <p>This pack contains 14 400 000 Credits, giving you 20% more in total compared to starter credit pack!</p>
             <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
               <input type="hidden" name="cmd" value="_s-xclick">
               <input type="hidden" name="hosted_button_id" value="AZBMYZCQUFV4A">
               <input type="hidden" name="custom" value="<?php echo $userInfo["userID"]; ?>">
               <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
               <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
             </form>
           </div>
           <div class="payment_pack" id="4">
             <h3>Credit pack XL</h3>
             <span>Price: 50€</span>
             <p>This pack contains 32 500 000 Credits, giving you 30% more in total compared to starter credit pack!</p>
             <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
               <input type="hidden" name="cmd" value="_s-xclick">
               <input type="hidden" name="hosted_button_id" value="5YRGYERG3Z3WL">
               <input type="hidden" name="custom" value="<?php echo $userInfo["userID"]; ?>">
               <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
               <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
             </form>
           </div>
           <div class="payment_pack" id="5">
             <h3>Credit pack XXL</h3>
             <span>Price: 100€</span>
             <p>This pack contains 75 000 000 Credits, giving you 50% more in total compared to starter credit pack!</p>
             <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
               <input type="hidden" name="cmd" value="_s-xclick">
               <input type="hidden" name="hosted_button_id" value="YAAPYGWSFVPHG">
               <input type="hidden" name="custom" value="<?php echo $userInfo["userID"]; ?>">
               <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
               <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
             </form>
           </div>
           <div class="payment_pack" id="6">
             <h3>Starter hyperid pack</h3>
             <span>Price: 5€</span>
             <p>This pack contains 500 000 Hyperids, enough for some dreadful cruisers!</p>
             <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
               <input type="hidden" name="cmd" value="_s-xclick">
               <input type="hidden" name="hosted_button_id" value="2HMZ4QRQVBJDG">
               <input type="hidden" name="custom" value="<?php echo $userInfo["userID"]; ?>">
               <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
               <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
             </form>
           </div>
           <div class="payment_pack" id="7">
             <h3>Hyperid pack M</h3>
             <span>Price: 10€</span>
             <p>This pack contains 1 150 000 Hyperids, that's 15% more than hyperid starter pack!</p>
             <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
               <input type="hidden" name="cmd" value="_s-xclick">
               <input type="hidden" name="hosted_button_id" value="KPKF48TWP6K9Y">
               <input type="hidden" name="custom" value="<?php echo $userInfo["userID"]; ?>">
               <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
               <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
             </form>
           </div>
           <div class="payment_pack" id="8">
             <h3>Hyperid pack L</h3>
             <span>Price: 25€</span>
             <p>This pack contains 2 880 000 Hyperids, giving you 20% more than the hyperid starter pack!</p>
             <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
               <input type="hidden" name="cmd" value="_s-xclick">
               <input type="hidden" name="hosted_button_id" value="6FMVMUDBDGREU">
               <input type="hidden" name="custom" value="<?php echo $userInfo["userID"]; ?>">
               <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
               <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
             </form>
           </div>
           <div class="payment_pack" id="9">
             <h3>Hyperid pack XL</h3>
             <span>Price: 50€</span>
             <p>This pack contains 6 500 000 Hyperids, giving you 30% more than the hyperid starter pack!</p>
             <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
               <input type="hidden" name="cmd" value="_s-xclick">
               <input type="hidden" name="hosted_button_id" value="YPZUFAMN25WMN">
               <input type="hidden" name="custom" value="<?php echo $userInfo["userID"]; ?>">
               <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
               <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
             </form>
           </div>
           <div class="payment_pack" id="10">
             <h3>Hyperid pack XXL</h3>
             <span>Price: 100€</span>
             <p>This pack contains 15 000 000 Hyperids,  giving you 50% more than the hyperid starter pack!</p>
             <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
               <input type="hidden" name="cmd" value="_s-xclick">
               <input type="hidden" name="hosted_button_id" value="QPU8BFTRDCRSG">
               <input type="hidden" name="custom" value="<?php echo $userInfo["userID"]; ?>">
               <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
               <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
             </form>
           </div>
           <div class="payment_pack" id="11">
             <h3>Natium pack</h3>
             <span>Price: 10€</span>
             <p>This pack contains 5000 units of the rarest resource "Natium", which is used in Na-Thalis destroyer and M-RS01 production.</p>
             <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
               <input type="hidden" name="cmd" value="_s-xclick">
               <input type="hidden" name="hosted_button_id" value="VXZ65FLM243R6">
               <input type="hidden" name="custom" value="<?php echo $userInfo["userID"]; ?>">
               <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
               <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
             </form>
           </div>
         </div>
         </main>
      </section>
     </main>

     <footer>
       <?php require "include/footer.php"; ?>
     </footer>
 </body>
 </html>
