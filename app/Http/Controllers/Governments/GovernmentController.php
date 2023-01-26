<?php

namespace App\Http\Controllers\Governments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Government;
use App\Traits\AllTrait;
use Illuminate\Support\Facades\Validator;

class GovernmentController extends Controller
{
    use AllTrait;
    public function store(Request $request){
        try{
            //validate request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:191|unique:governments',
                'registration_status' => 'boolean|required',
                'governmentStatus' => 'boolean|required',
                'parent_id' => 'integer'
            ]);
            if ($validator->fails()) {
                return $this->returnError(422, 'sorry this is an error in validation', 'Error', $validator->errors());
            }
            $ids = Government::select('id')->where('parent_id', 0)->get();
            $newIds = $ids->pluck('id')->toArray();
            if($request->governmentStatus == 1){
                if((in_array($request->parent_id , $newIds)) == 1){
                    $parent = $request->parent_id;
                }
                else{
                    return $this->returnError(422, 'sorry this is No parent has this id');
                }
            }else{
                if($request->parent_id != 0){
                    return $this->returnError(422, 'sorry this government is basic cant add parent');
                }else{
                    $parent = $request->parent_id;
                }
                $parent = 0;
    
            }
            //store request in db
            Government::create([
                'name' => $request->name,
                'desc' => $request->desc,
                'registration_status' => $request->registration_status,
                'governmentStatus' => $request->governmentStatus,
                'parent_id' => $parent
            ]);

            $lastGovernment = Government::latest('id')->first();

            return $this->returnSuccess(200, 'this Government is added succssfuly', $lastGovernment );

        }catch(\Exception $ex){
            return $ex;
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function changeReqStatus($id){
        try{
            $government = Government::find($id);
            if(! $government){
                return $this->returnError(200, 'sorry this is not exists');
            }
            if($government['registration_status'] == 1){
                $government->update([
                    'registration_status' => 0
                ]);
                return $this->returnSuccess(200, 'this Government is closed in regester succssfully' );
            }else{
                $government->update([
                    'registration_status' => 1
                ]);
                return $this->returnSuccess(200, 'this Government is open to regester succssfuly' );
            }
        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }
    }
        public function changeGovernmentStatus(Request $request, $id){
        try{
            $government = Government::find($id);
            if(! $government){
                return $this->returnError(200, 'sorry this is not exists');
            }
            //validate request
            $validator = Validator::make($request->all(), [
                'parent_id' => 'required|integer'
            ]);
            if ($validator->fails()) {
                return $this->returnError(422, 'sorry this is an error in validation', 'Error', $validator->errors());
            }
            if($government->governmentStatus == 'Sub Government'){
                $government->update([
                    'governmentStatus' => 0,
                    'parent_id' => 0
                ]);
                return $this->returnSuccess(200, 'this Government is Basic Government succssfully' );
            }else{
                $ids = Government::select('id')->where('parent_id', 0)->get();
                $newIds = $ids->pluck('id')->toArray();
                if((in_array($request->parent_id , $newIds)) == 1){
                    $parent = $request->parent_id;
                }
                else{
                    return $this->returnError(422, 'sorry this is No parent has this id');
                }
                $government->update([
                    'governmentStatus' => 1,
                    'parent_id' => $parent
                ]);
                return $this->returnSuccess(200, 'this Government is changed status succssfuly' );
            }
        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function update(Request $request, $id){
        try{
            //find intiative
            $government = Government::find($id);
            if(! $government){
                return $this->returnError(200, 'sorry this is not exists');
            }
            //validate request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:191',
                'registration_status' => 'boolean|required',
                'governmentStatus' => 'boolean|required',
                'parent_id' => 'integer'
            ]);
            if ($validator->fails()) {
                return $this->returnError(422, 'sorry this is an error in validation', 'Error', $validator->errors());
            }
            $ids = Government::select('id')->where('parent_id', 0)->get();
            $newIds = $ids->pluck('id')->toArray();
            if($request->governmentStatus == 1){
                if((in_array($request->parent_id , $newIds)) == 1){
                    $parent = $request->parent_id;
                }
                else{
                    return $this->returnError(422, 'sorry this is No parent has this id');
                }
            }else{
                if($request->parent_id != 0){
                    return $this->returnError(422, 'sorry this government is basic cant add parent');
                }else{
                    $parent = $request->parent_id;
                }
                $parent = 0;
    
            }
            //store request in db
            $government->update([
                'name' => $request->name,
                'desc' => $request->desc,
                'registration_status' => $request->registration_status,
                'governmentStatus' => $request->governmentStatus,
                'parent_id' => $parent
            ]);

            return $this->returnSuccess(200, 'this Government is updated succssfuly' );

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function getOne($id){
        try{
            $government = Government::find($id);
            if(! $government){
                return $this->returnError(200, 'sorry this is not exists');
            }
            $oneGovernment = collect($government->with(['news'])->where('id', $id)->first());
            $child = $government->child();
            $mearged = $oneGovernment->concat($child);
            return $this->returnData(200, 'there is government', $mearged);
            
        }catch(\Exception $ex){
            return $ex;
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function getAll(){
        try{
            $government = Government::select("*")->with(['news', 'child'])->paginate(PAGINATION_COUNT);
            return $this->returnData(200, 'there is all government', $government);
            
        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function getAllBasics(){
        try{
            $government = Government::select("*")->with(['news', 'child'])->where('governmentStatus', 0)->paginate(PAGINATION_COUNT);
            
            return $this->returnData(200, 'there is all government', $government);
            
        }catch(\Exception $ex){
            return $ex;
        }
    }
    public function getAllSubGovernment(){
        try{
            $government = Government::select("*")->with(['news'])->where('governmentStatus', 1)->paginate(PAGINATION_COUNT);
            
            return $this->returnData(200, 'there is all government', $government);
            
        }catch(\Exception $ex){
            return $ex;
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function destroy($id){
        try{
            $government = Government::find($id);
            if($government){
            //delete news with relation in initiative
            $government->news()->delete();
            //delete employee with relation in initiative
            $government->employee()->delete();
            //delete sub governments
            $government->child()->delete();
            //delete from database
            $government->delete();
            return $this->returnSuccess(200, 'This government successfuly Deleted');

            }
            return $this->returnError(200, 'sorry this id not exists');

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');

        }
    }
}
