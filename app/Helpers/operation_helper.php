<?php

if (! function_exists('get_operation_types')) {
    function get_operation_types(): array
    {
        $model = new \App\Models\TypeOperationModel();

        return $model->orderBy('id', 'ASC')->findAll();
    }
}
