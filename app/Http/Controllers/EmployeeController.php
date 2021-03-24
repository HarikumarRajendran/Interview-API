<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index()
    {
        $employee = auth()->user()->employee;
 
        return response()->json([
            'success' => true,
            'data' => $employee
        ]);
    }
 
    public function show($id)
    {
        $employee = auth()->user()->employee()->find($id);
 
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found '
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $employee->toArray()
        ], 400);
    }
 
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'designation' => 'required',
            'department' => 'required'
        ]);
 
        $employee = new Employee();
        $employee->name = $request->name;
        $employee->phone = $request->phone;
        $employee->email = $request->email;
        $employee->designation = $request->designation;
        $employee->department = $request->department;
        $employee->user_id = auth()->user()->id;
 
        if (auth()->user()->employee()->save($employee))
            return response()->json([
                'success' => true,
                'data' => $employee->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Employee not added'
            ], 500);
    }
 
    public function update(Request $request, $id)
    {
        $employee = auth()->user()->employee()->find($id);
 
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 400);
        }
 
        $updated = $employee->fill($request->all())->save();
 
        if ($updated)
            return response()->json([
                'success' => true
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Employee can not be updated'
            ], 500);
    }
 
    public function destroy($id)
    {
        $employee = auth()->user()->employee()->find($id);
 
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 400);
        }
 
        if ($employee->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Employee can not be deleted'
            ], 500);
        }
    }
}
