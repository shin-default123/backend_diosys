<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeeHistory;
use Illuminate\Support\Arr;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $roleGroup = $request->query('role_group');
        $archived = $request->query('archived', '0');

        $query = Employee::query();

        if ($archived === '1') {
            $query->onlyTrashed();
        }

        if ($roleGroup) {
            $query->where('role_group', $roleGroup);
        }

        return response()->json(
            $query->orderBy('last_name')->orderBy('first_name')->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'role' => 'required|string',
            'role_group' => 'required|string',

            // optional fields
            'middle_name' => 'nullable|string',
            'assigned_services' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'status' => 'nullable|string',
            'date_hired' => 'nullable|date',

            'date_of_birth' => 'nullable|date',
            'age' => 'nullable|integer|min:0',
            'place_of_birth' => 'nullable|string',
            'gender' => 'nullable|string',
            'citizenship' => 'nullable|string',
            'religion' => 'nullable|string',
            'civil_status' => 'nullable|string',

            'country' => 'nullable|string',
            'state_province' => 'nullable|string',
            'city_municipality' => 'nullable|string',
            'zipcode' => 'nullable|string',
            'barangay' => 'nullable|string',
            'complete_address' => 'nullable|string',

            'highest_educational_attainment' => 'nullable|string',
            'church_assigned' => 'nullable|string',
            'formation' => 'nullable|string',
            'date_of_ordination' => 'nullable|date',
            'years_in_ministry' => 'nullable|integer|min:0',

            'license_no' => 'nullable|string',
            'license_expiration' => 'nullable|date',
            'ordination_deacons' => 'nullable|string',
            'ordination_canonical_form' => 'nullable|string',
        ]);

        $employee = Employee::create($validated);

        $editedBy = $request->user()?->name ?? $request->user()?->email ?? 'System';

        $this->logFieldChanges($employee, [], $employee->toArray(), $editedBy);

        return response()->json($employee, 201);
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'role' => 'required|string',
            'role_group' => 'required|string',

            'middle_name' => 'nullable|string',
            'assigned_services' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'status' => 'nullable|string',
            'date_hired' => 'nullable|date',

            'date_of_birth' => 'nullable|date',
            'age' => 'nullable|integer|min:0',
            'place_of_birth' => 'nullable|string',
            'gender' => 'nullable|string',
            'citizenship' => 'nullable|string',
            'religion' => 'nullable|string',
            'civil_status' => 'nullable|string',

            'country' => 'nullable|string',
            'state_province' => 'nullable|string',
            'city_municipality' => 'nullable|string',
            'zipcode' => 'nullable|string',
            'barangay' => 'nullable|string',
            'complete_address' => 'nullable|string',

            'highest_educational_attainment' => 'nullable|string',
            'church_assigned' => 'nullable|string',
            'formation' => 'nullable|string',
            'date_of_ordination' => 'nullable|date',
            'years_in_ministry' => 'nullable|integer|min:0',

            'license_no' => 'nullable|string',
            'license_expiration' => 'nullable|date',
            'ordination_deacons' => 'nullable|string',
            'ordination_canonical_form' => 'nullable|string',
        ]);

        $editedBy = $request->user()?->name ?? $request->user()?->email ?? 'System';

        // Track changes before update
        $before = $employee->toArray();
        $employee->update($validated);
        $after = $employee->fresh()->toArray();

        $this->logFieldChanges($employee, $before, $after, $editedBy);

        return response()->json($employee);
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        return response()->json(['message' => 'Archived']);
    }

    public function restore($id)
    {
        $employee = Employee::onlyTrashed()->findOrFail($id);
        $employee->restore();
        return response()->json(['message' => 'Restored']);
    }

    public function history(Request $request)
    {
        $roleGroup = $request->query('role_group');
        $employeeId = $request->query('employee_id');

        $query = EmployeeHistory::query()->orderByDesc('time_stamp');

        if ($roleGroup) {
            $query->where('subject_updated', $roleGroup);
        }

        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }

        return response()->json($query->limit(500)->get());
    }

    private function logFieldChanges(Employee $employee, array $before, array $after, string $editedBy): void
    {
        $track = [
            'first_name','middle_name','last_name',
            'role','role_group','assigned_services',
            'email','phone','status','date_hired',
            'date_of_birth','age','place_of_birth','gender','citizenship','religion','civil_status',
            'country','state_province','city_municipality','zipcode','barangay','complete_address',
            'highest_educational_attainment','church_assigned','formation','date_of_ordination','years_in_ministry',
            'license_no','license_expiration','ordination_deacons','ordination_canonical_form',
        ];

        foreach ($track as $field) {
            $old = Arr::get($before, $field);
            $new = Arr::get($after, $field);

            $oldNorm = ($old === '') ? null : $old;
            $newNorm = ($new === '') ? null : $new;

            if ((string)$oldNorm === (string)$newNorm) continue;

            EmployeeHistory::create([
                'employee_id' => $employee->id,
                'subject_updated' => $employee->role_group,
                'full_name' => trim(($employee->first_name ?? '').' '.($employee->last_name ?? '')),
                'field' => $this->prettyField($field),
                'old_value' => is_array($oldNorm) ? json_encode($oldNorm) : (string)($oldNorm ?? ''),
                'new_value' => is_array($newNorm) ? json_encode($newNorm) : (string)($newNorm ?? ''),
                'edited_by' => $editedBy,
                'time_stamp' => now(),
            ]);
        }
    }

    private function prettyField(string $field): string
    {
        return ucwords(str_replace('_', ' ', $field));
    }
}