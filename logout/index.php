
<script>lockSession();</script>
<?php
// remove all session variables
session_unset(); 

// destroy the session 
session_destroy(); 

//header( 'Location: /' ) ;
?>
<script>
    window.location="/";
</script>