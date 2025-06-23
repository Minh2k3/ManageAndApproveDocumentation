<template>
  <div class="notifications-container">
    <div class="page-header">
      <h1><i class="fas fa-bell"></i> Thông báo</h1>
    </div>
    
    <div class="content-wrapper">
      <!-- Left Side - Notifications List -->
      <div class="notifications-section">
        <div class="section-header">
          <h2>Tất cả thông báo</h2>
          <div class="header-actions">
            <button 
              @click="markAllAsRead" 
              class="btn-mark-all"
              :disabled="unreadCount === 0"
            >
              <i class="fas fa-check-double"></i>
              Đánh dấu tất cả đã đọc
            </button>
            <button @click="refreshNotifications" class="btn-refresh">
              <i class="fas fa-sync-alt" :class="{ 'rotating': loading }"></i>
            </button>
          </div>
        </div>
        
        <!-- Filter Tabs -->
        <div class="filter-tabs">
          <button 
            v-for="tab in tabs" 
            :key="tab.key"
            @click="activeTab = tab.key"
            :class="['tab-btn', { active: activeTab === tab.key }]"
          >
            {{ tab.label }}
            <span v-if="tab.count > 0" class="tab-count">{{ tab.count }}</span>
          </button>
        </div>
        
        <!-- Notifications List -->
        <div class="notifications-list">
          <div v-if="loading" class="loading-state">
            <div class="spinner"></div>
            <p>Đang tải thông báo...</p>
          </div>
          
          <div v-else-if="filteredNotifications.length === 0" class="empty-state">
            <i class="fas fa-inbox"></i>
            <p>Không có thông báo nào</p>
          </div>
          
          <transition-group v-else name="notification" tag="div">
            <div 
              v-for="notification in paginatedNotifications" 
              :key="notification.id"
              :class="['notification-item', { unread: !notification.is_read }]"
              @click="viewNotification(notification)"
            >
              <div class="notification-avatar">
                <img 
                  :src="getAvatarUrl(notification.sender)" 
                  :alt="notification.sender?.name"
                >
                <div 
                  :class="['category-badge', getCategoryClass(notification.category?.name)]"
                >
                  <i :class="getCategoryIcon(notification.category?.name)"></i>
                </div>
              </div>
              
              <div class="notification-content">
                <div class="notification-header">
                  <strong>{{ notification.sender?.name || 'Hệ thống' }}</strong>
                  <span class="notification-time">{{ formatTime(notification.created_at) }}</span>
                </div>
                <h4 class="notification-title">{{ notification.title }}</h4>
                <p class="notification-message">{{ notification.content }}</p>
                <div class="notification-footer">
                  <span class="category-tag">
                    <i :class="getCategoryIcon(notification.category?.name)"></i>
                    {{ notification.category?.name || 'Chung' }}
                  </span>
                  <span v-if="!notification.is_read" class="unread-badge">Mới</span>
                </div>
              </div>
              
              <div class="notification-actions">
                <button 
                  v-if="!notification.is_read"
                  @click.stop="markAsRead(notification.id)"
                  class="btn-action"
                  title="Đánh dấu đã đọc"
                >
                  <i class="fas fa-check"></i>
                </button>
              </div>
            </div>
          </transition-group>
          
          <!-- Pagination -->
          <div v-if="totalPages > 1" class="pagination">
            <button 
              @click="currentPage--"
              :disabled="currentPage === 1"
              class="page-btn"
            >
              <i class="fas fa-chevron-left"></i>
            </button>
            <span class="page-info">{{ currentPage }} / {{ totalPages }}</span>
            <button 
              @click="currentPage++"
              :disabled="currentPage === totalPages"
              class="page-btn"
            >
              <i class="fas fa-chevron-right"></i>
            </button>
          </div>
        </div>
      </div>
      
      <!-- Right Side - Widgets -->
      <div class="widgets-section">
        <!-- Clock Widget -->
        <div class="widget clock-widget">
          <div class="digital-clock">
            <div class="time">{{ currentTime }}</div>
            <div class="date">{{ currentDate }}</div>
          </div>
        </div>
        
        <!-- Calendar Widget với Lịch Âm -->
        <div class="widget calendar-widget">
          <div class="widget-header">
            <h3><i class="fas fa-calendar-alt"></i> Lịch Âm Dương</h3>
          </div>
          <div class="calendar">
            <div class="calendar-header">
              <button @click="changeMonth(-1)" class="cal-nav">‹</button>
              <span class="cal-month">{{ calendarMonth }}</span>
              <button @click="changeMonth(1)" class="cal-nav">›</button>
            </div>
            <div class="calendar-weekdays">
              <div v-for="day in weekDays" :key="day" class="weekday">{{ day }}</div>
            </div>
            <div class="calendar-days">
              <div 
                v-for="day in calendarDays" 
                :key="day.key"
                :class="['calendar-day', {
                  'other-month': day.otherMonth,
                  'today': day.isToday,
                  'has-notification': day.hasNotification
                }]"
              >
                <div class="solar-date">{{ day.date }}</div>
                <div v-if="day.lunar" class="lunar-date">
                  {{ day.lunar.day }}/{{ day.lunar.month }}
                </div>
                <!-- Hiển thị thông báo ở lịch âm -->
                <!-- <div v-if="day.hasNotification" class="day-indicator"></div> -->
              </div>
            </div>
          </div>
        </div>
        
        <!-- Statistics Widget -->
        <div class="widget stats-widget">
          <div class="widget-header">
            <h3><i class="fas fa-chart-bar"></i> Thống kê</h3>
          </div>
          <div class="stats-grid">
            <div class="stat-item">
              <div class="stat-icon unread">
                <i class="fas fa-envelope"></i>
              </div>
              <div class="stat-info">
                <div class="stat-value">{{ unreadCount }}</div>
                <div class="stat-label">Chưa đọc</div>
              </div>
            </div>
            <div class="stat-item">
              <div class="stat-icon total">
                <i class="fas fa-inbox"></i>
              </div>
              <div class="stat-info">
                <div class="stat-value">{{ notifications.length }}</div>
                <div class="stat-label">Tổng cộng</div>
              </div>
            </div>
            <div class="stat-item">
              <div class="stat-icon today">
                <i class="fas fa-calendar-day"></i>
              </div>
              <div class="stat-info">
                <div class="stat-value">{{ todayCount }}</div>
                <div class="stat-label">Hôm nay</div>
              </div>
            </div>
            <div class="stat-item">
              <div class="stat-icon week">
                <i class="fas fa-calendar-week"></i>
              </div>
              <div class="stat-info">
                <div class="stat-value">{{ weekCount }}</div>
                <div class="stat-label">Tuần này</div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Recent Activity Widget -->
        <!-- <div class="widget activity-widget">
          <div class="widget-header">
            <h3><i class="fas fa-history"></i> Hoạt động gần đây</h3>
          </div>
          <div class="activity-list">
            <div v-for="activity in recentActivities" :key="activity.id" class="activity-item">
              <div class="activity-icon" :class="activity.type">
                <i :class="activity.icon"></i>
              </div>
              <div class="activity-content">
                <p>{{ activity.description }}</p>
                <span class="activity-time">{{ formatTime(activity.time) }}</span>
              </div>
            </div>
          </div>
        </div> -->
      </div>
    </div>
    
    <!-- Notification Detail Modal -->
    <div v-if="selectedNotification" class="modal-overlay" @click="closeModal">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h2>Chi tiết thông báo</h2>
          <button @click="closeModal" class="btn-close">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="modal-body">
          <div class="notification-detail">
            <div class="detail-header">
              <img 
                :src="getAvatarUrl(selectedNotification.sender)" 
                :alt="selectedNotification.sender?.name"
                class="sender-avatar"
              >
              <div class="sender-info">
                <h3>{{ selectedNotification.sender?.name || 'Hệ thống' }}</h3>
                <p>{{ formatFullDate(selectedNotification.created_at) }}</p>
              </div>
            </div>
            <div class="detail-content">
              <h4>{{ selectedNotification.title }}</h4>
              <p>{{ selectedNotification.message }}</p>
              <div class="detail-category">
                <i :class="getCategoryIcon(selectedNotification.category?.name)"></i>
                {{ selectedNotification.category?.name || 'Chung' }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount, watch } from 'vue';
import { format, formatDistanceToNow, startOfWeek, isToday, isSameDay } from 'date-fns';
import { vi } from 'date-fns/locale';
import { useNotificationStore } from '@/stores/use-notification.js';
import { useAuth } from '@/stores/use-auth.js';
import { message } from 'ant-design-vue';
import { LunarDate, SolarDate } from "@nghiavuive/lunar_date_vi";

// Lớp chuyển đổi lịch âm
// class LunarCalendar {
//   static PI = Math.PI;
  
//   // Hàm tính Julian Day Number
//   static jdFromDate(dd, mm, yy) {
//     const a = Math.floor((14 - mm) / 12);
//     const y = yy - a;
//     const m = mm + 12 * a - 3;
//     const jd = dd + Math.floor((153 * m + 2) / 5) + 365 * y + Math.floor(y / 4) - Math.floor(y / 100) + Math.floor(y / 400) - 32045;
//     if (jd < 2299161) {
//       return dd + Math.floor((153 * m + 2) / 5) + 365 * y + Math.floor(y / 4) - 32083;
//     }
//     return jd;
//   }
  
//   // Hàm tính new moon (sóc)
//   static newMoon(k) {
//     const T = k / 1236.85;
//     const T2 = T * T;
//     const T3 = T2 * T;
//     const dr = this.PI / 180;
//     let Jd1 = 2415020.75933 + 29.53058868 * k + 0.0001178 * T2 - 0.000000155 * T3;
//     Jd1 = Jd1 + 0.00033 * Math.sin((166.56 + 132.87 * T - 0.009173 * T2) * dr);
//     const M = 359.2242 + 29.10535608 * k - 0.0000333 * T2 - 0.00000347 * T3;
//     const Mpr = 306.0253 + 385.81691806 * k + 0.0107306 * T2 + 0.00001236 * T3;
//     const F = 21.2964 + 390.67050646 * k - 0.0016528 * T2 - 0.00000239 * T3;
//     let C1 = (0.1734 - 0.000393 * T) * Math.sin(M * dr) + 0.0021 * Math.sin(2 * dr * M);
//     C1 = C1 - 0.4068 * Math.sin(Mpr * dr) + 0.0161 * Math.sin(dr * 2 * Mpr);
//     C1 = C1 - 0.0004 * Math.sin(dr * 3 * Mpr) + 0.0104 * Math.sin(dr * 2 * F);
//     C1 = C1 - 0.0051 * Math.sin(dr * (M + Mpr)) - 0.0074 * Math.sin(dr * (M - Mpr));
//     C1 = C1 + 0.0004 * Math.sin(dr * (2 * F + M)) - 0.0004 * Math.sin(dr * (2 * F - M));
//     C1 = C1 - 0.0006 * Math.sin(dr * (2 * F + Mpr)) + 0.0010 * Math.sin(dr * (2 * F - Mpr));
//     C1 = C1 + 0.0005 * Math.sin(dr * (2 * Mpr + M));
//     const deltat = T < -11 ? 0.001 + 0.000839 * T + 0.0002261 * T2 - 0.00000845 * T3 - 0.000000081 * T * T3 : -0.000278 + 0.000265 * T + 0.000262 * T2;
//     return Jd1 + C1 - deltat;
//   }
  
//   // Chuyển đổi từ lịch dương sang lịch âm
//   static convertSolar2Lunar(dd, mm, yy, timeZone = 7) {
//     const jd = this.jdFromDate(dd, mm, yy);
//     const k = Math.floor((jd - 2415021.076998695) / 29.530588853);
//     let monthStart = this.newMoon(k + 1);
//     if (monthStart > jd) {
//       monthStart = this.newMoon(k);
//     }
//     let a11 = this.getLunarMonth11(yy, timeZone);
//     let b11 = a11;
//     let lunarYear;
//     if (a11 >= monthStart) {
//       lunarYear = yy;
//       a11 = this.getLunarMonth11(yy - 1, timeZone);
//     } else {
//       lunarYear = yy + 1;
//       b11 = this.getLunarMonth11(yy + 1, timeZone);
//     }
//     const lunarDay = Math.floor(jd - monthStart + 1);
//     const diff = Math.floor((monthStart - a11) / 29);
//     let lunarMonth = diff + 11;
//     if (b11 - a11 > 365) {
//       const leapMonthDiff = this.getLeapMonthOffset(a11, timeZone);
//       if (diff >= leapMonthDiff) {
//         lunarMonth = diff + 10;
//         if (diff === leapMonthDiff) {
//           lunarMonth = -lunarMonth;
//         }
//       }
//     }
//     if (lunarMonth > 12) {
//       lunarMonth = lunarMonth - 12;
//     }
//     if (lunarMonth >= 11 && diff < 4) {
//       lunarYear -= 1;
//     }
//     return [lunarDay, Math.abs(lunarMonth), lunarYear];
//   }
  
//   static getLunarMonth11(yy, timeZone) {
//     const off = this.jdFromDate(31, 12, yy) - 2415021.076998695;
//     const k = Math.floor(off / 29.530588853);
//     let nm = this.newMoon(k);
//     const sunLong = this.getSunLongitude(nm, timeZone);
//     if (sunLong >= 9) {
//       nm = this.newMoon(k - 1);
//     }
//     return nm;
//   }
  
//   static getLeapMonthOffset(a11, timeZone) {
//     const k = Math.floor(0.5 + (a11 - 2415021.076998695) / 29.530588853);
//     let last = 0;
//     let i = 1;
//     let arc = this.getSunLongitude(this.newMoon(k + i), timeZone);
//     do {
//       last = arc;
//       i++;
//       arc = this.getSunLongitude(this.newMoon(k + i), timeZone);
//     } while (arc !== last && i < 14);
//     return i - 1;
//   }
  
//   static getSunLongitude(jdn, timeZone) {
//     const T = (jdn - 2451545.5 - timeZone / 24) / 36525;
//     const T2 = T * T;
//     const dr = this.PI / 180;
//     const M = 357.52911 + 35999.05029 * T - 0.0001537 * T2;
//     const L0 = 280.46646 + 36000.76983 * T + 0.0003032 * T2;
//     let DL = (1.914602 - 0.004817 * T - 0.000014 * T2) * Math.sin(dr * M);
//     DL = DL + (0.019993 - 0.000101 * T) * Math.sin(dr * 2 * M) + 0.000289 * Math.sin(dr * 3 * M);
//     const L = L0 + DL;
//     const lambda = L - 360 * Math.floor(L / 360);
//     return Math.floor(lambda / 30);
//   }
// }

// Composables
const authStore = useAuth();
const notificationStore = useNotificationStore();

// Reactive state
const loading = ref(false);
const activeTab = ref('all');
const currentPage = ref(1);
const itemsPerPage = 10;
const selectedNotification = ref(null);

// Clock state
const currentTime = ref('');
const currentDate = ref('');
const clockInterval = ref(null);

// Calendar state
const currentMonth = ref(new Date());
const weekDays = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];

// Recent activities
const recentActivities = ref([]);

// Computed properties
const notifications = computed(() => notificationStore.notifications);
const unreadCount = computed(() => 
  notifications.value.filter(n => !n.is_read).length
);
const userId = computed(() => authStore.user?.id || null);

const tabs = computed(() => [
  { key: 'all', label: 'Tất cả', count: notifications.value.length },
  { key: 'unread', label: 'Chưa đọc', count: unreadCount.value },
  { key: 'read', label: 'Đã đọc', count: readCount.value }
]);

const readCount = computed(() => 
  notifications.value.length - unreadCount.value
); 

const filteredNotifications = computed(() => {
  switch (activeTab.value) {
    case 'unread':
      return notifications.value.filter(n => !n.is_read);
    case 'read':
      return notifications.value.filter(n => n.is_read);
    default:
      return notifications.value;
  }
});

const paginatedNotifications = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return filteredNotifications.value.slice(start, end);
});

const totalPages = computed(() => 
  Math.ceil(filteredNotifications.value.length / itemsPerPage)
);

// Hàm chuyển đổi định dạng từ "23:17:45 28/05/2025" sang Date object
function parseCustomDate(dateString) {
  // Tách thời gian và ngày
  const [time, date] = dateString.split(' ');
  const [day, month, year] = date.split('/');
  const [hour, minute, second] = time.split(':');
  
  // Tạo Date object (tháng trong JS bắt đầu từ 0)
  return new Date(year, month - 1, day, hour, minute, second);
}

// Sử dụng trong computed function
const todayCount = computed(() => {
  const today = new Date().toDateString();
  
  return notifications.value.filter(n => {
    // Chuyển đổi created_at từ định dạng custom sang Date
    const notificationDate = parseCustomDate(n.created_at);
    return notificationDate.toDateString() === today;
  }).length;
});

const weekCount = computed(() => {
  const weekStart = startOfWeek(new Date(), { locale: vi });
  
  return notifications.value.filter(n => {
    // Chuyển đổi created_at từ định dạng "23:17:45 28/05/2025" sang Date
    const notificationDate = parseCustomDate(n.created_at);
    return notificationDate >= weekStart;
  }).length;
});

const calendarMonth = computed(() => 
  format(currentMonth.value, 'MMMM yyyy', { locale: vi })
);

const calendarDays = computed(() => {
  const year = currentMonth.value.getFullYear();
  const month = currentMonth.value.getMonth();
  const firstDay = new Date(year, month, 1);
  const lastDay = new Date(year, month + 1, 0);
  const startDate = new Date(firstDay);
  startDate.setDate(startDate.getDate() - firstDay.getDay());
  
  const days = [];
  const current = new Date(startDate);
  
  // Tạo 42 ngày (6 tuần)
  for (let i = 0; i < 42; i++) {
    const date = current.getDate();
    const isOtherMonth = current.getMonth() !== month;
    const hasNotif = hasNotificationOnDate(current);
    
    // Chuyển đổi sang lịch âm
    let lunarInfo = null;
    const solar_date = new SolarDate({day: date, month: current.getMonth() + 1, year: current.getFullYear()});
    // console.log(solar_date.toLunarDate());
    const lunarDate = solar_date.toLunarDate();
    lunarInfo = {
      day: lunarDate.day,
      month: lunarDate.month,
      year: lunarDate.year
    };
    // try {
    //   const [lunarDay, lunarMonth, lunarYear] = LunarCalendar.convertSolar2Lunar(
    //     current.getDate(), 
    //     current.getMonth() + 1, 
    //     current.getFullYear()
    //   );
    //   lunarInfo = { day: lunarDay, month: lunarMonth, year: lunarYear };
    // } catch (error) {
    //   console.error('Lỗi chuyển đổi lịch âm:', error);
    // }
    
    days.push({
      key: current.toISOString(),
      date,
      otherMonth: isOtherMonth,
      isToday: isToday(current),
      hasNotification: hasNotif,
      lunar: lunarInfo
    });
    
    current.setDate(current.getDate() + 1);
  }
  
  return days;
});

// Methods
const fetchNotifications = async () => {
  loading.value = true;
  try {
    await notificationStore.fetchNotifications(userId.value, true);
  } catch (error) {
    console.error('Error fetching notifications:', error);
    message.error('Không thể tải thông báo');
  } finally {
    loading.value = false;
  }
};

const markAsRead = async (notificationId) => {
  try {
    await notificationStore.markAsRead(notificationId);
    const notification = notifications.value.find(n => n.id === notificationId);
    if (notification) {
      notification.is_read = true;
    }
  } catch (error) {
    console.error('Error marking as read:', error);
    message.error('Không thể cập nhật trạng thái');
  }
};

const markAllAsRead = async () => {
  try {
    await notificationStore.markAllAsRead(userId.value);
    message.success('Đã đánh dấu tất cả thông báo là đã đọc');
  } catch (error) {
    console.error('Error marking all as read:', error);
    message.error('Không thể đánh dấu tất cả thông báo là đã đọc');
  }
};

const viewNotification = (notification) => {
  selectedNotification.value = notification;
  if (!notification.is_read) {
    markAsRead(notification.id);
  }
};

const closeModal = () => {
  selectedNotification.value = null;
};

const refreshNotifications = () => {
  fetchNotifications();
};

const formatTime = (date) => {
  return date;
};

const formatFullDate = (date) => {
  return date;
};

const getCategoryClass = (category) => {
  const categoryMap = {
    'Văn bản': 'category-document',
    'Hệ thống': 'category-system',
    'Nhắc nhở': 'category-reminder',
    'Thông báo': 'category-info'
  };
  return categoryMap[category] || 'category-default';
};

const getCategoryIcon = (category) => {
  const iconMap = {
    'Văn bản': 'fas fa-file-alt',
    'Hệ thống': 'fas fa-cog',
    'Nhắc nhở': 'fas fa-bell',
    'Thông báo': 'fas fa-info-circle'
  };
  return iconMap[category] || 'fas fa-envelope';
};

const startClock = () => {
  const updateClock = () => {
    const now = new Date();
    currentTime.value = format(now, 'HH:mm:ss');
    currentDate.value = format(now, 'EEEE, dd/MM/yyyy', { locale: vi });
  };
  
  updateClock();
  clockInterval.value = setInterval(updateClock, 1000);
};

const changeMonth = (direction) => {
  const newMonth = new Date(currentMonth.value);
  newMonth.setMonth(newMonth.getMonth() + direction);
  currentMonth.value = newMonth;
};

const hasNotificationOnDate = (date) => {
  console.log('Checking notifications for date:', date);
  console.log('Some date: ', notifications.value[0]?.created_at);

  return notifications.value.some(n => {
    const notificationDate = parseCustomDate(n.created_at);
    return isSameDay(notificationDate, date);
  });
};

const generateRecentActivities = () => {
  recentActivities.value = [
    {
      id: 1,
      type: 'login',
      icon: 'fas fa-sign-in-alt',
      description: 'Đăng nhập vào hệ thống',
      time: new SolarDate(new Date())
    },
    {
      id: 2,
      type: 'document',
      icon: 'fas fa-file-alt',
      description: 'Xem văn bản VB-2024-001',
      time: new Date(Date.now() - 3600000)
    },
    {
      id: 3,
      type: 'notification',
      icon: 'fas fa-bell',
      description: 'Nhận 3 thông báo mới',
      time: new Date(Date.now() - 7200000)
    }
  ];
};

const randomAvatar = (id) => {
  if (id > 100 || id == null) {
    return `https://avatar.iran.liara.run/public`;
  }
  return `https://avatar.iran.liara.run/public/${id}`;
};

const getAvatarUrl = (user) => {
  if (!user) return randomAvatar(null);
  const API_BASE_URL = 'http://localhost:8000';
  if (user.avatar === null) return randomAvatar(user.id);
  return `${API_BASE_URL}/images/avatars/${user.avatar}`;
};

// Lifecycle
onMounted(() => {
  if (notificationStore.notifications.length === 0) {
    fetchNotifications();
  }
  startClock();
  generateRecentActivities();
});

onBeforeUnmount(() => {
  if (clockInterval.value) {
    clearInterval(clockInterval.value);
  }
});

// Watchers
watch(activeTab, () => {
  currentPage.value = 1;
});
</script>

<style scoped>
/* Existing styles... */
.notifications-container {
  padding: 20px;
  max-width: 1600px;
  margin: 0 auto;
}

.page-header {
  margin-bottom: 30px;
}

.page-header h1 {
  font-size: 28px;
  font-weight: 600;
  color: #333;
  display: flex;
  align-items: center;
  gap: 12px;
}

.page-header i {
  color: #2563eb;
}

/* Content Layout */
.content-wrapper {
  display: grid;
  grid-template-columns: 1fr 400px;
  gap: 30px;
}

/* Content Layout */
.content-wrapper {
  display: grid;
  grid-template-columns: 1fr 400px;
  gap: 30px;
}

/* Notifications Section */
.notifications-section {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.section-header {
  padding: 20px;
  border-bottom: 1px solid #eee;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.section-header h2 {
  font-size: 20px;
  font-weight: 600;
  color: #333;
  margin: 0;
}

.header-actions {
  display: flex;
  gap: 10px;
}

.btn-mark-all,
.btn-refresh {
  padding: 8px 16px;
  border: none;
  border-radius: 6px;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.3s;
  display: flex;
  align-items: center;
  gap: 8px;
}

.btn-mark-all {
  background: #2563eb;
  color: white;
}

.btn-mark-all:hover:not(:disabled) {
  background: #1d4ed8;
}

.btn-mark-all:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.btn-refresh {
  background: #f3f4f6;
  color: #666;
}

.btn-refresh:hover {
  background: #e5e7eb;
}

.rotating {
  animation: rotate 1s linear infinite;
}

@keyframes rotate {
  100% { transform: rotate(360deg); }
}

/* Filter Tabs */
.filter-tabs {
  display: flex;
  padding: 0 20px;
  border-bottom: 1px solid #eee;
}

.tab-btn {
  padding: 15px 20px;
  border: none;
  background: none;
  cursor: pointer;
  font-size: 14px;
  color: #666;
  position: relative;
  transition: all 0.3s;
  display: flex;
  align-items: center;
  gap: 8px;
}

.tab-btn:hover {
  color: #333;
}

.tab-btn.active {
  color: #2563eb;
}

.tab-btn.active::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 2px;
  background: #2563eb;
}

.tab-count {
  background: #e5e7eb;
  color: #374151;
  padding: 2px 8px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
}

.tab-btn.active .tab-count {
  background: #dbeafe;
  color: #2563eb;
}

/* Notifications List */
.notifications-list {
  padding: 20px;
  min-height: 400px;
}

.loading-state,
.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 80px 0;
  color: #999;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 3px solid #f3f3f3;
  border-top: 3px solid #2563eb;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.empty-state i {
  font-size: 48px;
  margin-bottom: 16px;
  opacity: 0.5;
}

/* Notification Item */
.notification-item {
  display: flex;
  padding: 16px;
  border-radius: 8px;
  margin-bottom: 12px;
  cursor: pointer;
  transition: all 0.3s;
  border: 1px solid #e5e7eb;
}

.notification-item:hover {
  background: #f9fafb;
  transform: translateX(4px);
}

.notification-item.unread {
  background: #eff6ff;
  border-color: #dbeafe;
}

.notification-avatar {
  position: relative;
  margin-right: 16px;
}

.notification-avatar img {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  object-fit: cover;
}

.category-badge {
  position: absolute;
  bottom: -4px;
  right: -4px;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 10px;
  color: white;
  border: 2px solid white;
}

.category-document { background: #3b82f6; }
.category-system { background: #6b7280; }
.category-reminder { background: #f59e0b; }
.category-info { background: #10b981; }
.category-default { background: #8b5cf6; }

.notification-content {
  flex: 1;
  min-width: 0;
}

.notification-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 4px;
}

.notification-header strong {
  color: #111827;
  font-weight: 600;
}

.notification-time {
  font-size: 12px;
  color: #6b7280;
}

.notification-title {
  font-size: 16px;
  font-weight: 600;
  color: #111827;
  margin: 4px 0;
}

.notification-message {
  color: #4b5563;
  font-size: 14px;
  line-height: 1.5;
  margin: 4px 0;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-box-orient: vertical;
}

.notification-footer {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-top: 8px;
}

.category-tag {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  color: #6b7280;
}

.unread-badge {
  background: #ef4444;
  color: white;
  padding: 2px 8px;
  border-radius: 12px;
  font-size: 11px;
  font-weight: 600;
}

.notification-actions {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-left: 16px;
}

.btn-action {
  padding: 8px;
  border: none;
  background: #f3f4f6;
  border-radius: 6px;
  cursor: pointer;
  color: #6b7280;
  transition: all 0.3s;
}

.btn-action:hover {
  background: #e5e7eb;
  color: #111827;
}

/* Pagination */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 16px;
  margin-top: 24px;
}

.page-btn {
  padding: 8px 12px;
  border: 1px solid #e5e7eb;
  background: white;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.3s;
}

.page-btn:hover:not(:disabled) {
  background: #f3f4f6;
}

.page-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.page-info {
  font-size: 14px;
  color: #6b7280;
}

/* Widgets Section */
.widgets-section {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.widget {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  padding: 20px;
}

.widget-header {
  display: flex;
  align-items: center;
  margin-bottom: 16px;
}

.widget-header h3 {
  font-size: 18px;
  font-weight: 600;
  color: #333;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 8px;
}

.widget-header i {
  color: #2563eb;
}

/* Clock Widget */
.clock-widget {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.digital-clock {
  text-align: center;
  padding: 20px 0;
}

.time {
  font-size: 48px;
  font-weight: 300;
  letter-spacing: 2px;
  margin-bottom: 8px;
}

.date {
  font-size: 18px;
  opacity: 0.9;
  text-transform: capitalize;
}

/* Calendar Widget với Lịch Âm */
.calendar {
  font-size: 14px;
}

.calendar-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
  padding: 0 8px;
}

.cal-nav {
  background: none;
  border: none;
  font-size: 20px;
  cursor: pointer;
  color: #6b7280;
  padding: 4px 8px;
  border-radius: 4px;
  transition: all 0.3s;
}

.cal-nav:hover {
  background: #f3f4f6;
  color: #111827;
}

.cal-month {
  font-weight: 600;
  color: #111827;
  text-transform: capitalize;
}

.calendar-weekdays {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  gap: 4px;
  margin-bottom: 8px;
}

.weekday {
  text-align: center;
  font-size: 12px;
  font-weight: 600;
  color: #6b7280;
  padding: 8px 0;
}

.calendar-days {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  gap: 2px;
  background: #f5f5f5;
  border-radius: 8px;
  overflow: hidden;
}

/* Calendar Day - Cập nhật để hỗ trợ lịch âm */
.calendar-day {
  background: white;
  min-height: 50px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s;
  position: relative;
  padding: 4px 2px;
}

.calendar-day:hover {
  background: #f8f9ff;
}

.calendar-day.other-month {
  color: #ccc;
  background: #fafafa;
}

.calendar-day.today {
  background: #2563eb;
  color: white;
  font-weight: bold;
}

.calendar-day.today .lunar-date {
  color: rgba(255, 255, 255, 0.8);
}

.calendar-day.has-notification::after {
  content: '';
  position: absolute;
  top: 4px;
  right: 4px;
  width: 6px;
  height: 6px;
  background: #ff4444;
  border-radius: 50%;
}

/* Solar and Lunar date styling */
.solar-date {
  font-size: 14px;
  font-weight: 500;
  line-height: 1;
}

.lunar-date {
  font-size: 9px;
  color: #888;
  margin-top: 2px;
  line-height: 1;
}

.day-indicator {
  position: absolute;
  bottom: 2px;
  left: 50%;
  transform: translateX(-50%);
  width: 4px;
  height: 4px;
  background: #2196F3;
  border-radius: 50%;
}

/* Statistics Widget */
.stats-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}

.stat-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px;
  border-radius: 8px;
  background: #f9fafb;
  transition: all 0.3s;
}

.stat-item:hover {
  background: #f3f4f6;
  transform: translateY(-2px);
}

.stat-icon {
  width: 40px;
  height: 40px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
  color: white;
}

.stat-icon.unread { background: #3b82f6; }
.stat-icon.total { background: #6b7280; }
.stat-icon.today { background: #10b981; }
.stat-icon.week { background: #f59e0b; }

.stat-info {
  flex: 1;
}

.stat-value {
  font-size: 24px;
  font-weight: 700;
  color: #111827;
  line-height: 1;
}

.stat-label {
  font-size: 12px;
  color: #6b7280;
  margin-top: 4px;
}

/* Recent Activity Widget */
.activity-list {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.activity-item {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  padding: 12px;
  border-radius: 8px;
  background: #f9fafb;
  transition: all 0.3s;
}

.activity-item:hover {
  background: #f3f4f6;
}

.activity-icon {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  color: white;
  flex-shrink: 0;
}

.activity-icon.login { background: #3b82f6; }
.activity-icon.document { background: #10b981; }
.activity-icon.notification { background: #f59e0b; }

.activity-content {
  flex: 1;
  min-width: 0;
}

.activity-content p {
  margin: 0;
  font-size: 14px;
  color: #111827;
  line-height: 1.4;
}

.activity-time {
  font-size: 12px;
  color: #6b7280;
  margin-top: 4px;
  display: block;
}

/* Modal */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 20px;
}

.modal-content {
  background: white;
  border-radius: 12px;
  width: 100%;
  max-width: 600px;
  max-height: 80vh;
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.modal-header {
  padding: 20px;
  border-bottom: 1px solid #e5e7eb;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-header h2 {
  font-size: 20px;
  font-weight: 600;
  color: #111827;
  margin: 0;
}

.btn-close {
  background: none;
  border: none;
  font-size: 24px;
  color: #6b7280;
  cursor: pointer;
  padding: 4px;
  line-height: 1;
  transition: color 0.3s;
}

.btn-close:hover {
  color: #111827;
}

.modal-body {
  padding: 20px;
  overflow-y: auto;
}

.notification-detail {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.detail-header {
  display: flex;
  align-items: center;
  gap: 16px;
}

.sender-avatar {
  width: 56px;
  height: 56px;
  border-radius: 50%;
  object-fit: cover;
}

.sender-info h3 {
  font-size: 18px;
  font-weight: 600;
  color: #111827;
  margin: 0 0 4px 0;
}

.sender-info p {
  font-size: 14px;
  color: #6b7280;
  margin: 0;
}

.detail-content h4 {
  font-size: 20px;
  font-weight: 600;
  color: #111827;
  margin: 0 0 12px 0;
}

.detail-content p {
  font-size: 16px;
  color: #4b5563;
  line-height: 1.6;
  margin: 0 0 16px 0;
}

.detail-category {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  background: #f3f4f6;
  border-radius: 20px;
  font-size: 14px;
  color: #4b5563;
}

/* Animations */
.notification-enter-active,
.notification-leave-active {
  transition: all 0.3s ease;
}

.notification-enter-from {
  opacity: 0;
  transform: translateX(-20px);
}

.notification-leave-to {
  opacity: 0;
  transform: translateX(20px);
}

/* Responsive Design */
@media (max-width: 1200px) {
  .content-wrapper {
    grid-template-columns: 1fr;
  }
  
  .widgets-section {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
  }
  
  .clock-widget {
    grid-column: 1 / -1;
  }
}

@media (max-width: 768px) {
  .notifications-container {
    padding: 10px;
  }
  
  .content-wrapper {
    gap: 20px;
  }
  
  .widgets-section {
    grid-template-columns: 1fr;
  }
  
  .notification-item {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .notification-avatar {
    margin-bottom: 12px;
  }
  
  .notification-actions {
    margin-left: 0;
    margin-top: 12px;
    width: 100%;
    justify-content: flex-end;
  }
  
  .time {
    font-size: 36px;
  }
  
  .stats-grid {
    grid-template-columns: 1fr;
  }
  
  .header-actions {
    flex-direction: column;
    width: 100%;
  }
  
  .btn-mark-all {
    width: 100%;
    justify-content: center;
  }
  
  .filter-tabs {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  
  .calendar-day {
    min-height: 45px;
    padding: 2px 1px;
  }
  
  .solar-date {
    font-size: 12px;
  }
  
  .lunar-date {
    font-size: 8px;
  }
}

/* Custom Scrollbar */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>