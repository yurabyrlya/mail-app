import { createApp } from 'vue';
import SubscribersList from './components/SubscribersList.vue';
import ApiKeyForm from './components/ApiKey.vue';

const app = createApp({
    components: {
        SubscribersList,
        ApiKeyForm,
    },
});

app.mount('#app');
