<?php

declare(strict_types=1);

return [
    'api_key' => env('UNITPOST_API_KEY'),
    // Organization key (ok_live_…) and the organization's id (org_…), for
    // Unitpost\OrganizationClient. Optional: an app without them boots as usual.
    'org_key' => env('UNITPOST_ORG_KEY'),
    'organization_id' => env('UNITPOST_ORGANIZATION_ID'),
    'base_url' => env('UNITPOST_BASE_URL', 'https://www.unitpost.com'),
];
