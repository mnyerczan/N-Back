<?php

use App\Core\Application;

// Start output buffer to handle errors auto
// headers sending.
ob_start();


Application::run(CLEANED_URI);