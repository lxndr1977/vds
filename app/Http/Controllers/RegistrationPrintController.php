<?php

namespace App\Http\Controllers;

use App\Models\Registration;

class RegistrationPrintController extends Controller
{
    public function print(Registration $record)
    {
        // Carrega todos os relacionamentos necessários
        $record->load([
            'school.choreographies',
            'school.choreographies.dancers',
            'school.choreographies.choreographers',
            'school.choreographies.choreographyType',
            'school.choreographies.choreographyCategory',
            'school.choreographies.danceStyle',
            'school.members.memberType',
        ]);

        return view('print.registration-details', compact('record'));
    }
}
