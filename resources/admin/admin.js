import Vue from 'vue';
import VueRouter from 'vue-router';

window.user = JSON.parse(document.head.querySelector('meta[name="user"]').content);
window.config = JSON.parse(document.head.querySelector('meta[name="settings"]').content);

Vue.use(VueRouter);

import App from "./components/App";
import Index from "./components/pages/Index";
import Settings from "./components/pages/Settings";
import AllItems from "./components/pages/items/AllItems";
import Styles from "./components/pages/items/Styles";
import Cases from "./components/pages/cases/Cases";
import Categories from "./components/pages/cases/Categories";
import CasesItems from "./components/pages/cases/Items";
import Promocodes from "./components/pages/Promocodes";
import Users from "./components/pages/users/users";
import User from "./components/pages/users/user";
import Seo from "./components/pages/Seo";

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/admin',
            name: 'index',
            component: Index
        },
        {
            path: '/admin/settings',
            name: 'settings',
            component: Settings
        },
        {
            path: '/admin/seo',
            name: 'seo',
            component: Seo
        },
        {
            path: '/admin/all_items',
            name: 'all_items',
            component: AllItems
        },
        {
            path: '/admin/styles',
            name: 'styles',
            component: Styles
        },
        {
            path: '/admin/cases',
            name: 'cases',
            component: Cases
        },
        {
            path: '/admin/cases/:id/items',
            name: 'cases.items',
            component: CasesItems
        },
        {
            path: '/admin/categories',
            name: 'categories',
            component: Categories
        },
        {
            path: '/admin/promocodes',
            name: 'promocodes',
            component: Promocodes
        },
        {
            path: '/admin/users',
            name: 'users',
            component: Users
        },
        {
            path: '/admin/user/:id',
            name: 'user',
            component: User
        },
        {
            path: '*',
            redirect: '/admin'
        }
    ]
});

const admin = new Vue({
    el: '#app',
    data: {
        user: null,
        config: null
    },
    methods: {
        async getUser() {
            this.user = window.user;
        },
        async getConfig() {
            this.config = window.config;
        }
    },
    created() {
        this.getUser();
        this.getConfig();
    },
    components: {
        App
    },
    router
});