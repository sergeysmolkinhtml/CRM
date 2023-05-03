<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

trait CustomFieldsTrait
{
    public $model;

    private $extraData;

    private function getModelName(): string
    {
        $model = new \ReflectionClass($this);

        $this->model = $model;

        return $this->model->getName();
    }

    public function addCustomFieldGroup($group): void
    {
        $modelName = $this->getModelName();

        $insertedData = [
            'name' => $group['name'],
            'model' => $modelName,
            //'company_id' => company()->id
        ];

        DB::table('custom_field_groups')->insert($insertedData);

        // Add Custom Fields for this group
        foreach ($group['fields'] as $field) {
            $insertedData = [
                'custom_field_group_id' => $field['groupID'],
                'label' => $field['label'],
                'name' => $field['name'],
                'type' => $field['type']
            ];

            if (isset($field['required']) && ((strtolower($field['required']) == 'yes' || strtolower($field['required']) == 'on' || $field['required'] == 1))) {
                $insertedData['required'] = 'yes';

            } else {
                $insertedData['required'] = 'no';
            }

            // Single value should be stored as text (multi value JSON encoded)
            if (isset($field['value'])) {
                if (is_array($field['value'])) {
                    $insertedData['value'] = json_encode($field['value']);

                } else {
                    $insertedData['value'] = $field['value'];
                }
            }

            DB::table('custom_fields')->insert($insertedData);
        }
    }
}
