<?php

namespace App\Services\Dashboard;

use App\Domains\Engine\Concerns\HasCache;
use App\Extensions\Announcement\System\Models\Announcement;
use App\Helpers\Classes\MarketplaceHelper;
use App\Models\Favourite;
use App\Models\UserOpenai;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class UserDashboardService
{
    use HasCache;

    public function setCache(): void
    {
        $this->setChatbotExtensionInstalled()
            ->setUserDocs()
            ->setFavoriteOpenAi()
            ->setAffiliateTotalEarning()
            ->setFavoriteChatbot()
            ->setAnnouncements()
            ->setUserChatbots();
    }

    public function setUserDocs(): static
    {
        $this->cache('user_docs', function () {
            $team = request()->user()->getAttribute('team');

            $myCreatedTeam = request()->user()->getAttribute('myCreatedTeam');

            return UserOpenai::query()
                ->with('generator', 'isFavoriteDocRelation')
                ->where(function (Builder $query) use ($team, $myCreatedTeam) {
                    $query->where('user_id', auth()->id())
                        ->when($team || $myCreatedTeam, function ($query) use ($team, $myCreatedTeam) {
                            if ($team && $team?->is_shared) {
                                $query->orWhere('team_id', $team->id);
                            }
                            if ($myCreatedTeam) {
                                $query->orWhere('team_id', $myCreatedTeam->id);
                            }
                        });
                })
                ->get();
        });

        return $this;
    }

    public function setUserChatbots(): void
    {
        if (! MarketplaceHelper::isRegistered('chatbot')) {
            Cache::forget('user_chatbots');

            $this->cache('user_chatbots', function () {
                return [];
            });

            return;
        }

        if (cache()->has('user_chatbots') && cache('user_chatbots') === []) {
            Cache::forget('user_chatbots');
        }

        $this->cache('user_chatbots', function () {
            return auth()->user()->chatbots;
        });
    }

    public function setAffiliateTotalEarning(): static
    {
        $this->cache('total_earnings', function () {
            $affiliates = auth()?->user()?->affiliates;
            $withdrawals = auth()?->user()?->withdrawals;

            $totalEarnings = 0;

            $onetimeCommission = setting('onetime_commission', 0);
            foreach ($affiliates as $affUser) {
                if ($onetimeCommission) {
                    // if one time commission is open then get only the first order
                    $totalEarnings += $affUser->orders->sortBy('id')->first()?->affiliate_earnings;
                } else {
                    $totalEarnings += $affUser->orders->sum('affiliate_earnings');
                }
            }

            $totalWithdrawal = 0;
            foreach ($withdrawals as $affWithdrawal) {
                $totalWithdrawal += $affWithdrawal->amount;
            }

            return max(0, $totalEarnings - $totalWithdrawal);
        });

        return $this;
    }

    public function setFavoriteChatbot(): static
    {
        if (! MarketplaceHelper::isRegistered('chatbot')) {
            Cache::forget('favorite_chatbots');

            $this->cache('favorite_chatbots', function () {
                return [];
            });

            return $this;
        }

        if (cache()->has('favorite_chatbots') && cache('favorite_chatbots') === []) {
            Cache::forget('chatbot');
        }

        $this->cache('favorite_chatbots', function () {
            if (MarketplaceHelper::isRegistered('chatbot')) {
                return Favourite::where('type', 'chat')->with('openaiGeneratorChatCategory')->take(4)->get();
            }

            return null;
        });

        return $this;
    }

    public function setChatbotExtensionInstalled(): static
    {
        $this->cache('is_chabot_extension_installed', function () {
            return MarketplaceHelper::isRegistered('chatbot');
        });

        return $this;
    }

    public function setAnnouncements(): static
    {
        if (! MarketplaceHelper::isRegistered('announcement')) {
            Cache::forget('announcements');

            $this->cache('announcements', function () {
                return [];
            });

            return $this;
        }

        if (cache()->has('announcements') && cache('announcements') === []) {
            Cache::forget('announcements');
        }

        $this->cache('announcements', function () {
            return Announcement::query()->whereActive(true)->orderByDesc('created_at')->take(4)->get();
        });

        return $this;
    }

    public function setFavoriteOpenAi(): static
    {
        $this->cache('favorite_openai', function () {
            return auth()?->user()?->favoriteOpenai;
        });

        return $this;
    }
}
