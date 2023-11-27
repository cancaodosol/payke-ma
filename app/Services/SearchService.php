<?php

namespace App\Services;

use App\Models\PaykeUser;

class SearchService
{
    public function search(string $keyword)
    {
        $pat = '%' . addcslashes($keyword, '%_\\') . '%';
        $users = PaykeUser::where('user_app_name', 'LIKE', $pat)
            ->orWhere('user_name', 'LIKE', $pat)
            ->orWhere('email_address', 'LIKE', $pat)
            ->orWhere('memo', 'LIKE', $pat)
            ->get();
        return $users;
    }
}