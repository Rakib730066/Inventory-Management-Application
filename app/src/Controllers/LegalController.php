<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;

class LegalController extends BaseController
{

    public function privacy(): void
    {
        View::render('legal/privacy');
    }

    public function terms(): void
    {
        View::render('legal/terms');
    }
}
