<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user || (!$user->isAdmin() && !$user->isHead())) {
            abort(403, 'Admin or Team Lead access required.');
        }

        $employees = User::whereIn('role', ['employee', 'accountant', 'head', 'tl', 'team_lead', 'admin'])
            ->withCount(['leads', 'bookings'])
            ->get();

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user || (!$user->isAdmin() && !$user->isHead())) {
            abort(403, 'Only Admin or Team Lead (TL) has permission to create new employees.');
        }

        return view('employees.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || (!$user->isAdmin() && !$user->isHead())) {
            abort(403, 'Only Admin or Team Lead (TL) has permission to create new employees.');
        }

        // If logged in user is TL (Head) and not Admin, force role to employee
        if ($user->isHead() && !$user->isAdmin()) {
            $request->merge(['role' => 'employee']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'login_id' => 'required|string|max:50|unique:users,login_id',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:employee,accountant,head,admin',
            'phone' => 'nullable|string|max:15',
        ]);

        User::create([
            'name' => $request->name,
            'login_id' => strtoupper(trim($request->login_id)),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'status' => 'active',
            'is_active' => true,
        ]);

        return redirect()->route('employees.index')->with('success', "Employee created successfully with Login ID: {$request->login_id}");
    }

    public function edit($id)
    {
        $user = Auth::user();
        if (!$user || (!$user->isAdmin() && !$user->isHead())) {
            abort(403, 'Admin or Team Lead access required.');
        }

        $employee = User::findOrFail($id);

        if ($user->isHead() && !$user->isAdmin() && $employee->role !== 'employee') {
            abort(403, 'Team Leads can only edit details of regular employees.');
        }

        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || (!$user->isAdmin() && !$user->isHead())) {
            abort(403, 'Admin or Team Lead access required.');
        }

        $employee = User::findOrFail($id);

        if ($user->isHead() && !$user->isAdmin()) {
            if ($employee->role !== 'employee') {
                abort(403, 'Team Leads can only update regular employees.');
            }
            $request->merge(['role' => 'employee']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'login_id' => "required|string|max:50|unique:users,login_id,{$id}",
            'email' => "required|string|email|max:255|unique:users,email,{$id}",
            'role' => 'required|in:employee,accountant,head,admin',
            'status' => 'required|in:active,inactive',
            'phone' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:6',
        ]);

        $data = [
            'name' => $request->name,
            'login_id' => strtoupper(trim($request->login_id)),
            'email' => $request->email,
            'role' => $request->role,
            'status' => $request->status,
            'is_active' => ($request->status === 'active'),
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $employee->update($data);

        return redirect()->route('employees.index')->with('success', 'Employee details updated successfully!');
    }

    public function toggleAccess(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || (!$user->isAdmin() && !$user->isHead())) {
            abort(403, 'Admin or Team Lead access required.');
        }

        $employee = User::findOrFail($id);

        if ($user->isHead() && !$user->isAdmin() && $employee->role !== 'employee') {
            abort(403, 'Team Leads can only revoke or grant access for regular employees.');
        }

        $newStatus = ($employee->status === 'active') ? 'inactive' : 'active';
        
        $employee->update([
            'status' => $newStatus,
            'is_active' => ($newStatus === 'active'),
        ]);

        $actionText = ($newStatus === 'active') ? 'GRANTED access to' : 'REVOKED access from';
        return back()->with('success', "Successfully {$actionText} {$employee->name} ({$employee->login_id}).");
    }

    public function destroy($id)
    {
        $user = Auth::user();
        if (!$user || (!$user->isAdmin() && !$user->isHead())) {
            abort(403, 'Admin or Team Lead access required.');
        }

        $employee = User::findOrFail($id);

        if ($employee->id === $user->id) {
            return back()->with('error', 'You cannot delete your own logged-in account.');
        }

        // Team Lead can only delete regular employees
        if ($user->isHead() && !$user->isAdmin() && $employee->role !== 'employee') {
            abort(403, 'Team Leads can only delete regular employees, not Accountants or Admins.');
        }

        $employeeName = $employee->name;
        $loginId = $employee->login_id;

        $employee->delete();

        return back()->with('success', "Employee account {$employeeName} ({$loginId}) has been permanently deleted.");
    }
}
