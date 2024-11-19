<?php

namespace App\Imports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TasksImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Task([
            'title' => $row['titre'], // Assurez-vous que le nom correspond à l'en-tête dans votre fichier Excel
            // 'completed' => $row['completée'], // Assurez-vous que le nom correspond à l'en-tête
        ]);
    }
}