<?php

namespace App\Http\Controllers\Governments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Gallary;
use App\Models\Hierarchical;
use DB;
use App\Traits\AllTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    use AllTrait;
    public function store(Request $request){
        try{
            //validate request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:191',
                'img' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                'folder_id' => 'required|integer',
                'government_id' => 'required|integer|max:11',
                'hierarchical_id' => 'required|integer|max:11'
            ]);
            if ($validator->fails()) {
                return $this->returnError(422, 'sorry this is an error in validation', 'Error', $validator->errors());
            }
            //store request in db
            Employee::create([
                'name' => $request->name,
                'government_id' => $request->government_id,
                'hierarchical_id' => $request->hierarchical_id
            ]);
            //store img in file
            $file_extentions = $request->img->getClientOriginalExtension();
            $file_name = uniqid('', true) . '.' . $file_extentions;
            $request->img->move("gallary", $file_name);
            // get id of employee
            $lastNewOfEmployee = Employee::select('id')->latest('id')->first();
            Gallary::create([
                'name' => $file_name,
                'folder_id' => $request->folder_id,
                'employee_id' => $lastNewOfEmployee->id,
            ]);
            $lastEmployee = Employee::latest('id')->first();
            return $this->returnSuccess(200, 'this Employee is added succssfuly', $lastEmployee );

        }catch(\Exception $ex){
            return $ex;
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function update(Request $request, $id){
        try{
            //find intiative
            $employee = Employee::find($id);
            if(! $employee){
                return $this->returnError(200, 'sorry this is not exists');
            }
            //validate request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:191',
                'img' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                'folder_id' => 'required|integer',
                'government_id' => 'required|integer|max:11',
                'hierarchical_id' => 'required|integer|max:11'
            ]);
            if ($validator->fails()) {
                return $this->returnError(422, 'sorry this is an error in validation', 'Error', $validator->errors());
            }
            //store request in db
            $employee->update([
                'name' => $request->name,
                'government_id' => $request->government_id,
                'hierarchical_id' => $request->hierarchical_id
            ]);
            if($request->has('img')){
                //store img in file
                $file_extentions = $request->img->getClientOriginalExtension();
                $file_name = uniqid('', true) . '.' . $file_extentions;
                $request->img->move("gallary", $file_name);
                $img = Gallary::select('*')->where('employee_id', $id)->first();
                $img->update([
                    'name' => $file_name,
                    'folder_id' => $request->folder_id,
                ]);
            }
            if($request->has('folder_id')){
                $image = Gallary::select('*')->where('employee_id', $id)->first();
                $image->update([
                    'folder_id' => $request->folder_id,
                ]);

            }

            
            return $this->returnSuccess(200, 'this employee is updated succssfuly' );

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function getAll(){
        try{
            $employee = Employee::with('gallary')->select("*")->paginate(PAGINATION_COUNT);
            return $this->returnData(200, 'there is all employees', $employee);
        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function getOne($id){
        try{
            $employee = Employee::with('gallary')->find($id);
            if(! $employee){
                return $this->returnError(200, 'sorry this is not exists');
            }
            return $this->returnData(200, 'there is employee', $employee);



        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }
    }

    
    
    public function destroy($id){
        try{
            $employee = Employee::find($id);
            if($employee){
                //delete from folder
                $img = Gallary::select('*')->where('employee_id', $id)->first();
                if($img != null){
                    $image = Str::afterLast($img['name'], 'assets/');
                    unlink($image); //delete photo from folder
                    //delete img from table gallary
                    $employee->gallary()->delete();
                }


                //delete from database
                $employee->delete();
                return $this->returnSuccess(200, 'This employee successfuly Deleted');

            }
            return $this->returnError(200, 'sorry this id not exists');

        }catch(\Exception $ex){
            return $ex;
            return $this->returnError(422, 'sorry this is an error');

        }
    }
}
