<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\AllTrait;
use Illuminate\Support\Facades\Validator;
use App\Models\Course;

class CoursesController extends Controller
{
    use AllTrait;
    public function store(Request $request){
        try{
            //validation
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:191',
                'desc' => 'string',
                'hours' => 'string|max:255',
                'price' => 'required|string|max:191',
                'price_after_discount' => 'required|string|max:191',
                'discount' => 'string|max:191',
                'start_date' => 'required|after:date("Y-m-d")',
                'last_date_booking' => 'after:date("Y-m-d")',
                'course_img' => 'image|mimes:jpeg,png,jpg,gif,svg,webp',
                'teacher' => 'required|string',
                'teacher_img' => 'image|mimes:jpeg,png,jpg,gif,svg,webp',
            ]);

            if ($validator->fails()) {
                return $this->returnError(422, 'sorry this is an error', 'Error', $validator->errors());
            }
            $course = new Course();
            $course->name = $request->name;
            $course->desc = $request->desc;
            $course->hours = $request->hours;
            $course->price = $request->price;
            $course->discount = $request->discount;
            $course->price_after_discount = $request->price_after_discount;
            $course->start_date = $request->start_date;
            $course->last_date_booking = $request->last_date_booking;
            $course->teacher = $request->teacher;
            if($request->file('course_img')){
                $file = $request->file('course_img');
                $filename = rand() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('courses'), $filename);
                $course->course_img = $filename;
            }
            if($request->file('teacher_img')){
                $fileTeatcher = $request->file('teacher_img');
                $filenameTeatcher = rand() . '.' . $fileTeatcher->getClientOriginalExtension();
                $fileTeatcher->move(public_path('courses'), $filenameTeatcher);
                $course->teacher_img  = $filenameTeatcher;
            }
            $course->save();
            return $this->returnSuccess(200, 'this Course is added succssfuly', $course);

        }catch(\Exception $ex){
            return $ex;
            return $this->returnError(422, 'sorry this is an error');
        }

    }
    public function update(Request $request, $id){
        try{
            //find course
            $course = Course::find($id);
            if(! $course){
                return $this->returnError(200, 'sorry this is not exists');
            }
            //validate request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:191',
                'desc' => 'string',
                'hours' => 'string|max:255',
                'price' => 'required|string|max:191',
                'price_after_discount' => 'required|string|max:191',
                'discount' => 'string|max:191',
                'start_date' => 'required|after:date("Y-m-d")',
                'last_date_booking' => 'after:date("Y-m-d")',
                'course_img' => 'image|mimes:jpeg,png,jpg,gif,svg,webp',
                'teacher' => 'required|string',
                'teacher_img' => 'image|mimes:jpeg,png,jpg,gif,svg,webp',

            ]);
            if ($validator->fails()) {
                return $this->returnError(422, 'sorry this is an error in validation', 'Error', $validator->errors());
            }
            //store request in db
            $course->name = $request->name;
            $course->desc = $request->desc;
            $course->hours = $request->hours;
            $course->price = $request->price;
            $course->discount = $request->discount;
            $course->price_after_discount = $request->price_after_discount;
            $course->start_date = $request->start_date;
            $course->last_date_booking = $request->last_date_booking;
            $course->teacher = $request->teacher;
            if($request->file('course_img')){
                $file = $request->file('course_img');
                @unlink(public_path('courses/' . $course->course_img));
                $filename = rand() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('courses'), $filename);
                $course->course_img = $filename;
            }
            if($request->file('teacher_img')){
                $fileTeatcher = $request->file('teacher_img');
                @unlink(public_path('courses/' . $course->teacher_img));
                $filenameTeatcher = rand() . '.' . $fileTeatcher->getClientOriginalExtension();
                $fileTeatcher->move(public_path('courses'), $filenameTeatcher);
                $course->teacher_img  = $filenameTeatcher;
            }
            $course->save();
            return $this->returnSuccess(200, 'this course is updated succssfuly');

        }catch(\Exception $ex){
            return $ex;

            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function getAllCourses(){
        try{
            $courses = Course::orderBy('id', 'DESC')->paginate(PAGINATION_COUNT);
            return $this->returnData(200, 'there is all courses', $courses);
        }
        catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }

    }
    public function getOneCourse($id){
        try{
            //find exam
            $course = Course::find($id);
            if(! $course){
                return $this->returnError(200, 'sorry this is not exists');
            }
            return $this->returnData(200, 'this is course', $course);
        }
        catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }

    }
    public function destroy($id){
        try{
            $course = Course::find($id);
            if($course){
            @unlink(public_path('courses/' . $course->course_img));
            @unlink(public_path('courses/' . $course->teacher_img));
            //delete course from database
            $course->delete();
            return $this->returnSuccess(200, 'This course successfuly Deleted');

            }
            return $this->returnError(200, 'sorry this id not exists');

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');

        }
    }
}
