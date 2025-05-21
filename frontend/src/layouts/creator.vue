<template>
    <TheHeader />

    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-sm-3 col-xxl-2 d-none d-sm-flex">
                <a-list bordered style="width: 100%;">
                    <TheMenu />
                    <template #header>
                        <div>BẢNG ĐIỀU KHIỂN</div>
                    </template>
                </a-list>
            </div>

            <div class="col-12 col-sm-9 col-xxl-10 bg-right bg-gradient pb-3">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script>
import TheHeader from '@/components/TheHeader.vue'
import TheMenu from '@/components/TheMenu.vue'

export default {
    components: {
        TheHeader,
        TheMenu
    }
}
</script>

<style>

.bg-right {
    background-color: rgba(74, 72, 72, 0.174);
}

</style>