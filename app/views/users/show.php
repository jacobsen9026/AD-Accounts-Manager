<a class="text-primary text-decoration-none fas fa-arrow-circle-left mb-3" href="#" onclick="window.history.back();"></a>
<?php

use App\Models\View\CardPrinter;

echo CardPrinter::printCard($this->districtUser, $this->user);
