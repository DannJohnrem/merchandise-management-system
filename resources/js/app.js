import { createApp } from 'vue';
import Toast from 'vue-toastification';
import "vue-toastification/dist/index.css";

// Create Vue app (or use existing app)
const app = createApp({});

// Toastification options
const toastOptions = {
  position: "top-right",
  timeout: 4000,
  closeOnClick: true,
  pauseOnHover: true,
  draggable: true,
  hideProgressBar: false, // ✅ shows loading bar at bottom
  icon: true,
};

// Install Toastification plugin
app.use(Toast, toastOptions);

// Expose global toast function
window.toast = (message, type = "success") => {
  app.config.globalProperties.$toast(message, { type });
};

// Mount app to some dummy element (required for Vue app)
app.mount('#toast-root');
