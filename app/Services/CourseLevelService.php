<?php

namespace App\Services;

use App\Models\CourseLevel;

class CourseLevelService{

    public function list(){
        
        return CourseLevel::query()->orderBy('priority_level','asc')->get();
    }

    public function save($value, $priorityLevel){
        $courseLevels = CourseLevel::query()->orderBy('priority_level','asc')->get();
        if(count($courseLevels) == 0){
            return CourseLevel::create([
                'value' => $value,
                'priority_level' => $priorityLevel
            ]);
        }else{
            foreach($courseLevels as $index=>$level){
                if($level->{'priority_level'} == $value){
                    $level->{'priority_level'} = $value;
                    for($i = $index+1; $i < count($courseLevels);$i++){
                        if( (($i-1) > 0) && ($courseLevels[$i-1] == $courseLevels[$i]) ){
                            $courseLevels[$i]->{'priority_level'}++;
                            $levelToUpdate = CourseLevel::find($courseLevels[$i]->id);
                            $levelToUpdate->{'priority_level'} = $courseLevels[$i]->{'priority_level'};
                            $levelToUpdate->save();

                        }
                    }
                    break;
                }
            }
            return CourseLevel::create([
                'value' => $value,
                'priority_level' => $priorityLevel
            ]);

        }
    }
}