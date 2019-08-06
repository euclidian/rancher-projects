<?php

namespace Tiketux\RancherProjects\Models;

use Illuminate\Database\Eloquent\Model;
// use Spatie\Activitylog\Traits\LogsActivity;
use DB;

class StackConfig extends Model
{
    protected $table = "stack_config";
    protected $guarded = ["id"];
    public $timestamps = false;

    public static function detailtemplate($id)
    {
        $st = StackTemplate::findOrFail($id);

        $indexSebelumnya = 0;
        $textUntukDitimpa1 = [];
        $textUntukDitimpa2 = [];

        while ($result = strpos($st->docker_compose_yml, "{%", $indexSebelumnya)) {
            $awal = $result + 2;
            $akhir = strpos($st->docker_compose_yml, "%}", $indexSebelumnya);
            $textUntukDitimpa1[] = ["key" => substr($st->docker_compose_yml, $awal, $akhir - $awal)];
            $indexSebelumnya = $akhir + 2;
        }
        $indexSebelumnya = 0;
        while ($result = strpos($st->rancher_compose_yml, "{%", $indexSebelumnya)) {
            $awal = $result + 2;
            $akhir = strpos($st->rancher_compose_yml, "%}", $indexSebelumnya);
            $textUntukDitimpa2[] = ["key" => substr($st->rancher_compose_yml, $awal, $akhir - $awal)];
            $indexSebelumnya = $akhir + 2;
        }

        return [
            "docker" => $textUntukDitimpa1,
            "rancher" => $textUntukDitimpa2
        ];
    }

    public static function saveConfig($template_id, $name, $docker, $rancher)
    {
        $st = StackTemplate::findOrFail($template_id);
        $generated_docker_compose_yml = $st->docker_compose_yml;
        foreach ($docker as $item) {
            $generated_docker_compose_yml = str_replace("{%" . $item["key"] . "%}", $item["value"], $generated_docker_compose_yml);
        }

        $generated_rancher_compose_yml = $st->rancher_compose_yml;
        foreach ($rancher as $item) {
            $generated_rancher_compose_yml = str_replace("{%" . $item["key"] . "%}", $item["value"], $generated_rancher_compose_yml);
        }

        return StackConfig::create([
            "template_id" => $template_id,
            "name" => $name,
            "generated_docker_compose_yml" => $generated_docker_compose_yml,
            "generated_rancher_compose_yml" => $generated_rancher_compose_yml,
        ]);
    }
}
