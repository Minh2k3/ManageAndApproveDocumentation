<template>
    <a-menu
      v-model:openKeys="openKeys"
      v-model:selectedKeys="selectedKeys"
      mode="inline"
    >
        <a-menu-item key="admin-users">
            <router-link :to="{ name: 'admin-users'}" title="Quản lý người dùng">
                <span>
                  <UserOutlined class="me-1"/>Quản lý người dùng
                </span>
            </router-link>
        </a-menu-item>

        <a-menu-item key="admin-roles">
            <router-link :to="{ name: 'admin-roles'}" title="Quản lý vai trò">
                <span><TagOutlined class="me-1" />Quản lý vai trò</span>
            </router-link>
        </a-menu-item>

        <a-menu-item key="admin-settings">
            <router-link :to="{ name: 'admin-settings'}" title="Cài đặt">
                <span><SettingOutlined class="me-1" />Cài đặt</span>
            </router-link>
        </a-menu-item>
    </a-menu>
</template>

<script>
import { UserOutlined, TagOutlined, SettingOutlined  } from '@ant-design/icons-vue';
import { defineComponent } from 'vue';
import { storeToRefs } from 'pinia';
import { useMenu } from '../stores/use-menu.js';
export default defineComponent({
  components: {
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