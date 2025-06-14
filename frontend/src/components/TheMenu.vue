<template>
	<!-- admin menu -->
	<a-menu v-if="role === 'admin'" v-model:openKeys="openKeys" v-model:selectedKeys="selectedKeys" mode="inline">
		<a-menu-item menu-item key="admin-dashboard">
			<router-link  class="text-decoration-none" :to="{ name: 'admin-dashboard' }" title="Quản lý chung">
				<span class="d-inline-flex align-items-center">
					<HomeOutlined class="me-2" />Quản lý chung
				</span>
			</router-link>
		</a-menu-item>

		<a-menu-item menu-item key="admin-notification">
			<router-link  class="text-decoration-none" :to="{ name: 'admin-notification' }" title="Thông báo">
				<span class="d-inline-flex align-items-center">
					<NotificationOutlined class="me-2" />Thông báo
				</span>
			</router-link>
		</a-menu-item>

		<a-sub-menu key="admin-users">
			<template #title>
				<span class="d-inline-flex align-items-center">
					<UserOutlined class="me-2" /> Quản lý người dùng
				</span>
			</template>

			<a-menu-item key="admin-users-list">
				<router-link  class="text-decoration-none" :to="{ name: 'admin-users' }">Danh sách người dùng</router-link>
			</a-menu-item>

			<a-menu-item key="admin-users-create">
				<router-link  class="text-decoration-none" :to="{ name: 'admin-users-create' }">Thêm người dùng</router-link>
			</a-menu-item>

			<a-menu-item key="admin-users-departments-and-rolls">
				<router-link  class="text-decoration-none" :to="{ name: 'admin-users-departments-and-rolls' }">Phòng ban và vai trò</router-link>
			</a-menu-item>
		</a-sub-menu>

		<a-sub-menu key="admin-documents">
			<template #title>
				<span class="d-inline-flex align-items-center">
					<FileOutlined class="me-2" /> Quản lý văn bản
				</span>
			</template>

			<a-menu-item key="admin-documents-all">
				<router-link  class="text-decoration-none" :to="{ name: 'admin-documents' }">Tất cả văn bản</router-link>
			</a-menu-item>

			<a-menu-item key="admin-documents-type">
				<router-link  class="text-decoration-none" :to="{ name: 'admin-documents-type' }">Loại văn bản</router-link>
			</a-menu-item>

			<a-menu-item key="admin-documents-template">
				<router-link  class="text-decoration-none" :to="{ name: 'admin-documents-template' }">Văn bản mẫu</router-link>
			</a-menu-item>

			<a-menu-item key="admin-documents-history">
				<router-link  class="text-decoration-none" :to="{ name: 'admin-documents-history' }">Lịch sử văn bản</router-link>
			</a-menu-item>
		</a-sub-menu>


		<!-- <a-menu-item key="admin-roles">
			<router-link  class="text-decoration-none" :to="{ name: 'admin-roles' }" title="Quản lý vai trò">
				<span class="fs-6 d-inline-flex align-items-center">
					<TagOutlined class="me-2" />Quản lý vai trò
				</span>
			</router-link>
		</a-menu-item> -->

		<a-sub-menu key="admin-roles">
			<template #title>
				<span class="d-inline-flex align-items-center">
					<TagOutlined class="me-2" /> Quản lý vai trò
				</span>
			</template>

			<a-menu-item key="admin-roles-all">
				<router-link  class="text-decoration-none" :to="{ name: 'admin-roles' }">Vai trò</router-link>
			</a-menu-item>

			<a-menu-item key="admin-roles-permissions">
				<router-link  class="text-decoration-none" :to="{ name: 'admin-roles-permissions' }">Quyền hạn</router-link>
			</a-menu-item>

			<a-menu-item key="admin-roles-make_permission">
				<router-link  class="text-decoration-none" :to="{ name: 'admin-roles-make_permission' }">Gán quyền</router-link>
			</a-menu-item>
		</a-sub-menu>

		<a-sub-menu key="admin-signatures">
			<template #title>
				<span class="d-inline-flex align-items-center">
					<TagOutlined class="me-2" /> Quản lý chữ ký số
				</span>
			</template>

			<a-menu-item key="admin-signatures">
				<router-link  class="text-decoration-none" :to="{ name: 'admin-signatures' }">Chữ ký số</router-link>
			</a-menu-item>

			<a-menu-item key="admin-signatures-detail" :disabled="!isDetailSignatureAdmin" :selectable="isDetailSignatureAdmin">
				Chi tiết chứng thực
			</a-menu-item>
		</a-sub-menu>

		<a-sub-menu key="admin-approval-flows">
			<template #title>
				<span class="d-inline-flex align-items-center">
					<BranchesOutlined class="me-2" /> Luồng phê duyệt
				</span>
			</template>

			<a-menu-item key="admin-approval-flows">
				<router-link  class="text-decoration-none" :to="{ name: 'admin-approval-flows' }">Mẫu luồng phê duyệt</router-link>
			</a-menu-item>

			<a-menu-item key="admin-approval-flows-detail" :disabled="!isDetailPageFlowTemplate" :selectable="isDetailPageFlowTemplate">
				Chi tiết luồng
			</a-menu-item>

			<a-menu-item key="approval-flows-create">
				<router-link  class="text-decoration-none" :to="{ name: 'admin-approval-flows-create' }">Tạo luồng mới</router-link>
			</a-menu-item>
		</a-sub-menu>

		<a-menu-item key="admin-settings">
			<router-link  class="text-decoration-none" :to="{ name: 'admin-settings' }" title="Cài đặt">
				<span class="d-inline-flex align-items-center">
					<SettingOutlined class="me-2" />Cài đặt
				</span>
			</router-link>
		</a-menu-item>
	</a-menu>

	<!-- creator menu -->
	<a-menu v-if="role === 'creator'" v-model:openKeys="openKeys" v-model:selectedKeys="selectedKeys" mode="inline">
		<a-menu-item menu-item key="creator-dashboard">
			<router-link  class="text-decoration-none" :to="{ name: 'creator-dashboard' }" title="Tin tức">
				<span class="d-inline-flex align-items-center">
					<HomeOutlined class="me-2" />Tin tức
				</span>
			</router-link>
		</a-menu-item>

		<a-menu-item menu-item key="creator-notification">
			<router-link  class="text-decoration-none" :to="{ name: 'creator-notification' }" title="Thông báo">
				<span class="d-inline-flex align-items-center">
					<NotificationOutlined class="me-2" />Thông báo
				</span>
			</router-link>
		</a-menu-item>

		<a-sub-menu key="creator-documents">
			<template #title>
				<span class="d-inline-flex align-items-center">
					<FileOutlined class="me-2" /> Văn bản của tôi nè
				</span>
			</template>

			<a-menu-item key="creator-documents-all">
				<router-link  class="text-decoration-none" :to="{ name: 'creator-documents' }">Tất cả văn bản</router-link>
			</a-menu-item>

			<a-menu-item key="creator-documents-create">
				<router-link  class="text-decoration-none" :to="{ name: 'creator-documents-create' }">Thêm văn bản</router-link>
			</a-menu-item>

			<!-- <a-menu-item key="creator-documents-edit" :disabled="!isEditPageCreator" :selectable="isEditPageCreator">
				Sửa văn bản
			</a-menu-item> -->
			
			<a-menu-item key="creator-documents-detail" :disabled="!isDetailPageCreator" :selectable="isDetailPageCreator">
				Chi tiết văn bản
			</a-menu-item>

			<!-- <a-menu-item key="creator-documents-history">
				<router-link  class="text-decoration-none" :to="{ name: 'creator-documents-history' }">Lịch sử văn bản</router-link>
			</a-menu-item> -->

			<a-menu-item key="creator-documents-template">
				<router-link class="text-decoration-none" :to="{ name: 'creator-documents-template' }">
					<span>
						<i class="fa-solid fa-file me-2"></i>Văn bản mẫu
					</span>
				</router-link>
			</a-menu-item>

		</a-sub-menu>

		<a-menu-item key="creator-signatures">
			<router-link  class="text-decoration-none" :to="{ name: 'creator-signatures' }">
				<span>
					<i class="fa-solid fa-signature me-2"></i>Chữ ký của tớ
				</span>
			</router-link>
		</a-menu-item>

		<a-menu-item key="creator-settings">
			<router-link  class="text-decoration-none" :to="{ name: 'creator-settings' }" title="Cài đặt">
				<span class="d-inline-flex align-items-center">
					<SettingOutlined class="me-2" />Cài đặt
				</span>
			</router-link>
		</a-menu-item>
	</a-menu>

	<!-- approver menu -->
	<a-menu v-if="role === 'approver'" v-model:openKeys="openKeys" v-model:selectedKeys="selectedKeys" mode="inline">
		<a-menu-item menu-item key="approver-dashboard">
			<router-link class="text-decoration-none" :to="{ name: 'approver-dashboard' }" title="Tin tức">
				<span class="d-inline-flex align-items-center">
					<HomeOutlined class="me-2" />Tin tức
				</span>
			</router-link>
		</a-menu-item>

		<a-menu-item menu-item key="approver-notification">
			<router-link class="text-decoration-none" :to="{ name: 'approver-notification' }" title="Thông báo">
				<span class="d-inline-flex align-items-center">
					<NotificationOutlined class="me-2" />Thông báo
				</span>
			</router-link>
		</a-menu-item>

		<a-sub-menu key="approver-documents">
			<template #title>
				<span class="d-inline-flex align-items-center">
					<FileOutlined class="me-2" /> Văn bản
				</span>
			</template>

			<a-menu-item key="approver-documents-all">
				<router-link class="text-decoration-none" :to="{ name: 'approver-documents' }">Tất cả văn bản</router-link>
			</a-menu-item>

			<a-menu-item key="approver-documents-create">
				<router-link class="text-decoration-none" :to="{ name: 'approver-documents-create' }">Thêm văn bản</router-link>
			</a-menu-item>

			<!-- <a-menu-item key="approver-documents-edit" :disabled="!isEditPageApprover" :selectable="isEditPageApprover">
				Sửa văn bản
			</a-menu-item> -->

			<a-menu-item key="approver-documents-detail" :disabled="!isDetailPageApprover" :selectable="isDetailPageApprover">
				Chi tiết văn bản
			</a-menu-item>

			<!-- <a-menu-item key="approver-documents-approve">
				<router-link class="text-decoration-none" :to="{ name: 'approver-documents-approve' }">Phê duyệt văn bản</router-link>
			</a-menu-item> -->

			<a-menu-item key="approver-documents-template">
				<router-link class="text-decoration-none" :to="{ name: 'approver-documents-template' }">
					<span>
						<i class="fa-solid fa-file me-2"></i>Văn bản mẫu
					</span>
				</router-link>
			</a-menu-item>
		</a-sub-menu>

		<a-menu-item key="approver-signatures">
			<router-link class="text-decoration-none" :to="{ name: 'approver-signatures' }">
				<span>
					<i class="fa-solid fa-signature me-2"></i>Chữ ký của tớ
				</span>
			</router-link>
		</a-menu-item>

		<a-menu-item key="approver-settings">
			<router-link class="text-decoration-none" :to="{ name: 'approver-settings' }" title="Cài đặt">
				<span class="d-inline-flex align-items-center">
					<SettingOutlined class="me-2" />Cài đặt
				</span>
			</router-link>
		</a-menu-item>
	</a-menu>

	<a-menu v-model:selectedKeys="selectedKeys" mode="inline">
		<a-menu-item key="logout" @click="handleLogout">
			<span class="d-inline-flex align-items-center">
				<i class="fa-solid fa-right-from-bracket me-2"></i>Đăng xuất
			</span>
		</a-menu-item>
	</a-menu>
</template>

<script>
import {
	UserOutlined,
	TagOutlined,
	SettingOutlined,
	HomeOutlined,
	FileOutlined,
	InteractionOutlined,
	BranchesOutlined,
	ExclamationCircleOutlined,
	NotificationOutlined,
	NotificationFilled,
} from '@ant-design/icons-vue';

import { 
	Modal, 
	message 
} from 'ant-design-vue';

import { defineComponent, ref, computed, createVNode } from 'vue';
import { storeToRefs } from 'pinia';
import { useMenu } from '@/stores/use-menu.js';
import { useAuth } from '@/stores/use-auth.js';
import { useRoute } from 'vue-router';
export default defineComponent({
	components: {
		HomeOutlined,
		UserOutlined,
		TagOutlined,
		SettingOutlined,
		FileOutlined,
		InteractionOutlined,
		BranchesOutlined,
		ExclamationCircleOutlined,
		NotificationOutlined,
	},
	setup() {
		const store = useMenu();
		const auth = useAuth();
		const role = ref(auth.role);

		const route = useRoute();

		const isDetailSignatureAdmin = computed(() => {
			return route.name === 'admin-signatures-detail';
		});

		const isDetailPageCreator = computed(() => {
			return route.name === 'creator-documents-detail';
		});

		const isEditPageCreator = computed(() => {
			return route.name === 'creator-documents-edit';
		});

		const isDetailPageApprover = computed(() => {
			return route.name === 'approver-documents-detail';
		});

		const isEditPageApprover = computed(() => {
			return route.name === 'approver-documents-edit';
		})

		function onMenuClick({ key }) {
			console.log('Đã bấm' + key);
			auth.role = key;
		}

        // Hàm xác nhận đăng xuất
        function showConfirmLogout() {
            Modal.confirm({
                title: 'Bạn có chắc chắn đăng xuất không?',
                icon: createVNode(ExclamationCircleOutlined),
                content: createVNode(
                    'div',
                    {
                        style: 'color:red;',
                    },
                ),
                async onOk() {
					await auth.logout();
                    message.success('Đăng xuất thành công');
                },
                onCancel() {
                    return;
                },
            });
        };

		// Hàm xử lý sự kiện đăng xuất
		function handleLogout() {
			showConfirmLogout();
		}

		return {
			...storeToRefs(store),
			role,
			isDetailSignatureAdmin,
			isDetailPageCreator,
			isEditPageCreator,
			isDetailPageApprover,
			isEditPageApprover,
			
			onMenuClick,
			handleLogout,
		};
	}
});
</script>