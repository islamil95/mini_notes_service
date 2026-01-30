import { createApp } from 'vue';
import App from './App.vue';
import axios from 'axios';

const app = createApp(App);

// API base URL for container/production
axios.defaults.baseURL = import.meta.env.VITE_APP_URL || '';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['Content-Type'] = 'application/json';

app.config.globalProperties.$axios = axios;
app.mount('#app');
