<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Approver;
use App\Models\Creator;
use App\Models\RoleAtDepartment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
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
                'can_approve' => $department->can_approve,
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
    public function update($id, Request $request)
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

            // Find the department by ID
            $department = Department::findOrFail($id);
            if (!$department) {
                return response()->json([
                    'success' => false,
                    'message' => 'Phòng ban không tồn tại',
                ], 404);
            }

            // Update the department
            $department->name = $data['name'];
            $department->description = $data['description'] ?? null;
            $department->phone_number = $data['phone'] ?? null;
            $department->location = $data['location'] ?? null;
            $department->avatar = $data['image'] ?? $department->avatar; // Keep existing avatar if no new image provided
            $department->can_approve = $data['can_approve'] ?? $department->can_approve; // Keep existing value if not provided
            $department->group = $data['group'];
            $department->updated_at = now();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật phòng ban thành công',
                'id' => $department['id'],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật phòng ban',
                'error' => $e->getMessage()
            ], 500);
        }
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
            ->where('status', 'active')
            ->where('group', '!=', 'CLB/Đội')
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

    /**
     * Lấy danh sách người dùng trong đơn vị
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMembers(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'department_id' => 'required|exists:departments,id',
                'search' => 'nullable|string|max:100',
                'role_id' => 'nullable|exists:roll_at_department,id',
                'page' => 'nullable|integer|min:1',
                'per_page' => 'nullable|integer|min:1|max:100',
                'sort_by' => 'nullable|string|in:name,email,created_at,role_level',
                'sort_direction' => 'nullable|string|in:asc,desc',
            ]);

            // Lấy thông tin department_id
            $departmentId = $request->input('department_id');
            $search = $request->input('search', '');
            $roleId = $request->input('role_id');
            $perPage = $request->input('per_page', 10);
            $sortBy = $request->input('sort_by', 'name');
            $sortDirection = $request->input('sort_direction', 'asc');

            // Query cơ bản
            $query = User::query()
                ->join('approvers', 'users.id', '=', 'approvers.user_id')
                ->join('roll_at_department', 'approvers.roll_at_department_id', '=', 'roll_at_department.id')
                ->join('departments', 'approvers.department_id', '=', 'departments.id')
                ->leftJoin('approvals', function($join) {
                    $join->on('users.id', '=', 'approvals.user_id')
                        ->whereNull('approvals.deleted_at');
                })
                ->where('approvers.department_id', $departmentId)
                ->whereNull('approvers.deleted_at')
                ->select([
                    'users.id',
                    'users.name',
                    'users.email',
                    'users.avatar',
                    'users.created_at',
                    'users.status',
                    'roll_at_department.id as role_id',
                    'roll_at_department.name as role_name',
                    'roll_at_department.level as role_level',
                    'departments.id as department_id',
                    'departments.name as department_name',
                    DB::raw('COUNT(DISTINCT approvals.id) as document_count')
                ])
                ->groupBy('users.id');

            // Tìm kiếm theo tên
            if (!empty($search)) {
                $query->where('users.name', 'like', "%{$search}%");
            }

            // Lọc theo vai trò
            if ($roleId) {
                $query->where('roll_at_department.id', $roleId);
            }

            // Sắp xếp
            switch ($sortBy) {
                case 'name':
                    $query->orderBy('users.name', $sortDirection);
                    break;
                case 'email':
                    $query->orderBy('users.email', $sortDirection);
                    break;
                case 'created_at':
                    $query->orderBy('users.created_at', $sortDirection);
                    break;
                case 'role_level':
                    $query->orderBy('roll_at_department.level', $sortDirection);
                    break;
                default:
                    $query->orderBy('users.name', $sortDirection);
            }

            // Lấy danh sách các vai trò trong đơn vị để hiển thị dropdown lọc
            $roles = RoleAtDepartment::query()
                ->select('id', 'name', 'level')
                ->orderBy('level')
                ->get();

            // Phân trang kết quả
            $members = $query->paginate($perPage);

            // Lấy thông tin chi tiết về department
            $department = Department::findOrFail($departmentId);
            
            // Lấy thông tin về trưởng đơn vị
            $departmentHead = User::query()
                ->join('approvers', 'users.id', '=', 'approvers.user_id')
                ->join('roll_at_department', 'approvers.roll_at_department_id', '=', 'roll_at_department.id')
                ->where('approvers.department_id', $departmentId)
                ->where('roll_at_department.level', 1)
                ->select('users.id', 'users.name')
                ->first();

            // Tổng số thành viên trong đơn vị
            $totalMembers = User::query()
                ->join('approvers', 'users.id', '=', 'approvers.user_id')
                ->where('approvers.department_id', $departmentId)
                ->whereNull('approvers.deleted_at')
                ->count();

            // Trả về kết quả
            return response()->json([
                'success' => true,
                'data' => [
                    'members' => $members,
                    'department' => [
                        'id' => $department->id,
                        'name' => $department->name,
                        'code' => $department->code,
                        'head' => $departmentHead,
                        'memberCount' => $totalMembers
                    ],
                    'roles' => $roles
                ],
                'message' => 'Lấy danh sách thành viên thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy danh sách thành viên: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy thông tin quyền phê duyệt loại văn bản của một thành viên
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMemberApprovalPermissions(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'department_id' => 'required|exists:departments,id',
            ]);

            $userId = $request->input('user_id');
            $departmentId = $request->input('department_id');

            // Kiểm tra user có thuộc department không
            $isInDepartment = DB::table('approvers')
                ->where('user_id', $userId)
                ->where('department_id', $departmentId)
                ->whereNull('deleted_at')
                ->exists();

            if (!$isInDepartment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Người dùng không thuộc đơn vị này'
                ], 400);
            }

            // Lấy thông tin người dùng
            $user = User::with(['approver' => function($query) use ($departmentId) {
                $query->where('department_id', $departmentId);
            }, 'approver.roleAtDepartment'])->findOrFail($userId);

            // Lấy quyền phê duyệt văn bản của người dùng này
            $documentPermissions = DB::table('document_type_permissions')
                ->join('document_types', 'document_type_permissions.document_type_id', '=', 'document_types.id')
                ->where('document_type_permissions.user_id', $userId)
                ->where('document_type_permissions.department_id', $departmentId)
                ->whereNull('document_type_permissions.deleted_at')
                ->select([
                    'document_types.id',
                    'document_types.name',
                    'document_types.description'
                ])
                ->get();

            // Lấy tất cả loại văn bản để hiển thị checkbox
            $allDocumentTypes = DB::table('document_types')
                ->whereNull('deleted_at')
                ->select(['id', 'name', 'description'])
                ->get();

            // Check xem user có quyền phê duyệt không (có ít nhất 1 loại văn bản được phê duyệt)
            $hasApprovalPermission = $documentPermissions->count() > 0;

            // Danh sách ID các loại văn bản được phê duyệt
            $approvalDocumentTypeIds = $documentPermissions->pluck('id')->toArray();

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'avatar' => $user->avatar,
                        'role' => $user->approver->roleAtDepartment ? [
                            'id' => $user->approver->roleAtDepartment->id,
                            'name' => $user->approver->roleAtDepartment->name,
                            'level' => $user->approver->roleAtDepartment->level
                        ] : null
                    ],
                    'hasApprovalPermission' => $hasApprovalPermission,
                    'approvalDocumentTypes' => $documentPermissions,
                    'approvalDocumentTypeIds' => $approvalDocumentTypeIds,
                    'allDocumentTypes' => $allDocumentTypes
                ],
                'message' => 'Lấy thông tin quyền phê duyệt thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy thông tin quyền phê duyệt: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật quyền phê duyệt loại văn bản cho một thành viên
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateMemberApprovalPermissions(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'department_id' => 'required|exists:departments,id',
                'has_approval_permission' => 'required|boolean',
                'document_type_ids' => 'nullable|array',
                'document_type_ids.*' => 'exists:document_types,id',
            ]);

            $userId = $request->input('user_id');
            $departmentId = $request->input('department_id');
            $hasApprovalPermission = $request->input('has_approval_permission');
            $documentTypeIds = $request->input('document_type_ids', []);

            // Kiểm tra xem người dùng hiện tại có phải là trưởng đơn vị không
            $currentUserId = auth()->id();
            $isHeadOfDepartment = DB::table('approvers')
                ->join('roll_at_department', 'approvers.roll_at_department_id', '=', 'roll_at_department.id')
                ->where('approvers.user_id', $currentUserId)
                ->where('approvers.department_id', $departmentId)
                ->where('roll_at_department.level', 1)
                ->exists();

            if (!$isHeadOfDepartment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền cập nhật quyền phê duyệt'
                ], 403);
            }

            // Kiểm tra user có thuộc department không
            $isInDepartment = DB::table('approvers')
                ->where('user_id', $userId)
                ->where('department_id', $departmentId)
                ->whereNull('deleted_at')
                ->exists();

            if (!$isInDepartment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Người dùng không thuộc đơn vị này'
                ], 400);
            }

            // Xóa tất cả quyền hiện tại
            DB::table('document_type_permissions')
                ->where('user_id', $userId)
                ->where('department_id', $departmentId)
                ->delete();

            // Nếu có quyền phê duyệt và có các loại văn bản được chọn
            if ($hasApprovalPermission && !empty($documentTypeIds)) {
                $permissions = [];
                foreach ($documentTypeIds as $documentTypeId) {
                    $permissions[] = [
                        'user_id' => $userId,
                        'department_id' => $departmentId,
                        'document_type_id' => $documentTypeId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                DB::table('document_type_permissions')->insert($permissions);
            }

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật quyền phê duyệt thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật quyền phê duyệt: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy thông tin vai trò của người dùng hiện tại trong đơn vị
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrentUserRoleInDepartment(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'department_id' => 'required|exists:departments,id',
            ]);

            $departmentId = $request->input('department_id');
            $userId = auth()->id();

            // Lấy thông tin vai trò của người dùng hiện tại trong đơn vị
            $userRole = DB::table('approvers')
                ->join('roll_at_department', 'approvers.roll_at_department_id', '=', 'roll_at_department.id')
                ->where('approvers.user_id', $userId)
                ->where('approvers.department_id', $departmentId)
                ->whereNull('approvers.deleted_at')
                ->select([
                    'roll_at_department.id',
                    'roll_at_department.name',
                    'roll_at_department.level',
                ])
                ->first();

            if (!$userRole) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không thuộc đơn vị này'
                ], 400);
            }

            // Xác định các quyền dựa trên level
            $permissions = [];
            if ($userRole->level == 1) {
                $permissions = [
                    'Quản lý đơn vị',
                    'Phân quyền văn bản',
                    'Duyệt văn bản',
                    'Tạo văn bản'
                ];
            } else if ($userRole->level == 2) {
                $permissions = [
                    'Duyệt văn bản',
                    'Tạo văn bản'
                ];
            } else {
                $permissions = [
                    'Tạo văn bản'
                ];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $userRole->id,
                    'name' => $userRole->name,
                    'level' => $userRole->level,
                    'permissions' => implode(', ', $permissions),
                    'isHeadOfDepartment' => $userRole->level == 1
                ],
                'message' => 'Lấy thông tin vai trò thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy thông tin vai trò: ' . $e->getMessage()
            ], 500);
        }
    }    

    /**
     * Lấy thông tin của một đơn vị và danh sách người dùng trong đơn vị đó
     * 
     * @param int $id ID của đơn vị cần lấy thông tin
     * @return JsonResponse
     */
    public function getDepartmentUsers($id)
    {
        // Lấy thông tin đơn vị
        $department = Department::findOrFail($id);
        
        // Lấy danh sách approvers trong đơn vị, kèm theo thông tin về quyền phê duyệt văn bản
        $approvers = Approver::with([
            'user:id,name,email,phone,avatar,status,sex,created_at', 
            'rollAtDepartment:id,name,level',
            'documentTypes:id,name,description' // Lấy thông tin về loại văn bản mà vai trò này có quyền phê duyệt
        ])
        ->where('department_id', $id)
        ->get();
            
        // Lấy danh sách creators trong đơn vị
        $creators = Creator::with([
            'user:id,name,email,phone,avatar,status,sex,created_at', 
            'rollAtDepartment:id,name,level'
        ])
        ->where('department_id', $id)
        ->get();
            
        // Gộp danh sách người dùng và loại bỏ trùng lặp (nếu có)
        $usersList = [];
        $processedUserIds = [];
        $headOfDeparment = $approvers->firstWhere('rollAtDepartment.level', 1);
        
        // Xử lý approvers
        foreach ($approvers as $approver) {
            if (!in_array($approver->user_id, $processedUserIds)) {
                // Lấy danh sách loại văn bản mà approver có quyền phê duyệt
                $documentTypes = [];
                if ($approver->rollAtDepartment && $approver->rollAtDepartment->documentTypes) {
                    $documentTypes = $approver->rollAtDepartment->documentTypes->map(function($documentType) {
                        return [
                            'id' => $documentType->id,
                            'name' => $documentType->name,
                            'description' => $documentType->description
                        ];
                    })->toArray();
                }
                
                $userInfo = [
                    'id' => $approver->user_id,
                    'name' => $approver->user->name,
                    'email' => $approver->user->email,
                    'phone' => $approver->user->phone,
                    'avatar' => $approver->user->avatar,
                    'status' => $approver->user->status,
                    'sex' => $approver->user->sex,
                    'created_at' => $approver->user->created_at,
                    'can_approve' => true,
                    'role' => [
                        'id' => $approver->rollAtDepartment->id,
                        'name' => $approver->rollAtDepartment->name,
                        'level' => $approver->rollAtDepartment->level,
                        'is_manager' => $approver->rollAtDepartment->level === 1 // level 1 là trưởng đơn vị
                    ],
                    'full_role' => $approver->full_role,
                    'document_types' => $documentTypes // Thêm thông tin về loại văn bản có quyền phê duyệt
                ];
                
                $usersList[] = $userInfo;
                $processedUserIds[] = $approver->user_id;
            }
        }
        
        // Xử lý creators
        foreach ($creators as $creator) {
            if (!in_array($creator->user_id, $processedUserIds)) {
                $userInfo = [
                    'id' => $creator->user_id,
                    'name' => $creator->user->name,
                    'email' => $creator->user->email,
                    'phone' => $creator->user->phone,
                    'avatar' => $creator->user->avatar,
                    'status' => $creator->user->status,
                    'sex' => $creator->user->sex,
                    'created_at' => $creator->user->created_at,
                    'can_approve' => false,
                    'role' => [
                        'id' => $creator->rollAtDepartment->id,
                        'name' => $creator->rollAtDepartment->name,
                        'level' => $creator->rollAtDepartment->level,
                        'is_manager' => $creator->rollAtDepartment->level === 1 // level 1 là trưởng đơn vị
                    ],
                    'full_role' => $creator->full_role,
                    'document_types' => [] // Creator không có quyền phê duyệt văn bản
                ];
                
                $usersList[] = $userInfo;
                $processedUserIds[] = $creator->user_id;
            }
        }
        
        // Lấy thông tin đơn vị
        $departmentInfo = [
            'id' => $department->id,
            'name' => $department->name,
            'description' => $department->description,
            'group' => $department->group,
            'status' => $department->status,
            'avatar_path' => $department->avatar,
            'phone' => $department->phone_number,
            'position' => $department->position,
            'can_approve' => $department->can_approve,
            'created_at' => $department->created_at,
            'updated_at' => $department->updated_at,
            'head_of_department' => $headOfDeparment ? [
                'id' => $headOfDeparment->user_id,
                'name' => $headOfDeparment->user->name
            ] : null
        ];
        
        return response()->json([
            'department' => $departmentInfo,
            'users' => $usersList,
            'total_users' => count($usersList)
        ])->setStatusCode(200, 'Department information and users retrieved successfully.');
    } 
}
