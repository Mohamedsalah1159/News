<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\AllTrait;
use App\Models\Folder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FolderController extends Controller
{
    use AllTrait;
    public function store(Request $request){
        try{
            //validation
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:191',
            ]);

            if ($validator->fails()) {
                return $this->returnError(422, 'sorry this is an error', 'Error', $validator->errors());
            }
            Folder::create([
                'name' => $request->name,                
            ]);
            $lastFolder = Folder::latest('id')->first();
            return $this->returnSuccess(200, 'this folder is added succssfuly', $lastFolder );

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }

    }
    public function update(Request $request, $id){
        try{
            //find folder
            $folder = Folder::find($id);
            if(! $folder){
                return $this->returnError(200, 'sorry this is not exists');
            }
            //validate request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:191',
            ]);
            if ($validator->fails()) {
                return $this->returnError(422, 'sorry this is an error in validation', 'Error', $validator->errors());
            }
            //store request in db
            $folder->update([
                'name' => $request->name,
            ]);

            return $this->returnSuccess(200, 'this folder is updated succssfuly' );

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }
    }
    public function getAllFolders(){
        try{
            $folder = Folder::paginate(PAGINATION_COUNT);
            return $this->returnData(200, 'there is all folders', $folder);
        }
        catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }

    }
    public function getOneFolder($id){
        try{
            //find folder
            $folder = Folder::with('gallary')->find($id);
            if(! $folder){
                return $this->returnError(200, 'sorry this is not exists');
            }
            return $this->returnData(200, 'there is folder with his all images', $folder);
        }
        catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');
        }

    }
    public function destroy($id){
        try{
            $folder = Folder::find($id);
            if($folder){
            //delete gallery in this folder 
            $folder->gallary()->delete();
            //delete folder from database
            $folder->delete();
            return $this->returnSuccess(200, 'This folder successfuly Deleted');

            }
            return $this->returnError(200, 'sorry this id not exists');

        }catch(\Exception $ex){
            return $this->returnError(422, 'sorry this is an error');

        }
    }
}
