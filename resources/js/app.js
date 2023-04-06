import { createApp } from 'vue';
import ExampleComponent from './components/Test.vue';

const app = createApp({
    components: {
        ExampleComponent,
    },
});

app.mount('#app');
