import { createApp } from 'vue';
import SubscribersList from './components/SubscribersList.vue';

const app = createApp({
    components: {
        SubscribersList,
    },
});

app.mount('#app');
