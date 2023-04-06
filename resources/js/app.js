require('./bootstrap');

import Vue from 'vue';
import ExampleComponent from './components/Test.vue';

Vue.component('example-component', ExampleComponent);

new Vue({
    el: '#app',
});



