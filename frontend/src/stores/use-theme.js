// stores/themeStore.js
import { defineStore } from 'pinia';

export const useThemeStore = defineStore('theme', {
  state: () => ({
    theme: localStorage.getItem('theme') || 'system', // 'light' | 'dark' | 'system'
  }),
  actions: {
    setTheme(theme) {
      this.theme = theme;
      localStorage.setItem('theme', theme);
      this.applyTheme();

      // Nếu muốn gọi API Laravel để lưu:
      // this.saveThemeToServer();
    },
    applyTheme() {
      const html = document.documentElement;
      html.classList.remove('light-mode', 'dark-mode');

      const isDark =
        this.theme === 'dark' ||
        (this.theme === 'system' &&
          window.matchMedia('(prefers-color-scheme: dark)').matches);

      html.classList.add(isDark ? 'dark-mode' : 'light-mode');
    },
    listenToSystemChange() {
      if (this.theme === 'system') {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
          this.applyTheme();
        });
      }
    },
    async saveThemeToServer() {
      try {
        await fetch('/api/user/theme', {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            Authorization: 'Bearer ' + localStorage.getItem('token'),
          },
          body: JSON.stringify({ theme: this.theme }),
        });
      } catch (err) {
        console.error('Lỗi lưu theme:', err);
      }
    },
  },
});
