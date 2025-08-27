<?php

namespace App\Console\Commands\Clear;

use App\Helpers\Classes\Helper;
use App\Models\UserOpenaiChat;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ClearUserOpenAIChatCommand extends Command
{
    private int $itemsToRetain = 22;

    protected $signature = 'app:clear-user-open-a-i-chat';

    protected $description = 'Command description';

    public function handle(): void
    {
        if (Helper::appIsNotDemo()) {
            return;
        }

        Log::info('Clearing user OpenAI chat data (except last 22)...');

        $idsToRetain = UserOpenaiChat::query()
            ->orderByDesc('id')
            ->take($this->itemsToRetain)
            ->pluck('id')
            ->toArray();

        UserOpenaiChat::query()
            ->whereNotIn('id', $idsToRetain)
            ->orderByDesc('id')
            ->take(PHP_INT_MAX)
            ->delete();

        Log::info('User OpenAI chat data cleared successfully (last 22 retained).');
    }
}
