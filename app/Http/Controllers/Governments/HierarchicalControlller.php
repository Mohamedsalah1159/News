<?php

namespace App\Http\Controllers\Governments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hierarchical;
use App\Traits\AllTrait;
use Illuminate\Support\Facades\Validator;

class HierarchicalControlller extends Controller
{
    use AllTrait;
    public function store(Request $request){
        try{
            //validate request
            $validator = Validator::make($request->all(), [
                'position' => 'required|string|max:191',
                'parent' => 'integer|max:11',
            ]);
            if ($validator->fails()) {
                return $this->returnError(422, 'sorry this is an error in validation', 'Error', $validator->errors());
            }
            //store request in db
            Hierarchical::create([
                'position' => $request->position,
                'parent' => $request->parent
            ]);
            $lastHierarchical = Hierarchical::latest('id')->first();
            return $this->returnSuccess(200, 'this Hierarchical is added succssfuly', $lastHierarchical );

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function update(Request $request, $id){
        try{
            //find intiative
            $hierarchical = Hierarchical::find($id);
            if(! $hierarchical){
                return $this->returnError(422, 'sorry this is not exists');
            }
            //validate request
            $validator = Validator::make($request->all(), [
                'position' => 'required|string|max:191',
                'parent' => 'integer|max:11',
            ]);
            if ($validator->fails()) {
                return $this->returnError(422, 'sorry this is an error in validation', 'Error', $validator->errors());
            }
            //store request in db
            $hierarchical->update([
                'position' => $request->position,
                'parent' => $request->parent
            ]);

            return $this->returnSuccess(200, 'this Hierarchical is updated succssfuly' );

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function getAll(){
        try{
            $hierarchical = Hierarchical::select("*")->paginate(PAGINATION_COUNT);
            return $this->returnData(200, 'there is all hierarchical', $hierarchical);
        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function destroy($id){
        try{
            $hierarchical = Hierarchical::find($id);
            if($hierarchical){
            //delete from database
            $hierarchical->delete();
            return $this->returnSuccess(200, 'This hierarchical successfuly Deleted');

            }
            return $this->returnError(422, 'sorry this id not exists');

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');

        }
    }
}
