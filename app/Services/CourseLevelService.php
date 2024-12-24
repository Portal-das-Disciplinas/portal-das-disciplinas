<?php

namespace App\Services;

use App\Models\CourseLevel;

class CourseLevelService
{

    public function list()
    {
        return CourseLevel::query()->orderBy('priority_level', 'asc')->get();
    }

    public function save($value, $priorityLevel)
    {
        $courseLevels = CourseLevel::query()->orderBy('priority_level', 'asc')->get();
        if (count($courseLevels) == 0) {
            return CourseLevel::create([
                'value' => $value,
                'priority_level' => $priorityLevel
            ]);
        } else {
            $this->reorderPriorityLevels($courseLevels, $priorityLevel);          
            return CourseLevel::create([
                'value' => $value,
                'priority_level' => $priorityLevel
            ]);
        }
    }

    private function reorderPriorityLevels($courseLevels,$priorityLevel){
        foreach ($courseLevels as $level) {
            if ($level->{'priority_level'} == $priorityLevel) {
                $level->{'priority_level'}++;
                $levelToUpdate = CourseLevel::findOrFail($level->id);
                $levelToUpdate->priority_level = $level->priority_level;
                $levelToUpdate->save();
                break;
            }
        }

        for ($i = 0; $i < count($courseLevels); $i++) {
            if ((($i - 1) >= 0) && ($courseLevels[$i - 1]->priority_level == $courseLevels[$i]->priority_level)) {
                $courseLevels[$i]->priority_level++;
                $levelToUpdate = CourseLevel::findOrFail($courseLevels[$i]->id);
                $levelToUpdate->{'priority_level'} = $courseLevels[$i]->priority_level;
                $levelToUpdate->save();
            }
        }
    }
}
