<?php

namespace App\Http\Controllers;

use App\Services\SVG\SvgDirectorInterface;
use Laravel\Lumen\Routing\Controller as BaseController;
use Psr\Log\LoggerInterface;

class Controller extends BaseController
{
    public function index(SvgDirectorInterface $cardBuilder, LoggerInterface $logger)
    {
        try {
            $card = $cardBuilder->buildCard();
        } catch (\Throwable $exception) {
            $logger->error($exception->getMessage(), $exception->getTrace());
            $card = $cardBuilder->buildFallbackCard();
        }

        return response($card, 200, ['content-type' => 'image/svg+xml']);
    }
}
