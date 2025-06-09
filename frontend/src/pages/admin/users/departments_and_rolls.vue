<template>
  <div class="departments-roles-management">
    <a-tabs v-model:activeKey="activeTab" type="card">
      <!-- Tab Phòng ban -->
      <a-tab-pane key="departments" tab="Quản lý Phòng ban">
        <div class="tab-content pt-0">
          <!-- Header actions -->
          <div class="header-actions">
            <a-input-search
              v-model:value="departmentSearch"
              placeholder="Tìm kiếm phòng ban..."
              style="width: 300px"
              @search="onSearchDepartments"
            />
            <a-button type="primary" @click="showAddDepartmentModal">
              <i class="bi bi-plus-circle me-2"></i>Thêm phòng ban
            </a-button>
          </div>

          <!-- Bảng phòng ban -->
          <a-table
            :columns="departmentColumns"
            :data-source="filteredDepartments"
            :pagination="departmentPagination"
            :loading="departmentLoading"
            row-key="id"
            @change="onDepartmentTableChange"
            :locale="{
                        triggerDesc: 'Nhấn để sắp xếp giảm dần',
                        triggerAsc: 'Nhấn để sắp xếp tăng dần',
                        cancelSort: 'Nhấn để hủy sắp xếp'
                    }"
          >
            <!-- <template #headerCell="{ column }">
              <template v-if="column.key === 'label'">
                  <a-tooltip title="Sắp xếp theo tên phòng/ban">
                      <span>{{ column.title }}</span>
                  </a-tooltip>
              </template>
              <template v-else-if="column.key === 'status'">
                  <a-tooltip title="Sắp xếp theo trạng thái">
                      <span>{{ column.title }}</span>
                  </a-tooltip>
              </template>
              <template v-else-if="column.key === 'created_at'">
                  <a-tooltip title="Sắp xếp theo ngày tạo">
                      <span>{{ column.title }}</span>
                  </a-tooltip>
              </template> -->
            <!-- </template> -->
            <template #bodyCell="{ column, record }">
              <template v-if="column.key === 'status'">
                <a-tag :color="record.status === 'active' ? 'green' : 'red'">
                  {{ record.status === 'active' ? 'Hoạt động' : 'Ngừng hoạt động' }}
                </a-tag>
              </template>
              <template v-else-if="column.key === 'actions'">
                <a-space>
                  <a-button type="text" @click="editDepartment(record)">
                    <template #icon><edit-outlined /></template>
                  </a-button>
                  <a-popconfirm
                    title="Bạn có chắc chắn muốn xóa phòng ban này?"
                    @confirm="deleteDepartment(record.id)"
                  >
                    <a-button type="text" danger>
                      <template #icon><delete-outlined /></template>
                    </a-button>
                  </a-popconfirm>
                </a-space>
              </template>
            </template>
          </a-table>
        </div>
      </a-tab-pane>

      <!-- Tab Vai trò -->
      <a-tab-pane key="roles" tab="Quản lý Vai trò">
        <div class="tab-content">
          <!-- Header actions -->
          <div class="header-actions">
            <a-input-search
              v-model:value="roleSearch"
              placeholder="Tìm kiếm vai trò..."
              style="width: 300px"
              @search="onSearchRoles"
            />
            <a-button type="primary" @click="showAddRoleModal">
              <template #icon><plus-outlined /></template>
              Thêm vai trò
            </a-button>
          </div>

          <!-- Bảng vai trò -->
          <a-table
            :columns="roleColumns"
            :data-source="filteredRoles"
            :pagination="rolePagination"
            :loading="roleLoading"
            row-key="id"
            @change="onRoleTableChange"
          >
            <template #bodyCell="{ column, record }">
              <template v-if="column.key === 'permissions'">
                <a-tag v-for="permission in record.permissions" :key="permission" color="blue">
                  {{ permission }}
                </a-tag>
              </template>
              <template v-else-if="column.key === 'status'">
                <a-tag :color="record.status === 'active' ? 'green' : 'red'">
                  {{ record.status === 'active' ? 'Hoạt động' : 'Ngừng hoạt động' }}
                </a-tag>
              </template>
              <template v-else-if="column.key === 'actions'">
                <a-space>
                  <a-button type="text" @click="editRole(record)">
                    <template #icon><edit-outlined /></template>
                  </a-button>
                  <a-popconfirm
                    title="Bạn có chắc chắn muốn xóa vai trò này?"
                    @confirm="deleteRole(record.id)"
                  >
                    <a-button type="text" danger>
                      <template #icon><delete-outlined /></template>
                    </a-button>
                  </a-popconfirm>
                </a-space>
              </template>
            </template>
          </a-table>
        </div>
      </a-tab-pane>
    </a-tabs>

    <!-- Modal thêm/sửa phòng ban -->
<!-- Modal thêm/sửa phòng ban -->
    <a-modal
      v-model:visible="departmentModalVisible"
      :title="departmentModalTitle"
      @ok="handleDepartmentSubmit"
      @cancel="resetDepartmentModal"
      width="600px"
    >
      <a-form
        ref="departmentFormRef"
        :model="departmentForm"
        :rules="departmentRules"
        layout="vertical"
      >
        <a-row :gutter="16">
          <a-col :span="10">
            <a-form-item label="Tên phòng ban" name="name">
              <a-input v-model:value="departmentForm.name" placeholder="Nhập tên phòng ban" />
            </a-form-item>
          </a-col>

          <a-col :span="6">
            <a-form-item label="Được phê duyệt" name="can_approve">
              <a-switch 
                v-model:checked="departmentForm.can_approve" 
                checked-children="Có" 
                un-checked-children="Không"
              />
            </a-form-item>
          </a-col>

          <a-col :span="8">
            <a-form-item label="Nhóm" name="group">
              <a-select v-model:value="departmentForm.group" placeholder="Chọn nhóm">
                <a-select-option value="faculty">Khoa/Trung tâm</a-select-option>
                <a-select-option value="lcd">LCĐ</a-select-option>
                <a-select-option value="lch">LCH</a-select-option>
                <a-select-option value="unit">Phòng/Ban/Đơn vị</a-select-option>
                <a-select-option value="club">CLB/Đội</a-select-option>
                <a-select-option value="other">Khác</a-select-option>
              </a-select>
            </a-form-item>
          </a-col>
        </a-row>
        
        <a-row :gutter="16">
          <a-col :span="12">
            <a-form-item label="Số điện thoại" name="phone">
              <a-input 
                v-model:value="departmentForm.phone" 
                placeholder="Nhập số điện thoại"
                allow-clear
              />
            </a-form-item>
          </a-col>
          <a-col :span="12">
            <a-form-item label="Vị trí" name="location">
              <a-input 
                v-model:value="departmentForm.location" 
                placeholder="Vị trí văn phòng"
                allow-clear
              />
            </a-form-item>
          </a-col>
        </a-row>

        <a-row :gutter="16">
          <a-col :span="8">
            <a-form-item label="Hình ảnh" name="image">
              <a-upload
                v-model:file-list="upload_files"
                name="avatar"
                :headers="headers"
                list-type="picture-card"
                class="avatar-uploader"
                accept=".jpg,.jpeg,.png"
                :show-upload-button="true"
                :show-upload-list="false"
                :before-upload="beforeUpload"
                @change="handleImageChange"
              >
                <div v-if="departmentForm.imageUrl">
                  <img :src="departmentForm.imageUrl" alt="avatar" style="width: 100%" />
                </div>
                <div v-else>
                  <i class="bi bi-plus-lg"></i>
                  <div style="margin-top: 8px">Tải ảnh lên</div>
                </div>
              </a-upload>
              <div style="margin-top: 8px; color: #999; font-size: 12px;">
                Hỗ trợ JPG, PNG. Kích thước tối đa 2MB
              </div>
            </a-form-item>
          </a-col>

          <a-col :span="16">
            <a-form-item label="Mô tả" name="description">
              <a-textarea 
                v-model:value="departmentForm.description" 
                placeholder="Nhập mô tả" 
                :rows="3"
                allow-clear
                show-count
                :maxlength="200"
              />
            </a-form-item>
          </a-col>
        </a-row>

        <a-row :gutter="16">
          
        </a-row>
      </a-form>
    </a-modal>

    <!-- Modal thêm/sửa vai trò -->
    <a-modal
      v-model:visible="roleModalVisible"
      :title="roleModalTitle"
      @ok="handleRoleSubmit"
      @cancel="resetRoleModal"
    >
      <a-form
        ref="roleFormRef"
        :model="roleForm"
        :rules="roleRules"
        layout="vertical"
      >
        <a-form-item label="Tên vai trò" name="name">
          <a-input v-model:value="roleForm.name" placeholder="Nhập tên vai trò" />
        </a-form-item>
        <a-form-item label="Mô tả" name="description">
          <a-textarea v-model:value="roleForm.description" placeholder="Nhập mô tả" :rows="3" />
        </a-form-item>
        <a-form-item label="Quyền hạn" name="permissions">
          <a-select
            v-model:value="roleForm.permissions"
            mode="multiple"
            placeholder="Chọn quyền hạn"
          >
            <a-select-option value="read">Xem</a-select-option>
            <a-select-option value="write">Tạo</a-select-option>
            <a-select-option value="update">Cập nhật</a-select-option>
            <a-select-option value="delete">Xóa</a-select-option>
            <a-select-option value="approve">Phê duyệt</a-select-option>
          </a-select>
        </a-form-item>
        <a-form-item label="Trạng thái" name="status">
          <a-select v-model:value="roleForm.status">
            <a-select-option value="active">Hoạt động</a-select-option>
            <a-select-option value="inactive">Ngừng hoạt động</a-select-option>
          </a-select>
        </a-form-item>
      </a-form>
    </a-modal>
  </div>
</template>

<script>
import { ref, reactive, computed, onMounted } from 'vue';
import { message } from 'ant-design-vue';
import { PlusOutlined, EditOutlined, DeleteOutlined } from '@ant-design/icons-vue';
import { useMenu } from '@/stores/use-menu.js';
import {useRegisterStore} from '@/stores/use-register.js';
import axiosInstance from '@/lib/axios';

export default {
  components: {
    PlusOutlined,
    EditOutlined,
    DeleteOutlined,
  },
  setup() {
    useMenu().onSelectedKeys(["admin-users-departments-and-rolls"]);
    const registerStore = useRegisterStore();

    // Reactive data
    const activeTab = ref('departments');
    
    // Mock data cho phòng ban
    const departments = ref([
      {
        id: 1,
        name: 'Phòng Công nghệ thông tin',
        description: 'Quản lý hệ thống công nghệ thông tin của trường',
        status: 'active',
        createdAt: '2024-01-15'
      },
      {
        id: 2,
        name: 'Phòng Đào tạo',
        description: 'Quản lý hoạt động đào tạo và giảng dạy',
        status: 'active',
        createdAt: '2024-01-10'
      },
      {
        id: 3,
        name: 'Phòng Tài chính - Kế toán',
        description: 'Quản lý tài chính và kế toán của trường',
        status: 'inactive',
        createdAt: '2024-01-05'
      }
    ]);

    // Mock data cho vai trò
    const roles = ref([
      {
        id: 1,
        name: 'Quản trị viên hệ thống',
        description: 'Có toàn quyền quản lý hệ thống',
        permissions: ['read', 'write', 'update', 'delete', 'approve'],
        status: 'active',
        createdAt: '2024-01-15'
      },
      {
        id: 2,
        name: 'Cán bộ phê duyệt',
        description: 'Có quyền phê duyệt văn bản',
        permissions: ['read', 'approve'],
        status: 'active',
        createdAt: '2024-01-12'
      },
      {
        id: 3,
        name: 'Người đề xuất',
        description: 'Có quyền tạo và gửi đề xuất',
        permissions: ['read', 'write'],
        status: 'active',
        createdAt: '2024-01-08'
      }
    ]);

    onMounted(async () => {
      await registerStore.fetchRegisterForm();
      departments.value = registerStore.departments;
      roles.value = registerStore.rolls;
    });

    // Search
    const departmentSearch = ref('');
    const roleSearch = ref('');

    // Loading states
    const departmentLoading = ref(false);
    const roleLoading = ref(false);

    // Filtered data
    const filteredDepartments = computed(() => {
      if (!departmentSearch.value) return departments.value;
      return departments.value.filter(dept => 
        dept.name.toLowerCase().includes(departmentSearch.value.toLowerCase()) ||
        dept.description.toLowerCase().includes(departmentSearch.value.toLowerCase())
      );
    });

    const filteredRoles = computed(() => {
      if (!roleSearch.value) return roles.value;
      return roles.value.filter(role => 
        role.name.toLowerCase().includes(roleSearch.value.toLowerCase()) ||
        role.description.toLowerCase().includes(roleSearch.value.toLowerCase())
      );
    });

    // Table columns
    const departmentColumns = [
      {
        title: 'Tên phòng ban',
        dataIndex: 'label',
        key: 'label',
        sorter: (a, b) => a.label.localeCompare(b.label),
        customHeaderCell: () => {
          return { style: { textAlign: 'center' } };
        }
      },
      {
        title: 'Mô tả',
        dataIndex: 'description',
        key: 'description',
        customHeaderCell: () => {
          return { style: { textAlign: 'center' } };
        }
      },
      {
        title: 'Trạng thái',
        dataIndex: 'status',
        key: 'status',
        width: 120,
        align: "center",
      },
      {
        title: 'Số người dùng',
        dataIndex: 'number_of_users',
        key: 'userCount',
        width: 150,
        align: "center",
        
      },
      {
        title: 'Ngày tạo',
        dataIndex: 'created_at',
        key: 'createdAt',
        width: 120,
        sorter: true,
        align: "center",
      },
      {
        title: 'Thao tác',
        key: 'actions',
        width: 120,
        align: "center",
      }
    ];

    const roleColumns = [
      {
        title: 'ID',
        dataIndex: 'id',
        key: 'id',
        width: 80,
        sorter: true
      },
      {
        title: 'Tên vai trò',
        dataIndex: 'name',
        key: 'name',
        sorter: true
      },
      {
        title: 'Mô tả',
        dataIndex: 'description',
        key: 'description'
      },
      {
        title: 'Quyền hạn',
        dataIndex: 'permissions',
        key: 'permissions'
      },
      {
        title: 'Trạng thái',
        dataIndex: 'status',
        key: 'status',
        width: 120
      },
      {
        title: 'Ngày tạo',
        dataIndex: 'createdAt',
        key: 'createdAt',
        width: 120,
        sorter: true
      },
      {
        title: 'Thao tác',
        key: 'actions',
        width: 120
      }
    ];

    // Pagination
    const departmentPagination = reactive({
      current: 1,
      pageSize: 10,
      total: computed(() => filteredDepartments.value.length),
      showSizeChanger: true,
      showQuickJumper: true,
      showTotal: (total, range) => `${range[0]}-${range[1]} của ${total} mục`,
      position: ['bottomCenter'],

    });

    const rolePagination = reactive({
      current: 1,
      pageSize: 10,
      total: computed(() => filteredRoles.value.length),
      showSizeChanger: true,
      showQuickJumper: true,
      showTotal: (total, range) => `${range[0]}-${range[1]} của ${total} mục`
    });

    // Modal states
    const departmentModalVisible = ref(false);
    const roleModalVisible = ref(false);
    const departmentModalTitle = ref('');
    const roleModalTitle = ref('');
    const isEditingDepartment = ref(false);
    const isEditingRole = ref(false);

    // Form refs
    const departmentFormRef = ref();
    const upload_files = ref([]);
    const headers = {
      authorization: 'authorization-text',
    };
    const roleFormRef = ref();

    // Form data
    const departmentForm = reactive({
      id: null,
      name: '',
      description: 'Mô tả ngắn về phòng ban',
      phone: '',
      location: '',
      can_approve: false,
      group: 'other',
    });

    const roleForm = reactive({
      id: null,
      name: '',
      description: 'Mô tả ngắn về vai trò',
      permissions: [],
      status: 'active'
    });

    // Form rules
    const departmentRules = {
      name: [
        { required: true, message: 'Vui lòng nhập tên phòng ban', trigger: 'blur' }
      ],
      description: [
        { required: false },
      ],
      phone: [
        { required: false },
        { 
          pattern: /^[0-9]{10}$/, 
          message: 'Số điện thoại phải có đúng 10 chữ số', 
          trigger: 'blur' 
        }
      ],
    };

    const roleRules = {
      name: [
        { required: true, message: 'Vui lòng nhập tên vai trò', trigger: 'blur' }
      ],
      description: [
        { required: false }
      ],
    };

    // Methods
    const onSearchDepartments = () => {
      departmentPagination.current = 1;
    };

    const onSearchRoles = () => {
      rolePagination.current = 1;
    };

    const onDepartmentTableChange = (pagination) => {
      departmentPagination.current = pagination.current;
      departmentPagination.pageSize = pagination.pageSize;
    };

    const onRoleTableChange = (pagination) => {
      rolePagination.current = pagination.current;
      rolePagination.pageSize = pagination.pageSize;
    };

    const showAddDepartmentModal = () => {
      departmentModalTitle.value = 'Thêm phòng ban mới';
      isEditingDepartment.value = false;
      departmentModalVisible.value = true;
    };

    const showAddRoleModal = () => {
      roleModalTitle.value = 'Thêm vai trò mới';
      isEditingRole.value = false;
      roleModalVisible.value = true;
    };

    const editDepartment = (record) => {
      departmentModalTitle.value = 'Chỉnh sửa phòng ban';
      isEditingDepartment.value = true;
      Object.assign(departmentForm, record);
      departmentModalVisible.value = true;
    };

    const editRole = (record) => {
      roleModalTitle.value = 'Chỉnh sửa vai trò';
      isEditingRole.value = true;
      Object.assign(roleForm, record);
      roleModalVisible.value = true;
    };

    const handleUploadFile = async ({ file, departmentId }) => {
      try {
          const formData = new FormData();
          formData.append('upload_file', file);
          formData.append('department_id', departmentId);

          const res = await axiosInstance.post('/api/departments/upload-image', formData, {
              headers: {
                  'Content-Type': 'multipart/form-data',
              },
          });
          message.success('Upload avatar thành công');
          if (res.success === false) {
            console.log('err: ' + res.errors)
          }
          // console.log('Đường dẫn file:', res.data.file_url);
      } catch (error) {
          // console.error(error);
          console.error('Lỗi khi upload avatar: ' + error.message);
      }
    };

    const handleDepartmentSubmit = async () => {
      try {
        await departmentFormRef.value.validate();
        
        if (isEditingDepartment.value) {
          // Update existing department
          const index = departments.value.findIndex(d => d.id === departmentForm.id);
          if (index !== -1) {
            departments.value[index] = { ...departmentForm };
            message.success('Cập nhật phòng ban thành công');
          }
        } else {
          // Add new department
          const newDepartment = {
            ...departmentForm,
          };
          // console.log('newDepartment', newDepartment);
          // return;
          // departments.value.push(newDepartment); 
          const new_department_id = await useRegisterStore().addDepartment(newDepartment);
          console.log('Phòng mới: ' + new_department_id);
          await handleUploadFile({ 
            file: upload_files.value[0]?.originFileObj, 
            departmentId: new_department_id 
          });
          message.success('Thêm phòng ban thành công');
        }
        
        resetDepartmentModal();
      } catch (error) {
        console.error('Validation failed:', error);
      }
    };

    const handleRoleSubmit = async () => {
      try {
        await roleFormRef.value.validate();
        
        if (isEditingRole.value) {
          // Update existing role
          const index = roles.value.findIndex(r => r.id === roleForm.id);
          if (index !== -1) {
            roles.value[index] = { ...roleForm };
            message.success('Cập nhật vai trò thành công');
          }
        } else {
          // Add new role
          const newRole = {
            ...roleForm,
            id: Date.now(),
            createdAt: new Date().toISOString().split('T')[0]
          };
          roles.value.push(newRole);
          message.success('Thêm vai trò thành công');
        }
        
        resetRoleModal();
      } catch (error) {
        console.error('Validation failed:', error);
      }
    };

    const deleteDepartment = (id) => {
      const index = departments.value.findIndex(d => d.id === id);
      if (index !== -1) {
        departments.value.splice(index, 1);
        message.success('Xóa phòng ban thành công');
      }
    };

    const deleteRole = (id) => {
      const index = roles.value.findIndex(r => r.id === id);
      if (index !== -1) {
        roles.value.splice(index, 1);
        message.success('Xóa vai trò thành công');
      }
    };

    const resetDepartmentModal = () => {
      departmentModalVisible.value = false;
      departmentFormRef.value?.resetFields();
      Object.assign(departmentForm, {
        id: null,
        name: '',
        description: '',
        status: 'active',
        phone: '',
        location: '',
        can_approve: false,
        group: 'other',
        imageUrl: '',
        upload_files: []
      });
    };

    const resetRoleModal = () => {
      roleModalVisible.value = false;
      roleFormRef.value?.resetFields();
      Object.assign(roleForm, {
        id: null,
        name: '',
        description: '',
        permissions: [],
        status: 'active'
      });
    };

    const beforeUpload = (file) => {
      const isJpgOrPng = file.type === 'image/jpeg' || file.type === 'image/png';
      if (!isJpgOrPng) {
        message.error('Chỉ có thể upload file JPG/PNG!');
        return false;
      }
      const isLt2M = file.size / 1024 / 1024 < 2;
      if (!isLt2M) {
        message.error('Kích thước file phải nhỏ hơn 2MB!');
        return false;
      }
      return true; // Prevent auto upload, handle manually
    };

    const handleImageChange = (info) => {
      if (info.file.status === 'uploading') {
        return;
      }
      
      // Get base64 URL for preview
      const reader = new FileReader();
      reader.addEventListener('load', () => {
        departmentForm.imageUrl = reader.result;
      });
      reader.readAsDataURL(info.file.originFileObj || info.file);
    };

    return {
      activeTab,
      departments,
      roles,
      upload_files,
      headers,
      departmentSearch,
      roleSearch,
      departmentLoading,
      roleLoading,
      filteredDepartments,
      filteredRoles,
      departmentColumns,
      roleColumns,
      departmentPagination,
      rolePagination,
      departmentModalVisible,
      roleModalVisible,
      departmentModalTitle,
      roleModalTitle,
      departmentFormRef,
      roleFormRef,
      departmentForm,
      roleForm,
      departmentRules,
      roleRules,
      onSearchDepartments,
      onSearchRoles,
      onDepartmentTableChange,
      onRoleTableChange,
      showAddDepartmentModal,
      showAddRoleModal,
      editDepartment,
      editRole,
      handleDepartmentSubmit,
      handleRoleSubmit,
      deleteDepartment,
      deleteRole,
      resetDepartmentModal,
      resetRoleModal,
      beforeUpload,
      handleImageChange,
    };
  }
};
</script>

<style scoped>
.departments-roles-management {
  padding: 20px;
}

.tab-content {
  padding: 20px 0;
}

.header-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

@media (max-width: 768px) {
  .header-actions {
    flex-direction: column;
    gap: 10px;
    align-items: stretch;
  }
  
  .header-actions .ant-input-search {
    width: 100% !important;
  }
}
</style>