/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

// import Vue from 'vue'
import VueProgressBar from 'vue-progressbar'
import { Form, HasError, AlertError } from 'vform'

const ProgressBarOptions = {
  color: '#00881d',
  failedColor: '#e22525',
  thickness: '5px',
  transition: {
    speed: '0.2s',
    opacity: '0.6s',
    termination: 600
  },
  autoRevert: true,
  location: 'top',
  inverse: false
}
import Snotify, { SnotifyPosition } from 'vue-snotify'

const snotifyOptions = {
  toast: {
    position: SnotifyPosition.rightTop
  }
}
window.Form = Form
Vue.use(Snotify, snotifyOptions)
Vue.use(VueProgressBar, ProgressBarOptions)

Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)


Vue.component('main-component', require('./components/MainComponent.vue').default);
Vue.component('pagination-component', require('./components/PaginationComponent.vue').default);


const app = new Vue({
    el: '#app',

});
