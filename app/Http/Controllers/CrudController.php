<?php

namespace App\Http\Controllers;
use App\Models\Designation;
use App\Models\Employee;
use Illuminate\Http\Request;
use Hash;
use File;
use Response;
use Validator;
use Mail;
class CrudController extends Controller
{
    public function index()
    {
        $designation=Designation::get();
        return view('index',compact('designation'));
    }
    public function addEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:employees,email',
            'designation' => 'required',
           
        ]);
        if ($validator->passes()) {
            $data=$request->all();
            $photo=$request->file('photo');
            $data['photo']="";
            if($photo)
            {
                $validator = Validator::make($request->all(), [
                        'photo'=>'mimes:jpeg,png,jpg,.jfif|max:5720',
                 ]);
                if($validator->fails())
                {
                    return response()->json(['error'=>$validator->errors()->all()]);
                }
                $extension=$photo->clientExtension();
                $path='/upload';
                $name='upload_'.date('Ymdhis').'.'.$extension;
                $photo->storeAs($path,$name);
                $data['photo']=$name;
            }
            $data['password']=rand();

            #mail function
            /*Mail::raw('email:'.$request->email.'| password : '.$data['password'], function ($message) use ($data['email']) {
            $message->to($email);
            $message->subject('Username and Password');
            $message ->from('email', 'company');
            
            });
*/
            #mail fuction end
            $data['password']=bcrypt($data['password']);
            //Email Function 
            //dd($data);
            Employee::create($data);
            return response()->json(['success'=>'Added new records.']);
        }
        else
        {
            return response()->json(['error'=>$validator->errors()->all()]);

        }
        
       
    }
    public function getEmployee(Request $request)
    {
        $employee=Employee::with('designation')->get();
        return datatables($employee)->make(true);
    }
    function image($name)
    {
        $path=storage_path('app/upload/'.$name);
        $file=File::get($path);
        $type=File::mimeType($path);
        $response=Response::make($file,200);
        $response->header("Content-Type",$type);
        return $response;
    }
    public function getOne(Request $request)
    {
         $employee=Employee::where('id',$request->id)->get();
         return response()->json($employee);
    }
    public function updateEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'designation' => 'required',
            'id'=>'required',
           
        ]);
        if ($validator->passes()) {

            $photo=$request->file('photo');
            $data=$request->all();
            unset($data['id']);
            unset($data['photo']);
            if($photo)
            {    $validator = Validator::make($request->all(), [
                        'photo'=>'mimes:jpeg,png,jpg,.jfif|max:5720',
                 ]);
                if($validator->fails())
                {
                    return response()->json(['error'=>$validator->errors()->all()]);
                }
                $extension=$photo->clientExtension();
                $path='/upload';
                $name='upload_'.date('Ymdhis').'.'.$extension;
                $photo->storeAs($path,$name);
                $data['photo']=$name;
            }
            Employee::where('id',$request->id)->update($data);
            return response()->json(['success'=>'Added new records.']);
        }
        else
        {
            return response()->json(['error'=>$validator->errors()->all()]);
        }
          
        
    }
    public function removeEmployee(Request $request)
    {
        Employee::where('id',$request->id)->delete();
        return response()->json("success");
    }
    
}
