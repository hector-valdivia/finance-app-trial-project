require('./bootstrap');

import {App, plugin} from '@inertiajs/inertia-vue'
import Vue from 'vue'
import store from './store'

Vue.use(plugin)

Vue.filter('datetime', function (value, format = 'D MMM, YYYY hh:mm a') {
    return value? $dayjs.utc(value).format(format) : ' - ';
});

const el = document.getElementById('app')

new Vue({
    store,
    render: h => h(App, {
        props: {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: name => require(`./Pages/${name}`).default,
        },
    }),
}).$mount(el)
