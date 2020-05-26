<a class="text-decoration-none fas fa-arrow-circle-left mb-3" href="/students"></a>
<?php

use App\Models\View\CardPrinter;

echo CardPrinter::printCard($this->student, $this->user);
?>
<div class="my-5 py-5"></div>
<?php
echo $this->view('/students/search');
