<a class="text-primary text-decoration-none fas fa-arrow-circle-left mb-3" href="#" onclick="window.history.back();"></a>
<?php

use app\models\view\CardPrinter;

echo CardPrinter::printCard($this->group, $this->user);
