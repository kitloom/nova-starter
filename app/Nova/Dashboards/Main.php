<?php

declare(strict_types=1);

namespace App\Nova\Dashboards;

use Kitloom\Ai\Nova\Cards\AgentCard;
use Kitloom\WpSchema\Nova\Cards\KitloomCard;
use Laravel\Nova\Card;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array<int, Card>
     */
    public function cards(): array
    {
        return [
            // kitloom/ai may have been left out at install.
            ...(class_exists(AgentCard::class) ? [new AgentCard] : []),
            new KitloomCard,
        ];
    }
}
