<?php

namespace FlightSimCorp\FSIdentity;

use SocialiteProviders\Manager\SocialiteWasCalled;

class FSIdentityExtendSocialite
{
    public function handle(SocialiteWasCalled $socialiteWasCalled): void
    {
        $socialiteWasCalled->extendSocialite('fsidentity', Provider::class);
    }
}
