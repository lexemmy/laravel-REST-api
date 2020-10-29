<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use App\User;

class ApiController extends Controller
{

public function register(Request $request){
    $validateData = $request->validate([
        'name' => 'required|max:55',
        'email' => 'email|required|unique:users',
        'password' => 'required|confirmed'
    ]);
    $validateData['password'] = bcrypt($request->password);

    $user = User::create($validateData);

    $accessToken = $user->createToken('authToken')->accessToken;

    return response(['user' => $user, 'access_token' => $accessToken]);
}

public function login(Request $request){
    $loginData = $request->validate([
        'email' => 'email|required',
        'password' => 'required'
    ]);

    if(!auth()->attempt($loginData)){
        return response(['message' => 'Invalid Credentials']);
    }

    $accessToken = auth()->user()->createToken('authToken')->accessToken;

    return response(['user' => auth()->user(), 'access_token' => $accessToken]);
}

public function getAllStudents(){
    $students = Student::get()->toJson(JSON_PRETTY_PRINT);
    return response($students, 200);
}
public function createStudent(Request $request){
    $student = new Student;
    $student->name = $request->name;
    $student->course = $request->course;
    $student->save();

    return response()->json(["message" => "student record created successfully"], 200);
}
public function getStudent($id){
    if (Student::where('id', $id)->exists()){
        $student = Student::where('id', $id)->get()->toJson(JSON_PRETTY_PRINT);
        return response($student, 200);
    } else{
        return response()->json(["message" => "student not found"], 404);
    }
}
public function updateStudent(Request $request, $id){
    if (Student::where('id', $id)->exists()){
        $student = Student::find($id);
        $student->name = is_null($request->name) ? $student->name : $request->name;
        $student->course = is_null($request->course) ? $student->course : $request->course;
        $student->save();

        return response()->json(["message" => "$student->name"], 200);
    } else{
        return response()->json(["message" => "student not found"], 404); 
    }
}
public function deleteStudent($id){
    if (Student::where('id', $id)->exists()){
        $student = Student::find($id);
        $student->delete();

        return response()->json(["message" => "student record deleted succefully"], 200);
    } else{
        return response()->json(["message" => "student not found"], 404);
    }
}
}
