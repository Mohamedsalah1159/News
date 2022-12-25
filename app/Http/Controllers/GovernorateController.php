<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\AllTrait;
use App\Models\Governorate;
use Illuminate\Support\Facades\Validator;

class GovernorateController extends Controller
{
    use AllTrait;
    public function store(Request $request){
        try{
            //validation
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:191|unique:governorates',
            ]);

            if ($validator->fails()) {
                return $this->returnError(422, 'sorry this is an error', 'Error', $validator->errors());
            }
            Governorate::create([
                'name' => $request->name
            ]);
            $lastGovernorate = Governorate::latest('id')->first();
            return $this->returnSuccess(200, 'this governorate is added succssfuly', $lastGovernorate );

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function getAll(){
        try{
            $gover = Governorate::select('*')->paginate(PAGINATION_COUNT);
            return $this->returnData(200, 'there is all governorates', $gover);
        }
        catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function destroy($id){
        try{
            $gover = Governorate::find($id);
            if($gover){
            //delete from database
            $gover->delete();
            return $this->returnSuccess(200, 'This governorate successfuly Deleted');

            }
            return $this->returnError(200, 'sorry this id not exists');

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');

        }

    }

}
