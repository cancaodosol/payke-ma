<?php

namespace App\Services;

use App\Helpers\ZipReadHelper;
use App\Models\PaykeResource;

class PaykeResourceService
{
    public function save(string $payke_zip_file_path): void
    {
        $resource = new PaykeResource();

        $version = (new ZipReadHelper())->get_payke_version($payke_zip_file_path);

        $resource->set_version($version);
        $resource->set_name($version);
        $resource->payke_zip_name = pathinfo($payke_zip_file_path, PATHINFO_FILENAME);
        $resource->payke_zip_file_path = $payke_zip_file_path;

        $resource->save();
    }
}