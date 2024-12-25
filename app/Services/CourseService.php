<?php

use App\Models\Course;

namespace App\Services;

use App\Models\Course;
use Illuminate\Support\Facades\Log;

class CourseService{

    public function list($unitId=null, $courseLevelId=null){
        $query = Course::query();
        if(isset($unitId)){
            $query->where('institutional_unit_id','=',$unitId);
        }
        if(isset($courseLevelId)){
            $query->where('course_level_id','=',$courseLevelId);
        }

        return $query->orderBy('name','asc')->get();
    }
}