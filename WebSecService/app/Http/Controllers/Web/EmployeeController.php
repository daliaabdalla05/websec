<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    public function create()
    {
    
            abort_unless(auth()->user()->hasRole('Admin'), 403);
            return view('admin.employees.create');
        
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $employee = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'credit' => 0 // Employees start with 0 credit
        ]);

        $employeeRole = Role::findByName('Employee');
        $employee->assignRole($employeeRole);

        return redirect()->route('users')
            ->with('success', 'Employee account created successfully!');
    }
}