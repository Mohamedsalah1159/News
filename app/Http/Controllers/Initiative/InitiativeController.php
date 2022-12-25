<?php

namespace App\Http\Controllers\Initiative;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Initiative;
use App\Traits\AllTrait;
use Illuminate\Support\Facades\Validator;


class InitiativeController extends Controller
{
    use AllTrait;
    public function store(Request $request){
        try{
            //validate request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:191',
                'desc' => 'required|string',
            ]);
            if ($validator->fails()) {
                return $this->returnError(422, 'sorry this is an error in validation', 'Error', $validator->errors());
            }
            //store request in db
            Initiative::create([
                'name' => $request->name,
                'desc' => $request->desc
            ]);
            $lastInitiative = Initiative::latest('id')->first();
            return $this->returnSuccess(200, 'this Initiative is added succssfuly', $lastInitiative );

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function update(Request $request, $id){
        try{
            //find intiative
            $initiative = Initiative::find($id);
            if(! $initiative){
                return $this->returnError(422, 'sorry this is not exists');
            }
            //validate request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:191',
                'desc' => 'required|string',
            ]);
            if ($validator->fails()) {
                return $this->returnError(422, 'sorry this is an error in validation', 'Error', $validator->errors());
            }
            //store request in db
            $initiative->update([
                'name' => $request->name,
                'desc' => $request->desc
            ]);

            return $this->returnSuccess(200, 'this Initiative is updated succssfuly' );

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function getOne($id){
        try{
            $initiative = Initiative::find($id);
            if(! $initiative){
                return $this->returnError(422, 'sorry this is not exists');
            }
            $oneInitiative = $initiative->with(['news'])->where('id', $id)->first();

            return $this->returnData(200, 'there is all initiative', $oneInitiative);
            
        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function getAll(){
        try{
            $initiative = Initiative::select("*")->with(['news'])->paginate(PAGINATION_COUNT);
            
            return $this->returnData(200, 'there is all initiative', $initiative);
            
        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function destroy($id){
        try{
            $initiative = Initiative::find($id);
            if($initiative){
            //delete news with relation in initiative
            $initiative->news()->delete();
            //delete from database
            $initiative->delete();
            return $this->returnSuccess(200, 'This initiative successfuly Deleted');

            }
            return $this->returnError(422, 'sorry this id not exists');

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');

        }
    }
}
