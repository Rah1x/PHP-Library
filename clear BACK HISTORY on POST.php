<?php
//put this on saving page.
//use this instead of location.href
//CAUTION: this will prevent form submit or location.href
echo '<script>location.replace("'.DOC_ROOT.'form_page.php");</script>';
//////////////////////////////////////////////////

# [IE]
//at the top of the page where user will go from browser back button, other wise, you will see "Page Has Expired" message.
header('Cache-Control:private, max-age=10800, pre-check=10800');
?>