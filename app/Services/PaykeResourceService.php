<?php

namespace App\Services;

use App\Helpers\ZipReadHelper;
use App\Models\PaykeResource;

class PaykeResourceService
{
    public function save(string $payke_zip_file_path, string $memo = ''): void
    {
        $resource = new PaykeResource();

        $version = (new ZipReadHelper())->read_payke_version($payke_zip_file_path);

        $resource->set_version($version);
        $resource->set_name($version);
        $resource->payke_zip_name = pathinfo($payke_zip_file_path, PATHINFO_FILENAME);
        $resource->payke_zip_file_path = $payke_zip_file_path;
        $resource->memo = $memo;

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

    public function find_upper_version_to_array(PaykeResource $payke): array
    {
        $resources = PaykeResource::orderByRaw(
            "version_x desc, version_y desc, version_z desc"
            )->get();

        $upper_resources = [];
        foreach ($resources as $resource)
        {
            if($resource->version == $payke->version) break;
            $upper_resources[] = $resource;
        }

        return array_map(function($x){
            return ["id" => $x['id'], "name" => $x['version']];
        }, $upper_resources);
    }

    // MEMO : 画面で使いやすいように加工してある。
    public function find_all_to_array(): array
    {
        $resources = $this->find_all();
        return array_map(function($x){
            return ["id" => $x['id'], "name" => $x['version']];
        }, $resources->toarray());
    }

    public function get_version_by_id(int $id): string
    {
        $resource = $this->find_by_id($id);
        return $resource->version;
    }

    public function edit(int $id, array $values)
    {
        $resource = $this->find_by_id($id);
        $resource->update($values);
    }

    public function get_release_version(): PaykeResource
    {
        // TODO 暫定で、最新版を指定している。本番実装では、公開フラグを持ったバージョンを指定する。
        return $this->find_all()[0];
    }
}