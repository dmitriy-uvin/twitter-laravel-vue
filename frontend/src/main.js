import Vue from 'vue';
import Buefy from 'buefy';
import { library } from '@fortawesome/fontawesome-svg-core';
import {
    faHeart,
    faAngleDown,
    faComments,
    faShare,
    faUpload,
    faSignOutAlt,
    faCog,
    faEllipsisH,
    faEdit,
    faTrash,
    faExclamationCircle,
    faCopy,
    faBars,
    faThLarge,
    faClock,
} from '@fortawesome/free-solid-svg-icons';
import {
    faTwitter,
    faTwitterSquare
} from '@fortawesome/free-brands-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import createFilters from './components/filter/filters';
import router from './router';
import store from './store';
import App from './App.vue';

library.add(
    faHeart,
    faAngleDown,
    faComments,
    faShare,
    faTwitter,
    faUpload,
    faSignOutAlt,
    faCog,
    faTwitterSquare,
    faUpload,
    faEllipsisH,
    faEdit,
    faTrash,
    faExclamationCircle,
    faCopy,
    faBars,
    faThLarge,
    faClock,
);

Vue.component('font-awesome-icon', FontAwesomeIcon);
Vue.config.productionTip = false;
Vue.use(Buefy, {
    defaultIconComponent: 'font-awesome-icon',
    defaultIconPack: 'fa'
});

createFilters(Vue);

new Vue({
    router,
    store,
    render: h => h(App),
}).$mount('#app');
