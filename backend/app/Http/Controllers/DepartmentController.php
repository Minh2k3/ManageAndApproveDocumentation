<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::all()->map(function ($department) {
            return [
                'value' => $department->id,
                'id' => $department->id,
                'label' => $department->name,
                'name' => $department->name,
                'description' => $department->description,
                'group' => $department->group,
                'status' => $department->status,
                'avatar_path' => $department->avatar,
                'phone' => $department->phone_number,
                'position' => $department->position,
                'number_of_users' => $department->number_of_users, // Gọi accessor
                'created_at' => $department->created_at,
                'updated_at' => $department->updated_at,
            ];
        });

        return response()->json([
            'departments' => $departments,
        ])->setStatusCode(200, 'Department retrieved successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:departments,name',
            'description' => 'nullable|string|max:200',
            'phone' => 'nullable|string|regex:/^[0-9]{10}$/',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|string', // Base64 or URL
            'can_approve' => 'boolean',
        ], [
            'name.required' => 'Tên phòng ban là bắt buộc',
            'name.unique' => 'Tên phòng ban đã tồn tại',
            'phone.regex' => 'Số điện thoại phải có đúng 10 chữ số',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->all();
            
            // Handle image upload if provided
            if ($request->filled('image') && $this->isBase64($request->image)) {
                $data['image'] = $this->saveBase64Image($request->image);
            }

            $group = $request->input('group', 'default'); // Default group if not provided
            switch ($group) {
                case 'faculty':
                    $data['group'] = 'Khoa/TT';
                    break;
                case 'lcd':
                    $data['group'] = 'LCĐ';
                    break;
                case 'lch':
                    $data['group'] = 'LCH';
                    break;
                case 'unit':
                    $data['group'] = 'Phòng/Ban';
                    break;
                case 'club':
                    $data['group'] = 'CLB/Đội';
                    break;
                default:
                    $data['group'] = 'Khác'; // Default group
            }

            $department = Department::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'phone_number' => $data['phone'] ?? null,
                'location' => $data['location'] ?? null,
                'avatar' => $data['image'] ?? null,
                'can_approve' => $data['can_approve'] ?? false,
                'status' => 1, // Assuming status is active by default
                'group' => $data['group'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thêm phòng ban thành công',
                'id' => $department['id'],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tạo phòng ban',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        //
    }

    public function getDepartmentsCanApprove()
    {
        $departments = \DB::table('departments')
            ->select(
                'id as value',
                'name as label',
                'status',
                'avatar as avatar_path',
                'group',
            )
            ->where('can_approve', 1)
            ->get();

        return response()->json([
            'departments' => $departments,
        ])->setStatusCode(200, 'Department retrieved successfully.');
    }

    // Hàm xử lý upload ảnh
    public function uploadFile(Request $request)
    {
        $file = $request->file('upload_file');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('', $filename, 'departments');

        // Update file_path cho Departments
        $department = Department::find($request['department_id']);
        $department->file_path = $filename; 
        $department->save();

        return response()->json([
            'message' => 'Upload file thành công',
            'file_url' => url(`/departments/` . $filename),
        ]);
    }
}
