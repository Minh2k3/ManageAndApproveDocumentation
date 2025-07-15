<template>
  <div class="my-department-page">
    <a-card class="mb-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="fa-solid fa-building me-2"></i>Đơn vị của tôi</h3>
        <div class="d-flex">
          <a-button type="primary" v-if="isHeadOfDepartment">
            <i class="fa-solid fa-cog me-1"></i>Quản lý đơn vị
          </a-button>
        </div>
      </div>

      <!-- Department Information -->
      <div class="department-info mb-4">
        <div class="row">
          <div class="col-md-6">
            <div class="info-card bg-light p-3 rounded border">
              <h5><i class="fa-solid fa-info-circle me-2"></i>Thông tin đơn vị</h5>
              <div class="mt-3">
                <p><strong>Tên đơn vị:</strong> {{ departmentInfo.name }}</p>
                <p><strong>Trưởng đơn vị:</strong> {{ departmentInfo.head?.name || 'Chưa có' }}</p>
                <p><strong>Số lượng thành viên:</strong> {{ departmentInfo.memberCount }}</p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="info-card bg-light p-3 rounded border">
              <h5><i class="fa-solid fa-user me-2"></i>Vai trò của tôi</h5>
              <div class="mt-3">
                <p><strong>Vai trò: </strong>{{ userRoleInDepartment.name }}</p>
                <div>
                    <p><strong>Quyền hạn duyệt các loại văn bản:</strong></p>
                    <ul>
                      <li v-for="permission in userRoleInDepartment.permissions" :key="permission">
                        {{ permission }}
                      </li>
                    </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Members List Header -->
      <div class="members-header mb-3">
        <div class="row align-items-center">
          <div class="col-md-6 mb-3 mb-md-0">
            <h5 class="mb-0"><i class="fa-solid fa-users me-2"></i>Danh sách thành viên</h5>
          </div>
          <div class="col-md-6">
            <div class="d-flex">
              <a-input-search
                v-model:value="searchKeyword"
                placeholder="Tìm kiếm theo tên..."
                style="width: 100%"
                @search="handleSearch"
                class="me-2"
              />
              <a-select
                v-model:value="filters.roleId"
                style="width: 200px"
                placeholder="Lọc theo vai trò"
                allow-clear
                @change="handleRoleFilter"
              >
                <a-select-option v-for="role in departmentRoles" :key="role.id" :value="role.id">
                  {{ role.name }}
                </a-select-option>
              </a-select>
            </div>
          </div>
        </div>
      </div>

      <!-- Members Table -->
      <a-table
        :columns="columns"
        :data-source="filteredMembers"
        :loading="loading"
        :pagination="pagination"
        @change="handleTableChange"
        row-key="id"
        :row-class-name="(record) => record.role.level === 1 ? 'head-row' : ''"
      >
        <!-- Cột thông tin người dùng -->
        <template #bodyCell="{ column, record }">
          <template v-if="column.key === 'name'">
            <div class="d-flex align-items-center">
              <a-avatar :size="40" :src="getAvatarUrl(record)"></a-avatar>
              <div class="ms-2">
                <div class="fw-bold">{{ record.name }}</div>
              </div>
            </div>
          </template>

          <!-- Cột vai trò -->
          <template v-if="column.key === 'role'">
            <a-tag :color="getRoleColor(record.role.level)">
              {{ record.role.name }}
            </a-tag>
          </template>

          <!-- Cột hành động -->
          <template v-if="column.key === 'actions'">
            <div class="d-flex">
              <a-button type="link" size="small" @click="viewMemberDetails(record)">
                <i class="fa-solid fa-eye"></i>
              </a-button>
              <a-button 
                v-if="isHeadOfDepartment" 
                type="link" 
                size="small" 
                @click="openDocumentTypeModal(record)"
              >
                <i class="fa-solid fa-file-signature"></i>
              </a-button>
            </div>
          </template>
        </template>
      </a-table>
    </a-card>

    <!-- Member Detail Modal -->
    <a-modal
      v-model:visible="memberDetailModalVisible"
      title="Chi tiết thành viên"
      :width="700"
      :footer="null"
    >
      <div v-if="selectedMember">
        <div class="selected-member-info mb-3 p-3 bg-light rounded">
          <div class="d-flex align-items-center">
            <a-avatar :size="60" :src="getAvatarUrl(selectedMember)"></a-avatar>
            <div class="ms-3">
              <h5 class="mb-1">{{ selectedMember.name }}</h5>
              <div>
                <a-tag :color="getRoleColor(selectedMember.role?.level)">
                  {{ selectedMember.role?.name || 'Chưa có vai trò' }}
                </a-tag>
              </div>
              <div class="text-muted">{{ selectedMember.email }}</div>
            </div>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <div class="info-item">
              <div class="text-muted">Ngày đăng ký:</div>
              <div>{{ formatDate(selectedMember.created_at) }}</div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="info-item">
              <div class="text-muted">Số tài liệu đã tạo:</div>
              <div>{{ selectedMember.documentCount || 0 }}</div>
            </div>
          </div>
        </div>

        <div class="row mb-4">
          <div class="col-md-6">
            <div class="info-item">
              <div class="text-muted">Phòng ban:</div>
              <div>{{ departmentInfo.name }}</div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="info-item">
              <div class="text-muted">Trạng thái:</div>
              <div>
                <a-tag :color="getStatusColor(selectedMember.status)">
                  {{ getStatusText(selectedMember.status) }}
                </a-tag>
              </div>
            </div>
          </div>
        </div>

        <a-divider />

        <div v-if="isHeadOfDepartment" class="approval-permissions mb-4">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="mb-0">Quyền phê duyệt tài liệu</h5>
            <a-switch
              v-model:checked="hasApprovalPermission"
              @change="toggleApprovalPermission"
            />
          </div>
          
          <div v-if="hasApprovalPermission">
            <a-alert class="mb-3" message="Chọn loại văn bản mà thành viên này được quyền phê duyệt" type="info" show-icon />
            
            <div class="document-types-list">
              <a-checkbox-group v-model:value="selectedDocumentTypes" style="width: 100%">
                <div class="document-type-item p-2 border-bottom" v-for="docType in documentTypes" :key="docType.id">
                  <a-checkbox :value="docType.id">
                    <div>
                      <div class="fw-bold">{{ docType.name }}</div>
                      <div class="text-muted small">{{ docType.description }}</div>
                    </div>
                  </a-checkbox>
                </div>
              </a-checkbox-group>
            </div>
          </div>
          
          <div v-else>
            <a-empty description="Thành viên này không có quyền phê duyệt tài liệu" />
          </div>
        </div>

        <div class="d-flex justify-content-end mt-3">
          <a-button v-if="isHeadOfDepartment && hasApprovalPermission" type="primary" @click="saveApprovalPermissions" :loading="saveLoading">
            Lưu quyền phê duyệt
          </a-button>
          <a-button class="ms-2" @click="memberDetailModalVisible = false">
            Đóng
          </a-button>
        </div>
      </div>
    </a-modal>
  </div>
</template>

<script>
import { useMenu } from '@/stores/use-menu.js';
import { defineComponent, ref, reactive, computed, onMounted, watch } from 'vue';
import { message } from 'ant-design-vue';
import { useAuth } from '@/stores/use-auth.js';
import { useDepartmentStore } from '@/stores/approver/department-store';
import { useDocumentStore } from '@/stores/approver/document-store';
import { ssrExportAllKey } from 'vite/module-runner';
export default defineComponent({
  name: 'MyDepartment',
  
  setup() {
    useMenu().onSelectedKeys(["approver-departments"]);
    const authStore = useAuth();
    const user = authStore.user;
    console.log('Current user:', user);
    const currentDepartmentId = user.approver ? user.approver.department_id : user.creator.department_id;
    console.log('Current department ID:', currentDepartmentId);

    const departmentStore = useDepartmentStore();
    const documentStore = useDocumentStore();

    // Department data
    const departmentInfo = ref({
      id: 1,
      name: 'Phòng Công nghệ thông tin',
      code: 'CNTT',
      head: {
        id: 1,
        name: 'Nguyễn Văn A'
      },
      memberCount: 15
    });

    // User role in department
    const userRoleInDepartment = ref({
      id: 1,
      name: 'Trưởng phòng',
      level: 1,
      permissions: 'Quản lý đơn vị, phân quyền văn bản, duyệt văn bản'
    });

    // Computed property to check if current user is head of department
    const isHeadOfDepartment = computed(() => {
      return userRoleInDepartment.value.level === 1;
    });

    // Table loading state
    const loading = ref(false);

    // Search and filter
    const searchKeyword = ref('');
    const filters = reactive({
      roleId: null,
      sortBy: 'name',
      sortOrder: 'asc'
    });

    // Table pagination
    const pagination = reactive({
      current: 1,
      pageSize: 10,
      total: 15,
      showSizeChanger: true,
      pageSizeOptions: ['10', '20', '50']
    });

    // Table columns
    const columns = [
      {
        title: 'Tên',
        key: 'name',
        dataIndex: 'name',
        sorter: true,
        width: 250,
      },
      {
        title: 'Email',
        key: 'email',
        dataIndex: 'email',
        width: 250,
      },
      {
        title: 'Vai trò',
        key: 'role',
        dataIndex: ['role', 'name'],
        sorter: true,
        width: 200,
      },
      {
        title: 'Ngày đăng ký',
        key: 'created_at',
        dataIndex: 'created_at',
        sorter: true,
        width: 150,
        render: (text) => formatDate(text),
      },
      {
        title: 'Hành động',
        key: 'actions',
        width: 120,
      }
    ];

    // Mock department roles for filter
    const departmentRoles = ref([
      { id: 1, name: 'Trưởng phòng', level: 1 },
      { id: 3, name: 'Phó phòng', level: 3 },
      { id: 10, name: 'Sinh viên', level: 10 },
    ]);

    // Mock member data
    const members = ref([
      {
        id: 1,
        name: 'Nguyễn Văn A',
        email: 'nguyenvana@example.com',
        avatar: null,
        role: { id: 1, name: 'Trưởng phòng', level: 1 },
        status: 'active',
        created_at: '2025-01-15',
        documentCount: 25,
        hasApprovalPermission: true,
        approvalDocumentTypes: [1, 3, 5],
      },
      {
        id: 2,
        name: 'Trần Thị B',
        email: 'tranthib@example.com',
        avatar: null,
        role: { id: 3, name: 'Phó phòng', level: 3 },
        status: 'active',
        created_at: '2025-02-20',
        documentCount: 18,
        hasApprovalPermission: true,
        approvalDocumentTypes: [1, 2],
      },
      {
        id: 3,
        name: 'Lê Văn C',
        email: 'levanc@example.com',
        avatar: null,
        role: { id: 10, name: 'Sinh viên', level: 10 },
        status: 'active',
        created_at: '2025-03-10',
        documentCount: 10,
        hasApprovalPermission: false,
        approvalDocumentTypes: [],
      },
    ]);

    // Member detail modal
    const memberDetailModalVisible = ref(false);
    const selectedMember = ref(null);
    const hasApprovalPermission = ref(false);
    const selectedDocumentTypes = ref([]);
    const saveLoading = ref(false);

    // Mock document types
    const documentTypes = ref([]);

    // Filter members based on search and filters
    const filteredMembers = computed(() => {
      let result = [...members.value];
      
      // Filter by search term
      if (searchKeyword.value) {
        const searchTerm = searchKeyword.value.toLowerCase();
        result = result.filter(member => 
          member.name.toLowerCase().includes(searchTerm)
        );
      }
      
      // Filter by role
      if (filters.roleId) {
        result = result.filter(member => member.role.id === filters.roleId);
      }
      
      return result;
    });

    // Methods
    const handleSearch = (value) => {
      console.log('Search for:', value);
      // In a real app, you would call an API to search for members
    };

    const handleRoleFilter = (value) => {
      console.log('Filter by role:', value);
      // In a real app, you would call an API to filter members
    };

    const handleTableChange = (pag, filters, sorter) => {
      console.log('Table change:', pag, filters, sorter);
      pagination.current = pag.current;
      pagination.pageSize = pag.pageSize;
      
      if (sorter.field) {
        filters.sortBy = sorter.field;
        filters.sortOrder = sorter.order === 'ascend' ? 'asc' : 'desc';
      }
      
      // In a real app, you would call an API to get the sorted/filtered data
    };

    const viewMemberDetails = (member) => {
      selectedMember.value = { ...member };
      hasApprovalPermission.value = member.hasApprovalPermission;
      selectedDocumentTypes.value = [...(member.approvalDocumentTypes || [])];
      memberDetailModalVisible.value = true;
    };

    const openDocumentTypeModal = (member) => {
      viewMemberDetails(member);
    };

    const toggleApprovalPermission = (checked) => {
      console.log('Toggle approval permission:', checked);
      if (!checked) {
        // Reset selected document types when approval permission is turned off
        selectedDocumentTypes.value = [];
      }
    };

    const saveApprovalPermissions = () => {
      saveLoading.value = true;
      
      // Simulate API call
      setTimeout(() => {
        saveLoading.value = false;
        
        // Update the member in the list
        const index = members.value.findIndex(m => m.id === selectedMember.value.id);
        if (index !== -1) {
          members.value[index].hasApprovalPermission = hasApprovalPermission.value;
          members.value[index].approvalDocumentTypes = [...selectedDocumentTypes.value];
        }
        
        memberDetailModalVisible.value = false;
        message.success(`Đã cập nhật quyền phê duyệt cho ${selectedMember.value.name}`);
        
        // In a real app, you would call an API to save the permissions
      }, 1000);
    };

    const formatDate = (dateString) => {
      if (!dateString) return '';
      const date = new Date(dateString);
      return date.toLocaleDateString('vi-VN');
    };

    const getRoleColor = (level) => {
      const colors = {
        1: 'red',
        3: 'orange',
        10: 'blue',
      };
      return colors[level] || 'default';
    };

    const getStatusColor = (status) => {
      const colors = {
        'active': 'green',
        'inactive': 'red',
        'pending': 'gold'
      };
      return colors[status] || 'default';
    };

    const getStatusText = (status) => {
      const texts = {
        'active': 'Đang hoạt động',
        'inactive': 'Không hoạt động',
        'pending': 'Chờ xác nhận'
      };
      return texts[status] || status;
    };

    const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000';

    const randomAvatar = (userId) =>{
        // Sử dụng DiceBear API với nhiều style khác nhau
        const styles = [
            'adventurer',
            'avataaars',
            'big-ears',
            'bottts',
            'croodles',
            'fun-emoji',
            'identicon',
            'initials',
            'lorelei',
            'micah',
            'miniavs',
            'open-peeps',
            'personas',
            'pixel-art'
        ];
        
        // Chọn ngẫu nhiên một style dựa trên userId để đảm bảo tính nhất quán
        const styleIndex = userId % styles.length;
        const style = styles[styleIndex];
        
        // Tạo seed dựa trên userId để avatar luôn giống nhau cho cùng một user
        const seed = `user-${userId}`;
        
        return `https://api.dicebear.com/7.x/${style}/svg?seed=${seed}&size=150`;
    };

    const getAvatarUrl = (user) => {
        if (!user.avatar) return randomAvatar(user.id);
        return `${API_BASE_URL}/images/avatars/${user.avatar}`;
    };

    const getDefaultAvatar = (id) => {
      // Generate a consistent avatar based on user ID
      return `https://api.dicebear.com/7.x/initials/svg?seed=user-${id}`;
    };

    // Watch for changes to the selected member
    watch(selectedMember, (newVal) => {
      if (newVal) {
        hasApprovalPermission.value = newVal.hasApprovalPermission || false;
        selectedDocumentTypes.value = [...(newVal.approvalDocumentTypes || [])];
      }
    });

    onMounted(async () => {
        await documentStore.fetchDocumentTypes();
        documentTypes.value = documentStore.documentTypes.map(docType => ({
          id: docType.id,
          name: docType.name,
          description: docType.description
        }));

        try {
            await departmentStore.fetchMyDepartment(currentDepartmentId, true);
            const current_department = departmentStore.my_department;
            console.log('Current department:', current_department);
            members.value = current_department.users.map(user => ({
                id: user.id,
                name: user.name,
                email: user.email,
                avatar: user.avatar,
                role: {
                    id: user.role.id,
                    name: user.role.name,
                    level: user.role.level,
                },
                status: user.status,
                created_at: user.created_at,
                hasApprovalPermission: user.can_approve,
                approvalDocumentTypes: user.document_types
            }));

            const currentUser = members.value.find(m => m.id === user.id);

            userRoleInDepartment.value = {
                id: currentUser.role.id,
                name: currentUser.role.name,
                level: currentUser.role.level,
                permissions: currentUser.approvalDocumentTypes.map(docType => docType.name)
            };

            departmentInfo.value = {
                id: current_department.department.id,
                name: current_department.department.name,
                memberCount: current_department.total_users,
                head: current_department.department.head_of_department,
            };
        } catch (error) {
            console.error('Error fetching department data:', error);
            message.error('Không thể tải thông tin đơn vị. Vui lòng thử lại sau.');
        }
        
    });

    return {
      departmentInfo,
      userRoleInDepartment,
      isHeadOfDepartment,
      loading,
      searchKeyword,
      filters,
      pagination,
      columns,
      departmentRoles,
      filteredMembers,
      memberDetailModalVisible,
      selectedMember,
      hasApprovalPermission,
      selectedDocumentTypes,
      saveLoading,
      documentTypes,
      
      handleSearch,
      handleRoleFilter,
      handleTableChange,
      viewMemberDetails,
      openDocumentTypeModal,
      toggleApprovalPermission,
      saveApprovalPermissions,
      formatDate,
      getRoleColor,
      getStatusColor,
      getStatusText,
      getDefaultAvatar,
      getAvatarUrl,
    };
  }
});
</script>

<style scoped>
.my-department-page {
  padding: 20px;
  max-width: 1400px;
  margin: 0 auto;
}

.head-row {
  background-color: rgba(250, 173, 20, 0.05);
}

.document-types-list {
  max-height: 300px;
  overflow-y: auto;
  border: 1px solid #f0f0f0;
  border-radius: 4px;
  margin-bottom: 10px;
}

.document-type-item {
  transition: background-color 0.3s;
}

.document-type-item:hover {
  background-color: #f9f9f9;
}

.info-item {
  margin-bottom: 10px;
}

.info-item .text-muted {
  font-size: 0.9rem;
}

/* Custom styling for Ant Design components with Bootstrap */
:deep(.ant-table-thead > tr > th) {
  background-color: #f8f9fa;
  font-weight: 600;
}

:deep(.ant-table-pagination.ant-pagination) {
  margin: 16px 0;
}

:deep(.ant-input-search-button) {
  height: 100%;
}
</style>