<?php

namespace App\Services;

use App\Helpers\ZipReadHelper;
use App\Models\PaykeResource;

class PaykeResourceService
{
    public function save(string $payke_zip_file_path): void
    {
        $resource = new PaykeResource();

        $version = (new ZipReadHelper())->read_payke_version($payke_zip_file_path);

        $resource->set_version($version);
        $resource->set_name($version);
        $resource->payke_zip_name = pathinfo($payke_zip_file_path, PATHINFO_FILENAME);
        $resource->payke_zip_file_path = $payke_zip_file_path;

        $resource->save();
    }

    public function find_by_id(int $id)
    {
        return PaykeResource::where('id', $id)->firstOrFail();
    }

    public function find_all()
    {
        return PaykeResource::orderByRaw('version_x DESC, version_y DESC, version_z DESC, payke_name DESC')->get();
    }

    // MEMO : 画面で使いやすいように加工してある。
    public function find_all_to_array(): array
    {
        $resources = $this->find_all();
        return array_map(function($x){
            return ["id" => $x['id'], "name" => $x['version']];
        }, $resources->toarray());
    }
}