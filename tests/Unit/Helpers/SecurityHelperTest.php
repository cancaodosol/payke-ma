<?php

namespace Tests\Unit\Helpers;

use App\Helpers\SecurityHelper;
use PHPUnit\Framework\TestCase;

class SecurityHelperTest extends TestCase
{
    /**
     * create_salt test.
     */
    public function test_create_salt(): void
    {
        $r1 = SecurityHelper::create_salt();
        print("{$r1}\n");
    }

    /**
     * create_seed test.
     */
    public function test_create_seed(): void
    {
        $r1 = SecurityHelper::create_seed();
        print("{$r1}\n");
    }

    /**
     * create_seed test.
     */
    public function test_preg_match(): void
    {
        $r1 = "https://hiderin.xyz/payke6/admin/users";
        $this->assertTrue(str_ends_with($r1, "/users"));

        $r2 = "https://hiderin.xyz/payke6/admin/users/login";
        $this->assertFalse(str_ends_with($r2, "/users"));
    }

}