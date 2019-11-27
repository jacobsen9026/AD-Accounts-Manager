<html>
    <head>

        <title>PHP Configuration</title>

        <meta name="theme-color" content="#ffffff">



        <link rel="apple-touch-icon" sizes="180x180" href="/public/img/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/public/img/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/public/img/favicon/favicon-16x16.png">
        <link rel="manifest" href="/public/manifest.json">
        <link rel="mask-icon" href="/public/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="theme-color" content="#ffffff">


        

		<link rel="stylesheet" type="text/css" href="/public/style/lightTheme.css">

		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" media="screen and (max-width: 719px)" type="text/css" href="/public/style/style.css">

		<link rel="stylesheet" media="screen and (max-width: 719px)" type="text/css" href="/public/style/mobilestyle.css">

		<link rel="stylesheet" media="screen and (min-width: 720px)" type="text/css" href="/public/style/style.css">


    </head>


    <body>















<style>
li {
padding-left:1em;

}
</style>
  


        <div id="wrapper" class=''>



<table id="container">
    <tr>
        <th>
            Web Server Configuration Issue
        </th>
    </tr>

    <tr>
        <td>
		<br/>
		Please correct these errors to begin installation<br/><br/>
		<ol>
		<li>The root directory is not set to <pre style="display:inline">/public</pre></li>
		<?php if (explode(".",phpversion())[0]<7){;?>
         <li>Update PHP to version 7 or later</li>   
		 <?php
		}
		?>
			</ol>
        </td>
    </tr>
	<tr>
		<td>
			<a href="/">
				<button type="button">
					Recheck
				</button>
			</a>
		</td>
	</tr>
</table>




<!--
Site Written By: Chris Jacobsen
With Credit to Codiad and IceCoder
Creation: September-October 2017
Updated: October 2019
-->

</body>

</html>


