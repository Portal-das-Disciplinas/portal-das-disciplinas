<?php

namespace App\Services;

use App\Models\EducationLevel;
use Illuminate\Support\Facades\Log;

class EducationLevelService
{

    public function list()
    {
        return EducationLevel::query()->orderBy('priority_level', 'asc')->get();
    }

    public function save($value, $priorityLevel)
    {
        $courseLevels = EducationLevel::query()->orderBy('priority_level', 'asc')->get();
        if (count($courseLevels) == 0) {
            return EducationLevel::create([
                'value' => $value,
                'priority_level' => $priorityLevel
            ]);
        } else {
            $this->reorderPriorityLevels($courseLevels, $priorityLevel);          
            return EducationLevel::create([
                'value' => $value,
                'priority_level' => $priorityLevel
            ]);
        }
    }

    public function update($id, $value, $priorityLevel){
        $educationLevel = EducationLevel::findOrFail($id);
        $courseLevels = EducationLevel::query()->orderBy('priority_level','asc')->get();
        if($priorityLevel != $educationLevel->priority_level){
            $this->reorderPriorityLevels($courseLevels, $priorityLevel);
        }
        $educationLevel->value = $value;
        $educationLevel->priority_level = $priorityLevel;
        $educationLevel->save();
    }

    public function delete($id){
        return EducationLevel::destroy($id);
    }

    private function reorderPriorityLevels($courseLevels,$priorityLevel){
        foreach ($courseLevels as $level) {
            if ($level->{'priority_level'} == $priorityLevel) {
                $level->{'priority_level'}++;
                $levelToUpdate = EducationLevel::findOrFail($level->id);
                $levelToUpdate->priority_level = $level->priority_level;
                $levelToUpdate->save();
                break;
            }
        }

        for ($i = 0; $i < count($courseLevels); $i++) {
            if ((($i - 1) >= 0) && ($courseLevels[$i - 1]->priority_level == $courseLevels[$i]->priority_level)) {
                $courseLevels[$i]->priority_level++;
                $levelToUpdate = EducationLevel::findOrFail($courseLevels[$i]->id);
                $levelToUpdate->{'priority_level'} = $courseLevels[$i]->priority_level;
                $levelToUpdate->save();
            }
        }
    }
}
