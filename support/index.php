<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Spacesabres||Support form</title>
    <meta name="description" content="Free browser MMO space game , explore the galaxy under the rhein of the company , build massive fleets and fight againts the deadly threats!">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/image/graphics/favicon.ico">
    <?php include "../include/font.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.js" data-cfasync="false"></script>
    <script>
    window.cookieconsent.initialise({
    "palette": {
      "popup": {
        "background": "#252e39"
      },
      "button": {
        "background": "#14a7d0"
      }
    }
    });
    </script>
    <style>
      * {
        margin: 0;
        padding: 0;
      }
      html {
        height: 100%;
        font-family: sans-serif;
      }
      textarea, input, select, option, button {
        font-family: sans-serif;
        padding: 2px 3px;
      }
      body {
        display: flex;
        flex-flow: column nowrap;
        align-items: center;
        background-image: url("../image/bg/supportbg.jpg");
        background-position: top center;
        background-color: black;
        background-repeat: no-repeat;
        height: 100%;
        width: 100%;
        color: rgb(235,235,235);
      }
      .main_container_support {
        width: 60%;
        height: 60%;
        border: 2px solid grey;
        border-radius: 5px;
        margin-top: 10%;
        background-color: rgb(50,50,50,0.9);
        display: flex;
        flex-flow: column nowrap;
        align-items: center;
        justify-content: center;
      }
      .main_container_support a {
        color: white;
        text-decoration: none;
        transition: 0.25s ease-in-out;
      }
      .main_container_support a:hover {
        text-decoration: underline;
      }
      .main_container_support h1 {
        margin-bottom: 10px;
      }
      .support_contact_form {
        display: flex;
        flex-flow: column nowrap;
        align-items: center;
        justify-content: space-evenly;
        width: 60%;
        margin-top: 10px;
      }
      .support_contact_form input, select, textarea {
        border: 2px solid grey;
        background-color: rgb(30,30,30);
        color: rgb(235,235,235);
      }
      .support_contact_form div, textarea {
        display: flex;
        flex-flow: row nowrap;
        justify-content: center;
        width: 100%;
        margin: 2px 0;
      }
      textarea {
        resize: none;
        height: 300px;
        padding: 5px 5px;
        margin-top: 5px;
      }
      label {
        margin-right: 20px;
      }
      button {
        width: 30%;
        border: 2px solid grey;
        background-color: rgb(35,35,35);
        margin-top: 10px;
        color: white;
        font-size: 18px;
        cursor: pointer;
        transition: 0.25s ease-in-out;
      }
      button:hover {
        border: 2px solid white;
        background-color: rgb(50,50,50);
      }
    </style>
    <meta property="og:title" content="Spacesabres">
    <meta property="og:image" content="https://spacesabres.com/image/graphics/ogimg.png">
    <meta property="og:url" content="https://www.spacesabres.com/support/index.php"/>
    <meta property="og:description" content="Spacesabres is a free, space MMO browser game where you can explore the galaxy under the rhein of the company , build massive fleets and fight againts the deadly threats!">
    <meta property="og:image:width" content="200"/>
    <meta property="og:image:height" content="200"/>
    <meta property="og:image:alt" content="Spacesabres logo"/>
  </head>
  <body>
    <img src="../image/graphics/logoNew.png" alt="Spacesabres logo">
    <section class="main_container_support">
      <h1>Support contact form</h1>
      <span>Make sure to check out our faq thread on forum before contacting support, you can do so <a href="https://forum.spacesabres.com/forum-10.html">here</a>.</span>
      <form class="support_contact_form" action="ticketHandler.php" method="post">
        <div>
        <label>Select section of the problem</label>
        <select name="problem">
          <option value="Technical problem">Technical problem</option>
          <option value="Payment problem">Payment problem</option>
          <option value="General problem">General problem</option>
        </select>
      </div>
        <div><label>Enter a brief description of the issue</label> <input type="text" name="subject" placeholder="Enter a subject"></div>
        <div><label>Enter a e-mail in order for us to contact you</label> <input type="text" name="email" placeholder="Enter your E-mail"></div>
        <label>Your message goes here (max. 1000 characters):</label> <textarea name="form_message" rows="8" cols="80" maxlength="1000" placeholder="Describe your issue here as best as you can in order to help us understand faster."></textarea>
        <button type="submit" name="btn_submit_form">Submit the form</button>
      </form>
    </section>
  </body>
</html>
