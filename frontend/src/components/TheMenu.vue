<template>
    <a-menu
      v-model:openKeys="openKeys"
      v-model:selectedKeys="selectedKeys"
      mode="inline"
    >
      <a-menu-item menu-item key="admin-dashboard">
          <router-link :to="{ name: 'admin-dashboard'}" title="Bảng điều khiển">
              <span class="fs-6 d-inline-flex align-items-center" >
                <HomeOutlined class="me-2"/>Bảng điều khiển
              </span>
          </router-link>
      </a-menu-item>

      <a-menu-item key="admin-users">
          <router-link :to="{ name: 'admin-users'}" title="Quản lý người dùng">
              <span class="fs-6 d-inline-flex align-items-center">
                <UserOutlined class="me-2"/>Quản lý người dùng
              </span>
          </router-link>
      </a-menu-item>

      <a-menu-item key="admin-roles">
          <router-link :to="{ name: 'admin-roles'}" title="Quản lý vai trò">
              <span class="fs-6 d-inline-flex align-items-center"><TagOutlined class="me-2" />Quản lý vai trò</span>
          </router-link>
      </a-menu-item>

      <a-menu-item key="admin-settings">
          <router-link :to="{ name: 'admin-settings'}" title="Cài đặt">
              <span class="fs-6 d-inline-flex align-items-center"><SettingOutlined class="me-2" />Cài đặt</span>
          </router-link>
      </a-menu-item>
  </a-menu>
</template>

<script>
import { UserOutlined, TagOutlined, SettingOutlined, HomeOutlined  } from '@ant-design/icons-vue';
import { defineComponent } from 'vue';
import { storeToRefs } from 'pinia';
import { useMenu } from '../stores/use-menu.js';
export default defineComponent({
  components: {
      HomeOutlined,
      UserOutlined,
      TagOutlined,
      SettingOutlined,
  },
  setup() {
    const store = useMenu();

    return {
      ...storeToRefs(store),
    };
  }
});
</script>