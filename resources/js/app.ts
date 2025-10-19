import '../css/app.css';
import { createApp } from 'vue';
import { initializeTheme } from '@/composables/useAppearance';
import router from '@/router'
import App from '@/App.vue'
import { createPinia } from 'pinia'
// Vuetify
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import { useAuthStore } from '@/stores/auth';
import colors from 'vuetify/util/colors'
import { VFileUpload } from 'vuetify/labs/VFileUpload'

const pinia = createPinia();

// Register Vuetify as plugin
const vuetify = createVuetify({
  components: {
    VFileUpload,
  },
  theme: {
    defaultTheme: 'light',
    variations: {
      colors: ['primary', 'secondary'],
      lighten: 2,
      darken: 2,
    }
  },
})

createApp(App)
  .use(pinia)
  .use(router)
  .use(vuetify)
  .mount('#app');


// This will set light / dark mode on page load...
initializeTheme();


const auth = useAuthStore();
auth.refresh();
